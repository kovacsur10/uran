<?php

namespace App\Http\Controllers\ECouncil;

use App\Classes\LayoutData;
use App\Http\Controllers\Controller;

/** Class name: RecordController
 *
 * This controller is for handling the council's records.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class RecordController extends Controller{
	
// PUBLIC FUNCTIONS
	/** Function name: show
	 *
	 * TODO
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
    public function show(){
		$layout = new LayoutData();
		return view('ecouncil.records', ["layout" => $layout]);
	}
	
	/** Function name: showRecord
	 *
	 * TODO
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function showRecord($id){
		$layout = new LayoutData();
		$layout->records()->setRecord($id);
		return view('tasks.task', ["layout" => $layout]);
	}
	
	/** Function name: add
	 *
	 * TODO
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function add(){
		$layout = new LayoutData();
		return view('tasks.add', ["layout" => $layout]);
	}
	
	
// PRIVATE FUNCTIONS
	
	/** Function name: inArray
	 *
	 * TODO
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



