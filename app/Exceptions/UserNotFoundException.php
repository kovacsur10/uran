<?php 

namespace App\Exceptions;

use Exception;

class UserNotFoundException extends Exception{
	public function __construct($code = 0, Exception $previous = null) {
		parent::__construct("The user was not found!", $code, $previous);
	}
}