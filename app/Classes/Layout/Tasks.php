<?php

namespace App\Classes\Layout;

use DB;
use Carbon\Carbon;
use App\Classes\LayoutData;
use App\Classes\Logger;

/* Class name: Tasks
 *
 * This class handles the tasks data
 * support in the layout namespace.
 *
 * Functionality:
 * 		- listing support
 * 		- filtering support
 * 		- tasks support
 * 		- comments support
 *
 * Functions that can throw exceptions:
 */
class Tasks{
	
// PRIVATE DATA
	
	private $tasks;
	private $task;
	private $types;
	private $comments;
	private $priorities;
	private $statusTypes;
	private $filters;
	
// PUBLIC FUNCTIONS
    
    /* Function name: __construct
     * Input: -
     * Output: -
     *
     * The constructor for the Tasks class.
     */
	public function __construct(){
		$this->filters = [
				'priority'		=> '',
				'status' 		=> '',
				'caption'		=> '',
				'myTasks'		=> false,
				'hideClosed'	=> true,
		];
		$this->tasks = $this->getTasks();
		$this->priorities = $this->getPriorities();
		$this->types = $this->getTaskTypes();
		$this->task = null;
		$this->comments = [];
		$this->statusTypes = $this->getTaskStatusTypes();
	}
	
	/* Function name: get
	 * Input: -
	 * Output: array of tasks
	 *
	 * Getter function for tasks.
	 */
	public function get(){
        return $this->tasks;
	}
	
	/* Function name: getTask
	 * Input: -
	 * Output: task
	 *
	 * Getter function for the current task.
	 */
	public function getTask(){
		return $this->task;
	}
	
	/* Function name: priorities
	 * Input: -
	 * Output: array of priorities
	 *
	 * Getter function for the task priorities.
	 */
	public function priorities(){
		return $this->priorities;
	}
	
	/* Function name: taskTypes
	 * Input: -
	 * Output: array of task types
	 *
	 * Getter function for the task types.
	 */
	public function taskTypes(){
		return $this->types;
	}
	
	/* Function name: getComments
	 * Input: -
	 * Output: array of comment
	 *
	 * Getter function for the comments of the selected task.
	 */
	public function getComments(){
		return $this->comments;
	}
	
	/* Function name: statusTypes
	 * Input: -
	 * Output: array of status types
	 *
	 * Getter function for the status types of the tasks.
	 */
	public function statusTypes(){
		return $this->statusTypes;
	}
	
	/* Function name: getFilter
	 * Input: $filterName (text) - filter name
	 * Output: filter value | NULL
	 *
	 * Getter function for the requested filter.
	 * 
	 * Returns NULL if the requested filter name
	 * does not exist.
	 */
	public function getFilter($filterName){
		if(array_key_exists($filterName, $this->filters)){
			return $this->filters[$filterName];
		}else{
			return null;
		}
	}
	
	/* Function name: filterTasks
	 * Input:	$status (text) - status filter
	 * 			$caption (text) - caption filter
	 * 			$priority (int) - priority identifier filter
	 * 			$myTasks (bool) - only user's tasks filter
	 * 			$hideClosed (bool) - closed tasks filter 
	 * Output: -
	 *
	 * This function sets the filters based
	 * on the given values. The tasks array is
	 * updated with the filtered array data.
	 */
	public function filterTasks($status, $caption, $priority, $myTasks, $hideClosed){
		$layout = new LayoutData();
		$this->filters['status'] = $status;
		$this->filters['caption'] = $caption;
		$this->filters['priority'] = $priority;
		$this->filters['myTasks'] = $myTasks;
		$this->filters['hideClosed'] = $hideClosed;
		$this->tasks = $this->getTasks($layout->user()->user()->id);
	}
	
