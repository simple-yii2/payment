<?php

namespace cms\payment\frontend\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Payment fail controller
  * User redirects to this controller from processing center when payment is fail
*/
class FailController extends Controller
{

	/**
	 * Process fail payment request from processing center
	 * @param string $name provider name. This is key of provider application component, see [[Payment::providers]].
	 * @return string
	 */
	public function actionIndex($name)
	{
		$provider = Yii::$app->payment->getProvider($name);
		if ($provider === null)
			throw new BadRequestHttpException('Payment provider not found.');

		$provider->fail();
		Yii::$app->end();
	}

}
