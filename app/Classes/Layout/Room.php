<?php

namespace App\Classes\Layout;

use App\Classes\Database;
use App\Classes\Logger;
use DB;
use Carbon\Carbon;

/* Class name: Room
 *
 * This class handles the database operations
 * of the Rooms module.
 * 
 * Functionality:
 * 		- user and room assignment
 * 		- more assignment table handling
 */
class Room{
	
// PRIVATE DATA
	
	private $rooms; //array of the existing rooms of the dormitory
	private $selectedTable; //this variable contains the selected table name (version)
	
// PUBLIC FUNCTIONS
	
	/* Function name: __construct
	 * Input: -
	 * Output: -
	 *
	 * Constuctor of class Room.
	 */
	public function __construct(){
		$this->rooms = $this->getRooms();
		$this->selectedTable = $this->getSelectedTable();
	}
	
	/* Function name: activeTable
	 * Input: -
	 * Output: text (selected assignment table name)
	 *
	 * Getter function of $selectedTable.
	 */
	public function activeTable(){
		return $this->selectedTable;
	}
	
	/* Function name: rooms
	 * Input: -
	 * Output: array of the rooms
	 *
	 * Getter function of $rooms.
	 */
	public function rooms(){
		return $this->rooms;
	}
	
	/* Function name: getRoomId
	 * Input: $roomNumber (text) - identifier of the room
	 * Output: int|NULL (room identifier)
	 *
	 * This function returns the id of a room.
	 */
	public function getRoomId($roomNumber){
		try{
			$roomId	= DB::table('rooms_rooms')
				->where('room_number', 'LIKE', $roomNumber)
				->select('id')
				->first();
		}catch(Exception $ex){
			$roomId = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'rooms_rooms' was not successful! ".$ex->getMessage());
		}
		return $roomId === null ? null : $roomId->id;
	}
	
	/* Function name: emptyRoom
	 * Input: $roomId (int) - identifier of the room
	 * Output: -
	 *
	 * This function removes all user assignments
	 * to the room.
	 */
	public function emptyRoom($roomId){
		try{
			DB::table(Room::getAssignmentTableName($this->selectedTable))
				->where('roomid', '=', $roomId)
				->delete();
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Delete from table '".Room::getAssignmentTableName($this->selectedTable)."' was not successful! ".$ex->getMessage());
		}
	}
	
	/* Function name: setUserToRoom
	 * Input: 	$roomId (int) - identifier of the room
	 * 			$userId (int) - identifier of the user
	 * Output: error code (int)
	 *
	 * This function assignes a user to a room.
	 */
	public function setUserToRoom($roomId, $userId){
		$ret = 0;
		try{
			DB::table(Room::getAssignmentTableName($this->selectedTable))
				->insert([
					'roomid' => $roomId,
					'userid' => $userId
				]);
		}catch(\Exception $ex){
			$ret = 1;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Insert into table '".Room::getAssignmentTableName($this->selectedTable)."' was not successful! ".$ex->getMessage());
		}
		return $ret;
	}
	
	/* Function name: getResidents
	 * Input: $roomNumber (text) - identifier of the room
	 * Output: array of residents of a room
	 *
	 * This function returns an array of the
	 * residents of the requested room. It returns
	 * the id and the name of the users.
	 */
	public function getResidents($roomNumber){
		try{
			$residents = DB::table('rooms_rooms')
				->join(Room::getAssignmentTableName($this->selectedTable), Room::getAssignmentTableName($this->selectedTable).'.roomid', '=', 'rooms_rooms.id')
				->join('users', 'users.id', '=', Room::getAssignmentTableName($this->selectedTable).'.userid')
				->select('users.id as id', 'users.name as name')
				->where('rooms_rooms.room_number', 'LIKE', $roomNumber)
				->get()
				->toArray();
		}catch(\Exception $ex){
			$residents = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'rooms_rooms' joined to '".Room::getAssignmentTableName($this->selectedTable)."' and 'users' was not successful! ".$ex->getMessage());
		}
		return $residents;
	}
	
