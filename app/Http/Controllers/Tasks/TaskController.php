<?php

namespace App\Http\Controllers\Tasks;

use App\Classes\LayoutData;
use App\Classes\Notify;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use DB;
use Mail;

class TaskController extends Controller{
	
	// Public functions
	
    public function show(){
		$layout = new LayoutData();
		return view('tasks.tasks', ["layout" => $layout]);
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
				if($request->status === 3){ // TODO: nicer
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
			}else{
				return view('errors.error', ["layout" => $layout,
											 "message" => $layout->language('task_not_found'),
											 "url" => '/tasks/list']);
			}
		}else{
			$layout->errors()->add('permission', $layout->language('permission'));
		}
		$layout->tasks()->setTask($taskId);
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
		return view('tasks.tasks', ["layout" => $layout]);
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
	
	// Private functions
	
	private function inArray($value, $array){
		$i = 0;
		while($i < count($array) && $array[$i]->id != $value){
			$i++;
		}
		return $i < count($array);
	}
}
