<?php

namespace cms\payment\provider;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use cms\payment\common\models\Invoice;

/**
 * Provider for ROBOKASSA processing center
 */
class Robokassa extends BaseProvider
{

	/**
	 * @var string
	 */
	public $merchantLogin;

	/**
	 * @var string
	 */
	public $merchantPass1;

	/**
	 * @var string
	 */
	public $merchantPass2;

	/**
	 * @var string
	 */
	private $_merchantUrl = 'https://auth.robokassa.ru/Merchant/Index.aspx';

	/**
	 * @var string
	 */
	private $_opStateUrl = 'https://merchant.roboxchange.com/WebService/Service.asmx/OpState';

	/**
	 * @var array
	 */
	private $_states = [
		5 => Invoice::STATE_NEW,		//init
		10 => Invoice::STATE_FAIL,		//canceled
		50 => Invoice::STATE_SUCCESS,	//in transit
		60 => Invoice::STATE_FAIL,		//refund
		80 => false,					//suspend
		100 => Invoice::STATE_SUCCESS,	//success
	];

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
		//request data
		$sign = md5("{$this->merchantLogin}:{$invoice->amount}:{$invoice->id}:{$this->merchantPass1}");
		$data = [
			'MrchLogin' => $this->merchantLogin,
			'OutSum' => $invoice->amount,
			'InvId' => $invoice->id,
			'Desc' => $invoice->description,
			'SignatureValue' => $sign,
		];

		//send request
		Yii::$app->getResponse()->redirect($this->_merchantUrl . '?' . http_build_query($data));
		Yii::$app->end();
	}

	/**
	 * @inheritdoc
	 */
	public function result()
	{
		//check for request params
		if (!isset($_REQUEST['OutSum'], $_REQUEST['InvId'], $_REQUEST['SignatureValue']))
			die('data error');

		//params
		$OutSum = $_REQUEST['OutSum'];
		$InvId = $_REQUEST['InvId'];
		$SignatureValue = $_REQUEST['SignatureValue'];

		//check sign
		$sign = md5("{$OutSum}:{$InvId}:{$this->merchantPass2}");
		if (strtoupper($sign) != strtoupper($SignatureValue))
			die('sign error');

		//check invoice
		$invoice = Invoice::findOne($InvId);
		if ($invoice === null)
			die('invoice id error');
		if ($invoice->amount != $OutSum)
			die('invoice amount error');

		//process payment for invoice
		if (!$this->processInvoice($invoice))
			die('invoice process error');

		die('OK' . $invoice->id);
	}

	/**
	 * @inheritdoc
	 */
	public function success()
	{
		//check for request params
		if (!isset($_REQUEST['OutSum'], $_REQUEST['InvId'], $_REQUEST['SignatureValue']))
			throw new BadRequestHttpException(Yii::t('payment', 'Error while processing request.'));

		//params
		$OutSum = $_REQUEST['OutSum'];
		$InvId = $_REQUEST['InvId'];
		$SignatureValue = $_REQUEST['SignatureValue'];

		//check sign
		$sign = md5("{$OutSum}:{$InvId}:{$this->merchantPass1}");
		if (strtoupper($sign) != strtoupper($SignatureValue))
			throw new BadRequestHttpException(Yii::t('payment', 'Error while processing request.'));

		//check invoice
		$invoice = Invoice::findOne($InvId);
		if ($invoice === null)
			throw new BadRequestHttpException(Yii::t('payment', 'Invoice not found.'));
		if ($invoice->amount != $OutSum)
			throw new BadRequestHttpException(Yii::t('payment', 'Error while processing request.'));

		//process payment for invoice
		$this->processInvoice($invoice);

		Yii::$app->getResponse()->redirect($invoice->url);
	}

	/**
	 * @inheritdoc
	 */
	public function fail()
	{
		//check for request params
		if (!isset($_REQUEST['OutSum'], $_REQUEST['InvId']))
			throw new BadRequestHttpException(Yii::t('payment', 'Error while processing request.'));

		//params
		$OutSum = $_REQUEST['OutSum'];
		$InvId = $_REQUEST['InvId'];

		//check invoice
		$invoice = Invoice::findOne($InvId);
		if ($invoice === null)
			throw new BadRequestHttpException(Yii::t('payment', 'Invoice not found.'));
		if ($invoice->amount != $OutSum)
			throw new BadRequestHttpException(Yii::t('payment', 'Error while processing request.'));

		//process payment for invoice
		$this->processInvoice($invoice);

		Yii::$app->getResponse()->redirect(Yii::$app->getUser()->getReturnUrl());
	}

	private function processInvoice(Invoice $invoice)
	{
		$state = $this->invoiceState($invoice);
		if ($state === false)
			return;

		switch ($state) {
			case Invoice::STATE_SUCCESS:
				return $invoice->success($this);
				break;

			case Invoice::STATE_FAIL:
				return $invoice->fail($this);
				break;
		}
	}

	/**
	 * Request to processing center for invoice state
	 * @param Invoice $invoice 
	 * @return int|false invoice state. If there are error, returns false.
	 */
	private function invoiceState(Invoice $invoice)
	{
		//request params
		$sign = md5("{$this->merchantLogin}:{$invoice->id}:{$this->merchantPass2}");
		$data = array(
			'MerchantLogin' => $this->merchantLogin,
			'InvoiceID' => $invoice->id,
			'Signature' => $sign,
		);

		//loading xml
		$s = @file_get_contents($this->_opStateUrl . '?' . http_build_query($data));
		if (!($xml = @simplexml_load_string($s)))
			return false;

		//if there are errors
		if ($xml->Result->Code != 0)
			return false;

		return ArrayHelper::getValue($this->_states, (integer) $xml->State->Code, false);
	}

}
