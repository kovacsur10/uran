<?php

namespace App\Http\Controllers\Ecnet;

use App\Classes\LayoutData;
use App\Classes\Layout\EcnetData;
use App\Classes\Logger;
use Validator;
use App\Classes\Notifications;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Carbon\Carbon;

/** Class name: AccessController
 *
 * This controller is for handling the internet access.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class AccessController extends Controller{	

// PUBLIC FUNCTIONS
	/** Function name: showInternet
	 *
	 * This function shows the internet access page.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function showInternet(){
		$layout = new LayoutData();
		$layout->setUser(new EcnetData(Session::get('user')->id()));
		$now = Carbon::now();
		if($layout->user()->ecnetUser() === null){
			Logger::warning('Ecnet user was not found!', null, null, 'ecnet/access');
			return view('errors.usernotfound', ["layout" => $layout]);
		}else{
			return view('ecnet.ecnet', ["layout" => $layout,
										"active" => $now->toDateTimeString() < $layout->user()->ecnetUser()->valid(),
										"users" => $layout->user()->users()]);
		}
	}
	
	/** Function name: updateValidationTime
	 *
	 * This function sets the new validation time.
	 * 
	 * @param Request request
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function updateValidationTime(Request $request){
		$layout = new LayoutData();
        $this->validate($request, [
			'new_valid_date' => 'required',
		]);
		if($layout->user()->permitted('ecnet_set_valid_time')){
			$newTime = $request->new_valid_date.' 05:00:00';
			try{
				EcnetData::changeDefaultValidDate($newTime);
				Logger::log('ECnet default access time was set!', null, $newTime, 'ecnet/access');
				return view('success.success', ["layout" => $layout,
						"message" => $layout->language('success_at_setting_the_default_time_to').$newTime,
						"url" => '/ecnet/access']);
			}catch(\Exception $ex){
				Logger::warning('At ECnet validation time setting. An error occured!', null, null, 'ecnet/access');
				return view('errors.authentication', ["layout" => $layout]);
			}
		}else{
			Logger::warning('At ECnet validation time setting. PERMISSIONS NEEDED!', null, null, 'ecnet/access');
			return view('errors.authentication', ["layout" => $layout]);
		}
	}
	
	/** Function name: activate
	 *
	 * This function activates a user's internet access.
	 *
	 * @param Request request
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function activate(Request $request){
		$layout = new LayoutData();
		$layout->setUser(new EcnetData(Session::get('user')->id()));
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
			try{
				$layout->user()->activateUserNet($request->account, $newTime);
				Notifications::notify($layout->user()->user(), $request->account, $layout->language('internet_access_was_modified'), $layout->language('internet_access_was_modified_to_description').$layout->formatDate($newTime), 'ecnet/access');
				Logger::log('Successfully activated user internet access for user #'.print_r($request->account, true).'!', null, $newTime, 'ecnet/access');
				return view('success.success', ["layout" => $layout,
						"message" => $layout->language('success_at_setting_users_internet_access_time'),
						"url" => '/ecnet/access']);
			}catch(\Exception $ex){
				Logger::warning('Could not activate user internet access for user #'.print_r($request->account, true).'!', null, null, 'ecnet/access');
				return view('errors.error', ["layout" => $layout,
											 "message" => $layout->language('error_at_setting_users_internet_access_time'),
											 "url" => '/ecnet/access']);
			}
		}else{
			Logger::warning('At ECnet activate user internet. PERMISSIONS NEEDED!', null, null, 'ecnet/access');
			return view('errors.authentication', ["layout" => $layout]);
		}
	}
	
	/** Function name: setMACAddresses
	 *
	 * This function sets a user's MAC addresses.
	 *
	 * @param Request request
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function setMACAddresses(Request $request){
		$layout = new LayoutData();
		$layout->setUser(new EcnetData(Session::get('user')->id()));
		$addresses = [];

		for($i = 0; $i < $layout->user()->ecnetUser()->maximumMacSlots(); $i++){
			if($request->input('mac_address_'.$i) !== null){
				$this->validate($request, [
					'mac_address_'.$i => ['regex:/^(?:(?:[0-9A-Fa-f]{2}:){5}[0-9A-Fa-f]{2})|(?:(?:[0-9A-Fa-f]{2}-){5}[0-9A-Fa-f]{2})$/'],
				]);
				$addresses[] = $request->input('mac_address_'.$i);
			}
		}
		try{
			$layout->user()->manageMacAddresses($addresses);
			Logger::log('MAC addresses was changed for user!', null, null, 'ecnet/setmacs');
			return view('success.success', ["layout" => $layout,
					"message" => $layout->language('success_at_updating_mac_addresses'),
					"url" => '/ecnet/access']);
		}catch(\Exception $ex){
			Logger::warning('Could not set the MAC addresses for user #'.print_r($layout->user()->user()->id(), true).'!', null, null, 'ecnet/setmacs');
			return view('errors.error', ["layout" => $layout,
					"message" => $layout->language('error_at_setting_mac_addresses'),
					"url" => '/ecnet/access']);
		}
	}
	
// PRIVATE FUNCTIONS
}
