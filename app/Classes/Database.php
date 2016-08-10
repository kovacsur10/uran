<?php

namespace App\Classes;

use DB;

class Database{
	
	public function __construct(){
	}

	public static function beginTransaction(){
		DB::beginTransaction();
	}
	
	public static function rollback(){
		DB::rollback();
	}
	
	public static function commit(){
		DB::commit();
	}
	
}