	/* Function name: getRoomResidentListText
	 * Input: 	$roomNumber (text) - identifier of the room
	 * 			$freeSpotText (text) - text of "Free spot"
	 * Output: text (resident list of the room)
	 *
	 * This function returns an HTML formatted text
	 * of the residents of the room. Free places are 
	 * in the list as well.
	 */
	public function getRoomResidentListText($roomNumber, $freeSpotText){
		$text = "";
		foreach($this->getResidents($roomNumber) as $resident){
			$text .= $resident->name."<br>";
		}
		for($i = 0; $i < $this->getFreePlaceCount($roomNumber); $i++){
			$text .= $freeSpotText."<br>";
		}
		return $text;
	}
	
	/* Function name: getFreePlaceCount
	 * Input: $roomNumber (text) - identifier of the room
	 * Output: int (count of free places in the room)
	 *
	 * This function returns the count of the
	 * free places in the requested room.
	 */
	public function getFreePlaceCount($roomNumber){
		try{
			$max_count = DB::table('rooms_rooms')
				->select('max_collegist_count as count')
				->where('room_number', 'LIKE', $roomNumber)
				->first();
			$residents = count($this->getResidents($roomNumber));
			$freePlaceCount = $max_count->count - $residents;
		}catch(\Exception $ex){
			$freePlaceCount = 0;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'rooms_rooms' was not successful! ".$ex->getMessage());
		}
		return $freePlaceCount;
	}
	
	/* Function name: getFreePlaces
	 * Input: -
	 * Output: array of free places
	 *
	 * This function returns an array which
	 * contains the free places in the dormitory.
	 */
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
	
	/* Function name: userHasResidence
	 * Input: $userid (int) - id of the user
	 * Output: bool (the user lives in a room or not)
	 *
	 * This function returns whether the user is assigned
	 * to a room or not.
	 */
	public function userHasResidence($userId){
		try{
			$ret = DB::table(Room::getAssignmentTableName($this->selectedTable))
				->where('userid', '=', $userId)
				->first();
		}catch(\Exception $ex){
			$ret = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table '".Room::getAssignmentTableName($this->selectedTable)."' was not successful! ".$ex->getMessage());
		}
		return $ret !== null;
	}
	
	/* Function name: addNewTable
	 * Input: $tableName (string) - name of the assignment table
	 * Output: bool
	 *
	 * This function adds a new assignment table
	 * to the database.
	 * True is returned if the assignment table creation
	 * was successful.
	 * False is returned if the creation failed.
	 * Failures can be: 
	 * 		- database exception on statements or insert
	 * 		- already existing table
	 */
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
			}catch(Exception $ex){
				Database::rollback();
				Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			}
		}
		return $ret;
	}
	
	/* Function name: removeTable
	 * Input: $tableName (string) - name of the assignment table
	 * Output: bool
	 *
	 * This function removes an assignment table from
	 * the database.
	 * True is returned if the assignment table deletion
	 * was successful.
	 * False is returned if the deletion failed.
	 * Failures can be:
	 * 		- database exception on delete
	 * 		- not existing table
	 */
	public function removeTable($tableName){
		$ret = false;
		if($this->tableExists($tableName) && $tableName !== $this->selectedTable){
			Database::beginTransaction();
			try{
				DB::statement('DROP TABLE '.Room::getAssignmentTableName($tableName));
				DB::table('rooms_tables')
					->where('table_name', 'LIKE', $tableName)
					->delete();
				Database::commit();
				$ret = true;
			}catch(Exception $ex){
				Database::rollback();
				Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			}
		}
		return $ret;
	}

	/* Function name: selectTable
	 * Input: $tableName (string) - name of the assignment table
	 * Output: bool
	 *
	 * This function is used to select the
	 * currently used assignment table.
	 * True is returned if the assignment table selection
	 * was successful.
	 * False is returned if the selection failed.
	 * Failures can be: database exception on update.
	 */
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
				$this->refreshGuard();
				$ret = true;
			}catch(Exception $ex){
				Database::rollback();
				Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			}
		}
		return $ret;
	}
	
	/* Function name: getTables
	 * Input: -
	 * Output: array of the assignment tables
	 *
	 * This function returns an array of the
	 * existing assignment tables.
	 * If there's no assignment table,
	 * it returns an empty array.
	 */
	public function getTables(){
		try{
			$tables = DB::table('rooms_tables')
				->orderBy('id', 'asc')
				->get()
				->toArray();
		}catch(Exception $ex){
			$tables = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'rooms_tables' was not successful! ".$ex->getMessage());
		}
		return $tables;
	}
	
	/* Function name: getTablesEX
	 * Input: -
	 * Output: array of the assignment tables
	 *
	 * This function returns an array of the existing assignment
	 * tables excluding the currently selected one.
	 * If there's no assignment table,
	 * it returns an empty array.
	 */
	public function getTablesEX(){
		try{
			$tables = DB::table('rooms_tables')
				->where('table_name','NOT LIKE', $this->selectedTable)
				->orderBy('id', 'asc')
				->get()
				->toArray();
		}catch(Exception $ex){
			$tables = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'rooms_tables' was not successful! ".$ex->getMessage());
		}
		return $tables;
	}
	
	/* Function name: checkGuard
	 * Input: $guard (known guard value, timestamp)
	 * Output: bool (same or not)
	 *
	 * This function checks whether the known (old)
	 * guard is the same as the current guard.
	 */
	public function checkGuard($guard){
		return $this->getGuard() === $guard;
	}
	
	/* Function name: getGuard
	 * Input: -
	 * Output: int/NULL (guard value)
	 *
	 * This functions returns the currently active
	 * rooms guard.
	 *
	 * INCONSISTENCY IF THE RETURNED VALUE IS NULL
	 */
	public function getGuard(){
		try{
			$ret = DB::table('rooms_last_modified')
				->first();
		}catch(Exception $ex){
			$ret = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'rooms_last_modified' was not successful! ".$ex->getMessage());
		}
		return $ret === null ? null : $ret->last_modified;
	}
	
