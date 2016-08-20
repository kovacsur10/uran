<?php

namespace App\Http\Controllers\Ecnet;

use App\Classes\LayoutData;
use App\Classes\Layout\EcnetUser;
use App\Classes\Notify;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class PrintingController extends Controller{

// PUBLIC FUNCTIONS

    public function showAccount(){
		$layout = new LayoutData();
		$layout->setUser(new EcnetUser(Session::get('user')->id));
		if($layout->user()->ecnetUser() === null){
			return view('errors.usernotfound', ["layout" => $layout]);
		}else{
			return view('ecnet.account', ["layout" => $layout,
										  "users" => $layout->user()->users()]);
		}
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
			$money = $layout->user()->getMoneyByUserId($request->account);
			if($money === null){
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
			$layout->user()->setMoneyForUser($request->account, $money);
			Notify::notify($layout->user(), $request->account, $layout->language('balance_was_modified'), $layout->language('balance_was_modified_description').' '.$oldmoney.' '.$layout->language('from_forint').' '.$money.' '.$layout->language('to_forint').'!', 'ecnet/account');
			return view('success.success', ["layout" => $layout,
											"message" => $layout->language('success_set_money'),
											"url" => '/ecnet/account']);
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
    }
	
// PRIVATE FUNCTIONS
}
