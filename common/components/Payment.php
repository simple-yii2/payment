<?php

namespace cms\payment\common\components;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Component;
use cms\payment\common\components\ProviderInterface;

/**
 * Payment application component contains common information about payment providers and register payment module on bootstrap
 */
class Payment extends Component implements BootstrapInterface
{

	/**
	 * @var string[]|array[]|ProviderInterface[] list of available providers with its configuration
	 */
	public $providers = [];

	/**
	 * @inheritdoc
	 */
	public function bootstrap($app)
	{
		$modules = $app->getModules();
		$modules['payment'] = 'cms\payment\frontend\Module';
		$app->setModules($modules);

		$app->getUrlManager()->addRules([
			[
				'pattern' => '/payment/success/<name:[\w\.]+>',
				'route' => '/payment/success/index',
			],
			[
				'pattern' => '/payment/fail/<name:[\w\.]+>',
				'route' => '/payment/fail/index',
			],
		], false);
	}

	/**
	 * Return provider by name
	 * @param string $name 
	 * @return ProviderInterface|null
	 */
	public function getProvider($name)
	{
		if (!array_key_exists($name, $this->providers))
			return null;

		$provider = $this->providers[$name];

		if (!($provider instanceof ProviderInterface)) {
			$provider = Yii::createObject($provider);

			if ($provider instanceof ProviderInterface) {
				$this->providers[$name] = $provider;
			} else {
				$provider = null;
			}
		}

		return $provider;
	}

}
