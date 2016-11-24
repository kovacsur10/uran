<?php

namespace App\Classes\Data;

/** Class name: TaskComment
 *
 * This class stores a task TaskComment.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class TaskComment{

	// PRIVATE DATA
	private $id;
	private $task_id;
	private $user_id;
	private $user_name;
	private $user_username;
	private $comment;
	private $creation_date;
	private $deleted;

	// PUBLIC FUNCTIONS

	/** Function name: __construct
	 *
	 * This is the constructor for the TaskComment class.
	 *
	 * @param int $id - comment identifier
	 * @param string $comment - comment text
	 * @param string $creationDate - comment creation date
	 * @param bool $deleted - comment is deleted
	 * @param int $taskId - task of comment identifier
	 * @param int $writerId - writer user's identifier
	 * @param string $writerUsername - writer user's username
	 * @param string $writerName - writer user's name
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	public function __construct(int $id, string $comment, string $creationDate, bool $deleted, int $taskId, int $writerId, string $writerUsername, string $writerName){
		$this->id = $id;
		$this->task_id = $taskId;
		$this->user_id = $writerId;
		$this->user_name = $writerName;
		$this->user_username = $writerUsername;
		$this->comment = $comment;
		$this->creation_date = $creationDate;
		$this->deleted = $deleted;
	}

	/** Function name: id
	 *
	 * This is the getter for id.
	 *
	 * @return int - The identifier of the comment.
	 */
	public function id() : int{
		return $this->id;
	}
	
	/** Function name: taskId
	 *
	 * This is the getter for task_id.
	 *
	 * @return int - The identifier of the task.
	 */
	public function taskId() : int{
		return $this->task_id;
	}
	
	/** Function name: authorId
	 *
	 * This is the getter for user_id.
	 *
	 * @return int - The identifier of the author user.
	 */
	public function authorId() : int{
		return $this->user_id;
	}
	
	/** Function name: authorName
	 *
	 * This is the getter for user_name.
	 *
	 * @return string - The name of the author user.
	 */
	public function authorName() : string{
		return $this->user_name;
	}
	
	/** Function name: authorUsername
	 *
	 * This is the getter for user_username.
	 *
	 * @return string - The username of the author user.
	 */
	public function authorUsername() : string{
		return $this->user_username;
	}
	
	/** Function name: comment
	 *
	 * This is the getter for comment.
	 *
	 * @return string - The text of the comment.
	 */
	public function comment() : string{
		return $this->comment;
	}

	/** Function name: creationDate
	 *
	 * This is the getter for creation_date.
	 *
	 * @return string - The creation date of the comment.
	 */
	public function creationDate() : string{
		return $this->creation_date;
	}
	
	/** Function name: isDeleted
	 *
	 * This is the getter for deleted.
	 *
	 * @return string - The deletion state of the comment.
	 */
	public function isDeleted() : bool{
		return $this->deleted;
	}

}