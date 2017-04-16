<?php

namespace cms\payment\frontend\controllers;

use yii\web\Controller;

/**
 * Payment success controller
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
		var_dump($name);
	}

}
