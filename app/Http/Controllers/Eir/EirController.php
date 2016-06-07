<?php

namespace App\Http\Controllers\Eir;

use App\Classes\EirUser;
use App\Classes\Notify;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Mail;

class EirController extends Controller{	
    public function showAccount(){
		$user = new EirUser(Session::get('user')->id);
		$users = $this->getUsers();
		if($user->eirUser() == null){
			return view('errors.usernotfound', ["logged" => Session::has('user'),
												"user" => $user,]);
		}else{
			return view('ecnet.account', ["logged" => Session::has('user'),
										  "user" => $user,
										  "users" => $users]);
		}
	}
	
	public function showInternet(){
		$now = Carbon::now();
		$user = new EirUser(Session::get('user')->id);
		$users = $this->getUsers();
		if($user->eirUser() == null){
			return view('errors.usernotfound', ["logged" => Session::has('user'),
												"user" => $user]);
		}else{
			return view('ecnet.ecnet', ["logged" => Session::has('user'),
										"user" => $user,
										"active" => $now->toDateTimeString() < $user->eirUser()->valid_time,
										"users" => $users]);
		}
	}
	
	public function showMACOrderForm(){
		$user = new EirUser(Session::get('user')->id);
		$orders = DB::table('eir_mac_slot_orders')->join('users', 'users.id', '=', 'eir_mac_slot_orders.user_id')
												  ->select('eir_mac_slot_orders.id', 'users.username', 'eir_mac_slot_orders.reason', 'eir_mac_slot_orders.order_time')
												  ->get();
		return view('ecnet.slotordering', ["logged" => Session::has('user'),
										   "user" => $user,
										   "orders" => $orders]);
	}
	
	public function showUsers(){
		return view('ecnet.showusers', ["logged" => Session::has('user'),
										"user" => new EirUser(Session::get('user')->id)]);
	}
	
	public function getUsers(){
		return DB::table('users')->select('id', 'username', 'name')
								 ->orderBy('name', 'asc')
								 ->get();
	}
	
	public function addMoney(Request $request){
		$user = new EirUser(Session::get('user')->id);
        $this->validate($request, [
			'money' => 'required',
			'reset' => 'required',
            'account' => 'required',
		]);
		if($user->permitted('ecnet_set_print_account')){
			$money = DB::table('eir_user_data')->where('user_id', '=', $request->account)
											   ->select('money')
											   ->first();
			if($money == null){
				return view('errors.error', ["logged" => Session::has('user'),
											 "user" => $user,
											 "message" => 'Valami probléma merült fel a pénz hozzáadásánál!',
											 "url" => '/ecnet/account']);
			}
			$oldmoney = $money->money;
			if($request->money == 0){
				$money = $request->reset;
			}else{
				$money = $money->money + $request->money;
			}
			DB::table('eir_user_data')->where('user_id', '=', $request->account)
									  ->update(['money' => $money]);
			Notify::notify($user, $request->account, 'Egyenleg módosítva!', 'A nyomtatószámlád egyenlege megváltozott '.$oldmoney.' forintról '.$money.' forintra!', 'ecnet/account');
			return view('success.success', ["logged" => Session::has('user'),
											"user" => $user,
											"message" => 'Sikeresen átállítottad a célszámla pénzösszegét!',
											"url" => '/ecnet/account']);
		}else{
			return view('errors.authentication', ["logged" => Session::has('user'),
												  "user" => $user]);
		}
    }
	
	public function updateValidationTime(Request $request){
		$user = new EirUser(Session::get('user')->id);
        $this->validate($request, [
			'new_valid_date' => 'required',
		]);
		if($user->permitted('ecnet_set_valid_time')){
			$new_time = $request->new_valid_date.' 05:00:00';
			DB::table('eir_valid_date')->delete();
			DB::table('eir_valid_date')->insert(['valid_date' => $new_time]);

			return view('success.success', ["logged" => Session::has('user'),
											"user" => $user,
											"message" => 'Sikeresen át lett állítva az alapértelmezett idő erre: '.$new_time,
											"url" => '/ecnet/access']);
		}else{
			return view('errors.authentication', ["logged" => Session::has('user'),
												  "user" => $user]);
		}
	}
	
	public function activate(Request $request){
		$user = new EirUser(Session::get('user')->id);
        $this->validate($request, [
			'account' => 'required',
		]);
		if($user->permitted('ecnet_set_valid_time')){
			if($request->custom_valid_date == null){
				if($user->validationTime() == null){
					return view('errors.error', ["logged" => Session::has('user'),
												 "user" => $user,
												 "message" => 'Nem találtunk alapértelmezett időt!',
												 "url" => '/ecnet/access']);
				}
				$new_time = $user->validationTime()->valid_date;
			}else{
				$new_time = $request->custom_valid_date.' 05:00:00';
			}
			DB::table('eir_user_data')->where('user_id', '=', $request->account)
									  ->update(['valid_time' => $new_time]);
			
			Notify::notify($user, $request->account, 'Internethozzáférés módosítva!', 'Az internethozzáférésed lejárati ideje módosítva lett erre a dátumra: '.str_replace("-", ". ", str_replace(" ", ". ", $new_time)), 'ecnet/access');
			return view('success.success', ["logged" => Session::has('user'),
											"user" => $user,
											"message" => 'Sikeresen módosítottuk a felhasználó internethozzáférésének idejét!',
											"url" => '/ecnet/access']);
		}else{
			return view('errors.authentication', ["logged" => Session::has('user'),
												  "user" => $user]);
		}
	}
	
