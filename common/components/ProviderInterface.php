<?php

namespace cms\payment\common\components;

/**
 * Interface for processing center provider
 */
interface ProviderInterface
{

	/**
	 * Gets the name of processing center
	 * @return string
	 */
	public function name();

	/**
	 * Start payment process
	 * @param PayableInterface $model 
	 * @param string|null $url 
	 * @return boolean
	 */
	public function pay(PayableInterface $model, $url = null);

}
