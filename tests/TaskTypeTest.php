<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Data\TaskType;

/** Class name: TaskStatusTest
 *
 * This class is the PHPUnit test for the Data\TaskType data structer.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class TaskTypeTest extends BrowserKitTestCase
{
	use DatabaseTransactions;

	/** Function name: test_task_type
	 *
	 * This function is testing the TaskStatus data structer.
	 *
	 * It is used for testing the functions of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_task_type(){
		$task_type = new TaskType(1, "alma");
		$this->assertEquals(1, $task_type->id());
		$this->assertEquals("alma", $task_type->name());

		$task_type = new TaskType("1", 2);
		$this->assertEquals(1, $task_type->id());
		$this->assertEquals("2", $task_type->name());
	}

	/** Function name: test_task_type_attr
	 *
	 * This function is testing the TaskType data structer.
	 *
	 * It is used for testing the attributes of the class.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_task_type_attr(){
		$this->assertClassHasAttribute('id', TaskType::class);
		$this->assertClassHasAttribute('type_name', TaskType::class);
	}
}