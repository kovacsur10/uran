<?php

namespace App\Persistence;

use DB;

/** Class name: P_Room
 *
 * This class is the database persistence layer class
 * for the room module tables.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class P_Room{
	
	/** Function name: getRoom
	 *
	 * This function returns the requested room.
	 *
	 * @param text $roomNumber - text identifier of the room
	 * @return Room|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getRoom($roomNumber){
		DB::table('rooms_rooms')
			->where('room_number', 'LIKE', $roomNumber)
			->first();
	}
	
	/** Function name: getRooms
	 *
	 * This function returns the rooms.
	 * 
	 * @return array of Rooms
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getRooms(){
		DB::table('rooms_rooms')
			->select('room_number as room', 'max_collegist_count', 'floor')
			->orderBy('id', 'asc')
			->get()
			->toArray();
	}
	
	/** Function name: getAssignmentTable
	 *
	 * This function returns the requested assignment table.
	 *
	 * @param text $tableName - text identifier of the assignment table
	 * @return AssignmentTable|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getAssignmentTable($tableName){
		DB::table('rooms_tables')
			->where('table_name', 'LIKE', $tableName)
			->first();
	}
	
	/** Function name: getAssignmentTables
	 *
	 * This function returns the assignment tables,
	 * excluded the requested one.
	 * 
	 * If the excluded table is null, there's no exluded table.
	 *
	 * @param text|null $excludedTable - excluded table name
	 * @return array of assignment tables
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getAssignmentTables($excludedTable = null){
		return DB::table('rooms_tables')
			->when($excludedTable !== null, function($query) use($excludedTable){
				return $query->where('table_name','NOT LIKE', $excludedTable);
			})
			->orderBy('id', 'asc')
			->get()
			->toArray();
	}
	
	/** Function name: getSelectedAssigmentTable
	 *
	 * This function returns the selected assignment table.
	 *
	 * @return AssignmentTable|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getSelectedAssigmentTable(){
		DB::table('rooms_tables')
			->where('selected', '=', true)
			->first();
	}
	
	/** Function name: selectAssignmentTable
	 *
	 * This function sets the requested table as selected.
	 *
	 * @param text $tableName - assignment table name
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function selectAssignmentTable($tableName){
		DB::table('rooms_tables')
			->where('table_name', 'LIKE', $tableName)
			->update([
					'selected' => true
			]);
	}
	
	/** Function name: unselectAssignmentTable
	 *
	 * This function sets the requested table as unselected.
	 *
	 * @param text $tableName - assignment table name
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function unselectAssignmentTable($tableName){
		DB::table('rooms_tables')
			->where('table_name', 'LIKE', $tableName)
			->update([
					'selected' => false
			]);
	}
	
	/** Function name: getModificationTime
	 *
	 * This function returns the last table
	 * modification time.
	 *
	 * @return timestamp
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getModificationTime(){
		DB::table('rooms_last_modified')
			->value('last_modified');
	}
	
	/** Function name: updateModificationTime
	 *
	 * This function updates the last modification
	 * time to the current one.
	 *
	 * @param timestamp $timestamp - update time
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function updateModificationTime($timestamp){
		DB::table('rooms_last_modified')
			->update([
					'last_modified' => $timestamp
			]);
	}
	
//DYNAMIC TABLES
	
	/** Function name: getRoomByUser
	 *
	 * This function returns the room, where the
	 * requested user lives.
	 *
	 * @param text $tableName - assignment table name
	 * @param int $userId - user's identifier
	 * @return Room|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getRoomByUser($tableName, $userId){
		return DB::table($tableName)
			->where('userid', '=', $userId)
			->first();
	}
	
	/** Function name: getRoomByUser
	 *
	 * This function returns the room, where the
	 * requested user lives.
	 *
	 * @param text $tableName - assignment table name
	 * @param text $roomNumber - room text identifier
	 * @return array of users
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getResidents($tableName, $roomNumber){
		return DB::table('rooms_rooms')
			->join($tableName, $tableName.'.roomid', '=', 'rooms_rooms.id')
			->join('users', 'users.id', '=', $tableName.'.userid')
			->select('users.id as id', 'users.name as name', 'users.username as username')
			->where('rooms_rooms.room_number', 'LIKE', $roomNumber)
			->get()
			->toArray();
	}
	
	/** Function name: clearRoom
	 *
	 * This function removes all assignments of the
	 * requested room.
	 *
	 * @param text $tableName - assignment table name
	 * @param int $roomNumber - room identifier
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function clearRoom($tableName, $roomId){
		DB::table($tableName)
			->where('roomid', '=', $roomId)
			->delete();
	}
	
	/** Function name: addUserToRoom
	 *
	 * This function creates a room-user assignment.
	 *
	 * @param text $tableName - assignment table name
	 * @param int $roomNumber - room identifier
	 * @param int $userId - user's identifier
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function addUserToRoom($tableName, $roomId, $userId){
		DB::table($tableName)
			->insert([
					'roomid' => $roomId,
					'userid' => $userId
			]);
	}
	
	/** Function name: addAssigmentTable
	 *
	 * This function creates a new room assignment database table
	 * and an entry for the maintaining.
	 *
	 * @param text $assignmentTableName - assignment table name as a database table
	 * @param text $tableName - assignment table name
	 * @param text $assignmentIdSeq - assignment sequence identifier
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function addAssigmentTable($assignmentTableName, $tableName, $assignmentIdSeq){
		try{
			P_General::beginTransaction();
			DB::statement('CREATE TABLE "'.$assignmentTableName.'" (id integer NOT NULL, userid integer NOT NULL, roomid integer NOT NULL)');
			DB::statement('ALTER TABLE "'.$assignmentTableName.'" OWNER TO laravel');
			DB::statement('COMMENT ON TABLE "'.$assignmentTableName.'" IS \'Rooms modul. Which room is assigned to a collegist.\'');
			DB::statement('CREATE SEQUENCE "'.$assignmentIdSeq.'" START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1');
			DB::statement('ALTER TABLE "'.$assignmentIdSeq.'" OWNER TO laravel');
			DB::statement('ALTER SEQUENCE "'.$assignmentIdSeq.'" OWNED BY "'.$assignmentTableName.'".id;');
			DB::statement('ALTER TABLE ONLY "'.$assignmentTableName.'" ALTER COLUMN id SET DEFAULT nextval(\''.$assignmentIdSeq.'\'::regclass)');
			DB::statement('ALTER TABLE ONLY "'.$assignmentTableName.'" ADD CONSTRAINT "'.$assignmentTableName.'_pkey" PRIMARY KEY (id)');
			DB::statement('ALTER TABLE ONLY "'.$assignmentTableName.'" ADD CONSTRAINT "'.$assignmentTableName.'_userid_unique" UNIQUE (userid)');
			DB::statement('ALTER TABLE ONLY "'.$assignmentTableName.'" ADD CONSTRAINT "'.$assignmentTableName.'_roomid_fkey" FOREIGN KEY (roomid) REFERENCES "rooms_rooms"(id) ON UPDATE CASCADE ON DELETE CASCADE');
			DB::statement('ALTER TABLE ONLY "'.$assignmentTableName.'" ADD CONSTRAINT "'.$assignmentTableName.'_userid_fkey" FOREIGN KEY (userid) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE');
			DB::table('rooms_tables')
				->insert([
						'table_name' => $tableName
				]);
			P_General::commit();
		}catch(\Exception $ex){
			P_General::rollback();
			throw $ex;
		}
	}
	
	/** Function name: removeAssignmentTable
	 *
	 * This function removes the requested room assignment database table
	 * and the entry for the maintaining.
	 *
	 * @param text $assignmentTableName - assignment table name as a database table
	 * @param text $tableName - assignment table name
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function removeAssignmentTable($assigmentTableName, $tableName){
		try{
			P_General::beginTransaction();
			DB::statement('DROP TABLE '.$assigmentTableName);
			DB::table('rooms_tables')
				->where('table_name', 'LIKE', $tableName)
				->delete();
			P_General::commit();
		}catch(\Exception $ex){
			P_General::rollback();
			throw $ex;
		}
	}
}