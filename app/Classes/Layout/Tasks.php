<?php

namespace App\Classes\Layout;

use Carbon\Carbon;
use App\Classes\LayoutData;
use App\Classes\Logger;
use App\Persistence\P_Tasks;

/** Class name: Tasks
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
 * 
 * @author Máté Kovács <kovacsur10@gmail.com>
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
    
    /** Function name: __construct
     *
     * The constructor for the Tasks class.
     * 
     * @author Máté Kovács <kovacsur10@gmail.com>
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
	
	/** Function name: get
	 *
	 * Getter function for tasks.
	 * 
	 * @return array of Tasks
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function get(){
        return $this->tasks;
	}
	
	/** Function name: getTask
	 *
	 * Getter function for the current task.
	 * 
	 * @return Task|null
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getTask(){
		return $this->task;
	}
	
	/** Function name: priorities
	 *
	 * Getter function for the task priorities.
	 * 
	 * @return array of Priorities
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function priorities(){
		return $this->priorities;
	}
	
	/** Function name: taskTypes
	 *
	 * Getter function for the task types.
	 * 
	 * @return array of task types
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function taskTypes(){
		return $this->types;
	}
	
	/** Function name: getComments
	 *
	 * Getter function for the comments of the selected task.
	 * 
	 * @return array of TaskComment
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getComments(){
		return $this->comments;
	}
	
	/** Function name: statusTypes
	 *
	 * Getter function for the status types of the tasks.
	 * 
	 * @return array of TaskStatus
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function statusTypes(){
		return $this->statusTypes;
	}
	
	/** Function name: getFilter
	 *
	 * Getter function for the requested filter.
	 * 
	 * Returns NULL if the requested filter name
	 * does not exist.
	 * 
	 * @param text $filterName - filter name
	 * @return filterValue|null
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getFilter($filterName){
		if(array_key_exists($filterName, $this->filters)){
			return $this->filters[$filterName];
		}else{
			return null;
		}
	}
	
	/** Function name: filterTasks
	 *
	 * This function sets the filters based
	 * on the given values. The tasks array is
	 * updated with the filtered array data.
	 * 
	 * @param text $status - status filter
	 * @param text $caption - caption filter
	 * @param int $priority - priority identifier filter
	 * @param bool $myTasks - only user's tasks filter
	 * @param bool $hideClosed - closed tasks filter 
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
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
	
	/** Function name: tasksToPages
	 *
	 * This function returns the requested count
	 * of tasks from the requested identifier.
	 * 
	 * @param int $from - first task identifier
	 * @param int $count - tasks count
	 * @return array of tasks
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
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
	
	/** Function name: getStatusById
	 *
	 * This function returns the task status data for the 
	 * requsted status identifier.
	 * 
	 * Null is returned if the requested identifier does
	 * not exist.
	 * 
	 * @param int $statusId - status identifier
	 * @return Status|null
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getStatusById($statusId){
		try{
			$status = P_Tasks::getStatusById($statusId);
		}catch(\Exception $ex){
			$status = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_status' was not successful! ".$ex->getMessage());
		}
		return $status;
	}
	
	/** Function name: getStatusByName
	 *
	 * This function returns the task status data for the
	 * requsted status name.
	 * 
	 * Null is returned if the requested name does
	 * not exist.
	 * 
	 * @param text $statusName - status name
	 * @return Status|null
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getStatusByName($statusName){
		try{
			$status = P_Tasks::getStatusByName($statusName);
		}catch(\Exception $ex){
			$status = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_status' was not successful! ".$ex->getMessage());
		}
		return $status;
	}
	
	/** Function name: getComment
	 *
	 * This function returns the comment data for the
	 * requsted comment identifier.
	 *
	 * Null is returned if the requested identifier does
	 * not exist.
	 * 
	 * @param int $commentId - comment identifier
	 * @return Comment|null
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function getComment($commentId){
		try{
			$comment = P_Tasks::getComment($commentId);
		}catch(\Exception $ex){
			$comment = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_comments', joined to 'users' was not successful! ".$ex->getMessage());
		}
		return $comment;
	}
	
	/** Function name: exists
	 *
	 * This function returns whether the requested
	 * task identifier exists or not.
	 * 
	 * @param int $taskId - task identifier
	 * @return bool
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function exists($taskId){
		try{
			$id = P_Tasks::getTask($taskId);
			$exist = ($id !== null);
		}catch(\Exception $ex){
			$exist = false;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_task' was not successful! ".$ex->getMessage());
		}
		return $exist;
	}
	
	/** Function name: commentExists
	 *
	 * This function returns whether the requested
	 * task identifier exists or not.
	 * 
	 * @param int $commentId - comment identifier
	 * @return bool
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function commentExists($commentId){
		try{
			$id = P_Tasks::getComment($commentId);
			$exist = ($id !== null);
		}catch(\Exception $ex){
			$exist = false;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_comments' was not successful! ".$ex->getMessage());
		}
		return $exist;
	}
	
	/** Function name: setTask
	 *
	 * This function sets the current task
	 * based on the requested task identifier.
	 * 
	 * @param int $taskId - task identifier
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function setTask($taskId){
		try{
			$this->task = P_Tasks::getTask($taskId);
			if($this->task->assigned_id !== null){
				try{
					$assigned = P_Tasks::getAssignedUserToTask($taskId);
					$this->task->assigned_id = $assigned->id;
					$this->task->assigned_name = $assigned->name;
					$this->task->assigned_username = $assigned->username;
				}catch(\Exception $ex){
					Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_task', joined to 'users' was not successful! ".$ex->getMessage());
				}
			}else{
				$this->task->assigned_name = null;
				$this->task->assigned_username = null;
			}
				
			//format deadline
			if($this->task->deadline !== null){
				$this->task->deadline = substr($this->task->deadline, 0, 10);
			}
				
			try{
				//set comments for the given task
				$this->comments = P_Tasks::getCommentsForTask($taskId);
			}catch(\Exception $ex){
				Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_comments', joined to 'tasks_type', 'tasks_status', 'tasks_priority', 'users' was not successful! ".$ex->getMessage());
			}
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_task', joined to 'tasks_type', 'tasks_status', 'tasks_priority', 'users' was not successful! ".$ex->getMessage());
		}
	}
	
	/** Function name: update
	 *
	 * This function updates a task based on the given
	 * values.
	 * 
	 * @param int $taskId - task identifier
	 * @param int $type - task type identifier 
	 * @param text $text - text of the task
	 * @param text $caption - caption of the task
	 * @param datetime|null $deadline - deadline of the task
	 * @param int $priority - priority identifier
	 * @param int $status - task status type identifier
	 * @param int $workingHours - working hours of the task
	 * @param int $assignedUser - assigned user's identifier
	 * @param bool $closed - closed or not
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function update($taskId, $type, $text, $caption, $deadline, $priority, $status, $workingHours, $assignedUser, $closed){
		try{
			$closingTime = $closed ? (Carbon::now()->toDateTimeString()) : null;
			$deadline = $deadline === null ? null : str_replace('.', '-', str_replace('. ', '-', $deadline));
			P_Tasks::updateTask($taskId, $status, $type, $text, $caption, $priority, $workingHours, $assignedUser, $closingTime, $deadline);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'tasks_task' was not successful! ".$ex->getMessage());
		}
	}
	
	/** Function name: canModify
	 *
	 * This function determines whether the current task
	 * can be modified by the user or not. This boolean
	 * value is returned.
	 * 
	 * @return bool - user can modify the task or not
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function canModify(){
		$layout = new LayoutData();
		return ($this->task !== null) && ($layout->user()->user()->id === $this->task->owner_id || $layout->user()->permitted('tasks_admin'));
	}
	
	/** Function name: addTask
	 *
	 * This function creates a new task with the
	 * given data.
	 * 
	 * @param int $type - type identifier
	 * @param int $createdById - creator user's identifier
	 * @param text $text - text of the task
	 * @param text $caption - caption of the task
	 * @param datetime|null $deadline - deadline of the task
	 * @param int $priority - priority identifier
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function addTask($type, $createdById, $text, $caption, $deadline, $priority){
		try{
			P_Tasks::addTask($type, $createdById, $text, $caption, $priority, Carbon::now()->toDateTimeString(), $deadline);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Insert into table 'tasks_task' was not successful! ".$ex->getMessage());
		}
	}
	
	/** Function name: removeTask
	 *
	 * This function removes the requested task.
	 * The removal is only logical!
	 * 
	 * @param int $taskId - task identifier
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function removeTask($taskId){
		try{
			P_Tasks::removeTask($taskId);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'tasks_task' was not successful! ".$ex->getMessage());
		}
	}
	
	/** Function name: addComment
	 *
	 * This function creates a new comment based on
	 * the given data.
	 * 
	 * @param int $taskId - task identifier
	 * @param int $userId - writer user's identifier 
	 * @param text $text - text of the comment
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function addComment($taskId, $userId, $text){
		try{
			P_Tasks::addComment($text, $taskId, $userId, Carbon::now()->toDateTimeString());
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_comments' was not successful! ".$ex->getMessage());
		}
	}
	
	/** Function name: removeComment
	 *
	 * This function removes the requested comment.
	 * The removal is only logical!
	 * 
	 * @param int $commentId - comment identifier
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function removeComment($commentId){
		try{
			P_Tasks::deleteComment($commentId);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'tasks_comments' was not successful! ".$ex->getMessage());
		}
	}
	
//PRIVATE FUNCTIONS
	
	/** Function name: getPriorities
	 *
	 * This function returns the task priorities.
	 * 
	 * @return array of TaskPriority
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	private function getPriorities(){
		try{
			$priorities = P_Tasks::getPriorities();
		}catch(\Exception $ex){
			$priorities = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_priority' was not successful! ".$ex->getMessage());
		}
		return $priorities;
	}
	
	/** Function name: getTaskTypes
	 *
	 * This function returns the task types.
	 * 
	 * @return array of TaskType
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	private function getTaskTypes(){
		try{
			$taskTypes = P_Tasks::getTypes();
		}catch(\Exception $ex){
			$taskTypes = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_type' was not successful! ".$ex->getMessage());
		}
		return $taskTypes;
	}
	
	/** Function name: getTaskStatusTypes
	 *
	 * This function returns the task status types.
	 * 
	 * @return array of TaskStatus
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	private function getTaskStatusTypes(){
		try{
			$statusTypes = P_Tasks::getStatusTypes();
		}catch(\Exception $ex){
			$statusTypes = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_status' was not successful! ".$ex->getMessage());
		}
		return $statusTypes;
	}
    
	/** Function name: getTasks
	 *
	 * This function returns the tasks.
	 * 
	 * @param int $userId - user's identifier
	 * @return array of Task
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
    private function getTasks($userId = 0){
		try{
			$tasks = P_Tasks::getTasks($this->filters['status'], $this->filters['priority'], $this->filters['myTasks'], $userId, $this->filters['hideClosed'], $this->filters['caption']);				
		}catch(\Exception $ex){
			$tasks = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
		return $tasks;
	}
}
