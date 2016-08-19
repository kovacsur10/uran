<?php

namespace App\Http\Controllers\Rooms;

use App\Classes\LayoutData;
use App\Classes\Notify;
use App\Classes\Database;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class RoomsController extends Controller{	
    public function showMap($level){
		$layout = new LayoutData();
		if($level == -2 || $level == -1 || $level == -1 || $level == 0 || $level == 1 || $level == 2 || $level == 3 || $level == 4 || $level == 5){
			return view('rooms.mapped', ["layout" => $layout,
										 "level" => $level]);
		}else{
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_floor_not_found'),
										 "url" => '/rooms/assigned/'.$level]);
		}
	}
	
	public function listRoomMembers($id){
		return view('rooms.showroom', ["layout" => new LayoutData(),
									   "room" => $id]);
	}
	
	public function assignResidents(Request $request){
		$layout = new LayoutData();
		if($layout->user()->permitted('rooms_assign')){
			$error = false;
			$roomId = $layout->room()->getRoomId($request->room);
			Database::beginTransaction(); //DATABASE TRANSACTION STARTS HERE
			if($roomId == null){
				$error = true;
			}else{
				$layout->room()->emptyRoom($roomId);
				for($i = 0; $i < $request->count; $i++){
					if($request->input('resident'.$i) != 0){
						try{
							$layout->room()->setUserToRoom($roomId, $request->input('resident'.$i));
						}catch(\Illuminate\Database\QueryException $e) {
							$error = true;
						}
					}
				}
			}
			
			if($error){
				Database::rollback();
				return view('errors.error', ["layout" => $layout,
											 "message" => $layout->language('error_already_lives_somewhere'),
											 "url" => '/rooms/room/'.$request->room]);
			}else{
				Database::commit();
				return view('rooms.showroom', ["layout" => new LayoutData(),
											   "room" => $request->room]);
			} //DATABASE TRANSACTION ENDS HERE
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
	}
}
