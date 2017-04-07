<?php

namespace App\Http\Controllers\Ecnet;

use App\Classes\LayoutData;
use App\Classes\Auth;
use App\Classes\Layout\EcnetData;

/** Class name: SharedController
 *
 * This class contains helper functions for the ECnet controllers.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
 class SharedController {
	/** Function name: getEcnetLayout
	 *
	 * This function returns a layout data with
	 * the set ECnet user.
	 *
	 * @return LayoutData the layout with ECnet user set
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getEcnetLayout(){
		$layout = new LayoutData();
		$layout->setUser(new EcnetData(Auth::user()->id()));
		return $layout;
	}
}