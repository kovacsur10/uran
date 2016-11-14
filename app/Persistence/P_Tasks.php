<?php

namespace App\Persistence;

use DB;

/** Class name: P_Tasks
 *
 * This class is the database persistence layer class
 * for the tasks module tables.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class P_Tasks{
	
	/** Function name: addTask
	 *
	 * This function creates a new task based on the
	 * provided data.
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
	static function addTask($type, $createdById, $text, $caption, $priority, $time, $deadline = null){
		DB::table('tasks_task')
			->insert([
					'created_datetime' => $time,
					'status' => 1,
					'type' => $type,
					'created_by' => $createdById,
					'text' => $text,
					'caption' => $caption,
					'deadline' => $deadline,
					'priority' => $priority
			]);
	}
	
	/** Function name: updateTask
	 * 
	 * This function updates the requested task
	 * with the provided data.
	 * 
	 * @param int $taskId - the task identifier
	 * @param int $status - status identifier
	 * @param int $type - task type identifier
	 * @param text $text - text
	 * @param text $caption - caption
	 * @param int $priority - priority identifier
	 * @param int $workingHours - already worked hours on it
	 * @param int $assignedUser - assigned user's identifier
	 * @param datetime|null $closedDate - task closing date
	 * @param datetime|null $deadline - deadline
	 * 
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function updateTask($taskId, $status, $type, $text, $caption, $priority, $workingHours, $assignedUser, $closedDate = null, $deadline = null){
		DB::table('tasks_task')
			->where('id', '=', $taskId)
			->update([
					'status' => $status,
					'type' => $type,
					'text' => $text,
					'caption' => $caption,
					'priority' => $priority,
					'deadline' => $deadline,
					'hours' => $workingHours,
					'assigned' => $assignedUser,
					'closed_datetime' => $closedDate
			]);
	}
	
	/** Function name: getTask
	 *
	 * This function returns the requested task.
	 *
	 * @param int $taskId - task identifier
	 * @return Task|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getTask($taskId){
		return DB::table('tasks_task')
			->join('tasks_type', 'tasks_type.id', '=', 'tasks_task.type')
			->join('tasks_status', 'tasks_status.id', '=', 'tasks_task.status')
			->join('tasks_priority', 'tasks_priority.id', '=', 'tasks_task.priority')
			->join('users', 'users.id', '=', 'tasks_task.created_by')
			->where('tasks_task.id', '=', $taskId)
			->select('tasks_task.id as id', 'created_datetime as date', 'tasks_status.status as status', 'tasks_type.type as type', 'users.id as owner_id', 'users.name as owner_user', 'users.username as owner_username', 'text', 'caption', 'closed_datetime as closed', 'deadline', 'tasks_priority.name as priority', 'tasks_task.hours as working_hours', 'assigned as assigned_id', 'deleted')
			->first();
	}

	/** Function name: getAssignedUserToTask
	 *
	 * This function returns the assigned user
	 * data for a task.
	 *
	 * @param int $taskId - task identifier
	 * @return Task|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getAssignedUserToTask($taskId){
		return DB::table('tasks_task')
			->join('users', 'users.id', '=', 'tasks_task.assigned')
			->where('tasks_task.id', '=', $taskId)
			->first();
	}
	
	/** Function name: getTasks
	 *
	 * This function returns the tasks.
	 *
	 * @param int $taskId - task identifier
	 * @return array of tasks
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getTasks($status = "", $priorityId = "", $onlyAssignedToUser = false, $userId = null, $hideClosed = false, $caption = ""){
		return DB::table('tasks_task')
			->join('tasks_type', 'tasks_type.id', '=', 'tasks_task.type')
			->join('tasks_status', 'tasks_status.id', '=', 'tasks_task.status')
			->join('tasks_priority', 'tasks_priority.id', '=', 'tasks_task.priority')
			->join('users', 'users.id', '=', 'tasks_task.created_by')
			->when($status != "", function ($query) use($status){
				return $query->where('tasks_status.id', '=', $statusId);
			})
			->when($priorityId != "", function ($query) use($priorityId){
				return $query->where('tasks_priority.id', '=', $priorityId);
			})
			->when($onlyAssignedToUser, function ($query) use ($userId){
				return $query->where('tasks_task.assigned', '=', $userId);
			})
			->when($hideClosed, function ($query){
				return $query->where('tasks_status.status', 'NOT LIKE', 'closed');
			})
			->when($caption != "", function($query) use($caption){
				return $query->where('tasks_task.caption', 'LIKE', '%'.$caption.'%');
			})
			->where('tasks_task.deleted', '=', false)
			->select('tasks_task.id as id', 'created_datetime as date', 'tasks_status.status as status', 'users.name as user', 'caption', 'tasks_priority.name as priority', 'users.username as username')
			->orderBy('tasks_task.id', 'desc')
			->get()
			->toArray();
	}
	
	/** Function name: removeTask
	 *
	 * This function virtually removes a task.
	 *
	 * @param int $taskId - task identifier
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function removeTask($taskId){
		DB::table('tasks_task')
			->where('id', '=', $taskId)
			->update([
				'deleted' => true
			]);
	}
	
	/** Function name: addComment
	 *
	 * This function adds a new comment to
	 * the database with the provided data.
	 *
	 * @param text $text - comment text
	 * @param int $taskId - task identifier
	 * @param int $userId - user's identifier
	 * @param datetime $time - creation time
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function addComment($text, $taskId, $userId, $time){
		DB::table('tasks_comments')
			->insert([
					'text' => $text,
					'task' => $taskId,
					'sender' => $userId,
					'datetime' => $time
			]);
	}
	
	/** Function name: getComment
	 *
	 * This function returns a comment based on the
	 * requested identifier.
	 *
	 * @param int $commentId - comment identifier
	 * @return Comment|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getComment($commentId){
		return DB::table('tasks_comments')
			->join('users', 'users.id', '=', 'tasks_comments.sender')
			->where('tasks_comments.id', '=', $commentId)
			->select('tasks_comments.id as id', 'users.name as poster', 'text as comment', 'datetime as date', 'users.username as poster_username')
			->first();
	}
	
	/** Function name: getCommentsForTask
	 *
	 * This function returns the comments related
	 * to the provided task.
	 *
	 * @param int $taskId - task identifier
	 * @return array of Comments
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getCommentsForTask($taskId){
		return DB::table('tasks_comments')
			->join('users', 'users.id', '=', 'tasks_comments.sender')
			->where('task', '=', $taskId)
			->where('deleted', '=', false)
			->select('tasks_comments.id as id', 'users.name as poster', 'text as comment', 'datetime as date', 'users.username as poster_username')
			->orderBy('tasks_comments.id','desc')
			->get()
			->toArray();
	}
	
	/** Function name: deleteComment
	 *
	 * This function deletes a comment based on the
	 * requested identifier. It's just a virtual deletion.
	 *
	 * @param int $commentId - comment identifier
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function deleteComment($commentId){
		DB::table('tasks_comments')
			->where('id', '=', $commentId)
			->update([
				'deleted' => true
			]);
	}
	
	/** Function name: getStatusById
	 *
	 * This function returns a status based on the
	 * requested identifier.
	 *
	 * @param int $statusId - status identifier
	 * @return Status|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getStatusById($statusId){
		return DB::table('tasks_status')
			->where('id', '=', $statusId)
			->first();
	}
	
	/** Function name: getStatusByName
	 *
	 * This function returns a status based on the
	 * requested text identifier.
	 *
	 * @param text $statusName - status text identifier
	 * @return Status|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getStatusByName($statusName){
		return DB::table('tasks_status')
			->where('status', 'LIKE', $statusName)
			->first();
	}
	
	/** Function name: getStatusTypes
	 *
	 * This function returns the task status types.
	 *
	 * @return array of statuses
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getStatusTypes(){
		return DB::table('tasks_status')
			->orderBy('id', 'asc')
			->get()
			->toArray();
	}
	
	/** Function name: getPriorities
	 *
	 * This function returns the task priorities.
	 *
	 * @return array of priorities
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getPriorities(){
		return DB::table('tasks_priority')
			->orderBy('id', 'asc')
			->get()
			->toArray();
	}
	
	/** Function name: getTypes
	 *
	 * This function returns the task types.
	 *
	 * @return array of task types
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getTypes(){
		return DB::table('tasks_type')
			->orderBy('id', 'asc')
			->get()
			->toArray();
	}
}