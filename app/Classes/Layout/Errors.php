<?php

namespace App\Classes\Layout;

class Errors{

	protected $errors;
	protected $old;
	
	public function __construct(){
		$this->errors = [];
		$this->old = [];
	}

	public function add($key, $data){
		$this->errors[$key] = $data;
	}
	
	public function has($key){
		return array_key_exists($key, $this->errors);
	}
	
	public function get($key){
		if($this->has($key)){
			return $this->errors[$key];
		}else{
			return null;
		}
	}
	
	public function addOld($key, $data){
		$this->old[$key] = $data;
	}
	
	public function hasOld($key){
		return array_key_exists($key, $this->old);
	}
	
	public function getOld($key){
		if($this->hasOld($key)){
			return $this->old[$key];
		}else{
			return null;
		}
	}
	
}
