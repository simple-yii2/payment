<?php

namespace cms\payment\frontend;

use cms\components\BaseModule;

/**
 * Payment frontend module
 * 
 * Recievs and process request from processing centers
 */
class Module extends BaseModule
{

	/**
	 * @inheritdoc
	 */
	public static function moduleName()
	{
		return 'payment';
	}

}
