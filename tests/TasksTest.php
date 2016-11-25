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

		$tasks = new Tasks();
		$this->assertNull($tasks->getFilter('priority'));
		$this->assertNull($tasks->getFilter('status'));
		$this->assertEquals('',$tasks->getFilter('caption'));
		$this->assertFalse($tasks->getFilter('myTasks'));
		$this->assertTrue($tasks->getFilter('hideClosed'));
		$this->assertNull($tasks->getTask());
		$this->assertNull($tasks->get());
		$this->assertCount(0, $tasks->getComments());
		$this->assertCount(5, $tasks->statusTypes());
		foreach($tasks->statusTypes() as $status){
			$this->assertInstanceOf(TaskStatus::class, $status);
		}
		$this->assertCount(3, $tasks->taskTypes());
		foreach($tasks->taskTypes() as $type){
			$this->assertInstanceOf(TaskType::class, $type);
		}
		$this->assertCount(4, $tasks->priorities());
		foreach($tasks->priorities() as $priority){
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
	function test_getFilter(){
		$tasks = new Tasks();
		//positive
		$this->assertNull($tasks->getFilter('priority'));
		$this->assertNull($tasks->getFilter('status'));
		$this->assertEquals('',$tasks->getFilter('caption'));
		$this->assertFalse($tasks->getFilter('myTasks'));
		$this->assertTrue($tasks->getFilter('hideClosed'));
		
		//negative
		$this->assertNull($tasks->getFilter(null));
		$this->assertNull($tasks->getFilter(12));
		$this->assertNull($tasks->getFilter('no_such_filter'));
	}
	
	function test_filterTasks(){
		$tasks = new Tasks();
		
		$this->assertNull($tasks->getFilter('priority'));
		$this->assertNull($tasks->getFilter('status'));
		$this->assertEquals('',$tasks->getFilter('caption'));
		$this->assertFalse($tasks->getFilter('myTasks'));
		$this->assertTrue($tasks->getFilter('hideClosed'));
		$this->assertNull($tasks->get());
		
		$tasks->filterTasks(null, null, null, null, null);
		$this->assertNull($tasks->getFilter('priority'));
		$this->assertNull($tasks->getFilter('status'));
		$this->assertEquals('',$tasks->getFilter('caption'));
		$this->assertFalse($tasks->getFilter('myTasks'));
		$this->assertTrue($tasks->getFilter('hideClosed'));
		$this->assertNull($tasks->get());
		
		$tasks->filterTasks(null, '', 3, null, null);
		$this->assertEquals(3, $tasks->getFilter('priority'));
		$this->assertNull($tasks->getFilter('status'));
		$this->assertEquals('',$tasks->getFilter('caption'));
		$this->assertFalse($tasks->getFilter('myTasks'));
		$this->assertTrue($tasks->getFilter('hideClosed'));
		$this->assertCount(13, $tasks->get());
	}
	
}