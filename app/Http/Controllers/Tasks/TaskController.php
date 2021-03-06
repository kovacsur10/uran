<?php

namespace App\Http\Controllers\Tasks;

use App\Classes\LayoutData;
use App\Classes\Notifications;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

/** Class name: TaskController
 *
 * This controller is for handling the task manager functionality.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class TaskController extends Controller{
	
// PUBLIC FUNCTIONS
	
	/** Function name: show
	 *
	 * This function shows the list of the tasks.
	 *
	 * @param int $count - count of the tasks to show
	 * @param int $first - first task to show
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
    public function show($count = null, $first = 0){
    	$layout = new LayoutData();
		if($first < 0 || !is_numeric($first)){
			$first = 0;
		}
		$count = $layout->tasks()->checkTaskCount($count);
		$first -= ($first % $count);
		$layout->tasks()->filterTasks();
		return view('tasks.tasks', ["layout" => $layout,
									"tasksToShow" => $count,
									"firstTask" => $first]);
	}
	
	/** Function name: showTask
	 *
	 * This function shows a task.
	 *
	 * @param int $id - task identifier
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function showTask($id){
		$layout = new LayoutData();
		$layout->tasks()->setTask($id);
		return view('tasks.task', ["layout" => $layout]);
	}
	
	/** Function name: add
	 *
	 * This function shows the task addition page.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function add(){
		$layout = new LayoutData();		
		return view('tasks.add', ["layout" => $layout]);
	}
	
	/** Function name: modify
	 *
	 * This function modifies a task.
	 *
	 * @param int $taskId - task identifier
	 * @param Request $request
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function modify($taskId, Request $request){
		$layout = new LayoutData();
		$layout->tasks()->setTask($taskId);
		$error = false;
		
		if($layout->user()->permitted('tasks_admin') || $layout->tasks()->getTask()->username === $layout->user()->user()->username){
			$assignedUser = $layout->user()->getUserDataByUsername($request->assigned_username);
			//validation
			if($request->type === null || !$this->inArray($request->type, $layout->tasks()->taskTypes())){
				$error = true;
				$layout->errors()->add('type', __('tasks.not_specified_value'));
			}
			if($request->text === null || trim($request->text) === ''){
				$error = true;
				$layout->errors()->add('text', __('tasks.empty_value_is_forbidden'));
			}
			if($request->working_hours === null || !is_numeric($request->working_hours)){
				$error = true;
				$layout->errors()->add('working_hours', __('tasks.not_specified_value'));
			}
			if($request->caption === null || trim($request->caption) === ''){
				$error = true;
				$layout->errors()->add('caption', __('tasks.empty_value_is_forbidden'));
			}
			if($request->priority === null || !$this->inArray($request->priority, $layout->tasks()->priorities())){
				$error = true;
				$layout->errors()->add('priority', __('tasks.not_specified_value'));
			}
			if($request->assigned_username !== null && $assignedUser === null && $request->assigned_username !== "admin"){
				$error = true;
				$layout->errors()->add('assigned_username', __('tasks.not_specified_value'));
			}
			if($request->status === null || !$this->inArray($request->status, $layout->tasks()->statusTypes())){
				$error = true;
				$layout->errors()->add('status', __('tasks.not_specified_value'));
			}
			preg_match("/^[1-9]\d\d\d. (?:1[012]|0[1-9]). (?:0[1-9]|[12][0-9]|3[01])$/", $request->deadline, $matched_values);
			if(trim($request->deadline) !== '' && $matched_values === []){
				$error = true;
				$layout->errors()->add('deadline', __('tasks.not_specified_value'));
			}
			//add task or return the errors
			if(!$error){
				if($request->status == $layout->tasks()->getStatusByName('closed')->id()){
					$closed = true;
				}else{
					$closed = false;
				}
				$assignedUser = $assignedUser !== null ? $assignedUser->id() : null;
				if(trim($request->deadline) === ''){
					$layout->tasks()->update($taskId, $request->type, $request->text, $request->caption, null, $request->priority, $request->status, $request->working_hours, $assignedUser, $closed);
				}else{
					$layout->tasks()->update($taskId, $request->type, $request->text, $request->caption, $request->deadline, $request->priority, $request->status, $request->working_hours, $assignedUser, $closed);
				}
				//alert for changing the status
				$newStatus = $layout->tasks()->getStatusById($request->status)->name();
				if($newStatus !== $layout->tasks()->getTask()->status()->name()){
					if($layout->tasks()->getTask()->creator()->id() !== $layout->user()->user()->id()){
						Notifications::notify($layout->user()->user(), $layout->tasks()->getTask()->creator()->id(), 'Feladat státusz változás', 'Egy általad létrehozott feladat státusza megváltozott ('.__('tasks.'.$layout->tasks()->getTask()->status()->name()).' -> '.__('tasks.'.$newStatus).')!', 'tasks/task/'.$taskId);
					}
					if($assignedUser !== null && $assignedUser !== $layout->tasks()->getTask()->creator()->id()){
						Notifications::notify($layout->user()->user(), $assignedUser, 'Feladat státusz változás', 'Egy feladat - amin éppen dolgozol - státusza megváltozott ('.__('tasks.'.$layout->tasks()->getTask()->status()->name()).' -> '.__('tasks.'.$newStatus).')!', 'tasks/task/'.$taskId);
					}
				}
				//alert for assignment
				if($assignedUser !== null && $layout->tasks()->getTask()->assignedTo() !== null && $assignedUser !== $layout->tasks()->getTask()->assignedTo()->id() && $assignedUser !== $layout->user()->user()->id()){
					Notifications::notify($layout->user()->user(), $assignedUser, 'Feladat hozzárendelés', 'Hozzá lettél rendelve egy feladathoz! Kérlek vedd fel a kapcsolatot a feladat elvégzése miatt velem!', 'tasks/task/'.$taskId);
				}
				$layout->tasks()->setTask($taskId); //need to refresh the data
				return view('tasks.task', ["layout" => $layout]);
			}else{
				$layout->errors()->addOld('type', $request->type);
				$layout->errors()->addOld('text', $request->text);
				$layout->errors()->addOld('caption', $request->caption);
				$layout->errors()->addOld('priority', $request->priority);
				$layout->errors()->addOld('deadline', $request->deadline);
				$layout->errors()->addOld('working_hours', $request->working_hours);
				$layout->errors()->addOld('status', $request->status);
				$layout->errors()->addOld('assigned_username', $request->assigned_username);
				return view('tasks.task', ["layout" => $layout]);
			}
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
	}
	
	/** Function name: addComment
	 *
	 * This function adds a comment.
	 *
	 * @param int $taskId - task identifier
	 * @param Request $request
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function addComment($taskId, Request $request){
		$layout = new LayoutData();
		if($layout->user()->permitted('tasks_add_comment')){
			if($layout->tasks()->exists($taskId)){
				$this->validate($request, [
					'commentText' => 'required',
				]);
				$layout->tasks()->addComment($taskId, $layout->user()->user()->id(), $request->commentText);
				$layout->tasks()->setTask($taskId);
				if($layout->user()->user()->id() !== $layout->tasks()->getTask()->creator()->id()){
					Notifications::notify($layout->user()->user(), $layout->tasks()->getTask()->creator()->id(), 'Új komment', 'Egy új kommentet írtak az általad készített feladathoz!', 'tasks/task/'.$taskId);
				}
				if($layout->tasks()->getTask()->assignedTo() !== null && $layout->tasks()->getTask()->assignedTo()->id() !== $layout->user()->user()->id()){ //assigned user exists and it's not the owner
					Notifications::notify($layout->user()->user(), $layout->tasks()->getTask()->assignedTo()->id(), 'Új komment', 'Egy új kommentet írtak egy feladathoz, amin aktuálisan dolgozol!', 'tasks/task/'.$taskId);
				}
			}else{
				return view('errors.error', ["layout" => $layout,
											 "message_indicator" => 'tasks.task_not_found',
											 "url" => '/tasks/list']);
			}
		}else{
			$layout->errors()->add('permission', __('general.permission'));
			$layout->tasks()->setTask($taskId);
		}
		return view('tasks.task', ["layout" => $layout]);
	}
	
	/** Function name: removeComment
	 *
	 * This function removes a comment.
	 *
	 * @param int $taskId - task identifier
	 * @param int $commentId - comment identifier
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function removeComment($taskId, $commentId){
		$layout = new LayoutData();
		if($layout->tasks()->commentExists($commentId)){
			if($layout->tasks()->getComment($commentId)->authorUsername() === $layout->user()->user()->username() || $layout->user()->permitted('tasks_admin')){
				$layout->tasks()->removeComment($commentId);
			}else{
				$layout->errors()->add('permission', __('error.insufficient_permissions'));
			}
		}else{
			$layout->errors()->add('comment_not_exists', __('tasks.comment_not_exists'));
		}
		$layout->tasks()->setTask($taskId);
		return view('tasks.task', ["layout" => $layout]);
	}
	
	/** Function name: addNew
	 *
	 * This function adds a new task.
	 *
	 * @param Request $request
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function addNew(Request $request){
		$layout = new LayoutData();
		$error = false;
		
		if($layout->user()->permitted('tasks_add')){
			//validation
			if($request->type === null || !$this->inArray($request->type, $layout->tasks()->taskTypes())){
				$error = true;
				$layout->errors()->add('type', __('tasks.not_specified_value'));
			}
			if($request->text === null || trim($request->text) === ''){
				$error = true;
				$layout->errors()->add('text', __('tasks.empty_value_is_forbidden'));
			}
			if($request->caption === null || trim($request->caption) === ''){
				$error = true;
				$layout->errors()->add('caption', __('tasks.empty_value_is_forbidden'));
			}
			if($request->priority === null || !$this->inArray($request->priority, $layout->tasks()->priorities())){
				$error = true;
				$layout->errors()->add('priority', __('tasks.not_specified_value'));
			}
			preg_match("/^[1-9]\d\d\d. (?:1[012]|0[1-9]). (?:0[1-9]|[12][0-9]|3[01])$/", $request->deadline, $matched_values);
			if(trim($request->deadline) !== '' && $matched_values === []){
				$error = true;
				$layout->errors()->add('deadline', __('tasks.not_specified_value'));
			}
			//add task or return the errors
			if(!$error){
				if(trim($request->deadline) === ''){
					$layout->tasks()->addTask($request->type, $layout->user()->user()->id(), $request->text, $request->caption, null, $request->priority);
				}else{
					$layout->tasks()->addTask($request->type, $layout->user()->user()->id(), $request->text, $request->caption, str_replace('. ', '-', $request->deadline).' 00:00:00', $request->priority);
				}
				$layout = new LayoutData();
			}else{
				$layout->errors()->addOld('type', $request->type);
				$layout->errors()->addOld('text', $request->text);
				$layout->errors()->addOld('caption', $request->caption);
				$layout->errors()->addOld('priority', $request->priority);
				$layout->errors()->addOld('deadline', $request->deadline);
				return view('tasks.add', ["layout" => $layout]);
			}
		}else{
			$layout->errors()->add('permission', __('error.insufficient_permissions'));
		}
		return view('tasks.tasks', ["layout" => $layout,
									"tasksToShow" => 10,
									"firstTask" => 0]);
	}
	
	/** Function name: remove
	 *
	 * This function removes a task.
	 *
	 * @param int $taskId - task identifier
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function remove($taskId){
		$layout = new LayoutData();
		$layout->tasks()->setTask($taskId);
		
		if($layout->user()->permitted('tasks_admin') || $layout->tasks()->getTask()->username === $layout->user()->user()->username()){
			$layout->tasks()->removeTask($taskId);
			return $this->show();
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
	}
	
	/** Function name: filterTasks
	 *
	 * This function sets the task filters.
	 *
	 * @param Request $request
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function filterTasks(Request $request){
		$layout = new LayoutData();
		$layout->tasks()->resetFilterTasks();
		$layout->tasks()->setFilterTasks($request->input('status', ""), $request->input('caption', ""), $request->input('priority', ""), $request->input('myTasks', false), $request->input('hide_closed', false));
		return redirect('tasks/list');
	}
	
	/** Function name: resetFilterTasks
	 *
	 * This function resets the task filters.
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function resetFilterTasks(){
		$layout = new LayoutData();
		$layout->tasks()->resetFilterTasks();
		return redirect('tasks/list');
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
		while($i < count($array) && $array[$i]->id() != $value){
			$i++;
		}
		return $i < count($array);
	}
}



