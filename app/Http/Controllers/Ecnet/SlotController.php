<?php

namespace App\Http\Controllers\Ecnet;

use App\Classes\LayoutData;
use App\Classes\Layout\EcnetData;
use App\Classes\Logger;
use App\Classes\Notifications;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class SlotController extends Controller{	
	
// PUBLIC FUNCTIONS
	
	public function showMACOrderForm(){
		$layout = new LayoutData();
		$layout->setUser(new EcnetData(Session::get('user')->id));
		
		if($layout->user()->ecnetUser() === null){
			Logger::warning('Ecnet user was not found!', null, null, 'ecnet/order');
			return view('errors.usernotfound', ["layout" => $layout]);
		}else{
			return view('ecnet.slotordering', ["layout" => $layout]);
		}
	}
	
	public function getSlot(Request $request){
		$layout = new LayoutData();
		$layout->setUser(new EcnetData(Session::get('user')->id));
        $this->validate($request, [
			'reason' => 'required',
		]);
		try{
			$layout->user()->addMACSlotOrder($layout->user()->user()->id, $request->input('reason'));
		}catch(\Exception $ex){
			Logger::warning('Cannot order a slot!', null, null, 'ecnet/order');
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_at_sending_mac_slot_order'),
										 "url" => '/ecnet/order']);
		}
		Logger::log('New MAC slot order!', null, $request->input('reason'), 'ecnet/order');
		Notifications::notifyAdmin($layout->user(), 'ecnet_slot_verify', $layout->language('mac_slot_ordering'), $layout->language('mac_slot_was_ordered_description').$request->input('reason'), 'ecnet/order');
		return view('success.success', ["layout" => $layout,
										"message" => $layout->language('success_at_sending_mac_slot_order'),
										"url" => '/ecnet/order']);
	}
	
	public function allowOrDenyOrder(Request $request){
		$layout = new LayoutData();
		$layout->setUser(new EcnetData(Session::get('user')->id));
        $this->validate($request, [
			'optradio' => 'required',
			'slot' => 'required',
		]);
		if($layout->user()->permitted('ecnet_slot_verify')){
			$target = $layout->user()->getMacSlotOrderById($request->input('slot'));
			if($target === null){
				Logger::warning('Could not find a MAC slot order with id#'.print_r($request->input('slot'), true).'!', null, null, 'ecnet/order');
				return view('errors.error', ["layout" => $layout,
											 "message" => $layout->language('error_at_allowing_mac_slot_order'),
											 "url" => '/ecnet/order']);
			}
			if($request->input('optradio') == "allow"){
				if($layout->user()->setMacSlotCountForUser($target->user_id, $target->mac_slots+1) !== 0){
					Logger::warning('Could not set the MAC slot count!', $target->mac_slots, $target->mac_slots+1, 'ecnet/order');
					return view('errors.error', ["layout" => $layout,
												"message" => $layout->language('error_at_allowing_mac_slot_order'),
												"url" => '/ecnet/order']);
				}
				Notifications::notify($layout->user(), $target->user_id, $layout->language('mac_slot_ordering'), $layout->language('mac_slot_order_was_accepted_description').$target->reason, 'ecnet/access');
			}else{
				Notifications::notify($layout->user(), $target->user_id, $layout->language('mac_slot_ordering'), $layout->language('mac_slot_order_was_denied_description').$target->reason, 'ecnet/order');
			}
			if($layout->user()->deleteMacSlotOrderById($request->input('slot')) !== 0){
				Logger::warning('Could not delete the MAC slot order with id #'.print_r($request->input('slot'), true).'!', null, null, 'ecnet/order');
				return view('errors.error', ["layout" => $layout,
											"message" => $layout->language('error_at_allowing_mac_slot_order'),
											"url" => '/ecnet/order']);
			}
			Logger::log('MAC slot order was removed (accepted or denied)!', null, null, 'ecnet/order');
			return view('success.success', ["layout" => $layout,
											"message" => $layout->language('success_at_allowing_mac_slot_order'),
											"url" => '/ecnet/order']);
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
	}

// PRIVATE FUNCTIONS
	
}
