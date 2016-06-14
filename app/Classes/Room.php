<?php

namespace App\Classes;

use DB;

class Room{
	protected $rooms;
	
	public function __construct(){
		$this->user = $this->getRooms();
	}
	
	public function rooms(){
		return $this->rooms;
	}
	
	public function getResidents($room_number){
		return DB::table('rooms_rooms')
			->join('rooms_room_assignments', 'rooms_room_assignments.roomid', '=', 'rooms_rooms.id')
			->join('users', 'users.id', '=', 'rooms_room_assignments.userid')
			->select('users.id as id', 'users.name as name')
			->where('rooms_rooms.room_number', 'LIKE', $room_number)
			->get();
	}
	
	protected function getRooms(){
		return DB::table('rooms_rooms')
			->select('room_number as room', 'max_collegist_count')
			->get();
	}
}
