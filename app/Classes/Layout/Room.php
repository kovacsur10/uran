<?php

namespace App\Classes\Layout;

use App\Classes\Database;
use DB;

class Room{
	private $rooms;
	private $selectedTable; //this variable contains the selected table name (version)
	
	public function __construct(){
		$this->rooms = $this->getRooms('2016-2017-1');
		$this->selectedTable = $this->getSelectedTable();
	}
	
	public function activeTable(){
		return $this->selectedTable;
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
		DB::table(Room::getAssignmentTableName($this->selectedTable))
			->where('roomid', '=', $roomId)
			->delete();
	}
	
	public function setUserToRoom($roomId, $userId){
		DB::table(Room::getAssignmentTableName($this->selectedTable))
			->insert([
				'roomid' => $roomId,
				'userid' => $userId
			]);
	}
	
	public function getResidents($room_number){
		return DB::table('rooms_rooms')
			->join(Room::getAssignmentTableName($this->selectedTable), Room::getAssignmentTableName($this->selectedTable).'.roomid', '=', 'rooms_rooms.id')
			->join('users', 'users.id', '=', Room::getAssignmentTableName($this->selectedTable).'.userid')
			->select('users.id as id', 'users.name as name')
			->where('rooms_rooms.room_number', 'LIKE', $room_number)
			->get()
			->toArray();
	}
	
	public function getRoomResidentListText($roomNumber){
		$text = "";
		if($this->getResidents($roomNumber) !== null){
			foreach($this->getResidents($roomNumber) as $resident){
				$text .= $resident->name."<br>";
			}
		}
		for($i = 0; $i < $this->getFreePlaceCount($roomNumber); $i++){
			$text .= "Szabad hely<br>";
		}
		return $text;
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
		$ret = DB::table(Room::getAssignmentTableName($this->selectedTable))
			->where('userid', '=', $userid)
			->first();
		return $ret !== null;
	}
	
	public function addNewTable($tableName){
		$ret = false;
		if(!$this->tableExists($tableName)){
			$assignmentTable = Room::getAssignmentTableName($tableName);
			$assignmentIdSeq = $assignmentTable."_id_seq";
			Database::beginTransaction();
			try{	
				// create the assignment table
				DB::statement('CREATE TABLE "'.$assignmentTable.'" (id integer NOT NULL, userid integer NOT NULL, roomid integer NOT NULL)');
				DB::statement('ALTER TABLE "'.$assignmentTable.'" OWNER TO laravel');
				DB::statement('COMMENT ON TABLE "'.$assignmentTable.'" IS \'Rooms modul. Which room is assigned to a collegist.\'');
				DB::statement('CREATE SEQUENCE "'.$assignmentIdSeq.'" START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1');
				DB::statement('ALTER TABLE "'.$assignmentIdSeq.'" OWNER TO laravel');
				DB::statement('ALTER SEQUENCE "'.$assignmentIdSeq.'" OWNED BY "'.$assignmentTable.'".id;');
				DB::statement('ALTER TABLE ONLY "'.$assignmentTable.'" ALTER COLUMN id SET DEFAULT nextval(\''.$assignmentIdSeq.'\'::regclass)');		
				DB::statement('ALTER TABLE ONLY "'.$assignmentTable.'" ADD CONSTRAINT "'.$assignmentTable.'_pkey" PRIMARY KEY (id)');
				DB::statement('ALTER TABLE ONLY "'.$assignmentTable.'" ADD CONSTRAINT "'.$assignmentTable.'_userid_unique" UNIQUE (userid)');
				DB::statement('ALTER TABLE ONLY "'.$assignmentTable.'" ADD CONSTRAINT "'.$assignmentTable.'_roomid_fkey" FOREIGN KEY (roomid) REFERENCES "rooms_rooms"(id) ON UPDATE CASCADE ON DELETE CASCADE');
	 			DB::statement('ALTER TABLE ONLY "'.$assignmentTable.'" ADD CONSTRAINT "'.$assignmentTable.'_userid_fkey" FOREIGN KEY (userid) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE');
	 			
	 			DB::table('rooms_tables')
	 				->insert([
	 					'table_name' => $tableName	
	 				]);
	 			Database::commit();
	 			$ret = true;
			}finally{
				Database::rollback();
			}
		}
		return $ret;
	}
	
	public function removeTable($tableName){
		if(!$this->tableExists($tableName) || $tableName === $this->selectedTable){
			return false;
		}else{
			Database::beginTransaction();
			try{
				DB::statement('DROP TABLE '.Room::getAssignmentTableName($tableName));
				DB::table('rooms_table')
					->where('table_name', 'LIKE', $tableName)
					->delete();
				Database::commit();
				return true;
			}finally{
				Database::rollback();
				return false;
			}
		}
	}
	
	public function selectTable($tableName){
		$ret = false;
		if($this->tableExists($tableName)){
			Database::beginTransaction();
			try{
				DB::table('rooms_tables')
					->where('table_name', 'LIKE', $this->selectedTable)
					->update([
						'selected' => 0
					]);
				DB::table('rooms_tables')
					->where('table_name', 'LIKE', $tableName)
					->update([
						'selected' => 1
					]);
				Database::commit();
				$ret = true;
			}finally{
				Database::rollback();
			}
		}
		return $ret;
	}
	
	public function getTables(){
		return DB::table('rooms_tables')
			->orderBy('id', 'asc')
			->get()
			->toArray();
	}
	
	private static function getAssignmentTableName($table){
		return "rooms_" . $table . "_room_assignments";
	}
	
	private function getRooms($table){
		return DB::table('rooms_rooms')
			->select('room_number as room', 'max_collegist_count', 'floor')
			->orderBy('id', 'asc')
			->get()
			->toArray();
	}
	
	private function getSelectedTable(){
		$ret = DB::table('rooms_tables')
			->where('selected', '=', true)
			->first();
		if($ret === []){
			return "";
		}else{
			return $ret->table_name;
		}
	}
	
	private function tableExists($tableName){
		$ret = DB::table('rooms_tables')
			->where('table_name', 'LIKE', $tableName)
			->first();
		return $ret !== null;
	}
	
}
