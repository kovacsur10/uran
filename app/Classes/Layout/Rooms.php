<?php

namespace App\Classes\Layout;

use App\Classes\Database;
use App\Classes\Logger;
use Carbon\Carbon;
use App\Persistence\P_Room;
use App\Exceptions\DatabaseException;
use App\Exceptions\RoomNotFoundException;
use App\Exceptions\UserNotFoundException;
use Illuminate\Contracts\Session\Session;

/** Class name: Rooms
 *
 * This class handles the database operations
 * of the Rooms module.
 * 
 * Functionality:
 * 		- user and room assignment
 * 		- more assignment table handling
 * 
 * Functions that can throw exceptions:
 * 		refreshGuard
 * 
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class Rooms{
	
// PRIVATE DATA
	
	private $rooms; //array of the existing rooms of the dormitory
	private $selectedTable; //this variable contains the selected table name (version)
	
// PUBLIC FUNCTIONS
	
	/** Function name: __construct
	 *
	 * Constuctor of class Rooms.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(){
		$this->rooms = $this->getRooms();
		$this->selectedTable = $this->getSelectedTable();
	}
	
	/** Function name: activeTable
	 *
	 * Getter function of $selectedTable.
	 * 
	 * @return text - selected assignment table name
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function activeTable(){
		return $this->selectedTable;
	}
	
	/** Function name: rooms
	 *
	 * Getter function of $rooms.
	 * 
	 * @return array of the rooms
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function rooms(){
		return $this->rooms;
	}
	
	/** Function name: getRoomId
	 *
	 * This function returns the id of a room.
	 * 
	 * @param text $roomNumber - text identifier of the room
	 * @return int|null - room identifier
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getRoomId($roomNumber){
		if($roomNumber === null){
			return null;
		}
		try{
			$room = P_Room::getRoom($roomNumber);
		}catch(Exception $ex){
			$room = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
		return $room === null ? null : $room->id();
	}
	
	/** Function name: emptyRoom
	 * 
	 * This function removes all user assignments
	 * to the room.
	 * 
	 * @param int $roomId - identifier of the room
	 * 
	 * @throws RoomNotFoundException when the room was not found.
	 * @throws DatabaseException when the clear proccess fails.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function emptyRoom($roomId){
		if($roomId === null){
			throw new RoomNotFoundException();
		}
		try{
			P_Room::clearRoom(Rooms::getAssignmentTableName($this->selectedTable), $roomId);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Could not clear the room!");
		}
	}
	
	/** Function name: setUserToRoom
	 *
	 * This function assignes a user to a room.
	 * 
	 * @param int $roomId - identifier of the room
	 * @param int $userId - identifier of the user
	 * 
	 * @throws DatabaseException when the assignment was unsuccessful!
	 * @throws RoomNotFoundException when the provided room number is invalid!
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function setUserToRoom($roomId, $userId){
		if($roomId === null){
			throw new RoomNotFoundException();
		}else if($userId === null){
			throw new UserNotFoundException();
		}
		$roomName = $this->getNumberById($roomId);
		if(Rooms::getFreePlaceCount($roomName) == 0){
			throw new DatabaseException("The room is full!");
		}
		if($this->userHasResidence($userId)){
			throw new DatabaseException("The user lives elsewhere!");
		}
		try{
			P_Room::addUserToRoom(Rooms::getAssignmentTableName($this->selectedTable), $roomId, $userId);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Room-user assignment was not successful!");
		}
	}
	
	/** Function name: getResidents
	 *
	 * This function returns an array of the
	 * residents of the requested room. It returns
	 * the id and the name of the users.
	 * 
	 * @param text $roomNumber - identifier of the room
	 * @return array of User
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getResidents($roomNumber){
		if($roomNumber === null){
			return [];
		}
		try{
			$residents = P_Room::getResidents(Rooms::getAssignmentTableName($this->selectedTable), $roomNumber);
		}catch(\Exception $ex){
			$residents = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
		return $residents;
	}
	
	/** Function name: getRoomResidentListText
	 *
	 * This function returns an HTML formatted text
	 * of the residents of the room. Free places are 
	 * in the list as well.
	 * 
	 * @param text $roomNumber - identifier of the room
	 * @param text $freeSpotText - text of "Free spot"
	 * @return text - resident list of the room
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getRoomResidentListText($roomNumber, $freeSpotText){
		$text = "";
		foreach($this->getResidents($roomNumber) as $resident){
			$text .= $resident->name()."<br>";
		}
		for($i = 0; $i < $this->getFreePlaceCount($roomNumber); $i++){
			$text .= $freeSpotText."<br>";
		}
		return $text;
	}
	
	/** Function name: getFreePlaceCount
	 *
	 * This function returns the count of the
	 * free places in the requested room.
	 * 
	 * @param text $roomNumber - identifier of the room
	 * @return int - count of free places in the room
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getFreePlaceCount($roomNumber){
		if($roomNumber === null){
			return 0;
		}
		try{
			$room = P_Room::getRoom($roomNumber);
			if($room === null){
				throw new DatabaseException("Room not found!");
			}
			$residents = count($this->getResidents($roomNumber));
			$freePlaceCount = $room->maxCollegistCount() - $residents;
		}catch(\Exception $ex){
			$freePlaceCount = 0;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
		return $freePlaceCount;
	}
	
	/** Function name: getFreePlaces
	 *
	 * This function returns an array which
	 * contains the free places in the dormitory.
	 * 
	 * @return array of free places
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getFreePlaces(){
		$freePlaces = [];
		if($this->rooms !== null){
			foreach($this->rooms as $room){
				$countOfPlaces = $this->getFreePlaceCount($room->roomNumber());
				if($countOfPlaces > 0){
					array_push($freePlaces, (object) [ 
							"room" => $room->roomNumber(), 
							"places" => $countOfPlaces
					]);
				}
			}
		}
		return $freePlaces;
	}
	
	/** Function name: userHasResidence
	 *
	 * This function returns whether the user is assigned
	 * to a room or not.
	 * 
	 * @param int $userId - id of the user
	 * @return bool - the user lives in a room or not
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function userHasResidence($userId){
		if($userId === null){
			return false;
		}
		try{
			$ret = P_Room::getRoomByUser(Rooms::getAssignmentTableName($this->selectedTable), $userId);
		}catch(\Exception $ex){
			$ret = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
		return $ret !== null;
	}
	
	/** Function name: addNewTable
	 *
	 * This function adds a new assignment table
	 * to the database.
	 * True is returned if the assignment table creation
	 * was successful.
	 * False is returned if the creation failed.
	 * Failures can be: 
	 * 		- database exception on statements or insert
	 * 		- already existing table
	 * 
	 * @param text $tableName - name of the assignment table
	 * 
	 * @throws DatabaseException when the addition process fails.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function addNewTable($tableName){
		if(Rooms::tableExists($tableName) || $tableName == "" || $tableName === null){
			throw new DatabaseException("Table already exists!");
		}
		$assignmentTable = Rooms::getAssignmentTableName($tableName);
		$assignmentIdSeq = $assignmentTable."_id_seq";
		try{	
 			P_Room::addAssigmentTable($assignmentTable, $tableName, $assignmentIdSeq);
		}catch(Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Adding a new table failed.");
		}
	}
	
	/** Function name: removeTable
	 *
	 * This function removes an assignment table from
	 * the database.
	 * True is returned if the assignment table deletion
	 * was successful.
	 * False is returned if the deletion failed.
	 * Failures can be:
	 * 		- database exception on delete
	 * 		- not existing table
	 * 
	 * @param text $tableName - name of the assignment table
	 * 
	 * @throws DatabaseException when the remove process fails.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function removeTable($tableName){
		if(!Rooms::tableExists($tableName) || $tableName == $this->selectedTable || $tableName == "" || $tableName === null){
			throw new DatabaseException("Table not exist or it is the active one!");
		}
		try{
			P_Room::removeAssignmentTable(Rooms::getAssignmentTableName($tableName), $tableName);
		}catch(Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Removing the table failed!");
		}
	}

	/** Function name: selectTable
	 *
	 * This function is used to select the
	 * currently used assignment table.
	 * True is returned if the assignment table selection
	 * was successful.
	 * False is returned if the selection failed.
	 * Failures can be: database exception on update.
	 * 
	 * @param text $tableName - name of the assignment table
	 * 
	 * @throws DatabaseException when the selection process fails.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function selectTable($tableName){
		if(!Rooms::tableExists($tableName) || $tableName === null){
			throw new DatabaseException("Table not found!");
		}
		try{
			Database::transaction(function() use($tableName){
				P_Room::unselectAssignmentTable($this->selectedTable);
				P_Room::selectAssignmentTable($tableName);
				$this->refreshGuard();
			});
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Table selection failed!");
		}
	}
	
	/** Function name: getTables
	 *
	 * This function returns an array of the
	 * existing assignment tables.
	 * If there's no assignment table,
	 * it returns an empty array.
	 * 
	 * @return array of the AssignmentTable
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getTables(){
		try{
			$tables = P_Room::getAssignmentTables();
		}catch(Exception $ex){
			$tables = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
		return $tables;
	}
	
	/** Function name: getTablesEX
	 *
	 * This function returns an array of the existing assignment
	 * tables excluding the currently selected one.
	 * If there's no assignment table,
	 * it returns an empty array.
	 * 
	 * @return array of the AssignmentTable
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getTablesEX(){
		try{
			$tables = P_Room::getAssignmentTables($this->selectedTable);
		}catch(Exception $ex){
			$tables = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'rooms_tables' was not successful! ".$ex->getMessage());
		}
		return $tables;
	}
	
	/** Function name: checkGuard
	 *
	 * This function checks whether the known (old)
	 * guard is the same as the current guard.
	 * 
	 * @param timestamp $guard - known guard value
	 * @return bool - same or not
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function checkGuard($guard){
		if($guard === null){
			return false;
		}
		return Rooms::getGuard() == $guard;
	}
	
	/** Function name: getGuard
	 *
	 * This functions returns the currently active
	 * rooms guard.
	 *
	 * INCONSISTENCY IF THE RETURNED VALUE IS NULL
	 * 
	 * @return int|null - guard value
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getGuard(){
		try{
			$ret = P_Room::getModificationTime();
		}catch(Exception $ex){
			$ret = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
		return $ret;
	}
	
	/** Function name: checkLevel
	 *
	 * This function returns the level, which should be shown.
	 *
	 * @param int $level - input level
	 * @return int - the level to show
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function checkLevel($level){
		if($level === null){
			if(session()->has('rooms_show_level')){
				$level = session()->get('rooms_show_level');
			}else{
				$level = 2;
			}
		}
		if(!($level == -2 || $level == -1 || $level == -1 || $level == 0 || $level == 1 || $level == 2 || $level == 3 || $level == 4 || $level == 5)){
			$level = 2;
		}
		session()->put('rooms_show_level', $level);
		return $level;
	}
	
	/** Function name: getSessionData
	 *
	 * This function returns an array or values, that
	 * should be saved as session data for the rooms.
	 *
	 * @return array of mixed - the returned values
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getSessionData(){
		$sessionData = [];
		if(session()->has('rooms_show_level')){
			$sessionData['rooms_show_level'] = session()->get('rooms_show_level');
		}
		return $sessionData;
	}
	
// PRIVATE FUNCTIONS
	
	/** Function name: getAssignmentTableName
	 *
	 * This functions returns the name of
	 * the assignment table linked to the
	 * table identifier name.
	 * 
	 * @param text $table - the identifier name of the assignment table
	 * @return text - the assignment table name
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	private static function getAssignmentTableName($table){
		return "rooms_" . $table . "_room_assignments";
	}
	
	/** Function name: getRooms
	 *
	 * This functions returns the existing rooms
	 * with room number, capacity and the floor
	 * in the dormitory.
	 * 
	 * @return array of the Room
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	private function getRooms(){
		try{
			$rooms = P_Room::getRooms();
		}catch(\Exception $ex){
			$rooms = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'rooms_rooms' was not successful! ".$ex->getMessage());
		}
		return $rooms;
	}
	
	/** Function name: getSelectedTable
	 *
	 * This functions returns the name of the 
	 * currently selected (active) assignment table.
	 * 
	 * @return text - name of the currently selected table
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	private function getSelectedTable(){
		try{
			$tableName = P_Room::getSelectedAssigmentTableName();
		}catch(\Exception $ex){
			$tableName = "";
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'rooms_tables' was not successful! ".$ex->getMessage());
		}
		return $tableName;
	}
	
	/** Function name: tableExists
	 *
	 * This functions returns true, if the
	 * given table exist in the database.
	 * It returs false, if it's not in the
	 * database or the table name is an empty string.
	 * 
	 * @param text $tableName - the identifier name of the assignment table
	 * @return bool - the table exist or not
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	private static function tableExists($tableName){
		try{
			$tables = P_Room::getAssignmentTable($tableName);
		}catch(Exception $ex){
			$tables = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
		return $tables !== null || $tableName == "";
	}
	
	/** Function name: refreshGuard
	 *
	 * This functions refreshes the guard, which protects
	 * the room assignments if a table swap was made.
	 * 
	 * @exception CustomException
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	private function refreshGuard(){
		try{
			P_Room::updateModificationTime(Carbon::parse(Carbon::now())->timestamp);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'rooms_last_modified' was not successful! ".$ex->getMessage());
			throw new Exception("CUSTOM EXCEPTION! The previous log message contains the error!");
		}
	}
	
	/** Function name: getNumberById
	 *
	 * This functions looks up a room number based on the id.
	 * 
	 * @param int $roomId - room identifier
	 * @return text - room number
	 *
	 * @exception RoomNotFoundException when the room is not found!
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	private function getNumberById($roomId){
		$i = 0;
		while($i < count($this->rooms) && $this->rooms[$i]->id() != $roomId){
			$i++;
		}
		if($i < count($this->rooms)){
			return $this->rooms[$i]->roomNumber();
		}else{
			throw new RoomNotFoundException;
		}
	}
		
}