	/* Function name: tasksToPages
	 * Input:	$from (int) - first task identifier
	 * 			$count (int) - tasks count
	 * Output: array of tasks
	 *
	 * This function returns the requested count
	 * of tasks from the requested identifier.
	 */
	public function tasksToPages($from = 0, $count = 50){ //TODO: refactor
		if($this->tasks === null){
			return null;
		}else if($count === 0){
			return array_slice($this->tasks, $from, count($this->tasks)-$from);
		}else if($from < 0 || count($this->tasks) < $from || $count <= 0){
			return null;
		}else if(count($this->tasks) < $from + $count){
			return array_slice($this->tasks, $from, count($this->tasks) - $from);
		}else{
			return array_slice($this->tasks, $from, $count);
        }
	}
	
	/* Function name: getStatusById
	 * Input: $statusId (int) - status identifier
	 * Output: data of status | null
	 *
	 * This function returns the task status data for the 
	 * requsted status identifier.
	 * 
	 * Null is returned if the requested identifier does
	 * not exist.
	 */
	public function getStatusById($statusId){
		try{
			$status = DB::table('tasks_status')
				->where('id', '=', $statusId)
				->first();
		}catch(\Exception $ex){
			$status = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_status' was not successful! ".$ex->getMessage());
		}
		return $status;
	}
	
	/* Function name: getStatusByName
	 * Input: $statusName (text) - status name
	 * Output: data of status | null
	 *
	 * This function returns the task status data for the
	 * requsted status name.
	 * 
	 * Null is returned if the requested name does
	 * not exist.
	 */
	public function getStatusByName($statusName){
		try{
			$status = DB::table('tasks_status')
				->where('status', 'LIKE', $statusName)
				->first();
		}catch(\Exception $ex){
			$status = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_status' was not successful! ".$ex->getMessage());
		}
		return $status;
	}
	
	/* Function name: getComment
	 * Input: $commentId (int) - comment identifier
	 * Output: data of comment | null
	 *
	 * This function returns the comment data for the
	 * requsted comment identifier.
	 *
	 * Null is returned if the requested identifier does
	 * not exist.
	 */
	public function getComment($commentId){
		try{
			$comment = DB::table('tasks_comments')
				->join('users', 'users.id', '=', 'tasks_comments.sender')
				->where('tasks_comments.id', '=', $commentId)
				->select('tasks_comments.id as id', 'users.name as poster', 'text as comment', 'datetime as date', 'users.username as poster_username')
				->first();
		}catch(\Exception $ex){
			$comment = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_comments', joined to 'users' was not successful! ".$ex->getMessage());
		}
		return $comment;
	}
	
	/* Function name: exists
	 * Input: $taskId (int) - task identifier
	 * Output: bool
	 *
	 * This function returns whether the requested
	 * task identifier exists or not.
	 */
	public function exists($taskId){
		try{
			$id = DB::table('tasks_task')
				->where('id', '=', $taskId)
				->first();
			$exist = ($id !== null);
		}catch(\Exception $ex){
			$exist = false;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_task' was not successful! ".$ex->getMessage());
		}
		return $exist;
	}
	
	/* Function name: commentExists
	 * Input: $commentId (int) - comment identifier
	 * Output: bool
	 *
	 * This function returns whether the requested
	 * task identifier exists or not.
	 */
	public function commentExists($commentId){
		try{
			$id = DB::table('tasks_comments')
				->where('id', '=', $commentId)
				->first();
			$exist = ($id !== null);
		}catch(\Exception $ex){
			$exist = false;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_comments' was not successful! ".$ex->getMessage());
		}
		return $exist;
	}
	
