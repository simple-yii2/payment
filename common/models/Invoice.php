<?php

namespace cms\payment\common\models;

use yii\db\ActiveRecord;

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

}
