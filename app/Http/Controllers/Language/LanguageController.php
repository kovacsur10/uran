<?php

namespace App\Http\Controllers\Language;

use App\Http\Controllers\Controller;
use App\Classes\LayoutData;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller{

	public function set($language){
		if(Session::has('lang')){
			Session::forget('lang');
		}
		Session::put('lang', $language);
		return redirect(isset($_GET['page']) ? $_GET['page'] : 'home');
	}

}