	/* Function name: setTask
	 * Input: $taskId (int) - task identifier
	 * Output: -
	 *
	 * This function sets the current task
	 * based on the requested task identifier.
	 */
	public function setTask($taskId){
		try{
			$this->task = DB::table('tasks_task')
				->join('tasks_type', 'tasks_type.id', '=', 'tasks_task.type')
				->join('tasks_status', 'tasks_status.id', '=', 'tasks_task.status')
				->join('tasks_priority', 'tasks_priority.id', '=', 'tasks_task.priority')
				->join('users', 'users.id', '=', 'tasks_task.created_by')
				->select('tasks_task.id as id', 'created_datetime as date', 'tasks_status.status as status', 'tasks_type.type as type', 'users.id as owner_id', 'users.name as user', 'users.username as username', 'text', 'caption', 'closed_datetime as closed', 'deadline', 'tasks_priority.name as priority', 'tasks_task.hours as working_hours')
				->where('tasks_task.id', '=', $taskId)
				->first();
			try{
				$assigned = DB::table('tasks_task')
					->join('users', 'users.id', '=', 'tasks_task.assigned')
					->where('tasks_task.id', '=', $taskId)
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
			}catch(\Exception $ex){
				Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_task', joined to 'users' was not successful! ".$ex->getMessage());
			}
				
			//format deadline
			if($this->task->deadline !== null){
				$this->task->deadline = substr($this->task->deadline, 0, 10);
			}
				
			try{
				//set comments for the given task
				$this->comments = DB::table('tasks_comments')
					->join('users', 'users.id', '=', 'tasks_comments.sender')
					->where('task', '=', $taskId)
					->where('deleted', '=', '0')
					->select('tasks_comments.id as id', 'users.name as poster', 'text as comment', 'datetime as date', 'users.username as poster_username')
					->orderBy('tasks_comments.id','desc')
					->get()
					->toArray();
			}catch(\Exception $ex){
				Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_comments', joined to 'tasks_type', 'tasks_status', 'tasks_priority', 'users' was not successful! ".$ex->getMessage());
			}
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_task', joined to 'tasks_type', 'tasks_status', 'tasks_priority', 'users' was not successful! ".$ex->getMessage());
		}
	}
	
	/* Function name: update
	 * Input: 	$taskId (int) - task identifier
	 * 			$type (int) - task type identifier 
	 * 			$text (text) - text of the task
	 * 			$caption (text) - caption of the task
	 * 			$deadline (date|NULL) - deadline of the task
	 * 			$priority (int) - priority identifier
	 * 			$status (int) - task status type identifier
	 * 			$workingHours (int) - working hours of the task
	 * 			$assignedUser (int) - assigned user's identifier
	 * 			$closed (bool) - closed or not
	 * Output: -
	 *
	 * This function updates a task based on the given
	 * values.
	 */
	public function update($taskId, $type, $text, $caption, $deadline, $priority, $status, $workingHours, $assignedUser, $closed){
		try{
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
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'tasks_task' was not successful! ".$ex->getMessage());
		}
	}
	
	/* Function name: canModify
	 * Input: -
	 * Output: bool (user can modify the task or not)
	 *
	 * This function determines whether the current task
	 * can be modified by the user or not. This boolean
	 * value is returned.
	 */
	public function canModify(){
		$layout = new LayoutData();
		return ($this->task !== null) && ($layout->user()->user()->id === $this->task->owner_id || $layout->user()->permitted('tasks_admin'));
	}
	
	/* Function name: addTask
	 * Input:	$type (int) - type identifier
	 * 			$createdById (int) - creator user's identifier
	 * 			$text (text) - text of the task
	 * 			$caption (text) - caption of the task
	 * 			$deadline (date|NULL) - deadline of the task
	 * 			$priority (int) - priority identifier
	 * Output: -
	 *
	 * This function creates a new task with the
	 * given data.
	 */
	public function addTask($type, $createdById, $text, $caption, $deadline, $priority){
		try{
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
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Insert into table 'tasks_task' was not successful! ".$ex->getMessage());
		}
	}
	
	/* Function name: removeTask
	 * Input: $taskId (int) - task identifier
	 * Output: -
	 *
	 * This function removes the requested task.
	 * The removal is only logical!
	 */
	public function removeTask($taskId){
		try{
			DB::table('tasks_task')
				->where('id', '=', $taskId)
				->update(['deleted' => 1]);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'tasks_task' was not successful! ".$ex->getMessage());
		}
	}
	
