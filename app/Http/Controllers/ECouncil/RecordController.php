<?php

namespace App\Http\Controllers\ECouncil;

use App\Classes\LayoutData;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/** Class name: RecordController
 *
 * This controller is for handling the council's records.
 *
 * @author Norbert Luksa <norbert.luksa@gmail.com>
 */
class RecordController extends Controller{
	
// PUBLIC FUNCTIONS
	/** Function name: show
	 *
	 * This function returns the view for the records.
     *
     * @param $count - number of records to show
     * @param $first - first record to show (for pagination)
	 *
     * @author Norbert Luksa <norbert.luksa@gmail.com>
	 */
    public function show($count = 10, $first = 0){
		if($first < 0 || !is_numeric($first)){
			$first = 0;
		}
		if($count < 0 || !is_numeric($count)){
			$count = 10;
		}
		$first -= ($first % $count);
		$layout = new LayoutData();
		return view('ecouncil.records', ["layout" => $layout,
									"recordsToShow" => $count,
									"firstRecord" => $first]);
	}
	
	/** Function name: showRecord
	 *
     * This function returns the given file as a response.
     *
	 * @param $filename - the given filename
	 *
     * @author Norbert Luksa <norbert.luksa@gmail.com>
	 */
	public function showRecord($filename){
		return response()->file(storage_path("app/records/" . $filename));
	}
	
	/** Function name: add
	 *
	 * This function adds a new record.
     *
     * @param Request $request
	 *
     * @author Norbert Luksa <norbert.luksa@gmail.com>
	 */
	public function add(Request $request){
		$this->validate($request, [
            'file_to_upload' => 'required|file|mimes:pdf|max:10000',
			'file_name' => 'required',
			'committee' => 'required',
			'meeting_date' => 'required'
		]);
		
		$layout = new LayoutData();
		if($layout->user()->permitted('record_admin')){
			$layout->records()->addRecord($layout->user()->user()->id(), $request->file_to_upload, $request->file_name, $request->committee, $request->meeting_date);
			return view('ecouncil.records', ["layout" => $layout,
										"recordsToShow" => 10,
										"firstRecord" => 0]);
		}else{
			Logger::warning('At record upload. PERMISSIONS NEEDED!', null, null, 'ecouncil/record');
			return view('errors.authentication', ["layout" => $layout]);
		}
	}

	
// PRIVATE FUNCTIONS

    /** Function name: inArray
     *
     * This function looks for a value in an array.
     *
     * The array elements MUST HAVE an id property.
     *
     * @param int $value - the value, we look for
     * @param array $array - the lookup array
     *
     * @author Máté Kovács <kovacsur10@gmail.com>
     */
	private function inArray($value, $array){
		$i = 0;
		while($i < count($array) && $array[$i]->id != $value){
			$i++;
		}
		return $i < count($array);
	}
}



