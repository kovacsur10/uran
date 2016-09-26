<?php

namespace App\Http\Controllers\ECouncil;

use App\Classes\LayoutData;
use App\Classes\Notify;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RecordController extends Controller{
	
// PUBLIC FUNCTIONS
	
    public function show(){
		$layout = new LayoutData();
		return view('ecouncil.records', ["layout" => $layout]);
	}
	
	public function showRecord($id){
		$layout = new LayoutData();
		$layout->records()->setRecord($id);
		return view('tasks.task', ["layout" => $layout]);
	}
	
	public function add(){
		$layout = new LayoutData();
		return view('tasks.add', ["layout" => $layout]);
	}
	
	
// PRIVATE FUNCTIONS
	
	private function inArray($value, $array){
		$i = 0;
		while($i < count($array) && $array[$i]->id != $value){
			$i++;
		}
		return $i < count($array);
	}
}



