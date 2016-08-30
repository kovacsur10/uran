@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('task_manager') }}</div>
                <div class="panel-body">
					@if($layout->errors()->has('permission'))
						<div class="alert alert-danger">
							{{ $layout->errors()->get('permission') }}
						</div>
					@endif
					<div class="well well-sm">
						<div class="row">
							<div class="col-sm-6">{{ $layout->language('caption') }}</div>
							<div class="col-sm-2">{{ $layout->language('user') }}</div>
							<div class="col-sm-2">{{ $layout->language('status') }}</div>
							<div class="col-sm-2">{{ $layout->language('date') }}</div>
						</div>
					</div>
					@foreach($layout->tasks()->get() as $task)
					  @if($task->status === 'closed')
					  <div class="alert alert-info">
					  @elseif($task->priority === 'high' || $task->priority === 'highest')
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
								<div class="col-sm-6">{{ $task->caption }}</div>
								<div class="col-sm-2">{{ $task->user }}</div>
								<div class="col-sm-2">{{ $layout->language($task->status) }}</div>
								<div class="col-sm-2">{{ $layout->formatDate($task->date) }}</div>
							</div>
						</a>
					</div>
					@endforeach
					@if($layout->user()->permitted('tasks_add'))
					<div>
						<a href="{{ url('tasks/new') }}" class="btn btn-primary">{{ $layout->language('create_new_task') }}</a>
					</div>
					@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
