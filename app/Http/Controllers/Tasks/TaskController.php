<?php

namespace App\Http\Controllers\Tasks;

use App\Classes\LayoutData;
use App\Classes\Notify;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TaskController extends Controller{
	
// PUBLIC FUNCTIONS
	
    public function show($count = 10, $first = 0){
		if($first < 0 || !is_numeric($first)){
			$first = 0;
		}
		if($count < 0 || !is_numeric($count)){
			$count = 10;
		}
		$first -= ($first % $count);
		$layout = new LayoutData();
		if(Session::has('tasks_status_filter') || Session::has('tasks_caption_filter') || Session::has('tasks_priority_filter') || (Session::has('tasks_mytasks_filter') && Session::get('tasks_mytasks_filter') == 1)){
			$layout->tasks()->filterTasks(Session::get('tasks_status_filter'), Session::get('tasks_caption_filter'), Session::get('tasks_priority_filter'), Session::get('tasks_mytasks_filter'));
		}
		return view('tasks.tasks', ["layout" => $layout,
									"tasksToShow" => $count,
									"firstTask" => $first]);
	}
	
	public function showTask($id){
		$layout = new LayoutData();
		$layout->tasks()->setTask($id);
		return view('tasks.task', ["layout" => $layout]);
	}
	
	public function add(){
		$layout = new LayoutData();
		return view('tasks.add', ["layout" => $layout]);
	}
	
	public function modify($taskId, Request $request){
		$layout = new LayoutData();
		$layout->tasks()->setTask($taskId);
		$error = false;
		
		if($layout->user()->permitted('tasks_admin') || $layout->tasks()->getTask()->username === $layout->user()->user()->username){
			$assignedUser = $layout->user()->getUserDataByUsername($request->assigned_username);
			//validation
			if($request->type === null || !$this->inArray($request->type, $layout->tasks()->taskTypes())){
				$error = true;
				$layout->errors()->add('type', $layout->language('not_specified_value'));
			}
			if($request->text === null || trim($request->text) === ''){
				$error = true;
				$layout->errors()->add('text', $layout->language('empty_value_is_forbidden'));
			}
			if($request->working_hours === null || !is_numeric($request->working_hours)){
				$error = true;
				$layout->errors()->add('working_hours', $layout->language('not_specified_value'));
			}
			if($request->caption === null || trim($request->caption) === ''){
				$error = true;
				$layout->errors()->add('caption', $layout->language('empty_value_is_forbidden'));
			}
			if($request->priority === null || !$this->inArray($request->priority, $layout->tasks()->priorities())){
				$error = true;
				$layout->errors()->add('priority', $layout->language('not_specified_value'));
			}
			if($request->assigned_username !== null && $assignedUser === null && $request->assigned_username !== "admin"){
				$error = true;
				$layout->errors()->add('assigned_username', $layout->language('not_specified_value'));
			}
			if($request->status === null || !$this->inArray($request->status, $layout->tasks()->statusTypes())){
				$error = true;
				$layout->errors()->add('status', $layout->language('not_specified_value'));
			}
			preg_match("/^[1-9]\d\d\d. (?:1[012]|0[1-9]). (?:0[1-9]|[12][0-9]|3[01])$/", $request->deadline, $matched_values);
			if(trim($request->deadline) !== '' && $matched_values === []){
				$error = true;
				$layout->errors()->add('deadline', $layout->language('not_specified_value'));
			}
			//add task or return the errors
			if(!$error){
				if($request->status == $layout->tasks()->getStatusByName('closed')->id){
					$closedDate = Carbon::now()->toDateTimeString();
				}else{
					$closedDate = null;
				}
				$assignedUser = $assignedUser !== null ? $assignedUser->id : null;
				if(trim($request->deadline) === ''){
					$layout->tasks()->update($taskId, $request->type, $request->text, $request->caption, null, $request->priority, $request->status, $request->working_hours, $assignedUser, $closedDate);
				}else{
					$layout->tasks()->update($taskId, $request->type, $request->text, $request->caption, $request->deadline, $request->priority, $request->status, $request->working_hours, $assignedUser, $closedDate);
				}
				//alert for changing the status
				$newStatus = $layout->tasks()->getStatusById($request->status)->status;
				if($newStatus !== $layout->tasks()->getTask()->status){
					Notify::notify($layout->user(), $layout->tasks()->getTask()->owner_id, 'Feladat státusz változás', 'Egy általad létrehozott feladat státusza megváltozott ('.$layout->language($layout->tasks()->getTask()->status).' -> '.$layout->language($newStatus).')!', 'tasks/task/'.$taskId);
					if($assignedUser !== null && $assignedUser !== $layout->tasks()->getTask()->owner_id){
						Notify::notify($layout->user(), $assignedUser, 'Feladat státusz változás', 'Egy feladat - amin éppen dolgozol - státusza megváltozott ('.$layout->language($layout->tasks()->getTask()->status).' -> '.$layout->language($newStatus).')!', 'tasks/task/'.$taskId);
					}
				}
				//alert for assignment
				if($assignedUser !== null && $assignedUser !== $layout->tasks()->getTask()->assigned_id){
					Notify::notify($layout->user(), $assignedUser, 'Feladat hozzárendelés', 'Hozzá lettél rendelve egy feladathoz! Kérlek vedd fel a kapcsolatot a feladat elvégzése miatt velem!', 'tasks/task/'.$taskId);
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
	
	public function addComment($taskId, Request $request){
		$layout = new LayoutData();
		if($layout->user()->permitted('tasks_add_comment')){
			if($layout->tasks()->exists($taskId)){
				$this->validate($request, [
					'commentText' => 'required',
				]);
				$layout->tasks()->addComment($taskId, $layout->user()->user()->id, $request->commentText);
				$layout->tasks()->setTask($taskId);
				Notify::notify($layout->user(), $layout->tasks()->getTask()->owner_id, 'Új komment', 'Egy új kommentet írtak az általad készített feladathoz!', 'tasks/task/'.$taskId);
				if($layout->tasks()->getTask()->assigned_id !== null && $layout->tasks()->getTask()->assigned_id !== $layout->tasks()->getTask()->owner_id){ //assigned user exists and it's not the owner
					Notify::notify($layout->user(), $layout->tasks()->getTask()->assigned_id, 'Új komment', 'Egy új kommentet írtak egy feladathoz, amin aktuálisan dolgozol!', 'tasks/task/'.$taskId);
				}
			}else{
				return view('errors.error', ["layout" => $layout,
											 "message" => $layout->language('task_not_found'),
											 "url" => '/tasks/list']);
			}
		}else{
			$layout->errors()->add('permission', $layout->language('permission'));
			$layout->tasks()->setTask($taskId);
		}
		return view('tasks.task', ["layout" => $layout]);
	}
	
	public function removeComment($taskId, $commentId){
		$layout = new LayoutData();
		if($layout->tasks()->commentExists($commentId)){
			if($layout->tasks()->getComment($commentId)->poster_username === $layout->user()->user()->username){
				$layout->tasks()->removeComment($commentId);
			}else{
				$layout->errors()->add('permission', $layout->language('insufficient_permissions'));
			}
		}else{
			$layout->errors()->add('comment_not_exists', $layout->language('comment_not_exists'));
		}
		$layout->tasks()->setTask($taskId);
		return view('tasks.task', ["layout" => $layout]);
	}
	
	public function addNew(Request $request){
		$layout = new LayoutData();
		$error = false;
		
		if($layout->user()->permitted('tasks_add')){
			//validation
			if($request->type === null || !$this->inArray($request->type, $layout->tasks()->taskTypes())){
				$error = true;
				$layout->errors()->add('type', $layout->language('not_specified_value'));
			}
			if($request->text === null || trim($request->text) === ''){
				$error = true;
				$layout->errors()->add('text', $layout->language('empty_value_is_forbidden'));
			}
			if($request->caption === null || trim($request->caption) === ''){
				$error = true;
				$layout->errors()->add('caption', $layout->language('empty_value_is_forbidden'));
			}
			if($request->priority === null || !$this->inArray($request->priority, $layout->tasks()->priorities())){
				$error = true;
				$layout->errors()->add('priority', $layout->language('not_specified_value'));
			}
			preg_match("/^[1-9]\d\d\d. (?:1[012]|0[1-9]). (?:0[1-9]|[12][0-9]|3[01])$/", $request->deadline, $matched_values);
			if(trim($request->deadline) !== '' && $matched_values === []){
				$error = true;
				$layout->errors()->add('deadline', $layout->language('not_specified_value'));
			}
			//add task or return the errors
			if(!$error){
				if(trim($request->deadline) === ''){
					$layout->tasks()->addTask($request->type, $layout->user()->user()->id, $request->text, $request->caption, null, $request->priority);
				}else{
					$layout->tasks()->addTask($request->type, $layout->user()->user()->id, $request->text, $request->caption, str_replace('. ', '-', $request->deadline).' 00:00:00', $request->priority);
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
			$layout->errors()->add('permission', $layout->language('insufficient_permissions'));
		}
		return view('tasks.tasks', ["layout" => $layout,
									"tasksToShow" => 10,
									"firstTask" => 0]);
	}
	
	public function remove($taskId){
		$layout = new LayoutData();
		$layout->tasks()->setTask($taskId);
		
		if($layout->user()->permitted('tasks_admin') || $layout->tasks()->getTask()->username === $layout->user()->user()->username){
			$layout->tasks()->removeTask($taskId);
			return $this->show();
		}else{
			return view('errors.authentication', ["layout" => $layout]);
		}
	}
		
	public function filterTasks(Request $request){
		if($request->input('status') == null){
			Session::put('tasks_status_filter', '');
		}else{
			Session::put('tasks_status_filter', $request->input('status'));
		}
		if($request->input('caption') == null){
			Session::put('tasks_caption_filter', '');
		}else{
			Session::put('tasks_caption_filter', $request->input('caption'));
		}
		if($request->input('priority') == null){
			Session::put('tasks_priority_filter', '');
		}else{
			Session::put('tasks_priority_filter', $request->input('priority'));
		}
		if($request->input('myTasks') == null){
			Session::put('tasks_mytasks_filter', '0');
		}else{
			Session::put('tasks_mytasks_filter', '1');
		}
		return redirect('tasks/list');
	}
	
	public function resetFilterTasks(){
		Session::forget('tasks_status_filter');
		Session::forget('tasks_caption_filter');
		Session::forget('tasks_priority_filter');
		return redirect('tasks/list');
	}
	
// PRIVATE FUNCTIONS
	
	private function inArray($value, $array){
		$i = 0;
		while($i < count($array) && $array[$i]->id != $value){
			$i++;
		}
		return $i < count($array);
	}
}



