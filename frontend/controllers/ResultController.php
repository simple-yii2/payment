<?php

namespace cms\payment\frontend\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Payment result controller
 * Processing center sent request to notify module about payment
 */
class ResultController extends Controller
{

	/**
	 * Process result request from processing center
	 * @param string $name provider name. This is key of provider application component, see [[Payment::providers]].
	 * @return string
	 */
	public function actionIndex($name)
	{
		$provider = Yii::$app->payment->getProvider($name);
		if ($provider === null)
			throw new BadRequestHttpException('Payment provider not found.');

		$provider->result();
		Yii::$app->end();
	}

}
