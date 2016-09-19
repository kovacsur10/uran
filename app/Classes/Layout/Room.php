<?php

namespace App\Classes\Layout;

use DB;

class Room{
	protected $rooms;
	
	public function __construct(){
		$this->rooms = $this->getRooms();
	}
	
	public function rooms(){
		return $this->rooms;
	}
	
	public function getRoomId($roomNumber){
		$roomId	= DB::table('rooms_rooms')
			->where('room_number', 'LIKE', $roomNumber)
			->select('id')
			->first();
		return $roomId === null ? null : $roomId->id;
	}
	
	public function emptyRoom($roomId){
		DB::table('rooms_room_assignments')
			->where('roomid', '=', $roomId)
			->delete();
	}
	
	public function setUserToRoom($roomId, $userId){
		DB::table('rooms_room_assignments')
			->insert([
				'roomid' => $roomId,
				'userid' => $userId
			]);
	}
	
	public function getResidents($room_number){
		return DB::table('rooms_rooms')
			->join('rooms_room_assignments', 'rooms_room_assignments.roomid', '=', 'rooms_rooms.id')
			->join('users', 'users.id', '=', 'rooms_room_assignments.userid')
			->select('users.id as id', 'users.name as name')
			->where('rooms_rooms.room_number', 'LIKE', $room_number)
			->get()
			->toArray();
	}
	
	public function getFreePlaceCount($room_number){
		$max_count = DB::table('rooms_rooms')
			->select('max_collegist_count as count')
			->where('room_number', 'LIKE', $room_number)
			->first();
		$residents = count($this->getResidents($room_number));
		return $max_count->count - $residents;
	}
	
	public function getFreePlaces(){
		$freePlaces = [];
		if($this->rooms !== null){
			foreach($this->rooms as $room){
				$countOfPlaces = $this->getFreePlaceCount($room->room);
				if($countOfPlaces > 0){
					array_push($freePlaces, [$room->room, $countOfPlaces]);
				}
			}
		}
		return $freePlaces;
	}
	
	public function userHasResidence($userid){
		$ret = DB::table('rooms_room_assignments')
			->where('userid', '=', $userid)
			->first();
		return $ret !== null;
	}
	
	protected function getRooms(){
		return DB::table('rooms_rooms')
			->select('room_number as room', 'max_collegist_count', 'floor')
			->orderBy('id', 'asc')
			->get()
			->toArray();
	}
	
}
