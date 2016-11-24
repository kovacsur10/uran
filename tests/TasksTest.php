<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Layout\Tasks;
use App\Classes\Data\TaskStatus;
use App\Classes\Data\TaskType;
use App\Classes\Data\TaskPriority;
use App\Classes\Data\TaskComment;

/** Class name: RoomsTest
 *
 * This class is the PHPUnit test for the Layout\Rooms model.
 * This is a unit test.
 *
 * @author Máté Kovács <kovacsur10@gmail.com>
 */
class TasksTest extends TestCase
{
	use DatabaseTransactions;

	/** Function name: test_class
	 *
	 * This function is testing the class itself and the constructor of the Tasks model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_class(){
		$this->assertClassHasAttribute('tasks', Tasks::class);
		$this->assertClassHasAttribute('task', Tasks::class);
		$this->assertClassHasAttribute('types', Tasks::class);
		$this->assertClassHasAttribute('comments', Tasks::class);
		$this->assertClassHasAttribute('priorities', Tasks::class);
		$this->assertClassHasAttribute('statusTypes', Tasks::class);
		$this->assertClassHasAttribute('filters', Tasks::class);

		$rooms = new Tasks();
		$this->assertEquals('',$rooms->getFilter('priority'));
		$this->assertEquals('',$rooms->getFilter('status'));
		$this->assertEquals('',$rooms->getFilter('caption'));
		$this->assertFalse($rooms->getFilter('myTasks'));
		$this->assertTrue($rooms->getFilter('hideClosed'));
		$this->assertNull($rooms->getTask());
		$this->assertCount(0, $rooms->getComments());
		$this->assertCount(5, $rooms->statusTypes());
		foreach($rooms->statusTypes() as $status){
			$this->assertInstanceOf(TaskStatus::class, $status);
		}
		$this->assertCount(3, $rooms->taskTypes());
		foreach($rooms->taskTypes() as $type){
			$this->assertInstanceOf(TaskType::class, $type);
		}
		$this->assertCount(4, $rooms->priorities());
		foreach($rooms->priorities() as $priority){
			$this->assertInstanceOf(TaskPriority::class, $priority);
		}
	}

	/** Function name: test_getRoomId
	 *
	 * This function is testing the getRoomId function of the Rooms model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	
}