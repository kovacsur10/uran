@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('task_manager') }}</div>
                <div class="panel-body">
					<div class="well well-sm">
						<div class="row">
							<div class="col-md-6">{{ $layout->language('caption') }}</div>
							<div class="col-md-2">{{ $layout->language('user') }}</div>
							<div class="col-md-2">{{ $layout->language('status') }}</div>
							<div class="col-md-2">{{ $layout->language('date') }}</div>
						</div>
					</div>
					@foreach($layout->tasks()->get() as $task)
					  @if($task->priority === 'high' || $task->priority === 'highest')
					  <div class="alert alert-{{ $task->priority === 'high' ? 'warning' : 'danger' }}">
					  @else
					  <div class="well well-sm">
					  @endif
						@if($layout->user()->permitted('tasks_admin') || $task->username === $layout->user()->user()->username)
						<a href="{{ url('/tasks/task/'.$task->id.'/remove') }}">
							<div style="width:20px;height:20px;overflow:hidden;float:right;">✖</div>
						</a>
						@endif
						<a href="{{ url('/tasks/task/'.$task->id) }}">
							<div class="row" style="margin-right:20px;">
								<div class="col-md-6">{{ $task->caption }}</div>
								<div class="col-md-2">{{ $task->user }}</div>
								<div class="col-md-2">{{ $task->status }}</div>
								<div class="col-md-2">{{ $task->date }}</div>
							</div>
						</a>
					</div>
					@endforeach
					<div><a href="{{ url('tasks/new') }}" class="btn btn-primary">{{ $layout->language('create_new_task') }}</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
