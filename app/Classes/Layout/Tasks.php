<?php

namespace App\Classes\Layout;

use DB;
use Carbon\Carbon;
use App\Classes\LayoutData;

class Tasks{
	protected $tasks;
	protected $task;
	protected $types;
	protected $comments;
	protected $priorities;
	protected $statusTypes;
    protected $filterPriority = "";
    protected $filterStatus = "";
    protected $filterCaption = "";
    protected $filterMyTasks = 1;
	
	public function __construct(){
		$this->tasks = $this->getTasks();
		$this->priorities = $this->getPriorities();
		$this->types = $this->getTaskTypes();
		$this->task = null;
		$this->comments = [];
		$this->statusTypes = $this->getTaskStatusTypes();
	}
	
	public function get(){
        return $this->tasks;
	}
	
	public function getTask(){
		return $this->task;
	}
	
	public function priorities(){
		return $this->priorities;
	}
	
	public function taskTypes(){
		return $this->types;
	}
	
	public function getComments(){
		return $this->comments;
	}
	
	public function statusTypes(){
		return $this->statusTypes;
	}
	
	public function getCaptionFilter(){
		return $this->filterCaption;
	}
	public function getStatusFilter(){
		return $this->filterStatus;
	}
	public function getPriorityFilter(){
		return $this->filterPriority;
	}
	public function getMyTasksFilter(){
		return $this->filterMyTasks;
	}
	public function filterTasks($status, $caption, $priority, $myTasks){
		$this->filterStatus = $status;
		$this->filterCaption = $caption;
		$this->filterPriority = $priority;
		$this->filterMyTasks = $myTasks;
		if($status == "" && $priority == ""){
			$this->tasks = $this->getTasks();
		}
		else{
			$this->tasks = $this->getFilteredTasks($this->filterStatus, $this->filterPriority);
		}
	}
	
	public function getStatusById($id){
		return DB::table('tasks_status')
			->where('id', '=', $id)
			->first();
	}
	
	public function getStatusByName($statusName){
		return DB::table('tasks_status')
			->where('status', 'LIKE', $statusName)
			->first();
	}
	
	public function getComment($commentId){
		return DB::table('tasks_comments')
			->join('users', 'users.id', '=', 'tasks_comments.sender')
			->where('tasks_comments.id', '=', $commentId)
			->select('tasks_comments.id as id', 'users.name as poster', 'text as comment', 'datetime as date', 'users.username as poster_username')
			->first();
	}
	
	public function exists($taskId){
		$id = DB::table('tasks_task')
			->where('id', '=', $taskId)
			->first();
		return $id !== null;
	}
	
	public function commentExists($commentId){
		$id = DB::table('tasks_comments')
			->where('id', '=', $commentId)
			->first();
		return $id !== null;
	}
	
	public function setTask($id){
		$this->task = DB::table('tasks_task')
			->join('tasks_type', 'tasks_type.id', '=', 'tasks_task.type')
			->join('tasks_status', 'tasks_status.id', '=', 'tasks_task.status')
			->join('tasks_priority', 'tasks_priority.id', '=', 'tasks_task.priority')
			->join('users', 'users.id', '=', 'tasks_task.created_by')
			->select('tasks_task.id as id', 'created_datetime as date', 'tasks_status.status as status', 'tasks_type.type as type', 'users.id as owner_id', 'users.name as user', 'users.username as username', 'text', 'caption', 'closed_datetime as closed', 'deadline', 'tasks_priority.name as priority', 'tasks_task.hours as working_hours')
			->where('tasks_task.id', '=', $id)
			->first();
		$assigned = DB::table('tasks_task')
			->join('users', 'users.id', '=', 'tasks_task.assigned')
			->where('tasks_task.id', '=', $id)
			->whereNotNull('tasks_task.id')
			->first();
		if($assigned !== null){
			$this->task->assigned_id = $assigned->id;
			$this->task->assigned_name = $assigned->name;
			$this->task->assigned_username = $assigned->username;
		}else{
			$this->task->assigned_id = null;
			$this->task->assigned_name = null;
			$this->task->assigned_username = null;
		}
		
		//format deadline
		if($this->task->deadline !== null){
			$this->task->deadline = substr($this->task->deadline, 0, 10);
		}
		
		//set comments
		$this->comments = DB::table('tasks_comments')
			->join('users', 'users.id', '=', 'tasks_comments.sender')
			->where('task', '=', $id)
			->where('deleted', '=', '0')
			->select('tasks_comments.id as id', 'users.name as poster', 'text as comment', 'datetime as date', 'users.username as poster_username')
			->orderBy('tasks_comments.id','desc')
			->get();
		if($this->comments === null){
			$this->comments = [];
		}
	}
	
	public function update($taskId, $type, $text, $caption, $deadline, $priority, $status, $workingHours, $assignedUser, $closed){
		if($deadline === null){
			DB::table('tasks_task')
				->where('id', '=', $taskId)
				->update([
					'status' => $status,
					'type' => $type,
					'text' => $text,
					'caption' => $caption,
					'priority' => $priority,
					'deadline' => null,
					'hours' => $workingHours,
					'assigned' => $assignedUser,
					'closed_datetime' => $closed
				]);
		}else{
			DB::table('tasks_task')
				->where('id', '=', $taskId)
				->update([
					'status' => $status,
					'type' => $type,
					'text' => $text,
					'caption' => $caption,
					'priority' => $priority,
					'deadline' => str_replace('.', '-', str_replace('. ', '-', $deadline)),
					'hours' => $workingHours,
					'assigned' => $assignedUser,
					'closed_datetime' => $closed
				]);
		}
	}
	
