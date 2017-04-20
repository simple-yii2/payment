<?php

namespace cms\payment\backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use cms\payment\backend\models\TransactionSearch;

/**
 * Payment account transaction controller
 */
class TransactionController extends Controller
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
		$search = new TransactionSearch;

		return $this->render('index', [
			'search' => $search,
		]);
	}

}
