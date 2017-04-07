<?php

namespace App\Classes\Layout;

use Carbon\Carbon;
use App\Classes\LayoutData;
use App\Classes\Logger;
use App\Persistence\P_Tasks;
use App\Classes\Interfaces\Pageable;
use App\Exceptions\ValueMismatchException;
use App\Exceptions\DatabaseException;
use App\Classes\Database;
use Illuminate\Support\Facades\Session;

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
class Tasks extends Pageable{
	
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
		$this->resetFilterTasks(false);
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
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function filterTasks(){ //TODO: modify test
		$layout = new LayoutData();
		
		if(Session::has('tasks_status_filter') || Session::has('tasks_caption_filter') || Session::has('tasks_priority_filter') || 	Session::has('tasks_hide_closed_filter') || Session::has('tasks_mytasks_filter')){
			$statusId = Session::get('tasks_status_filter');
			$caption = Session::get('tasks_caption_filter');
			$priority = Session::get('tasks_priority_filter');
			$myTasks = Session::get('tasks_mytasks_filter');
			$hideClosed = Session::get('tasks_hide_closed_filter');
			
			if($statusId === null || $statusId === ''){
				$this->filters['status'] = null;
			}else{
				$this->filters['status'] = $statusId;
			}
			if($caption !== null){
				$this->filters['caption'] = $caption;
			}else{
				$this->filters['caption'] = '';
			}
			if($priority === null || $priority === ''){
				$this->filters['priority'] = null;
			}else{
				$this->filters['priority'] = $priority;
			}
			if($myTasks !== null){
				$this->filters['myTasks'] = $myTasks === true;
			}else{
				$this->filters['myTasks'] = false;
			}
			if($hideClosed !== null){
				$this->filters['hideClosed'] = $hideClosed === true;
			}else{
				$this->filters['hideClosed'] = false;
			}
			$this->getTasks($layout->user()->user() === null ? 0 : $layout->user()->user()->id());
		}
	}
	
