<?php

namespace cms\payment\backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use cms\payment\backend\models\TurnoverFilter;

/**
 * Payment turnover controller
 */
class TurnoverController extends Controller
{

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					['allow' => true, 'roles' => ['Payment']],
				],
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function actions()
	{
		return [
			'users' => 'cms\user\common\actions\AutoComplete',
		];
	}

	/**
	 * Grid
	 * @return string
	 */
	public function actionIndex()
	{
		$filter = new TurnoverFilter;
		$filter->load(Yii::$app->getRequest()->post());

		return $this->render('index', [
			'filter' => $filter,
		]);
	}

}