	public function canModify(){
		$layout = new LayoutData();
		return $this->task && ($layout->user()->user()->id === $this->task->owner_id || $layout->user()->permitted('tasks_admin'));
	}
	
	public function addTask($type, $createdById, $text, $caption, $deadline, $priority){
		if($deadline === null){
			DB::table('tasks_task')
				->insert([
					'created_datetime' => Carbon::now()->toDateTimeString(),
					'status' => 1,
					'type' => $type,
					'created_by' => $createdById,
					'text' => $text,
					'caption' => $caption,
					'priority' => $priority
				]);
		}else{
			DB::table('tasks_task')
				->insert([
					'created_datetime' => Carbon::now()->toDateTimeString(),
					'status' => 1,
					'type' => $type,
					'created_by' => $createdById,
					'text' => $text,
					'caption' => $caption,
					'deadline' => $deadline,
					'priority' => $priority
				]);
		}
	}
	
	public function removeTask($taskId){
		DB::table('tasks_task')
			->where('id', '=', $taskId)
			->update(['deleted' => 1]);
	}
	
	public function addComment($taskId, $userId, $text){
		DB::table('tasks_comments')
			->insert([
				'text' => $text,
				'task' => $taskId,
				'sender' => $userId,
				'datetime' => Carbon::now()->toDateTimeString()
			]);
	}
	
	public function removeComment($commentId){
		DB::table('tasks_comments')
			->where('id', '=', $commentId)
			->update(['deleted' => 1]);
	}
	
	protected function getTasks(){
		$ret = DB::table('tasks_task')
			->join('tasks_type', 'tasks_type.id', '=', 'tasks_task.type')
			->join('tasks_status', 'tasks_status.id', '=', 'tasks_task.status')
			->join('tasks_priority', 'tasks_priority.id', '=', 'tasks_task.priority')
			->join('users', 'users.id', '=', 'tasks_task.created_by')
			->where('tasks_task.caption', 'LIKE', '%'.($this->filterCaption).'%')
			->where('tasks_task.deleted', '=', 0)
			->select('tasks_task.id as id', 'created_datetime as date', 'tasks_status.status as status', 'users.name as user', 'caption', 'tasks_priority.name as priority', 'users.username as username')
			->orderBy('tasks_task.id', 'desc')
			->get();
		return $ret == null ? [] : $ret;
	}
	
	protected function getPriorities(){
		$ret = DB::table('tasks_priority')
			->orderBy('id', 'asc')
			->get();
		return $ret == null ? [] : $ret;
	}
	
	protected function getTaskTypes(){
		$ret = DB::table('tasks_type')
			->orderBy('id', 'asc')
			->get();
		return $ret == null ? [] : $ret;
	}
	
	protected function getTaskStatusTypes(){
		$ret = DB::table('tasks_status')
			->orderBy('id', 'asc')
			->get();
		return $ret == null ? [] : $ret;
	}
    
    private function getFilteredTasks($status, $priority){
		if($priority != "" && $status != ""){
			$ret = DB::table('tasks_task')
				->join('tasks_type', 'tasks_type.id', '=', 'tasks_task.type')
				->join('tasks_status', 'tasks_status.id', '=', 'tasks_task.status')
				->join('tasks_priority', 'tasks_priority.id', '=', 'tasks_task.priority')
				->join('users', 'users.id', '=', 'tasks_task.created_by')
				->where('tasks_status.id', '=', $status)
				->where('tasks_priority.id', '=', $priority)
				->where('tasks_task.caption', 'LIKE', '%'.($this->filterCaption).'%')
				->where('tasks_task.deleted', '=', 0)
				->select('tasks_task.id as id', 'created_datetime as date', 'tasks_status.status as status', 'users.name as user', 'caption', 'tasks_priority.name as priority', 'users.username as username')
				->orderBy('tasks_task.id', 'desc')
				->get();
		}
		else if($priority == ""){
			$ret = DB::table('tasks_task')
				->join('tasks_type', 'tasks_type.id', '=', 'tasks_task.type')
				->join('tasks_status', 'tasks_status.id', '=', 'tasks_task.status')
				->join('tasks_priority', 'tasks_priority.id', '=', 'tasks_task.priority')
				->join('users', 'users.id', '=', 'tasks_task.created_by')
				->where('tasks_status.id', '=', $status)
				->where('tasks_task.caption', 'LIKE', '%'.($this->filterCaption).'%')
				->where('tasks_task.deleted', '=', 0)
				->select('tasks_task.id as id', 'created_datetime as date', 'tasks_status.status as status', 'users.name as user', 'caption', 'tasks_priority.name as priority', 'users.username as username')
				->orderBy('tasks_task.id', 'desc')
				->get();
		}
		else{
			$ret = DB::table('tasks_task')
				->join('tasks_type', 'tasks_type.id', '=', 'tasks_task.type')
				->join('tasks_status', 'tasks_status.id', '=', 'tasks_task.status')
				->join('tasks_priority', 'tasks_priority.id', '=', 'tasks_task.priority')
				->join('users', 'users.id', '=', 'tasks_task.created_by')
				->where('tasks_priority.id', '=', $priority)
				->where('tasks_task.caption', 'LIKE', '%'.($this->filterCaption).'%')
				->where('tasks_task.deleted', '=', 0)
				->select('tasks_task.id as id', 'created_datetime as date', 'tasks_status.status as status', 'users.name as user', 'caption', 'tasks_priority.name as priority', 'users.username as username')
				->orderBy('tasks_task.id', 'desc')
				->get();
		}
        return $ret == null ? [] : $ret;
	}
}