// PRIVATE FUNCTIONS
	
	/* Function name: getAssignmentTableName
	 * Input: $table (string) - the identifier name of the assignment table
	 * Output: string (the assignment table name)
	 *
	 * This functions returns the name of
	 * the assignment table linked to the
	 * table identifier name.
	 */
	private static function getAssignmentTableName($table){
		return "rooms_" . $table . "_room_assignments";
	}
	
	/* Function name: getRooms
	 * Input: -
	 * Output: array of the rooms
	 *
	 * This functions returns the existing rooms
	 * with room number, capacity and the floor
	 * in the dormitory.
	 */
	private function getRooms(){
		try{
			$rooms = DB::table('rooms_rooms')
				->select('room_number as room', 'max_collegist_count', 'floor')
				->orderBy('id', 'asc')
				->get()
				->toArray();
		}catch(\Exception $ex){
			$rooms = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'rooms_rooms' was not successful! ".$ex->getMessage());
		}
		return $rooms;
	}
	
	/* Function name: getSelectedTable
	 * Input: -
	 * Output: string (name of the currently selected table)
	 *
	 * This functions returns the name of the 
	 * currently selected (active) assignment table.
	 */
	private function getSelectedTable(){
		try{
			$table = DB::table('rooms_tables')
				->where('selected', '=', true)
				->first();
		}catch(\Exception $ex){
			$table = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'rooms_tables' was not successful! ".$ex->getMessage());
		}
		if($table === null){
			return "";
		}else{
			return $table->table_name;
		}
	}
	
	/* Function name: tableExists
	 * Input: $tableName (string) - the identifier name of the assignment table
	 * Output: bool (the table exist or not)
	 *
	 * This functions returns true, if the
	 * given table exist in the database.
	 * It returs false, if it's not in the
	 * database or the table name is an empty string.
	 */
	private function tableExists($tableName){
		try{
			$tables = DB::table('rooms_tables')
				->where('table_name', 'LIKE', $tableName)
				->first();
		}catch(Exception $ex){
			$tables = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'rooms_tables' was not successful! ".$ex->getMessage());
		}
		return $tables !== null || $tableName == "";
	}
	
	/* Function name: refreshGuard
	 * Input: -
	 * Output: -
	 *
	 * This functions refreshes the guard, which protects
	 * the room assignments if a table swap was made.
	 * 
	 * THROWING EXCEPTIONS!
	 */
	private function refreshGuard(){
		try{
			DB::table('rooms_last_modified')
				->update([
					'last_modified' => Carbon::parse(Carbon::now())->timestamp
				]);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'rooms_last_modified' was not successful! ".$ex->getMessage());
			throw new Exception("CUSTOM EXCEPTION! The previous log message contains the error!");
		}
	}
		
}
