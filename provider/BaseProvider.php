<?php

namespace cms\payment\provider;

use yii\base\Object;
use cms\payment\common\components\PayableInterface;
use cms\payment\common\components\ProviderInterface;
use cms\payment\common\models\Account;
use cms\payment\common\models\Invoice;

/**
 * Base class for all providers
 */
abstract class BaseProvider extends Object implements ProviderInterface
{

	/**
	 * @inheritdoc
	 * If there are enough money, pay with account
	 */
	public function pay(PayableInterface $model, $url = null)
	{
		if ($model->isPaid())
			return false;

		$amount = $model->paymentAmount();
		$description = $model->paymentDescription();
		$user = $model->paymentUser();
		$account = Account::findByUser($user->id);

		if ($amount <= $account->amount) {
			$account->addTransaction(-$amount, $description, $url);

			$model->paymentSuccess();
			return true;
		}

		$invoice = new Invoice([
			'provider' => $this->name(),
			'user_id' => $user->id,
			'modelClass' => get_class($model),
			'modelId' => $model->paymentModelId(),
			'amount' => $amount - $account->amount,
			'description' => $description,
			'url' => $url,
			'createDate' => gmdate('Y-m-d H:i:s'),
		]);
		$invoice->save();

		$this->payInvoice($invoice);
	}

	/**
	 * Pay invoice with processing center
	 * @param Invoice $invoice 
	 * @return void
	 */
	abstract protected function payInvoice(Invoice $invoice);

}
