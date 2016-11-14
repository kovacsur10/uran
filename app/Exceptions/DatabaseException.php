<?php

namespace App\Exceptions;

use Exception;

class DatabaseException extends Exception{
	public function __construct($message = "Database exception occured!", $code = 0, Exception $previous = null) {
		parent::__construct($message, $code, $previous);
	}
}