<?php

namespace App\Classes;

use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use DB;

class Logger{

// PUBLIC FUNCTIONS

	public static function log($description, $oldValue, $newValue, $route){
		Logger::write($description, $oldValue, $newValue, $route, 1);
	}
	
	public static function warning($description, $oldValue, $newValue, $route){
		Logger::write($description, $oldValue, $newValue, $route, 2);
	}
	
	public static function error($description, $oldValue, $newValue, $route){
		Logger::write($description, $oldValue, $newValue, $route, 3);
	}
	
// PRIVATE FUNCTIONS
	
	private static function write($description, $oldValue, $newValue, $route, $severity){
		DB::table('logs')
			->insert([
				'description' => $description,
				'old_value' => print_r($oldValue, true),
				'new_value' => print_r($newValue, true),
				'path' => $route,
				'user_id' => Session::has('user') ? Session::get('user')->id : 0,
				'datetime' => Carbon::now(),
				'type' => $severity,
			]);
	}
}