	/* Function name: addComment
	 * Input:	$taskId (int) - task identifier
	 * 			$userId (int) - writer user's identifier 
	 * 			$text (text) - text of the comment
	 * Output: -
	 *
	 * This function creates a new comment based on
	 * the given data.
	 */
	public function addComment($taskId, $userId, $text){
		try{
			DB::table('tasks_comments')
				->insert([
					'text' => $text,
					'task' => $taskId,
					'sender' => $userId,
					'datetime' => Carbon::now()->toDateTimeString()
				]);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_comments' was not successful! ".$ex->getMessage());
		}
	}
	
	/* Function name: removeComment
	 * Input: $commentId (int) - comment identifier
	 * Output: -
	 *
	 * This function removes the requested comment.
	 * The removal is only logical!
	 */
	public function removeComment($commentId){
		try{
			DB::table('tasks_comments')
				->where('id', '=', $commentId)
				->update(['deleted' => 1]);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'tasks_comments' was not successful! ".$ex->getMessage());
		}
	}
	
//PRIVATE FUNCTIONS
	
	/* Function name: getPriorities
	 * Input: -
	 * Output: array of priorities
	 *
	 * This function returns the task priorities.
	 */
	private function getPriorities(){
		try{
			$priorities = DB::table('tasks_priority')
				->orderBy('id', 'asc')
				->get()
				->toArray();
		}catch(\Exception $ex){
			$priorities = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_priority' was not successful! ".$ex->getMessage());
		}
		return $priorities;
	}
	
	/* Function name: getTaskTypes
	 * Input: -
	 * Output: array of task types
	 *
	 * This function returns the task types.
	 */
	private function getTaskTypes(){
		try{
			$taskTypes = DB::table('tasks_type')
				->orderBy('id', 'asc')
				->get()
				->toArray();
		}catch(\Exception $ex){
			$taskTypes = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_type' was not successful! ".$ex->getMessage());
		}
		return $taskTypes;
	}
	
	/* Function name: getTaskStatusTypes
	 * Input: -
	 * Output: array of task status types
	 *
	 * This function returns the task status types.
	 */
	private function getTaskStatusTypes(){
		try{
			$statusTypes = DB::table('tasks_status')
				->orderBy('id', 'asc')
				->get()
				->toArray();
		}catch(\Exception $ex){
			$statusTypes = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_status' was not successful! ".$ex->getMessage());
		}
		return $statusTypes;
	}
    
	/* Function name: getTasks
	 * Input: $userId (int) - user's identifier
	 * Output: array of tasks
	 *
	 * This function returns the tasks.
	 */
    private function getTasks($userId = 0){
		try{
			$tasks = DB::table('tasks_task')
				->join('tasks_type', 'tasks_type.id', '=', 'tasks_task.type')
				->join('tasks_status', 'tasks_status.id', '=', 'tasks_task.status')
				->join('tasks_priority', 'tasks_priority.id', '=', 'tasks_task.priority')
				->join('users', 'users.id', '=', 'tasks_task.created_by')
				->when($status != "", function ($query) {
					return $query->where('tasks_status.id', '=', $this->filters['status']);
				})
				->when($priority != "", function ($query){
					return $query->where('tasks_priority.id', '=', $this->filters['priority']);
				})
				->when($this->filters['myTask'], function ($query) use ($userId){
					return $query->where('tasks_task.assigned', '=', $userId);
				})
				->when($this->filters['hideClosed'], function ($query){
					return $query->where('tasks_status.status', '!=', 'closed');
				})
				->where('tasks_task.caption', 'LIKE', '%'.($this->filters['caption']).'%')
				->where('tasks_task.deleted', '=', 0)
				->select('tasks_task.id as id', 'created_datetime as date', 'tasks_status.status as status', 'users.name as user', 'caption', 'tasks_priority.name as priority', 'users.username as username')
				->orderBy('tasks_task.id', 'desc')
				->get()
				->toArray();
		}catch(\Exception $ex){
			$tasks = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_task', joined to 'tasks_type', 'tasks_status', 'tasks_priority', 'users' was not successful! ".$ex->getMessage());
		}
		return $tasks;
	}
}
