<?php

namespace App\Http\Controllers\Ecnet;

use App\Classes\LayoutData;
use App\Classes\Logger;
use App\Classes\Layout\EcnetData;
use App\Classes\Notifications;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
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
		$layout = new LayoutData();
		$layout->setUser(new EcnetData(Session::get('user')->id()));
		if($layout->user()->ecnetUser() === null){
			Logger::error('Ecnet user was not found #'.print_r(Session::get('user')->id(), true).'!', null, null, 'ecnet/account');
			return view('errors.usernotfound', ["layout" => $layout]);
		}else{
			return view('ecnet.account', ["layout" => $layout,
										  "users" => $layout->user()->users()]);
		}
	}
	
	/** Function name: addMoney
	 *
	 * This function modifies a user's printing account.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function addMoney(Request $request){
		$layout = new LayoutData();
		$layout->setUser(new EcnetData(Session::get('user')->id()));
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
				$layout->errors()->add('add_money', $layout->language('error_at_money_adding'));
				return view('ecnet.account', ["layout" => $layout,
						"users" => $layout->user()->users()]);
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
				Notifications::notify($layout->user()->user(), $request->account, $layout->language('balance_was_modified'), $layout->language('balance_was_modified_description').' '.$oldmoney.' '.$layout->language('from_forint').' '.$money.' '.$layout->language('to_forint').'!', 'ecnet/account');
				$layout->errors()->add('success_add_money', $layout->language('success_set_money'));
				return view('ecnet.account', ["layout" => $layout,
						"users" => $layout->user()->users()]);
			}catch(\Exception $ex){
				Logger::warning('At ecnet money modification for user #'.$request->account.'. Database error occured!', $oldmoney, $money, 'ecnet/account');
				$layout->errors()->add('add_money', $layout->language('error_at_money_adding'));
				return view('ecnet.account', ["layout" => $layout,
						"users" => $layout->user()->users()]);
			}
		}else{
			Logger::warning('At ecnet money modification for user #'.$request->account.'. PERMISSIONS NEEDED!', null, null, 'ecnet/account');
			return view('errors.authentication', ["layout" => $layout]);
		}
    }
	
// PRIVATE FUNCTIONS
}
