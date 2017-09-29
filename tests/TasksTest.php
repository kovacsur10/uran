<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\Layout\Tasks;
use App\Classes\Data\TaskStatus;
use App\Classes\Data\TaskType;
use App\Classes\Data\TaskPriority;
use Illuminate\Contracts\Session\Session;
use App\Classes\Data\TaskComment;
use App\Exceptions\DatabaseException;
use App\Exceptions\ValueMismatchException;
use App\Classes\Data\Task;

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
		$this->assertFalse($tasks->getFilter('hideClosed'));
		$this->assertNull($tasks->getTask());
		$this->assertCount(37, $tasks->get());
		foreach($tasks->get() as $task){
			$this->assertInstanceOf(Task::class, $task);
		}
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

	/** Function name: test_getFilter
	 *
	 * This function is testing the getFilter function of the Tasks model.
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
		$this->assertFalse($tasks->getFilter('hideClosed'));
		
		//negative
		$this->assertNull($tasks->getFilter(null));
		$this->assertNull($tasks->getFilter(12));
		$this->assertNull($tasks->getFilter('no_such_filter'));
	}
	
	/** Function name: test_filterTasks
	 *
	 * This function is testing the filterTasks function of the Tasks model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_filterTasks(){
		session()->flush();
		$tasks = new Tasks();
		
		$this->assertNull($tasks->getFilter('priority'));
		$this->assertNull($tasks->getFilter('status'));
		$this->assertEquals('',$tasks->getFilter('caption'));
		$this->assertFalse($tasks->getFilter('myTasks'));
		$this->assertFalse($tasks->getFilter('hideClosed'));
		$this->assertCount(37, $tasks->get());
		
		session()->flush();
		session()->put('tasks_status_filter', null);
		session()->put('tasks_caption_filter', null);
		session()->put('tasks_priority_filter', null);
		session()->put('tasks_hide_closed_filter', null);
		session()->put('tasks_mytasks_filter', null);
		$tasks->filterTasks();
		$this->assertNull($tasks->getFilter('priority'));
		$this->assertNull($tasks->getFilter('status'));
		$this->assertEquals('',$tasks->getFilter('caption'));
		$this->assertFalse($tasks->getFilter('myTasks'));
		$this->assertFalse($tasks->getFilter('hideClosed'));
		$this->assertCount(37, $tasks->get());
		
		//priority
		session()->flush();
		session()->put('tasks_status_filter', null);
		session()->put('tasks_caption_filter', '');
		session()->put('tasks_priority_filter', 3);
		session()->put('tasks_hide_closed_filter', null);
		session()->put('tasks_mytasks_filter', null);
		$tasks->filterTasks();
		$this->assertEquals(3, $tasks->getFilter('priority'));
		$this->assertNull($tasks->getFilter('status'));
		$this->assertEquals('',$tasks->getFilter('caption'));
		$this->assertFalse($tasks->getFilter('myTasks'));
		$this->assertFalse($tasks->getFilter('hideClosed'));
		$this->assertCount(10, $tasks->get());
		
		//closed
		session()->flush();
		session()->put('tasks_status_filter', null);
		session()->put('tasks_caption_filter', '');
		session()->put('tasks_priority_filter', null);
		session()->put('tasks_hide_closed_filter', true);
		session()->put('tasks_mytasks_filter', null);
		$tasks->filterTasks();
		$this->assertNull($tasks->getFilter('priority'));
		$this->assertNull($tasks->getFilter('status'));
		$this->assertEquals('',$tasks->getFilter('caption'));
		$this->assertFalse($tasks->getFilter('myTasks'));
		$this->assertTrue($tasks->getFilter('hideClosed'));
		$this->assertCount(12, $tasks->get());
		
		//combined
		session()->flush();
		session()->put('tasks_status_filter', null);
		session()->put('tasks_caption_filter', '');
		session()->put('tasks_priority_filter', 3);
		session()->put('tasks_hide_closed_filter', true);
		session()->put('tasks_mytasks_filter', null);
		$tasks->filterTasks();
		$this->assertEquals(3, $tasks->getFilter('priority'));
		$this->assertNull($tasks->getFilter('status'));
		$this->assertEquals('',$tasks->getFilter('caption'));
		$this->assertFalse($tasks->getFilter('myTasks'));
		$this->assertTrue($tasks->getFilter('hideClosed'));
		$this->assertCount(4, $tasks->get());
		
		//status
		session()->flush();
		session()->put('tasks_status_filter', 1);
		session()->put('tasks_caption_filter', '');
		session()->put('tasks_priority_filter', null);
		session()->put('tasks_hide_closed_filter', null);
		session()->put('tasks_mytasks_filter', null);
		$tasks->filterTasks();
		$this->assertNull($tasks->getFilter('priority'));
		$this->assertEquals(1, $tasks->getFilter('status'));
		$this->assertEquals('',$tasks->getFilter('caption'));
		$this->assertFalse($tasks->getFilter('myTasks'));
		$this->assertFalse($tasks->getFilter('hideClosed'));
		$this->assertCount(3, $tasks->get());
		
		//caption
		session()->flush();
		session()->put('tasks_status_filter', null);
		session()->put('tasks_caption_filter', 'Task');
		session()->put('tasks_priority_filter', null);
		session()->put('tasks_hide_closed_filter', null);
		session()->put('tasks_mytasks_filter', null);
		$tasks->filterTasks();
		$this->assertNull($tasks->getFilter('priority'));
		$this->assertNull($tasks->getFilter('status'));
		$this->assertEquals('Task',$tasks->getFilter('caption'));
		$this->assertFalse($tasks->getFilter('myTasks'));
		$this->assertFalse($tasks->getFilter('hideClosed'));
		$this->assertCount(3, $tasks->get());
		
		//my task
		session()->flush();
		session()->put('user', \App\Classes\Layout\User::getUserData(1));
		session()->put('tasks_status_filter', null);
		session()->put('tasks_caption_filter', '');
		session()->put('tasks_priority_filter', null);
		session()->put('tasks_hide_closed_filter', null);
		session()->put('tasks_mytasks_filter', true);
		$tasks->filterTasks();
		$this->assertNull($tasks->getFilter('priority'));
		$this->assertNull($tasks->getFilter('status'));
		$this->assertEquals('',$tasks->getFilter('caption'));
		$this->assertTrue($tasks->getFilter('myTasks'));
		$this->assertFalse($tasks->getFilter('hideClosed'));
		$this->assertCount(27, $tasks->get());
		session()->flush();
	}
	
	/** Function name: test_setFilterTasks
	 *
	 * This function is testing the setFilterTasks function of the Tasks model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_setFilterTasks(){
		session()->flush();
		$this->assertFalse(session()->has('tasks_caption_filter'));
		$this->assertFalse(session()->has('tasks_status_filter'));
		$this->assertFalse(session()->has('tasks_priority_filter'));
		$this->assertFalse(session()->has('tasks_mytasks_filter'));
		$this->assertFalse(session()->has('tasks_hide_closed_filter'));
		Tasks::setFilterTasks(null, null, null, null, null);
		$this->assertFalse(session()->has('tasks_caption_filter'));
		$this->assertFalse(session()->has('tasks_status_filter'));
		$this->assertFalse(session()->has('tasks_priority_filter'));
		$this->assertTrue(session()->has('tasks_mytasks_filter'));
		$this->assertTrue(session()->has('tasks_hide_closed_filter'));
		$this->assertFalse(session()->get('tasks_mytasks_filter'));
		$this->assertFalse(session()->get('tasks_hide_closed_filter'));
		
		session()->flush();
		
		$this->assertFalse(session()->has('tasks_caption_filter'));
		$this->assertFalse(session()->has('tasks_status_filter'));
		$this->assertFalse(session()->has('tasks_priority_filter'));
		$this->assertFalse(session()->has('tasks_mytasks_filter'));
		$this->assertFalse(session()->has('tasks_hide_closed_filter'));
		Tasks::setFilterTasks("", "", "", "", "");
		$this->assertTrue(session()->has('tasks_caption_filter'));
		$this->assertFalse(session()->has('tasks_status_filter'));
		$this->assertFalse(session()->has('tasks_priority_filter'));
		$this->assertTrue(session()->has('tasks_mytasks_filter'));
		$this->assertTrue(session()->has('tasks_hide_closed_filter'));
		$this->assertEquals("", session()->get('tasks_caption_filter'));
		$this->assertFalse(session()->get('tasks_mytasks_filter'));
		$this->assertFalse(session()->get('tasks_hide_closed_filter'));
		
		session()->flush();
		
		$this->assertFalse(session()->has('tasks_caption_filter'));
		$this->assertFalse(session()->has('tasks_status_filter'));
		$this->assertFalse(session()->has('tasks_priority_filter'));
		$this->assertFalse(session()->has('tasks_mytasks_filter'));
		$this->assertFalse(session()->has('tasks_hide_closed_filter'));
		Tasks::setFilterTasks("1", "alma", "4", false, true);
		$this->assertTrue(session()->has('tasks_caption_filter'));
		$this->assertTrue(session()->has('tasks_status_filter'));
		$this->assertTrue(session()->has('tasks_priority_filter'));
		$this->assertTrue(session()->has('tasks_mytasks_filter'));
		$this->assertTrue(session()->has('tasks_hide_closed_filter'));
		$this->assertEquals("1", session()->get('tasks_status_filter'));
		$this->assertEquals("alma", session()->get('tasks_caption_filter'));
		$this->assertEquals("4", session()->get('tasks_priority_filter'));
		$this->assertFalse(session()->get('tasks_mytasks_filter'));
		$this->assertTrue(session()->get('tasks_hide_closed_filter'));
		
		session()->flush();		
	}
	
	/** Function name: test_resetFilterTasks
	 *
	 * This function is testing the resetFilterTasks function of the Tasks model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_resetFilterTasks(){
		session()->flush();
		$task = new Tasks();
		$this->assertCount(37, $task->tasksToPages());
		
		$this->assertFalse(session()->has('tasks_status_filter'));
		$this->assertFalse(session()->has('tasks_caption_filter'));
		$this->assertFalse(session()->has('tasks_priority_filter'));
		$this->assertFalse(session()->has('tasks_mytasks_filter'));
		$this->assertFalse(session()->has('tasks_hide_closed_filter'));
		$this->assertNull($task->getFilter('priority'));
		$this->assertNull($task->getFilter('status'));
		$this->assertEquals('', $task->getFilter('caption'));
		$this->assertFalse($task->getFilter('myTasks'));
		$this->assertFalse($task->getFilter('hideClosed'));
		$task->resetFilterTasks(false);
		$this->assertFalse(session()->has('tasks_status_filter'));
		$this->assertFalse(session()->has('tasks_caption_filter'));
		$this->assertFalse(session()->has('tasks_priority_filter'));
		$this->assertFalse(session()->has('tasks_mytasks_filter'));
		$this->assertFalse(session()->has('tasks_hide_closed_filter'));
		$this->assertNull($task->getFilter('priority'));
		$this->assertNull($task->getFilter('status'));
		$this->assertEquals('', $task->getFilter('caption'));
		$this->assertFalse($task->getFilter('myTasks'));
		$this->assertFalse($task->getFilter('hideClosed'));
		$this->assertCount(37, $task->tasksToPages());
		
		session()->flush();
		$task = new Tasks();
		$this->assertCount(37, $task->tasksToPages());
		$this->assertFalse(session()->has('tasks_status_filter'));
		$this->assertFalse(session()->has('tasks_caption_filter'));
		$this->assertFalse(session()->has('tasks_priority_filter'));
		$this->assertFalse(session()->has('tasks_mytasks_filter'));
		$this->assertFalse(session()->has('tasks_hide_closed_filter'));
		$this->assertNull($task->getFilter('priority'));
		$this->assertNull($task->getFilter('status'));
		$this->assertEquals('', $task->getFilter('caption'));
		$this->assertFalse($task->getFilter('myTasks'));
		$this->assertFalse($task->getFilter('hideClosed'));
		$task->resetFilterTasks(true);
		$this->assertFalse(session()->has('tasks_status_filter'));
		$this->assertFalse(session()->has('tasks_caption_filter'));
		$this->assertFalse(session()->has('tasks_priority_filter'));
		$this->assertFalse(session()->has('tasks_mytasks_filter'));
		$this->assertFalse(session()->has('tasks_hide_closed_filter'));
		$this->assertNull($task->getFilter('priority'));
		$this->assertNull($task->getFilter('status'));
		$this->assertEquals('', $task->getFilter('caption'));
		$this->assertFalse($task->getFilter('myTasks'));
		$this->assertFalse($task->getFilter('hideClosed'));
		$this->assertCount(37, $task->tasksToPages());
		
		session()->flush();
		$task = new Tasks();
		$this->assertCount(37, $task->tasksToPages());
		Tasks::setFilterTasks("1", "alma", "4", false, true);
		$task->filterTasks();
		session()->flush();
		session()->put('tasks_status_filter', 'alma');
		session()->put('tasks_hide_closed_filter', true);
		$this->assertTrue(session()->has('tasks_status_filter'));
		$this->assertFalse(session()->has('tasks_caption_filter'));
		$this->assertFalse(session()->has('tasks_priority_filter'));
		$this->assertFalse(session()->has('tasks_mytasks_filter'));
		$this->assertTrue(session()->has('tasks_hide_closed_filter'));
		$this->assertEquals("4", $task->getFilter('priority'));
		$this->assertEquals("1", $task->getFilter('status'));
		$this->assertEquals('alma', $task->getFilter('caption'));
		$this->assertFalse($task->getFilter('myTasks'));
		$this->assertTrue($task->getFilter('hideClosed'));
		$task->resetFilterTasks(false);
		$this->assertTrue(session()->has('tasks_status_filter'));
		$this->assertFalse(session()->has('tasks_caption_filter'));
		$this->assertFalse(session()->has('tasks_priority_filter'));
		$this->assertFalse(session()->has('tasks_mytasks_filter'));
		$this->assertTrue(session()->has('tasks_hide_closed_filter'));
		$this->assertNull($task->getFilter('priority'));
		$this->assertNull($task->getFilter('status'));
		$this->assertEquals('', $task->getFilter('caption'));
		$this->assertFalse($task->getFilter('myTasks'));
		$this->assertFalse($task->getFilter('hideClosed'));
		$this->assertCount(37, $task->tasksToPages());
		
		session()->flush();
		$task = new Tasks();
		$this->assertCount(37, $task->tasksToPages());
		Tasks::setFilterTasks("1", "alma", "4", false, true);
		$task->filterTasks();
		session()->flush();
		session()->put('tasks_status_filter', 'alma');
		session()->put('tasks_mytasks_filter', true);
		$this->assertTrue(session()->has('tasks_status_filter'));
		$this->assertFalse(session()->has('tasks_caption_filter'));
		$this->assertFalse(session()->has('tasks_priority_filter'));
		$this->assertTrue(session()->has('tasks_mytasks_filter'));
		$this->assertFalse(session()->has('tasks_hide_closed_filter'));
		$this->assertEquals("4", $task->getFilter('priority'));
		$this->assertEquals("1", $task->getFilter('status'));
		$this->assertEquals('alma', $task->getFilter('caption'));
		$this->assertFalse($task->getFilter('myTasks'));
		$this->assertTrue($task->getFilter('hideClosed'));
		$task->resetFilterTasks(true);
		$this->assertFalse(session()->has('tasks_status_filter'));
		$this->assertFalse(session()->has('tasks_caption_filter'));
		$this->assertFalse(session()->has('tasks_priority_filter'));
		$this->assertFalse(session()->has('tasks_mytasks_filter'));
		$this->assertFalse(session()->has('tasks_hide_closed_filter'));
		$this->assertNull($task->getFilter('priority'));
		$this->assertNull($task->getFilter('status'));
		$this->assertEquals('', $task->getFilter('caption'));
		$this->assertFalse($task->getFilter('myTasks'));
		$this->assertFalse($task->getFilter('hideClosed'));
		$this->assertCount(37, $task->tasksToPages());
		session()->flush();
	}
	
	/** Function name: test_tasksToPages
	 *
	 * This function is testing the tasksToPages function of the Tasks model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_tasksToPages(){
		$tasks = new Tasks();
		
		$this->assertCount(37, $tasks->tasksToPages());
		$this->assertCount(0, $tasks->tasksToPages(-10, 5));
		$this->assertCount(0, $tasks->tasksToPages(-1, 1));
		$this->assertCount(1, $tasks->tasksToPages(0, 1));
		$this->assertCount(10, $tasks->tasksToPages(0, 10));
		$this->assertCount(10, $tasks->tasksToPages(3, 10));
		$this->assertCount(7, $tasks->tasksToPages(30, 10));
		$this->assertCount(0, $tasks->tasksToPages(37, 10));
		$this->assertCount(0, $tasks->tasksToPages(100, 10));
		
		$this->assertCount(0, $tasks->tasksToPages(20, -5));
		$this->assertCount(0, $tasks->tasksToPages(20, 0));
		
		$this->assertCount(0, $tasks->tasksToPages(null, 10));
		$this->assertCount(0, $tasks->tasksToPages(1, null));
		$this->assertCount(0, $tasks->tasksToPages(null, null));
	}
	
	/** Function name: test_getStatusById
	 *
	 * This function is testing the getStatusById function of the Tasks model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getStatusById(){
		$tasks = new Tasks();
		
		$this->assertNull($tasks->getStatusById(null));
		$this->assertNull($tasks->getStatusById(0));
		$status = $tasks->getStatusById(3);
		$this->assertInstanceOf(TaskStatus::class, $status);
		$this->assertEquals(new TaskStatus(3, 'closed'), $status);
	}
	
	/** Function name: test_getStatusByName
	 *
	 * This function is testing the getStatusByName function of the Tasks model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getStatusByName(){
		$tasks = new Tasks();
	
		$this->assertNull($tasks->getStatusByName(null));
		$this->assertNull($tasks->getStatusByName('alma'));
		$status = $tasks->getStatusByName('closed');
		$this->assertInstanceOf(TaskStatus::class, $status);
		$this->assertEquals(new TaskStatus(3, 'closed'), $status);
	}
	
	/** Function name: test_getComment
	 *
	 * This function is testing the getComment function of the Tasks model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getComment(){
		$tasks = new Tasks();
		
		$this->assertNull($tasks->getComment(null));
		$this->assertNull($tasks->getComment(0));
		$comment = $tasks->getComment(1);
		$this->assertInstanceOf(TaskComment::class, $comment);
		$this->assertEquals(new TaskComment(1, 'First comment.', '2016-07-23 15:01:00', false, 1, 1, 'kovacsur10', 'Kovács Máté'), $comment);
	}
	
	/** Function name: test_exists
	 *
	 * This function is testing the exists function of the Tasks model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_exists(){
		$tasks = new Tasks();
		
		$this->assertFalse($tasks->exists(null));
		$this->assertFalse($tasks->exists(0));
		$this->assertTrue($tasks->exists(10));
	}
	
	/** Function name: test_commentExists
	 *
	 * This function is testing the commentExists function of the Tasks model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_commentExists(){
		$tasks = new Tasks();
		
		$this->assertFalse($tasks->commentExists(null));
		$this->assertFalse($tasks->commentExists(0));
		$this->assertTrue($tasks->commentExists(1));
	}
	
	/** Function name: test_setTask
	 *
	 * This function is testing the setTask function of the Tasks model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_setTask(){
		$tasks = new Tasks();
		
		//null case
		try{
			$tasks->setTask(null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		//fail case
		$this->assertNull($tasks->getTask());
		$this->assertCount(0, $tasks->getComments());
		try{
			$tasks->setTask(0);
		}catch(\Exception $ex){
			$this->fail("Not expected exception: ".$ex->getMessage());
		}
		$this->assertNull($tasks->getTask());
		$this->assertCount(0, $tasks->getComments());
		
		//ok case
		$tasks = new Tasks();
		$this->assertNull($tasks->getTask());
		$this->assertCount(0, $tasks->getComments());
		try{
			$tasks->setTask(10);
		}catch(\Exception $ex){
			$this->fail("Not expected exception: ".$ex->getMessage());
		}
		$this->assertNotNull($tasks->getTask());
		$this->assertInstanceOf(Task::class, $tasks->getTask());
		$this->assertCount(2, $tasks->getComments());
	}
	
	/** Function name: test_update
	 *
	 * This function is testing the update function of the Tasks model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_update(){
		$tasks = new Tasks();
		
		//null cases
		try{
			$tasks->update(null, 3, '...', 'Registration accept review', null, 4, 3, 1, 1, false);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$tasks->update(16, null, '...', 'Registration accept review', null, 4, 3, 1, 1, false);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$tasks->update(16, 3, null, 'Registration accept review', null, 4, 3, 1, 1, false);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$tasks->update(16, 3, '...', null, null, 4, 3, 1, 1, false);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$tasks->update(16, 3, '...', 'Registration accept review', null, null, 3, 1, 1, false);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$tasks->update(16, 3, '...', 'Registration accept review', null, 4, null, 1, 1, false);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$tasks->update(16, 3, '...', 'Registration accept review', null, 4, 3, null, 1, false);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$tasks->update(16, 3, '...', 'Registration accept review', null, 4, 3, 1, 1, null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		//fail case
		try{
			$tasks->update(16, 3, '...', 'Registration accept review', 'wrong date format', 4, 3, 1, 1, false);
			$this->fail("An exception was expected!");
		}catch(DatabaseException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		//success case
		$tasks->setTask(16);
		$this->assertEquals(3, $tasks->getTask()->type()->id());
		$this->assertEquals(3, $tasks->getTask()->status()->id());
		$this->assertEquals('2016-08-10', $tasks->getTask()->deadline());
		try{
			$tasks->update(16, 2, '...', 'Registration accept review', '2016-10-10 00:00:00', 4, 3, 1, 1, false);
		}catch(\Exception $ex){
			$this->fail("Not expected exception: ".$ex->getMessage());
		}
		$tasks->setTask(16);
		$this->assertEquals(2, $tasks->getTask()->type()->id());
		$this->assertEquals(3, $tasks->getTask()->status()->id());
		$this->assertEquals('2016-10-10', $tasks->getTask()->deadline());
	}
	
	/** Function name: test_canModify
	 *
	 * This function is testing the canModify function of the Tasks model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_canModify(){
		$tasks = new Tasks();
		$this->assertFalse($tasks->canModify());
		$tasks->setTask(10);
		$this->assertFalse($tasks->canModify());
		
		session()->put('user', \App\Classes\Layout\User::getUserData(1));
		$tasks = new Tasks();
		$this->assertFalse($tasks->canModify());
		$tasks->setTask(10);
		$this->assertTrue($tasks->canModify());
		session()->forget('user');
		
		session()->put('user', \App\Classes\Layout\User::getUserData(1));
		$tasks = new Tasks();
		$this->assertFalse($tasks->canModify());
		$tasks->setTask(10);
		$this->assertTrue($tasks->canModify());
		session()->forget('user');
		
		session()->put('user', \App\Classes\Layout\User::getUserData(25));
		$tasks = new Tasks();
		$this->assertFalse($tasks->canModify());
		$tasks->setTask(10);
		$this->assertTrue($tasks->canModify());
		session()->forget('user');
	}
	
	/** Function name: test_addTask
	 *
	 * This function is testing the addTask function of the Tasks model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_addTask(){
		$tasks = new Tasks();
	
		//null cases
		try{
			$tasks->addTask(null, 1, 'text', 'caption', null, 1);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$tasks->addTask(1, null, 'text', 'caption', null, 1);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$tasks->addTask(1, 1, null, 'caption', null, 1);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$tasks->addTask(1, 1, 'text', null, null, 1);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		try{
			$tasks->addTask(1, 1, 'text', 'caption', null, null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		//failed case
		$tasks = new Tasks();
		$this->assertCount(37, $tasks->get());
		try{
			$tasks->addTask(3, 1, 'text', 'caption', 'wrong date format', 1);
			$this->fail("An exception was expected!");
		}catch(DatabaseException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		$this->assertCount(37, $tasks->get());
	
		//success cases
		$tasks = new Tasks();
		$this->assertCount(37, $tasks->get());
		try{
			$tasks->addTask(3, 1, 'text', 'caption', null, 1);
		}catch(\Exception $ex){
			$this->fail("Not expected exception: ".$ex->getMessage());
		}
		$this->assertCount(38, $tasks->get());
		try{
			$tasks->addTask('3', '1', 'text', 'caption', '1994-05-27 12:26:22', 1);
		}catch(\Exception $ex){
			$this->fail("Not expected exception: ".$ex->getMessage());
		}
		$this->assertCount(39, $tasks->get());
	}
	
	/** Function name: test_removeTask
	 *
	 * This function is testing the removeTask function of the Tasks model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_removeTask(){
		$tasks = new Tasks();
		
		//null case
		try{
			$tasks->removeTask(null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		
		//fail case
		$tasks = new Tasks();
		$this->assertCount(37, $tasks->get());
		try{
			$tasks->removeTask(0);
		}catch(\Exception $ex){
			$this->fail("Not expected exception: ".$ex->getMessage());
		}
		$this->assertCount(37, $tasks->get());
		
		//ok case
		$tasks = new Tasks();
		$this->assertCount(37, $tasks->get());
		try{
			$tasks->removeTask(16);
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		$this->assertCount(36, $tasks->get());
	}
	
	/** Function name: test_addComment
	 *
	 * This function is testing the addComment function of the Tasks model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_addComment(){
		$tasks = new Tasks();
	
		//null cases
		try{
			$tasks->addComment(null, 1, 1, 1);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	
		try{
			$tasks->addComment(1, null, 1);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	
		try{
			$tasks->addComment(1, 1, null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	
		try{
			$tasks->addComment(1, 1, null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	
		//success case
		$tasks = new Tasks();
		$tasks->setTask(10);
		$this->assertCount(2, $tasks->getComments());
		try{
			$tasks->addComment(10, 1, 'almafa');
		}catch(\Exception $ex){
			$this->fail("Not expected exception: ".$ex->getMessage());
		}
		$tasks->setTask(10);
		$this->assertCount(3, $tasks->getComments());
	}
	
	/** Function name: test_removeComment
	 *
	 * This function is testing the removeComment function of the Tasks model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_removeComment(){
		$tasks = new Tasks();
	
		//null case
		try{
			$tasks->removeComment(null);
			$this->fail("An exception was expected!");
		}catch(ValueMismatchException $ex){
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
	
		//fail case
		try{
			$tasks->removeComment(0);
		}catch(\Exception $ex){
			$this->fail("Not expected exception: ".$ex->getMessage());
		}
	
		//ok case
		$tasks = new Tasks();
		$tasks->setTask(10);
		$this->assertCount(2, $tasks->getComments());
		try{
			$tasks->removeComment(7);
		}catch(\Exception $ex){
			$this->fail("Not the expected exception: ".$ex->getMessage());
		}
		$tasks->setTask(10);
		$this->assertCount(1, $tasks->getComments());
	}
	
	/** Function name: test_getSessionData
	 *
	 * This function is testing the getSessionData function of the Tasks model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_getSessionData(){
		session()->flush();
		$this->assertCount(0, Tasks::getSessionData());
		
		session()->flush();
		session()->put('tasks_status_filter', 20);
		session()->put('tasks_paging', 15);
		session()->put('no_key_like_this', 40);
		$this->assertEquals(['tasks_status_filter' => 20, 'tasks_paging' => 15], Tasks::getSessionData());
	}
	
	/** Function name: test_checkTaskCount
	 *
	 * This function is testing the checkTaskCount function of the Tasks model.
	 *
	 * @return void
	 *
	 * @author Máté Kovács <kovacsur10@gmail.com>
	 */
	function test_checkTaskCount(){
		session()->flush();
		$this->assertFalse(session()->has('tasks_paging'));
		$this->assertEquals(10, Tasks::checkTaskCount(null));
		
		session()->flush();
		session()->put('tasks_paging', 20);
		$this->assertTrue(session()->has('tasks_paging'));
		$this->assertEquals(20, Tasks::checkTaskCount(null));
		
		session()->flush();
		$this->assertFalse(session()->has('tasks_paging'));
		$this->assertEquals(10, Tasks::checkTaskCount(0));
		
		session()->flush();
		$this->assertFalse(session()->has('tasks_paging'));
		$this->assertEquals(10, Tasks::checkTaskCount(101));
		
		session()->flush();
		session()->put('tasks_paging', 20);
		$this->assertTrue(session()->has('tasks_paging'));
		$this->assertEquals(10, Tasks::checkTaskCount(0));
		
		session()->flush();
		$this->assertFalse(session()->has('tasks_paging'));
		$this->assertEquals(40, Tasks::checkTaskCount(40));
		
		session()->flush();
		session()->put('tasks_paging', 20);
		$this->assertTrue(session()->has('tasks_paging'));
		$this->assertEquals(60, Tasks::checkTaskCount(60));
	}
}