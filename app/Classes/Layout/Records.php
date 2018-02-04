<?php

namespace App\Classes\Layout;

use Carbon\Carbon;
use App\Classes\LayoutData;
use App\Classes\Logger;
use App\Persistence\P_Records;
use App\Classes\Interfaces\Pageable;
use App\Exceptions\ValueMismatchException;
use App\Exceptions\DatabaseException;
use App\Classes\Database;
use Illuminate\Http\Request;

/** Class name: Records
 *
 * This class handles the records data
 * support in the layout namespace.
 *
 * Functions that can throw exceptions:
 * addRecord
 *
 * @author Norbert Luksa <norbert.luksa@gmail.com>
 */
class Records extends Pageable{
	
// PRIVATE DATA
	
	private $records;
	private $record;
	private $types;
	
// PUBLIC FUNCTIONS
    
    /** Function name: __construct
     *
     * The constructor for the Records class.
     * 
	 * @author Norbert Luksa <norbert.luksa@gmail.com>
     */
	public function __construct(){
		$this->getRecords();
		//$this->types = $this->getRecordTypes();
		$this->record = null;
	}
	
	/** Function name: get
	 *
	 * Getter function for records.
	 * 
	 * @return array of Records
	 * 
	 * @author Norbert Luksa <norbert.luksa@gmail.com>
	 */
	public function get(){
        return $this->records;
	}
	
	/** Function name: addRecord
	 *
	 * This function creates a new record with the
	 * given data.
	 * 
	 * @param int $createdById - creator user's identifier
	 * @param $file - uploaded file
     * @param $fileName - given fileName
     * @param $committeeId - given Committee's identifier
     * @param $meetingDate - given date of meeting
	 * 
	 * @throws ValueMismatchException when the file format is not pdf.
	 * @throws DatabaseException when the process failed!
	 * 
	 * @author Norbert Luksa <norbert.luksa@gmail.com>
	 */
	public function addRecord($createdById, $file, $fileName, $committeeId, $meetingDate){
		$committee = P_Records::getCommitteeById($committeeId);
		if($createdById === null || $file === null || $fileName === null || $committee === null || $meetingDate === null){
			throw new ValueMismatchException("A parameter is null, but cannot be that!");
		}
		if($file->getClientOriginalExtension() != "pdf"){
			throw new ValueMismatchException("Invalid file format! It should be pdf!");
		}
		try{
			$time = Carbon::now()->toDateTimeString();
			Database::transaction(function() use($createdById, $file, $fileName, $committee, $meetingDate, $time){
				P_Records::addRecord($createdById, $file, $fileName, $committee, $meetingDate, $time);
				$this->getRecords();
			});
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Record addition was not successful!");
		}
	}

	/** Function name: getRecords
	 *
	 * This function returns the records
	 * 
	 * @author Norbert Luksa <norbert.luksa@gmail.com>
	 */
    public function getRecords(){
		try{
			$this->records = P_Records::getRecords();				
		}catch(\Exception $ex){
			$this->records = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
		return $this->records;
	}
	
	/** Function name: getCommittees
	 *
	 * This function returns the committees.
	 * 
	 * @author Norbert Luksa <norbert.luksa@gmail.com>
	 */
    public function getCommittees($onlyActive = true){
		try{
			$committees = P_Records::getCommittees($onlyActive);				
		}catch(\Exception $ex){
			$committees = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
		return $committees;
	}
	
	/** Function name: recordsToPages
	 *
	 * This function returns the requested count
	 * of records from the requested identifier.
	 * 
	 * @param int $from - first record identifier
	 * @param int $count - records count
	 * @return array of Record
	 * 
	 * @author Norbert Luksa <norbert.luksa@gmail.com>
	 */
	public function recordsToPages($from = 0, $count = 50){
		return $this->toPages($this->records, $from, $count);
	}
}