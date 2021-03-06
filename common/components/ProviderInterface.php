<?php

namespace cms\payment\common\components;

use cms\payment\common\models\Invoice;

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

	/**
	 * Request from processing center when payment process is finished
	 * @return void
	 */
	public function result();

	/**
	 * Callback from processing center when payment is success
	 * @return void
	 */
	public function success();

	/**
	 * Callback from processing center when payment is fail
	 * @return void
	 */
	public function fail();

	/**
	 * Process invoice (check state and change if necessary)
	 * @param Invoice $invoice 
	 * @return boolean
	 */
	public function processInvoice(Invoice $invoice);

}
