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
	 * Getter for user name
	 * @return string
	 */
	public function getUsername()
	{
		return $this->user ? $this->user->getUsername() : '';
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
	 * Add income associated with user and update amount on accaunt
	 * @param float $amount 
	 * @param string $description 
	 * @param string|null $url 
	 * @return void
	 */
	public function addIncome($amount, $description, $url = null)
	{
		$d = time();
		$transaction = new Transaction([
			'user_id' => $this->user_id,
			'date' => gmdate('Y-m-d H:i:s', $d),
			'year' => gmdate('Y', $d),
			'month' => gmdate('m', $d),
			'income' => $amount,
			'description' => $description,
			'url' => $url,
			'balance' => $this->amount + $amount,
		]);
		$transaction->save(false);

		$this->updateCounters(['amount' => $amount]);
	}

	/**
	 * Add expense associated with user and update amount on accaunt
	 * @param float $amount 
	 * @param string $description 
	 * @param string|null $url 
	 * @return void
	 */
	public function addExpense($amount, $description, $url = null)
	{
		$d = time();
		$transaction = new Transaction([
			'user_id' => $this->user_id,
			'date' => gmdate('Y-m-d H:i:s', $d),
			'year' => gmdate('Y', $d),
			'month' => gmdate('m', $d),
			'expense' => $amount,
			'description' => $description,
			'url' => $url,
			'balance' => $this->amount - $amount,
		]);
		$transaction->save(false);

		$this->updateCounters(['amount' => -$amount]);
	}

}
