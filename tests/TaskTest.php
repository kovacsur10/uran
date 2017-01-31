<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Data\Task;
use App\Classes\Data\TaskStatus;
use App\Classes\Data\TaskPriority;
use App\Classes\Data\TaskType;
use App\Classes\Data\User;
use App\Classes\Data\StatusCode;

/** Class name: TaskTest
 *
 * This class is the PHPUnit test for the Data\Task data structer.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class TaskTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_task
	 *
	 * This function is testing the Task data structer.
	 *
	 * It is used for testing the functions of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_task(){
		$user = new User(1, '1', '1', '1', '1', '1', new StatusCode(1, 'stat'), null, 'hu_HU', true, true, '1994', 'alma', 'addr1', 'addr2', 'addr3', 'addr4', 'addr5', "reason", null, "0036123456");
		$task = new Task(1, 'almafa', 'alma', '1994-05-27 12:12:02', new TaskStatus(2, 'closed'), new TaskPriority(1, 'urgent'), new TaskType(5, 'task'), $user, $user, null, '1995', 120, false);
		$this->assertEquals(1, $task->id());
		$this->assertEquals("almafa", $task->caption());
		$this->assertEquals("alma", $task->text());
		$this->assertEquals("1994-05-27 12:12:02", $task->createdOn());
		$this->assertEquals($user, $task->creator());
		$this->assertEquals($user, $task->assignedTo());
		$this->assertEquals(new TaskPriority(1, 'urgent'), $task->priority());
		$this->assertEquals(new TaskStatus(2, 'closed'), $task->status());
		$this->assertEquals(new TaskType(5, 'task'), $task->type());
		$this->assertEquals("1995", $task->closedOn());
		$this->assertEquals(120, $task->workingHours());
		$this->assertFalse($task->deleted());
		$this->assertNull($task->deadline());
		
		$user = new User(1, '1', '1', '1', '1', '1', new StatusCode(1, 'stat'), null, 'hu_HU', true, true, '1994', 'alma', 'addr1', 'addr2', 'addr3', 'addr4', 'addr5', "reason", null, "0036123456");
		$task = new Task(1, 'almafa', 'alma', '1994', new TaskStatus(2, 'closed'), new TaskPriority(1, 'urgent'), new TaskType(5, 'task'), $user, $user, '1994-05-27 12:12:02', '1995', 120, false);
		$this->assertEquals(1, $task->id());
		$this->assertEquals("almafa", $task->caption());
		$this->assertEquals("alma", $task->text());
		$this->assertEquals("1994", $task->createdOn());
		$this->assertEquals($user, $task->creator());
		$this->assertEquals($user, $task->assignedTo());
		$this->assertEquals(new TaskPriority(1, 'urgent'), $task->priority());
		$this->assertEquals(new TaskStatus(2, 'closed'), $task->status());
		$this->assertEquals(new TaskType(5, 'task'), $task->type());
		$this->assertEquals("1995", $task->closedOn());
		$this->assertEquals(120, $task->workingHours());
		$this->assertFalse($task->deleted());
		$this->assertEquals('1994-05-27', $task->deadline());
		
		$task = new Task("1", 12, 42, 1994, new TaskStatus(2, 'closed'), new TaskPriority(1, 'urgent'), new TaskType(5, 'task'), $user);
		$this->assertEquals(1, $task->id());
		$this->assertEquals("12", $task->caption());
		$this->assertEquals("42", $task->text());
		$this->assertEquals("1994", $task->createdOn());
		$this->assertEquals($user, $task->creator());
		$this->assertNull($task->assignedTo());
		$this->assertEquals(new TaskPriority(1, 'urgent'), $task->priority());
		$this->assertEquals(new TaskStatus(2, 'closed'), $task->status());
		$this->assertEquals(new TaskType(5, 'task'), $task->type());
		$this->assertNull($task->closedOn());
		$this->assertEquals(0, $task->workingHours());
		$this->assertFalse($task->deleted());
		$this->assertNull($task->deadline());
	}

	/** Function name: test_task_attr
	 *
	 * This function is testing the Task data structer.
	 *
	 * It is used for testing the attributes of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_task_attr(){
		$this->assertClassHasAttribute('id', Task::class);
		$this->assertClassHasAttribute('caption', Task::class);
		$this->assertClassHasAttribute('text', Task::class);
		$this->assertClassHasAttribute('createdTime', Task::class);
		$this->assertClassHasAttribute('closedTime', Task::class);
		$this->assertClassHasAttribute('deadline', Task::class);
		$this->assertClassHasAttribute('creator', Task::class);
		$this->assertClassHasAttribute('assigned', Task::class);
		$this->assertClassHasAttribute('status', Task::class);
		$this->assertClassHasAttribute('priority', Task::class);
		$this->assertClassHasAttribute('type', Task::class);
		$this->assertClassHasAttribute('workingHours', Task::class);
		$this->assertClassHasAttribute('deleted', Task::class);
	}
}