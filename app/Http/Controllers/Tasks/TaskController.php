<?php

namespace App\Http\Controllers\Tasks;

use App\Classes\LayoutData;
use App\Classes\Notify;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use DB;
use Mail;

class TaskController extends Controller{
	
    public function show(){
		$layout = new LayoutData();
		return view('tasks.tasks', ["layout" => $layout]);
	}
	
	public function showTask($id){
		$layout = new LayoutData();
		$layout->tasks()->setTask($id);
		return view('tasks.task', ["layout" => $layout]);
	}
	
	public function add(){
		$layout = new LayoutData();
		return view('tasks.add', ["layout" => $layout]);
	}
	
	public function addComment($taskId, Request $request){
		$layout = new LayoutData();
		if($layout->user()->permitted('tasks_add_comment')){
			if($layout->tasks()->exists($taskId)){
				//TODO: check if not empty
				$layout->tasks()->addComment($taskId, $layout->user()->user()->id, $request->commentText);
			}else{
				//TODO
			}
			$layout->tasks()->setTask($taskId);
			return view('tasks.task', ["layout" => $layout]);
		}else{
			//TODO
		}
	}
	
	public function removeComment($taskId, $commentId){
		$layout = new LayoutData();
		if($layout->tasks()->commentExists($commentId)){
			if($layout->tasks()->getComment($commentId)->poster_username === $layout->user()->user()->username){
				$layout->tasks()->removeComment($commentId);
				return $this->showTask($taskId);
			}else{
				//TODO
				return $this->showTask($taskId);
			}
		}else{
			//TODO
			return $this->showTask($taskId);
		}
	}
	
	public function addNew(Request $request){
		$layout = new LayoutData();
		
		if($layout->user()->permitted('tasks_add')){
			//TODO: checks
			if(trim($request->deadline) === ''){
				$layout->tasks()->addTask($request->type, $layout->user()->user()->id, $request->text, $request->caption, null, $request->priority);
			}else{
				$layout->tasks()->addTask($request->type, $layout->user()->user()->id, $request->text, $request->caption, $request->deadline, $request->priority);
			}
		}else{
			//TODO
		}
		return $this->show();
	}
	
	public function remove($taskId){
		$layout = new LayoutData();
		$layout->tasks()->setTask($taskId);
		
		if($layout->user()->permitted('tasks_admin') || $layout->tasks()->getTask()->username === $layout->user()->user()->username){
			$layout->tasks()->removeTask($taskId);
			//TODO
		}else{
			//TODO: errors.error
		}
	}
}
