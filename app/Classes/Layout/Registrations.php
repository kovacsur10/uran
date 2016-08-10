<?php

namespace App\Classes\Layout;

use DB;
use Carbon\Carbon;

class Registrations{
	protected $registrationUser;	
	
	public function __construct(){
	}
	
	public function get(){
		$ret = DB::table('users')
			->where('registered', '=', 0)
			->where('id', '!=', 0)
			->orderBy('name', 'asc')
			->get();
		return $ret == null ? [] : $ret;
	}
	
	public function getNames(){
		$ret = DB::table('users')
			->join('registrations', 'registrations.user_id', '=', 'users.id')
			->select('id', 'name', 'verified')
			->where('registered', '=', 0)
			->where('id', '!=', 0)
			->orderBy('name', 'asc')
			->get();
		return $ret == null ? [] : $ret;
	}
	
	public function getRegistrationUser(){
		return $this->registrationUser;
	}
	
	public function setRegistrationUserById($id){
		$user = DB::table('users')
			->join('registrations', 'registrations.user_id', '=', 'users.id')
			->where('registered', '=', 0)
			->where('id', '=', $id)
			->first();
		$this->registrationUser = $user == null ? [] : $user;
	}
	
	public function getRegistrationByCode($code){
		return DB::table('registrations')
			->where('code', 'LIKE', $code)
			->first();
	}
	
	public function verify($code){
		try{
			DB::table('registrations')
				->where('code', 'LIKE', $code)
				->update([
					'verified' => 1,
					'verification_date' => Carbon::now()
				]);
		}catch(\Illuminate\Database\QueryException $e){
			return null;
		}
	}
	
	public function getNotVerifiedUserData($username){
		try{
			return DB::table('users')
				->where('username', 'LIKE', $username)
				->where('registered', '=', 0)
				->first();
		}catch(\Illuminate\Database\QueryException $e){
			return null;
		}
	}
	
	public function addCode($userId, $code){
		DB::table('registrations')->insert([
				'user_id' => $userId,
				'code' => $code,
			]);
	}
	
	public function insertGuestData($username, $password, $email, $name, $country, $shire, $postalCode, $address, $city, $reasonOfRegistration, $phoneNumber, $defaultLanguage){
		DB::table('users')->insert([
			'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'email' => $email,
            'name' => $name,
            'registration_date' => Carbon::now()->toDateTimeString(),
			'country' => $country,
			'shire' => $shire,
			'postalcode' => $postalCode,
			'address' => $address,
			'city' => $city,
			'reason' => $reasonOfRegistration,
			'phone' => $phoneNumber,
			'language' => $defaultLanguage,
		]);
	}
	
	public function insertCollegistData($username, $password, $email, $name, $country, $shire, $postalCode, $address, $city, $phoneNumber, $defaultLanguage, $cityOfBirth, $dateOfBirth, $nameOfMother, $yearOfLeavingExam, $highSchool, $neptun, $applicationYear, $faculty, $workshop){
		DB::table('users')->insert([
			'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'email' => $email,
            'name' => $name,
            'registration_date' => Carbon::now()->toDateTimeString(),
			'country' => $country,
			'shire' => $shire,
			'postalcode' => $postalCode,
			'address' => $address,
			'city' => $city,
			'reason' => null,
			'phone' => $phoneNumber,
			'language' => $defaultLanguage,
			'city_of_birth' => $cityOfBirth,
			'date_of_birth' => $dateOfBirth,
			'name_of_mother' => $nameOfMother,
			'year_of_leaving_exam' => $yearOfLeavingExam,
			'high_school' => $highSchool,
			'neptun' => $neptun,
			'from_year' => $applicationYear,
			'faculty' => $faculty,
			'workshop' => $workshop,
		]);
	}
}
