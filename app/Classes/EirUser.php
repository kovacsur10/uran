<?php

namespace App\Classes;

use DB;

class EirUser extends User{
	protected $eirUser;
	protected $validationTime;
	protected $macAddresses;
	
	public function __construct($id){
		$this->eirUser = $this->getEirUserData($id);
		$this->validationTime = $this->getValidationTime();
		$this->macAddresses = $this->getMACAddresses($id);
		parent::__construct($id);
	}
	
	public function eirUser(){
		return $this->eirUser;
	}
	
	public function validationTime(){
		return $this->validationTime;
	}
	
	public function macAddresses(){
		return $this->macAddresses;
	}
	
	protected function getEirUserData($id){
		return DB::table('eir_user_data')->where('user_id', '=', $id)
										 ->first();
	}
	
	protected function getValidationTime(){
		return DB::table('eir_valid_date')->first();
	}
	
	protected function getMACAddresses($id){
		return DB::table('eir_mac_addresses')->where('user_id', '=', $id)
											 ->select('id', 'mac_address')
											 ->get();
	}
}
