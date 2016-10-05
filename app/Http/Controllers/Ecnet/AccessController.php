<?php

namespace App\Http\Controllers\Ecnet;

use App\Classes\LayoutData;
use App\Classes\Layout\EcnetUser;
use App\Classes\Logger;
use Validator;
use App\Classes\Notifications;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AccessController extends Controller{	

// PUBLIC FUNCTIONS

	public function showInternet(){
		$layout = new LayoutData();
		$layout->setUser(new EcnetUser(Session::get('user')->id));
		$now = Carbon::now();
		if($layout->user()->ecnetUser() === null){
			Logger::warning('Ecnet user was not found!', null, null, 'ecnet/access');
			return view('errors.usernotfound', ["layout" => $layout]);
		}else{
			return view('ecnet.ecnet', ["layout" => $layout,
										"active" => $now->toDateTimeString() < $layout->user()->ecnetUser()->valid_time,
										"users" => $layout->user()->users()]);
		}
	}
	
	public function updateValidationTime(Request $request){
		$layout = new LayoutData();
		$layout->setUser(new EcnetUser(Session::get('user')->id));
        $this->validate($request, [
			'new_valid_date' => 'required',
		]);
		if($layout->user()->permitted('ecnet_set_valid_time')){
			$newTime = $request->new_valid_date.' 05:00:00';
			$layout->user()->changeDefaultValidDate($newTime);
			
			Logger::log('ECnet default access time was set!', null, $newTime, 'ecnet/access');
			return view('success.success', ["layout" => $layout,
											"message" => $layout->language('success_at_setting_the_default_time_to').$newTime,
											"url" => '/ecnet/access']);
		}else{
			Logger::warning('At ECnet validation time setting. PERMISSIONS NEEDED!', null, null, 'ecnet/access');
			return view('errors.authentication', ["layout" => $layout]);
		}
	}
	
	public function activate(Request $request){
		$layout = new LayoutData();
		$layout->setUser(new EcnetUser(Session::get('user')->id));
        $this->validate($request, [
			'account' => 'required',
		]);
		if($layout->user()->permitted('ecnet_set_valid_time')){
			if($request->custom_valid_date === null || $request->custom_valid_date === ''){
				if($layout->user()->validationTime() === null){
					Logger::warning('At ECnet user internet activation the default time was not set!', null, null, 'ecnet/access');
					return view('errors.error', ["layout" => $layout,
												 "message" => $layout->language('error_no_default_time_set'),
												 "url" => '/ecnet/access']);
				}
				$newTime = $layout->user()->validationTime();
			}else{
				$newTime = $request->custom_valid_date.' 05:00:00';
			}
			if($layout->user()->activateUserNet($request->account, $newTime) !== 0){
				Logger::warning('Could not activate user internet access for user #'.print_r($request->account, true).'!', null, null, 'ecnet/access');
				return view('errors.error', ["layout" => $layout,
											 "message" => $layout->language('error_at_setting_users_internet_access_time'),
											 "url" => '/ecnet/access']);
			}
			Notifications::notify($layout->user(), $request->account, $layout->language('internet_access_was_modified'), $layout->language('internet_access_was_modified_to_description').$layout->formatDate($newTime), 'ecnet/access');
			Logger::log('Successfully activated user internet access for user #'.print_r($request->account, true).'!', null, $newTime, 'ecnet/access');
			return view('success.success', ["layout" => $layout,
											"message" => $layout->language('success_at_setting_users_internet_access_time'),
											"url" => '/ecnet/access']);
		}else{
			Logger::warning('At ECnet activate user internet. PERMISSIONS NEEDED!', null, null, 'ecnet/access');
			return view('errors.authentication', ["layout" => $layout]);
		}
	}
	
	public function setMACAddresses(Request $request){
		$layout = new LayoutData();
		$layout->setUser(new EcnetUser(Session::get('user')->id));
		$addresses = [];
		$newAddresses = [];
		$existingAddresses = [];
		$deletableAddresses = [];

		foreach($layout->user()->macAddresses() as $address){
			if($request->input('mac_address_'.$address->id) != null){
				$this->validate($request, [
					'mac_address_'.$address->id => 'required|regex:/(^(?:(?:[0-9A-Fa-f]{2}:){5}[0-9A-Fa-f]{2})|(?:(?:[0-9A-Fa-f]{2}-){5}[0-9A-Fa-f]{2})$)/',
				]);
				array_push($addresses, $request->input('mac_address_'.$address->id));
			}
		}
		for($i = 0; $i < $layout->user()->ecnetUser()->mac_slots - count($layout->user()->macAddresses()); $i++){
			if($request->input('new_mac_address_'.$i) != null){
				$this->validate($request, [
					'new_mac_address_'.$i => 'required|regex:/(^(?:[A-Fa-f0-9]{2}[\-:]){5}[A-Fa-f0-9]{2}$)/',
				]);
				array_push($addresses, $request->input('new_mac_address_'.$i));
			}
		}
		//calculate existing and new addresses
		foreach($addresses as $address){
			if(!$layout->user()->macAddressExists($address)){
				array_push($newAddresses, $address);
			}else{
				array_push($existingAddresses, $address);
				if(($key = array_search($address, $deletableAddresses)) !== false) {
					unset($deletableAddresses[$key]);
				}
			}
		}
		//calculate deletable addresses
		foreach($layout->user()->macAddresses() as $address){
			if(($key = array_search($address->mac_address, $addresses)) === false){
				array_push($deletableAddresses, $address->mac_address);
			}
		}
		
		//commit the changes
		foreach($deletableAddresses as $address){
			$layout->user()->deleteMacAddress($address);
		}
		foreach($newAddresses as $address){
			$layout->user()->insertMacAddress($layout->user()->user()->id, $address);
		}
		
		Logger::log('MAC addresses was changed for user!', null, null, 'ecnet/access');
		return view('success.success', ["layout" => $layout,
										"message" => $layout->language('success_at_updating_mac_addresses'),
										"url" => '/ecnet/access']);
	}
	
// PRIVATE FUNCTIONS
}
