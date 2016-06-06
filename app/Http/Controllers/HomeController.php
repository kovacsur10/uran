<?php

namespace App\Http\Controllers;

use App\Classes\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home', ["logged" => Session::has('user'),
							 "user" => new User(Session::get('user') == null ? null : Session::get('user')->id)]);
    }
}
