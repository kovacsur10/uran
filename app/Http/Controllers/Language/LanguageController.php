<?php

namespace App\Http\Controllers\Language;

use App\Classes\LayoutData;
use App\Http\Controllers\Controller;

/** Class name: LanguageController
 *
 * This controller is for handling the website language.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class LanguageController extends Controller{

	/** Function name: set
	 *
	 * This function sets the language of the website.
	 * 
	 * @param string $language - the language to use/set
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function set($language){
		$layout = new LayoutData();
		LayoutData::setLanguage($language);
		if($layout->logged()){
			$layout->user()->saveUserLanguage($language);
		}
		return redirect(isset($_GET['page']) ? $_GET['page'] : 'home');
	}

}
