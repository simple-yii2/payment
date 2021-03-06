<?php

namespace cms\payment\frontend\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Payment success controller
 * User redirects to this controller from processing center when payment is successfully
 */
class SuccessController extends Controller
{

	/**
	 * Process success payment request from processing center
	 * @param string $name provider name. This is key of provider application component, see [[Payment::providers]].
	 * @return string
	 */
	public function actionIndex($name)
	{
		$provider = Yii::$app->payment->getProvider($name);
		if ($provider === null)
			throw new BadRequestHttpException('Payment provider not found.');

		$provider->success();
		Yii::$app->end();
	}

}
