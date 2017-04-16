<?php

namespace cms\payment\common\components;

use yii\base\Component;

/**
 * Payment application component contains common information about payment providers and register payment module on bootstrap
 */
class Payment extends Component
{

	/**
	 * @var string[]|array[]|ProviderInterface[] list of available providers with its configuration
	 */
	public $providers = [];

}
