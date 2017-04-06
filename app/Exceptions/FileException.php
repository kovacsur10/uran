<?php

namespace App\Exceptions;

use Exception;

class FileException extends Exception{
	public function __construct($message = "File exception occured!", $code = 0, Exception $previous = null) {
		parent::__construct($message, $code, $previous);
	}
}