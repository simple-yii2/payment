<?php

namespace cms\payment\backend\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Payment provider controller
 */
class ProviderController extends Controller
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
	 * List
	 * @return string
	 */
	public function actionIndex()
	{
		$dataProvider = new ArrayDataProvider([
			'allModels' => Yii::$app->payment->getAllProviders(),
		]);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
		]);
	}

}
