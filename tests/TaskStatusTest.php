<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Data\TaskStatus;

/** Class name: TaskStatusTest
 *
 * This class is the PHPUnit test for the Data\TaskStatus data structer.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class TaskStatusTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_task_status
	 *
	 * This function is testing the TaskStatus data structer.
	 *
	 * It is used for testing the functions of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_task_status(){
		$task_status = new TaskStatus(1, "alma");
		$this->assertEquals(1, $task_status->id());
		$this->assertEquals("alma", $task_status->name());

		$task_status = new TaskStatus("1", 2);
		$this->assertEquals(1, $task_status->id());
		$this->assertEquals("2", $task_status->name());
	}

	/** Function name: test_task_status_attr
	 *
	 * This function is testing the TaskStatus data structer.
	 *
	 * It is used for testing the attributes of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_task_status_attr(){
		$this->assertClassHasAttribute('id', TaskStatus::class);
		$this->assertClassHasAttribute('status_name', TaskStatus::class);
	}
}