<?php

namespace cms\payment\frontend\controllers;

use Yii;
use yii\web\Controller;
use cms\payment\frontend\models\TransactionSearch;

/**
 * Account controller for payment frontend module
 */
class AccountController extends Controller
{

	/**
	 * List transactions for the current user
	 * @return string
	 */
	public function actionIndex()
	{
		$user = Yii::$app->user;
		if ($user->isGuest)
			return $user->loginRequired();

		$search = new TransactionSearch;
		$search->user_id = $user->id;

		return $this->render('index', [
			'search' => $search,
		]);
	}

}
