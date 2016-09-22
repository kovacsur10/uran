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
		$roomId	= DB::table(Room::getRoomsTableName($this->selectedTable))
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
		return DB::table(Room::getRoomsTableName($this->selectedTable))
			->join(Room::getAssignmentTableName($this->selectedTable), Room::getAssignmentTableName($this->selectedTable).'.roomid', '=', Room::getRoomsTableName($this->selectedTable).'.id')
			->join('users', 'users.id', '=', Room::getAssignmentTableName($this->selectedTable).'.userid')
			->select('users.id as id', 'users.name as name')
			->where(Room::getRoomsTableName($this->selectedTable).'.room_number', 'LIKE', $room_number)
			->get()
			->toArray();
	}
	
	public function getFreePlaceCount($room_number){
		$max_count = DB::table(Room::getRoomsTableName($this->selectedTable))
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
		if(tableExists($tableName)){
			return false;
		}else{
			$roomsTable = Room::getRoomsTableName($tableName);
			$roomsIdSeq = $roomsTable."_id_seq";
			$assignmentTable = Room::getAssignmentTableName($tableName);
			$assignmentIdSeq = $assignmentTable."_id_seq";
			
			Database::beginTransaction();
			try{
				//create the rooms table
				DB::raw('CREATE TABLE "'.$roomsTable.'" (id integer NOT NULL, room_number character varying(255) NOT NULL, max_collegist_count integer DEFAULT 0 NOT NULL, floor integer DEFAULT 2 NOT NULL);');
				DB::raw('ALTER TABLE "'.$roomsTable.'" OWNER TO laravel;');
				DB::raw('COMMENT ON TABLE "'.$roomsTable.'" IS \'Rooms modul. Available rooms.\';');
				DB::raw('CREATE SEQUENCE "'.$roomsIdSeq.'" START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;');
				DB::raw('ALTER TABLE "'.$roomsIdSeq.'" OWNER TO laravel;');
				DB::raw('ALTER SEQUENCE "'.$roomsIdSeq.'" OWNED BY "'.$roomsTable.'".id;');
				DB::raw('ALTER TABLE ONLY "'.$roomsTable.'" ALTER COLUMN id SET DEFAULT nextval(\''.$roomsIdSeq.'\'::regclass);');
				DB::raw('ALTER TABLE ONLY "'.$roomsTable.'"	ADD CONSTRAINT "'.$roomsTable.'_pkey" PRIMARY KEY (id);');
				DB::raw('ALTER TABLE ONLY "'.$roomsTable.'"	ADD CONSTRAINT "'.$roomsTable.'_room_number_unique" UNIQUE (room_number);');
				
				// create the assignment table
				DB::raw('CREATE TABLE "'.$assignmentTable.'" id integer NOT NULL, userid integer NOT NULL, roomid integer NOT NULL);');
				DB::raw('ALTER TABLE "'.$assignmentTable.'" OWNER TO laravel;');
				DB::raw('COMMENT ON TABLE "'.$assignmentTable.'" IS \'Rooms modul. Which room is assigned to a collegist.\';');
				DB::raw('CREATE SEQUENCE "'.$assignmentIdSeq.'" START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;');
				DB::raw('ALTER TABLE "'.$assignmentIdSeq.'" OWNER TO laravel;');
				DB::raw('ALTER SEQUENCE "'.$assignmentIdSeq.'" OWNED BY "'.$assignmentTable.'".id;');
				DB::raw('ALTER TABLE ONLY "'.$assignmentTable.'" ALTER COLUMN id SET DEFAULT nextval(\''.$assignmentIdSeq.'\'::regclass);');		
				DB::raw('ALTER TABLE ONLY "'.$assignmentTable.'" ADD CONSTRAINT "'.$assignmentTable.'_pkey" PRIMARY KEY (id);');
				DB::raw('ALTER TABLE ONLY "'.$assignmentTable.'" ADD CONSTRAINT "'.$assignmentTable.'_userid_unique" UNIQUE (userid);');
				DB::raw('ALTER TABLE ONLY "'.$assignmentTable.'" ADD CONSTRAINT "'.$assignmentTable.'_roomid_fkey" FOREIGN KEY (roomid) REFERENCES "'.$roomsTable.'"(id) ON UPDATE CASCADE ON DELETE CASCADE;');
	 			DB::raw('ALTER TABLE ONLY "'.$assignmentTable.'" ADD CONSTRAINT "'.$assignmentTable.'_userid_fkey" FOREIGN KEY (userid) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE;');
	 			
	 			DB::table('rooms_tables')
	 				->insert([
	 					'table_name' => $tableName	
	 				]);
	 			
	 			Database::rollback();
			}finally{
				Database::commit();
			}
		}
	}
	
	public function removeTable($tableName){
		if(!tableExists($tableName) || $tableName === $this->selectedTable){
			return false;
		}else{
			DB::raw('DROP TABLE '.getAssignmentTableName($tableName).';');
			DB::raw('DROP TABLE '.getRoomsTableName($tableName).';');
			return true;
		}
	}
	
	public function selectTable($tableName){
		if(!tableExists($tableName)){
			return false;
		}else{
			DB::table('rooms_tables')
				->where('table_name', 'LIKE', $tableName)
				->update([
					'selected' => true
				]);
		}
	}
	
	public function getTables(){
		return DB::table('rooms_tables')
			->get()
			->toArray();
	}
	
	private static function getRoomsTableName($table){
		return "rooms_" . $table . "_rooms";
	}
	
	private static function getAssignmentTableName($table){
		return "rooms_" . $table . "_room_assignments";
	}
	
	private function getRooms($table){
		return DB::table(Room::getRoomsTableName($table))
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
			->where('table_name', '=', $tableName)
			->first();
		return $ret !== [];
	}
	
}
