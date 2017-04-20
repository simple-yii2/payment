<?php

namespace cms\payment\backend\models;

use Yii;
use yii\data\ActiveDataProvider;
use cms\payment\common\models\Account;
use cms\user\common\models\User;

/**
 * Account search model
 */
class AccountSearch extends Account
{

	/**
	 * @var string user e-mail for search
	 */
	private $_userEmail;

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'userEmail' => Yii::t('payment', 'User'),
			'amount' => Yii::t('payment', 'Amount'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['userEmail', 'safe'],
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
	public function getUserEmail()
	{
		if ($this->_userEmail !== null)
			return $this->_userEmail;

		$user = $this->user;
		if ($user === null)
			return null;

		return $this->_userEmail = $user->email;
	}

	/**
	 * User e-mail setter
	 * @param string $value 
	 * @return void
	 */
	public function setUserEmail($value)
	{
		$this->_userEmail = $value;
	}

}
