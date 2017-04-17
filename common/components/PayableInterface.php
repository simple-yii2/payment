<?php

namespace cms\payment\common\components;

use cms\user\common\models\User;

/**
 * Interface for any object that can be paid
 */
interface PayableInterface
{

	/**
	 * Function for check that object is paid
	 * @return boolean
	 */
	public function isPaid();

	/**
	 * Getter for id of the model
	 * @return string
	 */
	public function paymentModelId();

	/**
	 * Getter for user, associated with payment
	 * @return User
	 */
	public function paymentUser();

	/**
	 * Getter for cost amount
	 * @return float
	 */
	public function paymentAmount();

	/**
	 * Description of payment for invoice and transaction
	 * @return string
	 */
	public function paymentDescription();

	/**
	 * Create model with id
	 * @param integer $id 
	 * @return static
	 */
	public static function paymentCreate($id);

	/**
	 * Callback for successfully payment
	 * @return void
	 */
	public function paymentSuccess();

}
