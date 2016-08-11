<?php

namespace App\Http\Controllers\Ecnet;

use App\Classes\LayoutData;
use App\Classes\Layout\EcnetUser;
use App\Classes\Notify;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Mail;

class EcnetController extends Controller{	
    public function showAccount(){
		$layout = new LayoutData();
		$layout->setUser(new EcnetUser(Session::get('user')->id));
		if($layout->user()->ecnetUser() == null){
			return view('errors.usernotfound', ["layout" => $layout]);
		}else{
			return view('ecnet.account', ["layout" => $layout,
										  "users" => $layout->user()->users()]);
		}
	}
	
	public function showInternet(){
		$layout = new LayoutData();
		$layout->setUser(new EcnetUser(Session::get('user')->id));
		$now = Carbon::now();
		if($layout->user()->ecnetUser() == null){
			return view('errors.usernotfound', ["layout" => $layout]);
		}else{
			return view('ecnet.ecnet', ["layout" => $layout,
										"active" => $now->toDateTimeString() < $layout->user()->ecnetUser()->valid_time,
										"users" => $layout->user()->users()]);
		}
	}
	
	public function showMACOrderForm(){
		$layout = new LayoutData();
		$layout->setUser(new EcnetUser(Session::get('user')->id));
		$orders = DB::table('ecnet_mac_slot_orders')->join('users', 'users.id', '=', 'ecnet_mac_slot_orders.user_id')
												  ->select('ecnet_mac_slot_orders.id', 'users.username', 'ecnet_mac_slot_orders.reason', 'ecnet_mac_slot_orders.order_time')
												  ->get();
		return view('ecnet.slotordering', ["layout" => $layout,
										   "orders" => $orders]);
	}
	
	public function showUsers($count = 50, $first = 0){
		$layout = new LayoutData();
		$layout->setUser(new EcnetUser(Session::get('user')->id));
		if(Session::has('ecnet_username_filter') && Session::has('ecnet_name_filter')){
			$layout->user()->filterUsers(Session::get('ecnet_username_filter'), Session::get('ecnet_name_filter'));
		}
		return view('ecnet.showusers', ["layout" => $layout,
										"usersToShow" => $count,
										"firstUser" => $first]);
	}
	
