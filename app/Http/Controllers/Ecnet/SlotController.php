<?php

namespace App\Http\Controllers\Ecnet;

use App\Classes\LayoutData;
use App\Classes\Layout\EcnetData;
use App\Classes\Logger;
use App\Classes\Notifications;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

/** Class name: SlotController
 *
 * This controller is for handling the MAC slots.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class SlotController extends Controller{	
	
// PUBLIC FUNCTIONS
	/** Function name: showMACOrderForm
	 *
	 * This function shows MAC slot order page.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function showMACOrderForm(){
		$layout = new LayoutData();
		$layout->setUser(new EcnetData(Session::get('user')->id()));
		
		if($layout->user()->ecnetUser() === null){
			Logger::warning('Ecnet user was not found!', null, null, 'ecnet/order');
			return view('errors.usernotfound', ["layout" => $layout]);
		}else{
			return view('ecnet.slotordering', ["layout" => $layout]);
		}
	}
	
	/** Function name: getSlot
	 *
	 * This function tries to create a MAC slot order.
	 * 
	 * @param Request $request
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getSlot(Request $request){
		$layout = new LayoutData();
		$layout->setUser(new EcnetData(Session::get('user')->id()));
        $this->validate($request, [
			'reason' => 'required',
		]);
		try{
			$layout->user()->addMACSlotOrder($layout->user()->user()->id(), $request->input('reason'));
			Logger::log('New MAC slot order!', null, $request->input('reason'), 'ecnet/order');
			Notifications::notifyAdmin($layout->user()->user(), 'ecnet_slot_verify', $layout->language('mac_slot_ordering'), $layout->language('mac_slot_was_ordered_description').$request->input('reason'), 'ecnet/order');
			return view('success.success', ["layout" => $layout,
					"message" => $layout->language('success_at_sending_mac_slot_order'),
					"url" => '/ecnet/order']);
		}catch(\Exception $ex){
			Logger::warning('Cannot order a slot!', null, null, 'ecnet/order');
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_at_sending_mac_slot_order'),
										 "url" => '/ecnet/order']);
		}
	}
	
	/** Function name: allowOrDenyOrder
	 *
	 * This function allows or denies an existing MAC slot order.
	 * 
	 * @param Request $request
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function allowOrDenyOrder(Request $request){
		$layout = new LayoutData();
		$layout->setUser(new EcnetData(Session::get('user')->id()));
        $this->validate($request, [
			'optradio' => 'required',
			'slot' => 'required',
		]);
		if($layout->user()->permitted('ecnet_slot_verify')){
			try{
				$macSlotOrder = $layout->user()->getMacSlotOrderById($request->input('slot'));
				$targetUser = $layout->user()->getUserDataByUsername($macSlotOrder->username());
				$target = new EcnetData($targetUser->id());
			}catch(\Exception $ex){
				Logger::warning('Could not find a MAC slot order with id#'.print_r($request->input('slot'), true).'!', null, null, 'ecnet/order');
				return view('errors.error', ["layout" => $layout,
						"message" => $layout->language('error_at_allowing_mac_slot_order'),
						"url" => '/ecnet/order']);
			}
			if($request->input('optradio') === "allow"){
				try{
					$layout->user()->setMacSlotCountForUser($target->user()->id(), $target->ecnetUser()->maximumMacSlots()+1);
					Notifications::notify($layout->user()->user(), $target->user()->id(), $layout->language('mac_slot_ordering'), $layout->language('mac_slot_order_was_accepted_description').$macSlotOrder->reason(), 'ecnet/access');
				}catch(\Exception $ex){
					Logger::warning('Could not set the MAC slot count!', $target->ecnetUser()->maximumMacSlots(), $target->ecnetUser()->maximumMacSlots()+1, 'ecnet/order');
					return view('errors.error', ["layout" => $layout,
												"message" => $layout->language('error_at_allowing_mac_slot_order'),
												"url" => '/ecnet/order']);
				}
			}else{
				Notifications::notify($layout->user()->user(), $target->user()->id(), $layout->language('mac_slot_ordering'), $layout->language('mac_slot_order_was_denied_description').$macSlotOrder->reason(), 'ecnet/order');
			}
			try{
				$layout->user()->deleteMacSlotOrderById($request->input('slot'));
				Logger::log('MAC slot order was removed (accepted or denied)!', null, null, 'ecnet/order');
				return view('success.success', ["layout" => $layout,
						"message" => $layout->language('success_at_allowing_mac_slot_order'),
						"url" => '/ecnet/order']);
			}catch(\Exception $ex){
				Logger::warning('Could not delete the MAC slot order with id #'.print_r($request->input('slot'), true).'!', null, null, 'ecnet/order');
				return view('errors.error', ["layout" => $layout,
											"message" => $layout->language('error_at_allowing_mac_slot_order'),
											"url" => '/ecnet/order']);
			}
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
	}

// PRIVATE FUNCTIONS
	
}
