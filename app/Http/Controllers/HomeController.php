<?php

namespace App\Http\Controllers;

use App\Classes\LayoutData;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/** Class name: HomeController
 *
 * This controller is for handling website home page related things.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class HomeController extends Controller
{
	/** Function name: __construct
	 *
	 * This function creates a new controller instance.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /** Function name: index
     *
     * This function shows the application dashboard.
     *
     * @return \Illuminate\Http\Response
     *
     * @author Máté Kovács <kovacsur10@gmail.com>
     */
    public function index()
    {
        return view('home', ["layout" => new LayoutData()]);
    }
}
