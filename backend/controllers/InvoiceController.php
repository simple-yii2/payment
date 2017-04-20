<?php

namespace cms\payment\backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
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
	 * @return string
	 */
	public function actionIndex()
	{
		$search = new InvoiceSearch;

		return $this->render('index', [
			'search' => $search,
		]);
	}

	/**
	 * View
	 * @param int $id 
	 * @return string
	 */
	public function actionView($id)
	{
		$model = InvoiceSearch::findOne($id);
		if ($model === null)
			throw new BadRequestHttpException(Yii::t('payment', 'Item not found.'));

		return $this->render('view', [
			'model' => $model,
		]);
	}

}
