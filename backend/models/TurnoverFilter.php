<?php

namespace cms\payment\backend\models;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use cms\payment\common\models\Transaction;
use cms\user\common\models\User;

/**
 * Turnover filter model
 */
class TurnoverFilter extends Model
{

	/**
	 * @var int
	 */
	public $_user_id;

	/**
	 * @var string
	 */
	public $_user_email;

	/**
	 * @var int
	 */
	public $year;

	/**
	 * @inheritdoc
	 */
	public function __construct($config = [])
	{
		if (!array_key_exists('year', $config))
			$config['year'] = date('Y');

		parent::__construct($config);
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'user_email' => Yii::t('payment', 'User'),
			'year' => Yii::t('payment', 'Year'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['user_id', 'integer'],
			['year', 'integer', 'skipOnEmpty' => false],
		];
	}

	/**
	 * Getter for data provider
	 * @return ArrayDataProvider
	 */
	public function getDataProvider()
	{
		if (!$this->validate())
			return new ArrayDataProvider;

		//build query
		$query = Transaction::find()
		->select(['month', 'SUM(`income`) AS `income`', 'SUM(`expense`) AS `expense`'])
		->where(['year' => $this->year])
		->andFilterWhere(['user_id' => $this->_user_id])
		->groupBy(['month'])
		->orderBy(['month' => SORT_ASC]);

		//make items
		$items = [];
		$totalIncome = 0;
		$totalExpense = 0;
		foreach ($query->asArray()->all() as $row) {
			$row['turnover'] = $row['income'] - $row['expense'];
			$items[$row['month']] = $row;
			$totalIncome += $row['income'];
			$totalExpense += $row['expense'];
		}

		//make total
		if (!empty($items)) {
			$items[] = [
				'income' => $totalIncome,
				'expense' => $totalExpense,
				'turnover' => $totalIncome - $totalExpense,
			];
		}

		return new ArrayDataProvider([
			'allModels' => $items,
			'pagination' => false,
		]);
	}

	/**
	 * Getter for user id
	 * @return int
	 */
	public function getUser_id()
	{
		return $this->_user_id;
	}

	/**
	 * Setter for user id
	 * @param int $value 
	 * @return void
	 */
	public function setUser_id($value)
	{
		$user = User::findOne($value);
		if ($user) {
			$this->_user_id = $value;
			$this->_user_email = $user->email;
		}
	}

	/**
	 * Getter for user e-mail
	 * @return string
	 */
	public function getUser_email()
	{
		return $this->_user_email;
	}

}
