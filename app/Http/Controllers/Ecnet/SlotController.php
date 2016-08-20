<?php

namespace App\Http\Controllers\Ecnet;

use App\Classes\LayoutData;
use App\Classes\Layout\EcnetUser;
use App\Classes\Notify;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class SlotController extends Controller{	
	
// PUBLIC FUNCTIONS
	
	public function showMACOrderForm(){
		$layout = new LayoutData();
		$layout->setUser(new EcnetUser(Session::get('user')->id));
		return view('ecnet.slotordering', ["layout" => $layout]);
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
			$target = $layout->user()->getMacSlotOrderById($request->input('slot'));
			if($target === null){
				return view('errors.error', ["layout" => $layout,
											 "message" => $layout->language('error_at_allowing_mac_slot_order'),
											 "url" => '/ecnet/order']);
			}
			if($request->input('optradio') == "allow"){
				$layout->user()->setMacSlotCountForUser($target->user_id, $target->mac_slots+1);
				Notify::notify($layout->user(), $target->user_id, $layout->language('mac_slot_ordering'), $layout->language('mac_slot_order_was_accepted_description').$target->reason, 'ecnet/access');
			}else{
				Notify::notify($layout->user(), $target->user_id, $layout->language('mac_slot_ordering'), $layout->language('mac_slot_order_was_denied_description').$target->reason, 'ecnet/order');
			}
			$layout->user()->deleteMacSlotOrderById($request->input('slot'));
			return view('success.success', ["layout" => $layout,
											"message" => $layout->language('success_at_allowing_mac_slot_order'),
											"url" => '/ecnet/order']);
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
	}

// PRIVATE FUNCTIONS
	
}
