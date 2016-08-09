<?php

namespace App\Classes;

use App\Classes\Database;
use DB;

class Database{
	
	public function __construct(){
	}

	public function beginTransaction(){
		DB::beginTransaction();
	}
	
	public function rollback(){
		DB::rollback();
	}
	
	public function commit(){
		DB::commit();
	}
	
}
