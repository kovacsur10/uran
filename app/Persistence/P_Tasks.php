<?php

namespace App\Persistence;

use DB;
use App\Classes\Data\TaskStatus;
use App\Classes\Data\TaskType;
use App\Classes\Data\TaskPriority;
use App\Classes\Data\TaskComment;
use App\Classes\Data\StatusCode;
use App\Classes\Data\Task;
use App\Classes\Data\User;

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
	 * @param int|null $assignedUser - assigned user's identifier
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
		$task = DB::table('tasks_task')
			->join('tasks_type', 'tasks_type.id', '=', 'tasks_task.type')
			->join('tasks_status', 'tasks_status.id', '=', 'tasks_task.status')
			->join('tasks_priority', 'tasks_priority.id', '=', 'tasks_task.priority')
			->where('tasks_task.id', '=', $taskId)
			->select('created_by', 'assigned', 'tasks_task.id as id', 'created_datetime as date', 'tasks_status.id as status_id', 'tasks_status.status as status', 'tasks_type.id as type_id', 'tasks_type.type as type', 'text', 'caption', 'closed_datetime as closed', 'deadline', 'tasks_priority.id as priority_id', 'tasks_priority.name as priority', 'tasks_task.hours as working_hours', 'assigned as assigned_id', 'tasks_task.deleted as deleted')
			->first();
		if($task !== null){
			$creator = P_User::getUserById($task->created_by);
		}
		if($task !== null){
			$assigned = $task->assigned === null ? null : P_User::getUserById($task->assigned);
		}
		return $task === null ? null : new Task(
				$task->id,
				$task->caption,
				$task->text,
				$task->date,
				new TaskStatus($task->status_id, $task->status),
				new TaskPriority($task->priority_id, $task->priority),
				new TaskType($task->type_id, $task->type),
				$creator,
				$assigned,
				$task->deadline,
				$task->closed,
				$task->working_hours,
				$task->deleted);
	}
	
	/** Function name: getTasks
	 *
	 * This function returns the tasks.
	 *
	 * @param int|null $statusId - identifier of a task status
	 * @param int|null $priorityId - identifier of a task priority
	 * @param bool $onlyAssignedToUser - only get tasks assigned to a specific user
	 * @param int|null $userId - assigned user's identifier
	 * @param bool $hideClosed - get only the not closed tasks 
	 * @param string $caption - caption filter
	 * @return array of Task
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getTasks(int $statusId = null, int $priorityId = null, bool $onlyAssignedToUser = false, int $userId = null, bool $hideClosed = false, string $caption = ""){
		$getTasks = DB::table('tasks_task')
			->join('tasks_type', 'tasks_type.id', '=', 'tasks_task.type')
			->join('tasks_status', 'tasks_status.id', '=', 'tasks_task.status')
			->join('tasks_priority', 'tasks_priority.id', '=', 'tasks_task.priority')
			->when($statusId !== null, function ($query) use($statusId){
				return $query->where('tasks_status.id', '=', $statusId);
			})
			->when($priorityId !== null, function ($query) use($priorityId){
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
			->select('created_by', 'assigned', 'tasks_task.id as id', 'created_datetime as date', 'tasks_status.id as status_id', 'tasks_status.status as status', 'tasks_type.id as type_id', 'tasks_type.type as type', 'text', 'caption', 'closed_datetime as closed', 'deadline', 'tasks_priority.id as priority_id', 'tasks_priority.name as priority', 'tasks_task.hours as working_hours', 'assigned as assigned_id', 'tasks_task.deleted as deleted')
			->orderBy('tasks_task.id', 'desc')
			->get();
		$tasks = [];
		foreach($getTasks as $task){
			array_push($tasks, new Task(
				$task->id,
				$task->caption,
				$task->text,
				$task->date,
				new TaskStatus($task->status_id, $task->status),
				new TaskPriority($task->priority_id, $task->priority),
				new TaskType($task->type_id, $task->type),
				P_User::getUserById($task->created_by),
				($task->assigned === null ? null : P_User::getUserById($task->assigned)),
				$task->deadline,
				$task->closed,
				$task->working_hours,
				$task->deleted));
		}
		return $tasks;
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
	 * @return TaskComment|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getComment($commentId){
		$comment = DB::table('tasks_comments')
			->join('users', 'users.id', '=', 'tasks_comments.sender')
			->where('tasks_comments.id', '=', $commentId)
			->where('tasks_comments.deleted', '=', false)
			->select('tasks_comments.*', 'users.name as poster', 'users.username as poster_username')
			->first();
		return $comment === null ? null : new TaskComment($comment->id, $comment->text, $comment->datetime, $comment->deleted, $comment->task, $comment->sender, $comment->poster_username, $comment->poster);
	}
	
	/** Function name: getCommentsForTask
	 *
	 * This function returns the comments related
	 * to the provided task.
	 *
	 * @param int $taskId - task identifier
	 * @return array of TaskComment
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getCommentsForTask($taskId){
		$getComments = DB::table('tasks_comments')
			->join('users', 'users.id', '=', 'tasks_comments.sender')
			->where('task', '=', $taskId)
			->where('deleted', '=', false)
			->select('tasks_comments.*', 'users.name as poster', 'users.username as poster_username')
			->orderBy('tasks_comments.id','desc')
			->get();
		$comments = [];
		foreach($getComments as $comment){
			array_push($comments, new TaskComment($comment->id, $comment->text, $comment->datetime, $comment->deleted, $comment->task, $comment->sender, $comment->poster_username, $comment->poster));
		}
		return $comments;
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
	 * @return TaskStatus|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getStatusById($statusId){
		$status = DB::table('tasks_status')
			->where('id', '=', $statusId)
			->first();
		return $status === null ? null : new TaskStatus($status->id, $status->status);
	}
	
	/** Function name: getStatusByName
	 *
	 * This function returns a status based on the
	 * requested text identifier.
	 *
	 * @param text $statusName - status text identifier
	 * @return TaskStatus|null
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getStatusByName($statusName){
		$status = DB::table('tasks_status')
			->where('status', 'LIKE', $statusName)
			->first();
		return $status === null ? null : new TaskStatus($status->id, $status->status);
	}
	
	/** Function name: getStatusTypes
	 *
	 * This function returns the task status types.
	 *
	 * @return array of TaskStatus
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getStatusTypes(){
		$getStatus = DB::table('tasks_status')
			->orderBy('id', 'asc')
			->get();
		$states = [];
		foreach($getStatus as $status){
			array_push($states, new TaskStatus($status->id, $status->status));
		}
		return $states;
	}
	
	/** Function name: getPriorities
	 *
	 * This function returns the task priorities.
	 *
	 * @return array of TaskPriority
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getPriorities(){
		$getPriorities = DB::table('tasks_priority')
			->orderBy('id', 'asc')
			->get();
		$priorities = [];
		foreach($getPriorities as $priority){
			array_push($priorities, new TaskPriority($priority->id, $priority->name));
		}
		return $priorities;
	}
	
	/** Function name: getTypes
	 *
	 * This function returns the task types.
	 *
	 * @return array of TaskType
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	static function getTypes(){
		$getTypes = DB::table('tasks_type')
			->orderBy('id', 'asc')
			->get();
		$types = [];
		foreach($getTypes as $type){
			array_push($types, new TaskType($type->id, $type->type));
		}
		return $types;
	}
}