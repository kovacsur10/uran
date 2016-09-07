<?php

namespace App\Http\Controllers\Language;

use App\Classes\LayoutData;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller{

	public function set($language){
		$layout = new LayoutData();
		$layout->setLanguage($language);
		if($layout->logged()){
			$layout->user()->saveUserLanguage($language);
		}
		return redirect(isset($_GET['page']) ? $_GET['page'] : 'home');
	}

}
