<?php

namespace App\Classes\Data;

use App\Classes\Data\User;
use App\Classes\Data\Committee;

/** Class name: Record
 *
 * This class stores a Record.
 *
 * @author Norbert Luksa <norbert.luksa@gmail.com>
 */
class Record{

	// PRIVATE DATA
	private $id;
	private $filename;
	private $committee;
	private $meeting_date;
	private $upload_date;
	private $uploader_id;

	// PUBLIC FUNCTIONS

	/** Function name: __construct
	 *
	 * This is the constructor for the Record class.
	 *
	 * @param int $id
	 * @param string $filename
	 * @param string $committee
	 * @param string $meeting_date
	 * @param string $upload_date
	 * @param int $uploader_id
	 *
	 * @author Norbert Luksa <norbert.luksa@gmail.com>
	 */
	public function __construct(int $id, string $filename, Committee $committee, string $meeting_date, string $upload_date, int $uploader_id){
		$this->id = $id;
		$this->filename = $filename;
		$this->committee = $committee;
		$this->meeting_date = $meeting_date;
		$this->upload_date = $upload_date;
		$this->uploader_id = $uploader_id;
	}

	/** Function name: id
	 *
	 * This is the getter for id.
	 *
	 * @return int - The identifier of the record.
	 *
	 * @author Norbert Luksa <norbert.luksa@gmail.com>
	 */
	public function id() : int{
		return $this->id;
	}
	
	/** Function name: filename
	 *
	 * This is the getter for filename.
	 *
	 * @return string - The filename of the record.
	 *
	 * @author Norbert Luksa <norbert.luksa@gmail.com>
	 */
	public function filename() : string{
		return $this->filename;
	}
	
	/** Function name: committee
	 *
	 * This is the getter for committee.
	 *
	 * @return string - The committee of the record.
	 *
	 * @author Norbert Luksa <norbert.luksa@gmail.com>
	 */
	public function committee() : Committee{
		return $this->committee;
	}
	
	/** Function name: meeting_date
	 *
	 * This is the getter for meeting_date.
	 *
	 * @return string - The date of the record's meeting.
	 *
	 * @author Norbert Luksa <norbert.luksa@gmail.com>
	 */
	public function meeting_date() : string{
		return $this->meeting_date;
	}
	
	/** Function name: upload_date
	 *
	 * This is the getter for upload_date.
	 *
	 * @return string - The upload time of the record.
	 *
	 * @author Norbert Luksa <norbert.luksa@gmail.com>
	 */
	public function upload_date(){
		return $this->upload_date;
	}
	
	/** Function name: uploader_id
	 *
	 * This is the getter for uploader_id.
	 *
	 * @return int - The id of the record's uploader.
	 *
	 * @author Norbert Luksa <norbert.luksa@gmail.com>
	 */
	public function uploader_id(){
		return $this->uploader_id;
	}
	
	/** Function name: file_name
	 *
	 * This is function returns the file's name on the server.
	 *
	 * @return string - The file's name
	 *
	 * @author Norbert Luksa <norbert.luksa@gmail.com>
	 */
	public function file_name(){
		return $this->upload_date . '_' . $this->uploader_id . '.pdf';
	}
}