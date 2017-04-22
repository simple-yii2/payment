<?php

namespace cms\payment\backend;

use Yii;

use cms\components\BackendModule;

/**
 * Payment backend module
 */
class Module extends BackendModule {

	/**
	 * @inheritdoc
	 */
	public static function moduleName()
	{
		return 'payment';
	}

	/**
	 * @inheritdoc
	 */
	public static function cmsSecurity()
	{
		$auth = Yii::$app->getAuthManager();
		if ($auth->getRole('Payment') === null) {
			//role
			$role = $auth->createRole('Payment');
			$auth->add($role);
		}
	}

	/**
	 * @inheritdoc
	 */
	public static function cmsMenu($base)
	{
		if (!Yii::$app->user->can('Payment'))
			return [];

		return [
			['label' => Yii::t('payment', 'Payment'), 'items' => [
				['label' => Yii::t('payment', 'Payment providers'), 'url' => ["$base/payment/provider/index"]],
				['label' => Yii::t('payment', 'Accounts'), 'url' => ["$base/payment/account/index"]],
				['label' => Yii::t('payment', 'Invoices'), 'url' => ["$base/payment/invoice/index"]],
				['label' => Yii::t('payment', 'Transactions'), 'url' => ["$base/payment/transaction/index"]],
				['label' => Yii::t('payment', 'Turnovers'), 'url' => ["$base/payment/turnover/index"]],
			]],
		];
	}

}