	public function showActiveUsers($type){
		$layout = new LayoutData();
		$layout->setUser(new EcnetUser(Session::get('user')->id));
		if($type == "name" || $type == "username" || $type == "both"){
			return view('ecnet.showactiveusers.'.$type, ["logged" => Session::has('user'),
												  "layout" => $layout]);
		}else{
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_page_not_found'),
										 "url" => '/ecnet/users']);
		}
	}
	
	public function getUsers(){
		return DB::table('users')->select('id', 'username', 'name')
								 ->orderBy('name', 'asc')
								 ->get();
	}
	
	public function filterUsers(Request $request){
		if($request->input('username') == null){
			Session::put('ecnet_username_filter', '');
		}else{
			Session::put('ecnet_username_filter', $request->input('username'));
		}
		if($request->input('name') == null){
			Session::put('ecnet_name_filter', '');
		}else{
			Session::put('ecnet_name_filter', $request->input('name'));
		}
		return redirect('ecnet/users');
	}
	
	public function resetFilterUsers(){
		Session::forget('ecnet_username_filter');
		Session::forget('ecnet_name_filter');
		return redirect('ecnet/users');
	}
	
	public function addMoney(Request $request){
		$layout = new LayoutData();
		$layout->setUser(new EcnetUser(Session::get('user')->id));
        $this->validate($request, [
			'money' => 'required',
			'reset' => 'required',
            'account' => 'required',
		]);
		if($layout->user()->permitted('ecnet_set_print_account')){
			$money = DB::table('ecnet_user_data')->where('user_id', '=', $request->account)
											   ->select('money')
											   ->first();
			if($money == null){
				return view('errors.error', ["layout" => $layout,
											 "message" => $layout->language('error_at_money_adding'),
											 "url" => '/ecnet/account']);
			}
			$oldmoney = $money->money;
			if($request->money == 0){
				$money = $request->reset;
			}else{
				$money = $money->money + $request->money;
			}
			DB::table('ecnet_user_data')->where('user_id', '=', $request->account)
									  ->update(['money' => $money]);
			Notify::notify($layout->user(), $request->account, $layout->language('balance_was_modified'), $layout->language('balance_was_modified_description').' '.$oldmoney.' '.$layout->language('from_forint').' '.$money.' '.$layout->language('to_forint').'!', 'ecnet/account');
			return view('success.success', ["layout" => $layout,
											"message" => $layout->language('success_set_money'),
											"url" => '/ecnet/account']);
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
    }
	
	public function updateValidationTime(Request $request){
		$layout = new LayoutData();
		$layout->setUser(new EcnetUser(Session::get('user')->id));
        $this->validate($request, [
			'new_valid_date' => 'required',
		]);
		if($layout->user()->permitted('ecnet_set_valid_time')){
			$new_time = $request->new_valid_date.' 05:00:00';
			DB::table('ecnet_valid_date')->delete();
			DB::table('ecnet_valid_date')->insert(['valid_date' => $new_time]);

			return view('success.success', ["layout" => $layout,
											"message" => $layout->language('success_at_setting_the_default_time_to').$new_time,
											"url" => '/ecnet/access']);
		}else{
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
			if($request->custom_valid_date == null){
				if($layout->user()->validationTime() == null){
					return view('errors.error', ["layout" => $layout,
												 "message" => $layout->language('error_no_default_time_set'),
												 "url" => '/ecnet/access']);
				}
				$new_time = $layout->user()->validationTime()->valid_date;
			}else{
				$new_time = $request->custom_valid_date.' 05:00:00';
			}
			DB::table('ecnet_user_data')->where('user_id', '=', $request->account)
									  ->update(['valid_time' => $new_time]);
			
			Notify::notify($layout->user(), $request->account, $layout->language('internet_access_was_modified'), $layout->language('internet_access_was_modified_to_description').str_replace("-", ". ", str_replace(" ", ". ", $new_time)), 'ecnet/access');
			return view('success.success', ["layout" => $layout,
											"message" => $layout->language('success_at_setting_users_internet_access_time'),
											"url" => '/ecnet/access']);
		}else{
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
					'mac_address_'.$address->id => 'required|regex:/(^(?:[A-Fa-f0-9]{2}[\-:]){5}[A-Fa-f0-9]{2}$)/',
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
			if(DB::table('ecnet_mac_addresses')->where('mac_address', 'LIKE', $address)->first() == null){
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
			DB::table('ecnet_mac_addresses')->where('mac_address', 'LIKE', $address)->delete();
		}
		foreach($newAddresses as $address){
			DB::table('ecnet_mac_addresses')->insert(['user_id' => $layout->user()->user()->id, 'mac_address' => $address]);
		}
		
		return view('success.success', ["layout" => $layout,
										"message" => $layout->language('success_at_updating_mac_addresses'),
										"url" => '/ecnet/access']);
	}
	
	public function getSlot(Request $request){
		$layout = new LayoutData();
		$layout->setUser(new EcnetUser(Session::get('user')->id));
        $this->validate($request, [
			'reason' => 'required',
		]);
		$layout->user()->addMACSlotOrder($layout->user()->user()->id, $request->input('reason'));
		Notify::notifyAdmin($layout->user(), 'ecnet_slot_verify', $layout->language('mac_slot_ordering'), $layout->language('mac_slot_was_ordered_description').$request->input('reason'), 'ecnet/order');
		return view('success.success', ["layout" => $layout,
										"message" => $layout->language('success_at_sending_mac_slot_order'),
										"url" => '/ecnet/order']);
	}
	
	public function allowOrDenyOrder(Request $request){
		$layout = new LayoutData();
		$layout->setUser(new EcnetUser(Session::get('user')->id));
        $this->validate($request, [
			'optradio' => 'required',
			'slot' => 'required',
		]);
		if($layout->user()->permitted('ecnet_slot_verify')){
			$target = DB::table('ecnet_user_data')->join('ecnet_mac_slot_orders', 'ecnet_mac_slot_orders.user_id', '=', 'ecnet_user_data.user_id')
												->where('ecnet_mac_slot_orders.id', '=', $request->input('slot'))
												->first();
			if($target == null){
				return view('errors.error', ["layout" => $layout,
											 "message" => $layout->language('error_at_allowing_mac_slot_order'),
											 "url" => '/ecnet/order']);
			}
			if($request->input('optradio') == "allow"){
				DB::table('ecnet_user_data')->where('user_id', '=', $target->user_id)
										  ->update(['mac_slots' => $target->mac_slots+1]);
				Notify::notify($layout->user(), $target->user_id, $layout->language('mac_slot_ordering'), $layout->language('mac_slot_order_was_accepted_description').$target->reason, 'ecnet/access');
			}else{
				Notify::notify($layout->user(), $target->user_id, $layout->language('mac_slot_ordering'), $layout->language('mac_slot_order_was_denied_description').$target->reason, 'ecnet/order');
			}
			DB::table('ecnet_mac_slot_orders')->where('id', '=', $request->input('slot'))->delete();
			return view('success.success', ["layout" => $layout,
											"message" => $layout->language('success_at_allowing_mac_slot_order'),
											"url" => '/ecnet/order']);
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
	}
}
