<?php

namespace App\Http\Controllers\Rooms;

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

class RoomsController extends Controller{	
    public function showMap($level){
		$layout = new LayoutData();
		if($level == -2 || $level == -1 || $level == -1 || $level == 0 || $level == 1 || $level == 2 || $level == 3 || $level == 4 || $level == 5){
			return view('rooms.mapped', ["layout" => $layout,
										 "level" => $level]);
		}else{
			return view('errors.error', ["layout" => $layout,
										 "message" => 'Nincsen ilyen emelet a Collegiumban!',
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
			$roomid = DB::table('rooms_rooms')
				->where('room_number', 'LIKE', $request->room)
				->select('id')
				->first();
			DB::beginTransaction(); //DATABASE TRANSACTION STARTS HERE
			DB::table('rooms_room_assignments')
				->where('roomid', '=', $roomid->id)
				->delete();
			for($i = 0; $i < $request->count; $i++){
				if($request->input('resident'.$i) != 0){
					try{
						DB::table('rooms_room_assignments')
							->insert(['roomid' => $roomid->id, 'userid' => $request->input('resident'.$i)]);
					}catch(\Illuminate\Database\QueryException $e) {
						$error = true;
					}
				}
			}
			
			if($error){
				DB::rollback();
				return view('errors.error', ["layout" => $layout,
											 "message" => 'Már másik szobában lakik ez a személy!',
											 "url" => '/rooms/room/'.$request->room]);
			}else{
				DB::commit();
				return view('rooms.showroom', ["layout" => new LayoutData(),
											   "room" => $request->room]);
			} //DATABASE TRANSACTION ENDS HERE
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
	}
}
