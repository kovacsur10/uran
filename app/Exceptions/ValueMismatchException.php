<?php 

namespace App\Exceptions;

use Exception;

class ValueMismatchException extends Exception{
	public function __construct($message = "The given value doesn't match the expectations.", $code = 0, Exception $previous = null) {
		parent::__construct($message, $code, $previous);
	}
}