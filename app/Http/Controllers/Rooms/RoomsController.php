<?php

namespace App\Http\Controllers\Rooms;

use App\Classes\LayoutData;
use App\Classes\Database;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions\ValueMismatchException;
use App\Exceptions\DatabaseException;

/** Class name: RoomsController
 *
 * This controller is for handling the rooms and the assignments.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class RoomsController extends Controller{
	
	/** Function name: showMap
	 *
	 * This function shows the user data page.
	 * 
	 * @param int $level - current level to show
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
    public function showMap($level = 2){
		$layout = new LayoutData();
		if($level == -2 || $level == -1 || $level == -1 || $level == 0 || $level == 1 || $level == 2 || $level == 3 || $level == 4 || $level == 5){
			return view('rooms.mapped', ["layout" => $layout,
										 "level" => $level]);
		}else{
			return view('errors.error', ["layout" => $layout,
										 "message" => $layout->language('error_floor_not_found'),
										 "url" => '/rooms/map/'.$level]);
		}
	}
	
	/** Function name: listRoomMembers
	 *
	 * This function shows the people living in the requested room.
	 *
	 * @param string $id - room identifier
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function listRoomMembers($id){
		return view('rooms.showroom', ["layout" => new LayoutData(),
									   "room" => $id]);
	}
	
	/** Function name: downloadList
	 *
	 * This function downloads the list of the current assignment.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function downloadList(){
		return view('rooms.download', ["layout" => new LayoutData()]);
	}
	
	/** Function name: assignResidents
	 *
	 * This function saves a rooms' assignments.
	 *
	 * @param Request $request
	 * @param string $guard - assignment table guard
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function assignResidents(Request $request, $guard){
		$layout = new LayoutData();
		if($layout->user()->permitted('rooms_assign')){
			$roomId = $layout->room()->getRoomId($request->room);
			try{
				Database::transaction(function() use($roomId, $guard, $layout, $request){
					if($roomId === null){
						throw new ValueMismatchException("The room identifier must not be null!");
					}else if(!$layout->room()->checkGuard($guard)){
						throw new DatabaseException("Guard checking was not successful!");
					}else{
						$layout->room()->emptyRoom($roomId);
						for($i = 0; $i < $request->count; $i++){
							if($request->input('resident'.$i) != 0){
								$layout->room()->setUserToRoom($roomId, $request->input('resident'.$i));
							}
						}
					}
				});
				return view('rooms.showroom', ["layout" => new LayoutData(),
						"room" => $request->room]);
			}catch(ValueMismatchException $ex){
				return view('errors.error', ["layout" => $layout,
						"message" => $layout->language('error_already_lives_somewhere'),
						"url" => '/rooms/room/'.$request->room]);
			}catch(\Exception $ex){
				return view('errors.error', ["layout" => $layout,
						"message" => $layout->language('error_rooms_guard_mismatch'),
						"url" => '/rooms/room/'.$request->room]);
			}
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
	}
	
	/** Function name: selectTable
	 *
	 * This function sets an assignment table as the active one.
	 *
	 * @param Request $request
	 * @param string $level - floor of the dormitory
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function selectTable(Request $request, $level){
		$layout = new LayoutData();
		try{
			$layout->room()->selectTable($request->table_version);
			return redirect('rooms/map/'.$level);
		}catch(\Exception $ex){
			return view('errors.error', ["layout" => $layout,
					"message" => $layout->language('error_at_selecting_rooms_table'),
					"url" => '/rooms/map/2']);
		}
	}
	
	/** Function name: addTable
	 *
	 * This function adds a new room assignment table.
	 * 
	 * @param Request $request
	 * @param string $level - floor of the dormitory
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function addTable(Request $request, $level){
		$layout = new LayoutData();
		try{
			$layout->room()->addNewTable($request->newTableName);
			return redirect('rooms/map/'.$level);
		}catch(\Exception $ex){
			return view('errors.error', ["layout" => $layout,
				"message" => $layout->language('error_at_adding_new_rooms_table'),
				"url" => '/rooms/map/2']);
		}
	}
	
	/** Function name: removeTable
	 *
	 * This function removes an existing room assignment table.
	 *
	 * @param Request $request
	 * @param string $level - floor of the dormitory
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function removeTable(Request $request, $level){
		$layout = new LayoutData();
		try{
			$layout->room()->removeTable($request->table_version);
			return redirect('rooms/map/'.$level);
		}catch(\Exception $ex){
			return view('errors.error', ["layout" => $layout,
				"message" => $layout->language('error_at_removing_new_rooms_table'),
				"url" => '/rooms/map/2']);
		}
	}
}
