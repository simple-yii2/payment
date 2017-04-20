<?php

namespace cms\payment\backend\controllers;

use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use cms\payment\common\models\Account;
use cms\payment\backend\models\InvoiceSearch;

/**
 * Payment account invoices controller
 */
class InvoiceController extends Controller
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
	 * @param int $id account id
	 * @return string
	 */
	public function actionIndex($id)
	{
		$model = Account::findOne($id);
		if ($model === null)
			throw new BadRequestHttpException(Yii::t('payment', 'Item not found.'));

		$search = new InvoiceSearch($model);

		return $this->render('index', [
			'model' => $model,
			'search' => $search,
		]);
	}

}
