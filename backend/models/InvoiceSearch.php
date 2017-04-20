<?php

namespace cms\payment\backend\models;

use Yii;
use yii\data\ActiveDataProvider;
use cms\payment\common\models\Invoice;

/**
 * Invoice search model
 */
class InvoiceSearch extends Invoice
{

	/**
	 * @inheritdoc
	 */
	public function __construct($config = [])
	{
		if (!array_key_exists('state', $config))
			$config['state'] = null;

		parent::__construct($config);
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('payment', 'Number'),
			'createDate' => Yii::t('payment', 'Date'),
			'provider' => Yii::t('payment', 'Payment provider'),
			'amount' => Yii::t('payment', 'Amount'),
			'description' => Yii::t('payment', 'Description'),
			'state' => Yii::t('payment', 'State'),
			'payDate' => Yii::t('payment', 'Pay date'),
			'refundDate' => Yii::t('payment', 'Refund date'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'state'], 'integer'],
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
		->orderBy(['createDate' => SORT_DESC, 'id' => SORT_DESC]);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		//return data provider if no search
		if (!($this->load($params) && $this->validate()))
			return $dataProvider;

		//search
		$query->andFilterWhere(['id' => $this->id]);
		$query->andFilterWhere(['state' => $this->state]);

		return $dataProvider;
	}

}
