<?php 

namespace App\Exceptions;

use Exception;

class NotEnoughMoneyException extends Exception{
    public $needed_amount;
	public function __construct($message = "Not enough money", $code = 0, $needed_amount = -1, Exception $previous = null) {
		parent::__construct($message, $code, $previous);
		$this->needed_amount = $needed_amount;
	}
}