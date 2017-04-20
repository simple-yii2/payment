<?php

namespace cms\payment\backend\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use cms\payment\backend\models\AccountSearch;

/**
 * Payment account controller
 */
class AccountController extends Controller
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
		$search = new AccountSearch;

		return $this->render('index', [
			'search' => $search,
		]);
	}

}
