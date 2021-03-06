<?php

namespace App\Http\Controllers\Ecnet;

use App\Classes\Auth;
use App\Classes\Logger;
use App\Classes\Notifications;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/** Class name: PrintingController
 *
 * This controller is for handling the printing in the dormitory.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class PrintingController extends Controller{

// PUBLIC FUNCTIONS
	/** Function name: showAccount
	 *
	 * This function shows the money account page.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
    public function showAccount(){
    	$layout = SharedController::getEcnetLayout();
		if($layout->user()->ecnetUser() === null){
			Logger::error('Ecnet user was not found #'.print_r(Auth::user()->id(), true).'!', null, null, 'ecnet/account');
			return view('errors.usernotfound', ["layout" => $layout]);
		}else{
			return view('ecnet.account', ["layout" => $layout,
										  "users" => $layout->user()->users(0, -1)]);
		}
	}
	
	/** Function name: addMoney
	 *
	 * This function modifies a user's printing account.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function addMoney(Request $request){
		$layout = SharedController::getEcnetLayout();
        $this->validate($request, [
			'money' => 'required',
			'reset' => 'required',
            'account' => 'required',
		]);
		if($layout->user()->permitted('ecnet_set_print_account')){
			try{
				$money = $layout->user()->getEcnetUserData($request->account)->money();
			}catch(\Exception $ex){
				$money = null;
			}
			if($money === null){
				Logger::warning('At ecnet money modification for user #'.$request->account.'. No money for that user. Maybe that user does not exist!', $oldmoney, $money, 'ecnet/account');
				$layout->errors()->add('add_money', __('ecnet.error_at_money_adding'));
				return view('ecnet.account', ["layout" => $layout,
						"users" => $layout->user()->users(0, -1)]);
			}
			$oldmoney = $money;
			if($request->money === "0"){
				$money = $request->reset;
			}else{
				$money += $request->money;
			}
			try{
				$layout->user()->setMoneyForUser($request->account, $money);
				Logger::log('Ecnet money was modified for user #'.$request->account.'.', $oldmoney, $money, 'ecnet/account');
				Notifications::notify($layout->user()->user(), $request->account, __('ecnet.balance_was_modified'), __('ecnet.balance_was_modified_description').' '.$oldmoney.' '.__('ecnet.from_forint').' '.$money.' '.__('ecnet.to_forint').'!', 'ecnet/account');
				$layout->errors()->add('success_add_money', __('ecnet.success_set_money'));
				return view('ecnet.account', ["layout" => $layout,
						"users" => $layout->user()->users(0, -1)]);
			}catch(\Exception $ex){
				Logger::warning('At ecnet money modification for user #'.$request->account.'. Database error occured!', $oldmoney, $money, 'ecnet/account');
				$layout->errors()->add('add_money', __('ecnet.error_at_money_adding'));
				return view('ecnet.account', ["layout" => $layout,
						"users" => $layout->user()->users(0, -1)]);
			}
		}else{
			Logger::warning('At ecnet money modification for user #'.$request->account.'. PERMISSIONS NEEDED!', null, null, 'ecnet/account');
			return view('errors.authentication', ["layout" => $layout]);
		}
    }
    
    /** Function name: addFreePages
     *
     * This function modifies a user's printing account.
     *
     * @author Máté Kovács <kovacsur10@gmail.com>
     */
    public function addFreePages(Request $request){
    	$layout = SharedController::getEcnetLayout();
    	$this->validate($request, [
    			'pages' => 'required',
    			'valid_date' => 'required',
    			'account' => 'required',
    	]);
    	if($layout->user()->permitted('ecnet_set_print_account')){
    		try{
    			$freePages = $layout->user()->getEcnetUserData($request->account)->freePages();
    		}catch(\Exception $ex){
    			$freePages = null;
    		}
    		if($freePages=== null){
    			Logger::warning('At ecnet free pages modification for user #'.$request->account.'. No free pages for that user. Maybe that user does not exist!', $request->pages, $request->valid_date, 'ecnet/account');
    			$layout->errors()->add('add_freepages', __('ecnet.error_at_freepages_adding'));
    			return view('ecnet.account', ["layout" => $layout,
    					"users" => $layout->user()->users(0, -1)]);
    		}
    		try{
    			$layout->user()->addFreePagesForUser($request->account, $request->pages, $request->valid_date);
    			Logger::log('Ecnet free printing pages was modified for user #'.$request->account.'.', $request->pages, $request->valid_date, 'ecnet/account');
    			Notifications::notify($layout->user()->user(), $request->account, __('ecnet.freeprinting_balance_was_modified'), __('ecnet.freeprinting_balance_was_modified_description').'!', 'ecnet/account');
    			$layout->errors()->add('success_add_freepages', __('ecnet.success_add_freepages'));
    			return view('ecnet.account', ["layout" => $layout,
    					"users" => $layout->user()->users(0, -1)]);
    		}catch(\Exception $ex){
    			Logger::warning('At ecnet free pages modification for user #'.$request->account.'. Database error occured!', $request->pages, $request->valid_date, 'ecnet/account');
    			$layout->errors()->add('add_freepages', __('ecnet.error_at_freepages_adding'));
    			return view('ecnet.account', ["layout" => $layout,
    					"users" => $layout->user()->users(0, -1)]);
    		}
    	}else{
    		Logger::warning('At ecnet free pages modification for user #'.$request->account.'. PERMISSIONS NEEDED!', null, null, 'ecnet/account');
    		return view('errors.authentication', ["layout" => $layout]);
    	}
    }
	
// PRIVATE FUNCTIONS
}