	/** Function name: setFilterTasks
	 *
	 * This function sets the filters based
	 * on the given values.
	 *
	 * @param int $status
	 * @param text $caption
	 * @param int $priority
	 * @param bool $myTasks
	 * @param bool $hide_closed
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function setFilterTasks($status, $caption, $priority, $myTasks, $hide_closed){
		if($caption !== null && $caption !== ""){
			Session::put('tasks_caption_filter', $caption);
		}
		if($status !== null && $status !== ""){
			Session::put('tasks_status_filter', $status);
		}
		if($priority !== null && $priority !== ""){
			Session::put('tasks_priority_filter', $priority);
		}
		if($myTasks === null){
			Session::put('tasks_mytasks_filter', false);
		}else{
			Session::put('tasks_mytasks_filter', true);
		}
		if($hide_closed === null){
			Session::put('tasks_hide_closed_filter', false);
		}else{
			Session::put('tasks_hide_closed_filter', true);
		}
	}
	
	/** Function name: resetFilterTasks
	 *
	 * This function resets the filters. The tasks array is
	 * updated with the filtered array data.
	 * 
	 * @param bool $hardReset - reset the session data as well
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function resetFilterTasks($hardReset = true){ //TODO: test
		$this->filters = [
				'priority'		=> null,
				'status' 		=> null,
				'caption'		=> '',
				'myTasks'		=> false,
				'hideClosed'	=> false,
		];
		if($hardReset){
			Session::forget('tasks_status_filter');
			Session::forget('tasks_caption_filter');
			Session::forget('tasks_priority_filter');
			Session::forget('tasks_mytasks_filter');
			Session::forget('tasks_hide_closed_filter');
		}
		$this->getTasks();
	}
	
	/** Function name: tasksToPages
	 *
	 * This function returns the requested count
	 * of tasks from the requested identifier.
	 * 
	 * @param int $from - first task identifier
	 * @param int $count - tasks count
	 * @return array of Task
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function tasksToPages($from = 0, $count = 50){
		return $this->toPages($this->tasks, $from, $count);
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
		if($statusId === null){
			return null;
		}
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
		if($statusName === null){
			return null;
		}
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
		if($commentId === null){
			return null;
		}
		try{
			$comment = P_Tasks::getComment($commentId);
		}catch(\Exception $ex){
			$comment = null;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
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
		if($taskId === null){
			return false;
		}
		try{
			$id = P_Tasks::getTask($taskId);
			$exist = ($id !== null);
		}catch(\Exception $ex){
			$exist = false;
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
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
		if($commentId === null){
			return false;
		}
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
	 * @throws ValueMismatchException when the parameter is null.
	 * @throws DatabaseException when the selection fails.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function setTask($taskId){
		if($taskId === null){
			throw new ValueMismatchException("Null value is not accepted!");
		}
		try{
			$this->task = P_Tasks::getTask($taskId);
			try{
				//set comments for the given task
				$this->comments = P_Tasks::getCommentsForTask($taskId);
			}catch(\Exception $ex){
				Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
				throw new DatabaseException("Comment cannot be loaded!");
			}
		}catch(\Exception $ex){
			$this->task = null;
			$this->comments = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("The task selection failed!");
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
	 * @param int|null $assignedUser - assigned user's identifier
	 * @param bool $closed - closed or not
	 * 
	 * @throws ValueMismatchException when the parameter is null!
	 * @throws DatabaseException when the process failed!
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function update($taskId, $type, $text, $caption, $deadline, $priority, $status, $workingHours, $assignedUser, $closed){
		if($taskId === null || $type === null || $text === null || $caption === null || $priority === null || $status === null || $workingHours === null || $closed === null){
			throw new ValueMismatchException("A parameter is null, but cannot be that!");
		}
		try{
			$closingTime = $closed ? (Carbon::now()->toDateTimeString()) : null;
			$deadline = $deadline === null ? null : str_replace('.', '-', str_replace('. ', '-', $deadline));
			Database::transaction(function() use($taskId, $status, $type, $text, $caption, $priority, $workingHours, $assignedUser, $closingTime, $deadline){
				P_Tasks::updateTask($taskId, $status, $type, $text, $caption, $priority, $workingHours, $assignedUser, $closingTime, $deadline);
				$this->getTasks();
			});
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'tasks_task' was not successful! ".$ex->getMessage());
			throw new DatabaseException("Task update was not successful!");
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
		return ($this->task !== null) && ($layout->user()->user() !== null) && ($layout->user()->user()->id() === $this->task->creator()->id() || $layout->user()->permitted('tasks_admin'));
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
	 * @throws ValueMismatchException when the parameter is null!
	 * @throws DatabaseException when the process failed!
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function addTask($type, $createdById, $text, $caption, $deadline, $priority){
		if($type === null || $createdById === null || $text === null || $caption === null || $priority === null){
			throw new ValueMismatchException("A parameter is null, but cannot be that!");
		}
		try{
			$time = Carbon::now()->toDateTimeString();
			Database::transaction(function() use($type, $createdById, $text, $caption, $priority, $deadline, $time){
				P_Tasks::addTask($type, $createdById, $text, $caption, $priority, $time, $deadline);
				$this->getTasks();
			});
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Task addition was not successful!");
		}
	}
	
	/** Function name: removeTask
	 *
	 * This function removes the requested task.
	 * The removal is only logical!
	 * 
	 * @param int $taskId - task identifier
	 * 
	 * @throws ValueMismatchException when the parameter is null!
	 * @throws DatabaseException when the process failed!
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function removeTask($taskId){
		if($taskId === null){
			throw new ValueMismatchException("Parameter cannot be null!");
		}
		try{
			Database::transaction(function() use($taskId){
				P_Tasks::removeTask($taskId);
				$this->getTasks();
			});
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
			throw new DatabaseException("Task removal was not successful!");
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
	 * @throws ValueMismatchException when a parameter is null.
	 * @throws DatabaseException when the addition fails.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function addComment($taskId, $userId, $text){
		if($taskId === null || $userId === null || $text === null){
			throw new ValueMismatchException("Null values are not permitted!");
		}
		try{
			P_Tasks::addComment($text, $taskId, $userId, Carbon::now()->toDateTimeString());
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Select from table 'tasks_comments' was not successful! ".$ex->getMessage());
			throw new DatabaseException("Adding the comment was unsuccessful!");
		}
	}
	
	/** Function name: removeComment
	 *
	 * This function removes the requested comment.
	 * The removal is only logical!
	 * 
	 * @param int $commentId - comment identifier
	 * 
	 * @throws ValueMismatchException when a parameter is null.
	 * @throws DatabaseException when the removal fails.
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function removeComment($commentId){
		if($commentId === null){
			throw new ValueMismatchException("Null values are not permitted!");
		}
		try{
			P_Tasks::deleteComment($commentId);
		}catch(\Exception $ex){
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). Update table 'tasks_comments' was not successful! ".$ex->getMessage());
			throw new DatabaseException("Removing the comment was unsuccessful!");
		}
	}
	
	/** Function name: checkTaskCount
	 *
	 * This function checks the showable task count and
	 * saves session data.
	 *
	 * @param int $count - task count to show
	 * @return int - corrected task count to shw
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function checkTaskCount($count){ //TODO: test
		if(($count === null && !Session::has('tasks_paging')) || ($count !== null && ($count < 1 || 100 < $count || !is_numeric($count)))){
			$count = 10;
		}else if($count === null){
			$count = Session::get('tasks_paging');
		}
		Session::put('tasks_paging', $count);
		return $count;
	}
	
	/** Function name: getSessionData
	 *
	 * This function returns an array or values, that
	 * should be saved as session data for the task.
	 *
	 * @return array of mixed - the returned values
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public static function getSessionData(){ //TODO: test
		$sessionData = [];
		if(Session::has('tasks_status_filter')){
			$sessionData['tasks_status_filter'] = Session::get('tasks_status_filter');
		}
		if(Session::has('tasks_caption_filter')){
			$sessionData['tasks_caption_filter'] = Session::get('tasks_caption_filter');
		}
		if(Session::has('tasks_priority_filter')){
			$sessionData['tasks_priority_filter'] = Session::get('tasks_priority_filter');
		}
		if(Session::has('tasks_mytasks_filter')){
			$sessionData['tasks_mytasks_filter'] = Session::get('tasks_mytasks_filter');
		}
		if(Session::has('tasks_hide_closed_filter')){
			$sessionData['tasks_hide_closed_filter'] = Session::get('tasks_hide_closed_filter');
		}
		if(Session::has('tasks_paging')){
			$sessionData['tasks_paging'] = Session::get('tasks_paging');
		}
		return $sessionData;
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
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
    private function getTasks($userId = 0){
		try{
			$this->tasks = P_Tasks::getTasks($this->filters['status'], $this->filters['priority'], $this->filters['myTasks'], $userId, $this->filters['hideClosed'], $this->filters['caption']);				
		}catch(\Exception $ex){
			$this->tasks = [];
			Logger::error_log("Error at line: ".__FILE__.":".__LINE__." (in function ".__FUNCTION__."). ".$ex->getMessage());
		}
	}
}
