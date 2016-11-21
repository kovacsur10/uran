<?php

namespace App\Http\Controllers\Rooms;

use App\Classes\LayoutData;
use App\Classes\Database;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Exceptions\ValueMismatchException;
use App\Exceptions\DatabaseException;

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
			$roomId = $layout->room()->getRoomId($request->room);
			try{
				Database::transaction(function() use($roomId, $guard, $layout, $request){
					if($roomId === null){
						throw new ValueMismatchException("The room identifier must not be null!");
					}else if(!$layout->room()->checkGuard($guard)){
						throw new DatabaseException("Guard checking was not successful!");
					}else{
						$layout->room()->emptyRoom($roomId);
						for($i = 0; $i < $request->count; $i++){
							if($request->input('resident'.$i) != 0){
								$layout->room()->setUserToRoom($roomId, $request->input('resident'.$i));
							}
						}
					}
				});
			}catch(ValueMismatchException $ex){
				return view('errors.error', ["layout" => $layout,
						"message" => $layout->language('error_already_lives_somewhere'),
						"url" => '/rooms/room/'.$request->room]);
			}catch(\Exception $ex){
				return view('errors.error', ["layout" => $layout,
						"message" => $layout->language('error_rooms_guard_mismatch'),
						"url" => '/rooms/room/'.$request->room]);
			}
			return view('rooms.showroom', ["layout" => new LayoutData(),
				"room" => $request->room]);
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
	}
	
	public function selectTable(Request $request, $level){
		$layout = new LayoutData();
		try{
			$layout->room()->selectTable($request->table_version);
		}catch(\Exception $ex){
			return view('errors.error', ["layout" => $layout,
					"message" => $layout->language('error_at_selecting_rooms_table'),
					"url" => '/rooms/map/2']);
		}
		$layout = new LayoutData();
		return redirect('rooms/map/'.$level);
	}
	
	public function addTable(Request $request, $level){
		$layout = new LayoutData();
		try{
			$layout->room()->addNewTable($request->newTableName);
		}catch(\Exception $ex){
			return view('errors.error', ["layout" => $layout,
				"message" => $layout->language('error_at_adding_new_rooms_table'),
				"url" => '/rooms/map/2']);
		}
		return redirect('rooms/map/'.$level);
	}
	
	public function removeTable(Request $request, $level){
		$layout = new LayoutData();
		try{
			$layout->room()->removeTable($request->table_version);
		}catch(\Exception $ex){
			return view('errors.error', ["layout" => $layout,
				"message" => $layout->language('error_at_removing_new_rooms_table'),
				"url" => '/rooms/map/2']);
		}
		return redirect('rooms/map/'.$level);
	}
}
