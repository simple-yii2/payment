<?php

namespace cms\payment\backend\models;

use Yii;
use yii\data\ActiveDataProvider;
use cms\payment\common\models\Transaction;

/**
 * Transaction search model
 */
class TransactionSearch extends Transaction
{

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'date' => Yii::t('payment', 'Date'),
			'receipt' => Yii::t('payment', 'Receipt'),
			'paid' => Yii::t('payment', 'Paid'),
			'balance' => Yii::t('payment', 'Balance'),
			'description' => Yii::t('payment', 'Description'),
		];
	}

	/**
	 * Search function
	 * @param array|null $params Attributes array
	 * @return ActiveDataProvider
	 */
	public function getDataProvider($params = null)
	{
		if ($params === null)
			$params = Yii::$app->getRequest()->get();

		//ActiveQuery
		$query = static::find()
		->where(['user_id' => $this->user_id])
		->orderBy(['date' => SORT_DESC, 'id' => SORT_DESC]);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		//return data provider if no search
		if (!($this->load($params) && $this->validate()))
			return $dataProvider;

		//search

		return $dataProvider;
	}

	/**
	 * Receipt amount getter
	 * @return string
	 */
	public function getReceipt()
	{
		if ($this->amount <= 0)
			return '';

		return $this->amount;
	}

	/**
	 * Paid amount getter
	 * @return string
	 */
	public function getPaid()
	{
		if ($this->amount >= 0)
			return '';

		return -$this->amount;
	}

}
