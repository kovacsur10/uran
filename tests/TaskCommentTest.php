<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Data\TaskComment;

/** Class name: TaskCommentTest
 *
 * This class is the PHPUnit test for the Data\TaskComment data structer.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class TaskCommentTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_task_comment
	 *
	 * This function is testing the TaskComment data structer.
	 *
	 * It is used for testing the functions of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_task_comment(){
		$task_comment = new TaskComment(1, "alma", "1994", true, 3, 2, "korte", "KorteName");
		$this->assertEquals(1, $task_comment->id());
		$this->assertEquals("alma", $task_comment->comment());
		$this->assertEquals("1994", $task_comment->creationDate());
		$this->assertTrue($task_comment->isDeleted());
		$this->assertEquals(3, $task_comment->taskId());
		$this->assertEquals(2, $task_comment->authorId());
		$this->assertEquals("korte", $task_comment->authorUsername());
		$this->assertEquals("KorteName", $task_comment->authorName());

		$task_comment = new TaskComment("1", 12, 1994, "true", "3", "2", 66, 42);
		$this->assertEquals(1, $task_comment->id());
		$this->assertEquals("alma", $task_comment->comment());
		$this->assertEquals("1994", $task_comment->creationDate());
		$this->assertTrue($task_comment->isDeleted());
		$this->assertEquals(3, $task_comment->taskId());
		$this->assertEquals(2, $task_comment->authorId());
		$this->assertEquals("korte", $task_comment->authorUsername());
		$this->assertEquals("KorteName", $task_comment->authorName());
	}

	/** Function name: test_task_comment_attr
	 *
	 * This function is testing the TaskComment data structer.
	 *
	 * It is used for testing the attributes of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_task_comment_attr(){
		$this->assertClassHasAttribute('id', TaskComment::class);
		$this->assertClassHasAttribute('task_id', TaskComment::class);
		$this->assertClassHasAttribute('user_id', TaskComment::class);
		$this->assertClassHasAttribute('user_name', TaskComment::class);
		$this->assertClassHasAttribute('user_username', TaskComment::class);
		$this->assertClassHasAttribute('comment', TaskComment::class);
		$this->assertClassHasAttribute('creation_date', TaskComment::class);
		$this->assertClassHasAttribute('deleted', TaskComment::class);
	}
}