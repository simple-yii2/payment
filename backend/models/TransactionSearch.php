<?php

namespace cms\payment\backend\models;

use Yii;
use yii\data\ActiveDataProvider;
use cms\payment\common\models\Transaction;
use cms\user\common\models\User;

/**
 * Transaction search model
 */
class TransactionSearch extends Transaction
{

	/**
	 * @inheritdoc
	 */
	public function formName()
	{
		return 'search';
	}

	/**
	 * @var string user e-mail for search
	 */
	private $_user_email;

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'user_email' => Yii::t('payment', 'User'),
			'date' => Yii::t('payment', 'Date'),
			'income' => Yii::t('payment', 'Income'),
			'expense' => Yii::t('payment', 'Expense'),
			'balance' => Yii::t('payment', 'Balance'),
			'description' => Yii::t('payment', 'Description'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['user_email', 'string'],
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
		->joinWith(['user'])
		->orderBy(['date' => SORT_DESC, 'id' => SORT_DESC]);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		//return data provider if no search
		if (!($this->load($params) && $this->validate()))
			return $dataProvider;

		//search
		$query->andFilterWhere(['like', 'User.email', $this->_user_email]);

		return $dataProvider;
	}

	/**
	 * User relation
	 * @return yii\db\ActiveQueryInterface
	 */
	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}

	/**
	 * User e-mail getter
	 * @return string
	 */
	public function getUser_email()
	{
		if ($this->_user_email !== null)
			return $this->_user_email;

		$user = $this->user;
		if ($user === null)
			return null;

		return $this->_user_email = $user->email;
	}

	/**
	 * User e-mail setter
	 * @param string $value 
	 * @return void
	 */
	public function setUser_email($value)
	{
		$this->_user_email = $value;
	}

}
