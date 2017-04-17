<?php

namespace cms\payment\provider;

use cms\payment\common\models\Invoice;

/**
 * Provider for ROBOKASSA processing center
 */
class Robokassa extends BaseProvider
{

	/**
	 * @var string
	 */
	public $marchantLogin;

	/**
	 * @var string
	 */
	public $merchantPass1;

	/**
	 * @var string
	 */
	public $merchantPass2;

	/**
	 * @inheritdoc
	 */
	public function name()
	{
		return 'ROBOKASSA';
	}

	/**
	 * @inheritdoc
	 */
	protected function payInvoice(Invoice $invoice)
	{

	}

}