	public function setMACAddresses(Request $request){
		$user = new EirUser(Session::get('user')->id);
		$addresses = [];
		$newAddresses = [];
		$existingAddresses = [];
		$deletableAddresses = [];

		foreach($user->macAddresses() as $address){
			if($request->input('mac_address_'.$address->id) != null){
				$this->validate($request, [
					'mac_address_'.$address->id => 'required|regex:/(^(?:[A-Fa-f0-9]{2}[\-:]){5}[A-Fa-f0-9]{2}$)/',
				]);
				array_push($addresses, $request->input('mac_address_'.$address->id));
			}
		}
		for($i = 0; $i < $user->eirUser()->mac_slots - count($user->macAddresses()); $i++){
			if($request->input('new_mac_address_'.$i) != null){
				$this->validate($request, [
					'new_mac_address_'.$i => 'required|regex:/(^(?:[A-Fa-f0-9]{2}[\-:]){5}[A-Fa-f0-9]{2}$)/',
				]);
				array_push($addresses, $request->input('new_mac_address_'.$i));
			}
		}
		//calculate existing and new addresses
		foreach($addresses as $address){
			if(DB::table('eir_mac_addresses')->where('mac_address', 'LIKE', $address)->first() == null){
				array_push($newAddresses, $address);
			}else{
				array_push($existingAddresses, $address);
				if(($key = array_search($address, $deletableAddresses)) !== false) {
					unset($deletableAddresses[$key]);
				}
			}
		}
		//calculate deletable addresses
		foreach($user->macAddresses() as $address){
			if(($key = array_search($address->mac_address, $addresses)) === false){
				array_push($deletableAddresses, $address->mac_address);
			}
		}
		
		//commit the changes
		foreach($deletableAddresses as $address){
			DB::table('eir_mac_addresses')->where('mac_address', 'LIKE', $address)->delete();
		}
		foreach($newAddresses as $address){
			DB::table('eir_mac_addresses')->insert(['user_id' => $user->user()->id, 'mac_address' => $address]);
		}
		
		return view('success.success', ["logged" => Session::has('user'),
										"user" => $user,
										"message" => 'A MAC címek sikeresen frissítve!',
										"url" => '/ecnet/access']);
	}
	
	public function getSlot(Request $request){
		$time = Carbon::now();
		$user = new EirUser(Session::get('user')->id);
        $this->validate($request, [
			'reason' => 'required',
		]);
		DB::table('eir_mac_slot_orders')->insert(['user_id' => Session::get('user')->id, 'reason' => $request->input('reason'), 'order_time' => $time->toDateTimeString()]);
		Notify::notifyAdmin($user, 'ecnet_slot_verify', 'MAC slot igénylés', 'MAC slot lett igényelve! Kérelem: '.$request->input('reason'), 'ecnet/order');
		return view('success.success', ["logged" => Session::has('user'),
										"user" => $user,
										"message" => 'A MAC slot igénylésed le lett adva!',
										"url" => '/ecnet/order']);
	}
	
	public function allowOrDenyOrder(Request $request){
		$user = new EirUser(Session::get('user')->id);
        $this->validate($request, [
			'optradio' => 'required',
			'slot' => 'required',
		]);
		if($user->permitted('ecnet_slot_verify')){
			$target = DB::table('eir_user_data')->join('eir_mac_slot_orders', 'eir_mac_slot_orders.user_id', '=', 'eir_user_data.user_id')
												->where('eir_mac_slot_orders.id', '=', $request->input('slot'))
												->first();
			if($target == null){
				return view('errors.error', ["logged" => Session::has('user'),
											 "user" => $user,
											 "message" => 'Valami probléma merült fel a slot jóváhagyásánál!',
											 "url" => '/ecnet/order']);
			}
			if($request->input('optradio') == "allow"){
				DB::table('eir_user_data')->where('user_id', '=', $target->user_id)
										  ->update(['mac_slots' => $target->mac_slots+1]);
				Notify::notify($user, $target->user_id, 'MAC slot igénylés', 'MAC slot igénylésed el lett fogadva! Kérelem: '.$target->reason, 'ecnet/access');
			}else{
				Notify::notify($user, $target->user_id, 'MAC slot igénylés', 'MAC slot igénylésed el lett utasítva! Kérelem: '.$target->reason, 'ecnet/order');
			}
			DB::table('eir_mac_slot_orders')->where('id', '=', $request->input('slot'))->delete();
			return view('success.success', ["logged" => Session::has('user'),
											"user" => $user,
											"message" => 'Sikeresen jóvá lett hagyva a slot igénylés!',
											"url" => '/ecnet/order']);
		}else{
			return view('errors.authentication', ["logged" => Session::has('user'),
												  "user" => $user]);
		}
	}
}
