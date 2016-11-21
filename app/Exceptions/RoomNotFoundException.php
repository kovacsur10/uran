<?php

namespace App\Exceptions;

use Exception;

class RoomNotFoundException extends Exception{
	public function __construct($code = 0, Exception $previous = null) {
		parent::__construct("The room was not found!", $code, $previous);
	}
}