<?php

namespace cms\payment\common\models;

use yii\db\ActiveRecord;

/**
 * User paymant account
 */
class Account extends ActiveRecord
{

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'PaymentAccount';
	}

	/**
	 * Find account by user id
	 * @param int $user_id 
	 * @param bool $forceCreate 
	 * @return static|null
	 */
	public static function findByUser($user_id, $forceCreate = true)
	{
		$model = self::find()->where(['user_id' => $user_id])->one();
		if ($model)
			return $model;

		if (!$forceCreate)
			return null;

		$model = new self([
			'user_id' => $user_id,
			'amount' => 0,
		]);
		$model->save(false);

		return $model;
	}

	/**
	 * Add transaction associated with user and update amount on accaunt
	 * @param float $amount 
	 * @param string $description 
	 * @param string|null $url 
	 * @return void
	 */
	public function addTransaction($amount, $description, $url = null)
	{
		$transaction = new Transaction([
			'user_id' => $this->user_id,
			'date' => gmdate('Y-m-d H:i:s'),
			'amount' => $amount,
			'description' => $description,
			'url' => $url,
			'balance' => $this->amount + $amount,
		]);
		$transaction->save(false);

		$this->updateCounters(['amount' => $amount]);
	}

}
