<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Data\TaskPriority;

/** Class name: TaskStatusTest
 *
 * This class is the PHPUnit test for the Data\TaskPriority data structer.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class TaskPriorityTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_task_priority
	 *
	 * This function is testing the TaskPriority data structer.
	 *
	 * It is used for testing the functions of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_task_priority(){
		$task_priority = new TaskPriority(1, "alma");
		$this->assertEquals(1, $task_priority->id());
		$this->assertEquals("alma", $task_priority->name());

		$task_priority = new TaskPriority("1", 2);
		$this->assertEquals(1, $task_priority->id());
		$this->assertEquals("2", $task_priority->name());
	}

	/** Function name: test_task_priority_attr
	 *
	 * This function is testing the TaskPriority data structer.
	 *
	 * It is used for testing the attributes of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_task_priority_attr(){
		$this->assertClassHasAttribute('id', TaskPriority::class);
		$this->assertClassHasAttribute('priority_name', TaskPriority::class);
	}
}