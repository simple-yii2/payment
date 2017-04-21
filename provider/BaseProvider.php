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

		//try to pay from account
		$amount = $this->payFromAccount($model, $url);
		if (is_bool($amount))
			return $amount;

		//create invoice and try to pay via payment provider
		$invoice = new Invoice([
			'provider' => get_class($this),
			'user_id' => $model->paymentUser()->id,
			'modelClass' => get_class($model),
			'modelId' => $model->paymentModelId(),
			'amount' => $amount,
			'description' => $model->paymentDescription(),
			'url' => $url,
			'createDate' => gmdate('Y-m-d H:i:s'),
		]);
		$invoice->save();

		$this->payInvoice($invoice);
	}

	/**
	 * Try to pay model with account
	 * @param PayableInterface $model 
	 * @param string|null $url 
	 * @return float|true|false if model is paid returns false. If payment is success returns true. If there are not enought money, returns amount need to pay.
	 */
	public function payFromAccount(PayableInterface $model, $url = null)
	{
		if ($model->isPaid())
			return false;

		$amount = $model->paymentAmount();
		$account = Account::findByUser($model->paymentUser()->id);

		if ($amount > $account->amount)
			return $amount - $account->amount;

		$account->addExpense($amount, $model->paymentDescription(), $url);
		$model->paymentSuccess();

		return true;
	}

	/**
	 * Pay invoice with processing center
	 * @param Invoice $invoice 
	 * @return void
	 */
	abstract protected function payInvoice(Invoice $invoice);

}
