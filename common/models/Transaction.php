<?php

namespace cms\payment\common\models;

use yii\db\ActiveRecord;

class Transaction extends ActiveRecord
{

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'PaymentTransaction';
	}

}
