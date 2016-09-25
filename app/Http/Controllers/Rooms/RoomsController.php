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
    public function showMap($level = 2){
		$layout = new LayoutData();
		if($level == -2 || $level == -1 || $level == -1 || $level == 0 || $level == 1 || $level == 2 || $level == 3 || $level == 4 || $level == 5){
			return view('rooms.mapped', ["layout" => $layout,
										 "level" => $level]);
		}else{
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_floor_not_found'),
										 "url" => '/rooms/map/'.$level]);
		}
	}
	
	public function listRoomMembers($id){
		return view('rooms.showroom', ["layout" => new LayoutData(),
									   "room" => $id]);
	}
	
	public function downloadList(){
		return view('rooms.download', ["layout" => new LayoutData()]);
	}
	
	public function assignResidents(Request $request, $guard){
		$layout = new LayoutData();
		if($layout->user()->permitted('rooms_assign')){
			$error = 0;
			$roomId = $layout->room()->getRoomId($request->room);
			Database::beginTransaction(); //DATABASE TRANSACTION STARTS HERE
			if($roomId === null){
				$error = 1;
			}else if(!$layout->room()->checkGuard($guard)){
				$error = 2;
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
			
			if($error === 0){
				Database::commit();
				return view('rooms.showroom', ["layout" => new LayoutData(),
						"room" => $request->room]);
			}else if($error === 2){
				Database::rollback();
				return view('errors.error', ["layout" => $layout,
						"message" => $layout->language('error_rooms_guard_mismatch'),
						"url" => '/rooms/room/'.$request->room]);
			}else{
				Database::rollback();
				return view('errors.error', ["layout" => $layout,
						"message" => $layout->language('error_already_lives_somewhere'),
						"url" => '/rooms/room/'.$request->room]);
			} //DATABASE TRANSACTION ENDS HERE
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
	}
	
	public function selectTable(Request $request, $level){
		$layout = new LayoutData();
		if($layout->room()->selectTable($request->table_version)){
			$layout = new LayoutData();
			return redirect('rooms/map/'.$level);
		}else{
			return view('errors.error', ["layout" => $layout,
					"message" => $layout->language('error_at_selecting_rooms_table'),
					"url" => '/rooms/map/2']);
		}
	}
	
	public function addTable(Request $request, $level){
		$layout = new LayoutData();
		if($layout->room()->addNewTable($request->newTableName)){
			return redirect('rooms/map/'.$level);
		}else{
			return view('errors.error', ["layout" => $layout,
				"message" => $layout->language('error_at_adding_new_rooms_table'),
				"url" => '/rooms/map/2']);
		}
	}
	
	public function removeTable(Request $request, $level){
		$layout = new LayoutData();
		if($layout->room()->removeTable($request->table_version)){
			return redirect('rooms/map/'.$level);
		}else{
			return view('errors.error', ["layout" => $layout,
				"message" => $layout->language('error_at_removing_new_rooms_table'),
				"url" => '/rooms/map/2']);
		}
	}
}
