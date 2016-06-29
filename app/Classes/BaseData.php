<?php

namespace App\Classes;

use DB;

class BaseData{
	protected $faculties;
	protected $workshops;
	protected $admissionYears;
	
	public function __construct(){
		$this->faculties = $this->getFaculties();
		$this->workshops = $this->getWorkshops();
		$this->admissionYears = $this->getAdmissionYears();
	}
	
	public function faculties(){
		return $this->faculties;
	}
	
	public function workshops(){
		return $this->workshops;
	}
	
	public function admissionYears(){
		return $this->admissionYears;
	}
	
	protected function getFaculties(){
		$ret = DB::table('faculties')
			->get();
		return $ret == null ? [] : $ret;
	}
	
	protected function getWorkshops(){
		$ret = DB::table('workshops')
			->get();
		return $ret == null ? [] : $ret;
	}
	
	protected function getAdmissionYears(){
		$ret = DB::table('admission_years')
			->get();
		return $ret == null ? [] : $ret;
	}
	
}
