<?php

namespace App\Classes\Data;

use App\Classes\Data\User;
use App\Classes\Data\TaskStatus;
use App\Classes\Data\TaskPriority;
use App\Classes\Data\TaskType;

/** Class name: Task
 *
 * This class stores a task Task.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class Task{

	// PRIVATE DATA
	private $id;
	private $caption;
	private $text;
	private $createdTime;
	private $closedTime;
	private $deadline;
	private $creator;
	private $assigned;
	private $status;
	private $priority;
	private $type;
	private $workingHours;
	private $deleted;

	// PUBLIC FUNCTIONS

	/** Function name: __construct
	 *
	 * This is the constructor for the Task class.
	 *
	 * @param int $id
	 * @param string $caption
	 * @param string $text
	 * @param string $createdTime
	 * @param TaskStatus $status
	 * @param TaskPriority $priority
	 * @param TaskType $type
	 * @param User $creatorUser
	 * @param User|null $assignedUser
	 * @param string|null $deadline
	 * @param string|null $closedTime
	 * @param int $workingHours
	 * @param bool $deleted
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(int $id, string $caption, string $text, string $createdTime, TaskStatus $status, TaskPriority $priority, TaskType $type, User $creatorUser, User $assignedUser = null, string $deadline = null, string $closedTime = null, int $workingHours = 0, bool $deleted = false){
		$this->id = $id;
		$this->caption = $caption;
		$this->text = $text;
		$this->createdTime = $createdTime;
		$this->closedTime = $closedTime;
		if($deadline !== null){
			$this->deadline = substr($deadline, 0, 10);
		}else{
			$this->deadline = null;
		}
		$this->creator = $creatorUser;
		$this->assigned = $assignedUser;
		$this->status = $status;
		$this->priority = $priority;
		$this->type = $type;
		$this->workingHours = $workingHours;
		$this->deleted = $deleted;
	}

	/** Function name: id
	 *
	 * This is the getter for id.
	 *
	 * @return int - The identifier of the task.
	 */
	public function id() : int{
		return $this->id;
	}
	
	/** Function name: caption
	 *
	 * This is the getter for caption.
	 *
	 * @return string - The caption of the task.
	 */
	public function caption() : string{
		return $this->caption;
	}
	
	/** Function name: text
	 *
	 * This is the getter for text.
	 *
	 * @return string - The text of the task.
	 */
	public function text() : string{
		return $this->text;
	}
	
	/** Function name: createdOn
	 *
	 * This is the getter for createdTime.
	 *
	 * @return string - The creation time of the task.
	 */
	public function createdOn() : string{
		return $this->createdTime;
	}
	
	/** Function name: closedOn
	 *
	 * This is the getter for closedTime.
	 *
	 * @return string|null - The closing time of the task.
	 */
	public function closedOn(){
		return $this->closedTime;
	}
	
	/** Function name: deadline
	 *
	 * This is the getter for deadline.
	 *
	 * @return string|null - The deadline date of the task.
	 */
	public function deadline(){
		return $this->deadline;
	}
	
	/** Function name: creator
	 *
	 * This is the getter for creator.
	 *
	 * @return User - The creator user of the task.
	 */
	public function creator() : User{
		return $this->creator;
	}
	
	/** Function name: assignedTo
	 *
	 * This is the getter for assigned.
	 *
	 * @return User|null - The assigned user of the task.
	 */
	public function assignedTo(){
		return $this->assigned;
	}
	
	/** Function name: status
	 *
	 * This is the getter for status.
	 *
	 * @return TaskStatus - The status of the task.
	 */
	public function status() : TaskStatus{
		return $this->status;
	}
	
	/** Function name: priority
	 *
	 * This is the getter for priority.
	 *
	 * @return TaskPriority - The priority of the task.
	 */
	public function priority() : TaskPriority{
		return $this->priority;
	}
	
	/** Function name: type
	 *
	 * This is the getter for type.
	 *
	 * @return TaskType - The type of the task.
	 */
	public function type() : TaskType{
		return $this->type;
	}
	
	/** Function name: workingHours
	 *
	 * This is the getter for workingHours.
	 *
	 * @return int - The working hours of the task.
	 */
	public function workingHours() : int{
		return $this->workingHours;
	}
	
	/** Function name: deleted
	 *
	 * This is the getter for deleted.
	 *
	 * @return bool - The deletion status of the task.
	 */
	public function deleted() : bool{
		return $this->deleted;
	}

}