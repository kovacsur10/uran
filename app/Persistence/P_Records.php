<?php

namespace App\Persistence;

use DB;
use App\Classes\Data\Committee;
use App\Classes\Data\Record;
use Illuminate\Support\Facades\Storage;

/** Class name: P_Records
 *
 * This class is the database persistence layer class
 * for the records module tables.
 *
 * @author Norbert Luksa <norbert.luksa@gmail.com>
 */
class P_Records{
	
	/** Function name: addRecord
	 *
	 * This function creates a new record based on the
	 * provided data.
	 *
     * @param $recordId - identifier of record
     * @param $createdById - identifier of uploader
     * @param $file - the uploaded file
     * @param $fileName - the given fileName
     * @param $committee - the given Committee
     * @param $meetingDate - the date of the meeting
     * @param $uploadDate - the time of the uploading
     *
     * @author Norbert Luksa <norbert.luksa@gmail.com>
	 */
	static function addRecord($createdById, $file, $fileName, $committee, $meetingDate, $uploadDate){
		DB::table('ecouncil_record')
			->insert([
					'filename' => $fileName,
					'committee' => $committee->id(),
					'meeting_date' => $meetingDate,
					'upload_date' => $uploadDate,
					'uploader_id' => $createdById
			]);
		$file->storeAs('', $uploadDate.'_'.$createdById.'.pdf', 'records');
	}
	
	/** Function name: updateRecord
	 * 
	 * This function updates the requested record
	 * with the provided data.
     *
     * @param $recordId - identifier of record
     * @param $createdById - identifier of uploader
     * @param $file - the uploaded file
     * @param $fileName - the given fileName
     * @param $committee - the given Committee
     * @param $meetingDate - the date of the meeting
     * @param $uploadDate - the time of the uploading
	 *
     * @author Norbert Luksa <norbert.luksa@gmail.com>
	 */
	static function updateRecord($recordId, $createdById, $file, $fileName, $committee, $meetingDate, $uploadDate){
		DB::table('ecouncil_record')
			->where('id', '=', $recordId)
			->update([
					'filename' => $fileName,
					'committee' => $committee->id(),
					'meeting_date' => $meetingDate,
					'upload_date' => $uploadDate,
					'uploader_id' => $createdById
			]);
        $file->storeAs('', $uploadDate.'_'.$createdById.'.pdf', 'records');
	}
	
	
	/** Function name: getRecords
	 *
	 * This function returns the records.
	 * @return array of Record
	 *
     * @author Norbert Luksa <norbert.luksa@gmail.com>
	 */
	static function getRecords(){
		$getRecords = DB::table('ecouncil_record')
			->orderBy('ecouncil_record.id', 'desc')
			->get();
		$records = [];
		foreach($getRecords as $record){
			$records[] = new Record(
				$record->id,
				$record->filename,
				P_Records::getCommitteeById($record->committee),
				$record->meeting_date,
				$record->upload_date,
				$record->uploader_id);
		}
		return $records;
	}
	
	//TODO
	/** Function name: removeRecord
	 *
	 * This function virtually removes a record.
	 *
	 * @param int $recordId - record identifier
	 *
     * @author Norbert Luksa <norbert.luksa@gmail.com>
	 *
	static function removeTask($recordId){
		DB::table('ecouncil_record')
			->where('id', '=', $recordId)
			->update([
				'deleted' => true
			]);
	}*/
	

	/** Function name: getCommittees
	 *
	 * This function returns the committees.
	 *
	 * @param bool $onlyActive - if true, only active committees are returned
	 *
	 * @return array of Committee
	 *
     * @author Norbert Luksa <norbert.luksa@gmail.com>
	 */
	static function getCommittees($onlyActive){
		$getCommittees = DB::table('ecouncil_committee')
			->when($onlyActive, function ($query) {
				return $query->where('ecouncil_committee.is_active', '=', true);
			})
			->orderBy('id', 'asc')
			->get();
		$committees = [];
		foreach($getCommittees as $committee){
			array_push($committees, new Committee($committee->id, $committee->name, $committee->is_active));
		}
		return $committees;
	}

    /** Function name: getCommitteeById
     *
     * This function returns the committees.
     *
     * @param int $id - the id of the requested committee
     *
     * @return null|Committee
     *
     * @author Norbert Luksa <norbert.luksa@gmail.com>
     */
	static function getCommitteeById($id){
		$getCommittees = DB::table('ecouncil_committee')
			->where('id', '=', $id)
			->first();
		return $getCommittees === null ? null : new Committee($getCommittees->id, $getCommittees->name, $getCommittees->is_active);
	}
}