<?php

namespace App\Classes;

use App\Classes\Errors;

class Errors{

	protected $errors;
	
	public function __construct(){
		$this->errors = [];
	}

	public function add($key, $data){
		$this->errors[$key] = $data;
	}
	
	public function has($key){
		return array_key_exists($key, $this->errors);
	}
	
	public function get($key){
		return $this->errors[$key];
	}
	
}
