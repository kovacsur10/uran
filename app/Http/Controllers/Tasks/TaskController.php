<?php

namespace App\Http\Controllers\Tasks;

use App\Classes\LayoutData;
use App\Classes\Notify;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use DB;
use Mail;

class TaskController extends Controller{
	
    public function show(){
		$layout = new LayoutData();
		return view('tasks.tasks', ["layout" => $layout]);
	}
	
}
