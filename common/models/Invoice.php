<?php

namespace cms\payment\common\models;

use Yii;
use yii\db\ActiveRecord;
use cms\payment\common\components\PayableInterface;
use cms\payment\common\components\ProviderInterface;

/**
 * Invoice for payment in processing center
 */
class Invoice extends ActiveRecord
{

	//state
	const STATE_NEW = 0;
	const STATE_SUCCESS = 1;
	const STATE_FAIL = 2;
	const STATE_REFUND = 3;

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'PaymentInvoice';
	}

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		if ($this->state === null)
			$this->state = self::STATE_NEW;
	}

	/**
	 * Getter for model associated with invoice
	 * @return PayableInterface|null
	 */
	public function getModel()
	{
		$class = $this->modelClass;
		return $class::paymentCreate($this->modelId);
	}

	/**
	 * Call when payment is success
	 * @param ProviderInterface $provider 
	 * @return bool
	 */
	public function success(ProviderInterface $provider)
	{
		if ($this->state == self::STATE_SUCCESS)
			return false;

		//invoice
		$this->state = self::STATE_SUCCESS;
		$this->payDate = gmdate('Y-m-d H:i:s');
		$this->refundDate = null;
		$this->update(false, ['state', 'payDate', 'refundDate']);

		//transaction
		$description = Yii::t('payment', '{provider} (Invoice #{number} of {date})', [
			'provider' => $provider->name(),
			'number' => $this->id,
			'date' => Yii::$app->formatter->asDate($this->createDate, 'short'),
		]);
		$account = Account::findByUser($this->user_id);
		$account->addTransaction($this->amount, $description, $this->url);

		//model
		if ($model = $this->getModel())
			$provider->pay($model, $this->url);

		return true;
	}

	/**
	 * Call when payment is fail
	 * @param ProviderInterface $provider 
	 * @return bool
	 */
	public function fail(ProviderInterface $provider)
	{
		if ($this->state == self::STATE_FAIL || $this->state == self::STATE_REFUND)
			return true;

		$isRefund = $this->state == self::STATE_SUCCESS;

		//invoice
		$this->state = $isRefund ? self::STATE_REFUND : self::STATE_FAIL;
		if ($isRefund)
			$this->refundDate = gmdate('Y-m-d H:i:s');
		$this->update(false, ['state', 'refundDate']);

		if (!$isRefund)
			return true;

		//model
		if ($model = $this->getModel())
			$model->paymentRefund();

		return true;
	}

}
