<?php

namespace cms\payment\backend\models;

use Yii;
use yii\data\ActiveDataProvider;
use cms\payment\common\models\Account;
use cms\payment\common\models\Invoice;

/**
 * Invoice search model
 */
class InvoiceSearch extends Invoice
{

	/**
	 * @var Account
	 */
	private $_model;

	/**
	 * @inheritdoc
	 * @param Account $model 
	 */
	public function __construct(Account $model, $config = [])
	{
		$this->_model = $model;

		parent::__construct($config);
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
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
		$query = static::find()->joinWith(['user']);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		//return data provider if no search
		if (!($this->load($params) && $this->validate()))
			return $dataProvider;

		//search
		$query->andFilterWhere(['like', 'User.email', $this->_userEmail]);

		return $dataProvider;
	}

}
