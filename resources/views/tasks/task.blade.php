@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><a href="{{ url('tasks/list') }}">{{ $layout->language('task') }}</a></div>
                <div class="panel-body">
					<?php
						$task = $layout->tasks()->getTask();
						$comments = $layout->tasks()->getComments();
					?>
					<div class="col-md-8 col-md-offset-2">
						<div class="panel panel-{{ $task->priority === 'high' ? 'warning' : ($task->priority === 'highest' ? 'danger' : 'default') }}">
							<div class="panel-heading">
								<div class="row">
									<div class="col-md-8">{{ $task->caption }}</div>
									<div class="text-right col-md-4"><a href="{{ url('data/'.$task->username) }}">{{ $task->user }}</a></div>
								</div>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="well col-md-7"><?php echo nl2br($task->text); ?></div>
									<div class="well col-md-4 col-md-offset-1">
										<span>{{ $layout->language('date') }}: {{ $layout->formatDate($task->date) }}</span><br>
										<span>{{ $layout->language('deadline') }}: 
										@if($task->deadline !== null)
											{{ $layout->formatDate($task->deadline) }}
										@else
											{{ $layout->language('not_set') }}
										@endif
										</span><br>
										<span>{{ $layout->language('priority') }}: {{ $layout->language($task->priority) }}</span><br>
										<span>{{ $layout->language('status') }}: {{ $layout->language($task->status) }}</span><br>
										@if($task->status == "Closed")
											<span>{{ $task->closed }}</span><br>
										@endif
										<span>{{ $layout->language('type') }}: {{ $layout->language($task->type) }}</span><br>
										@if($task->assigned_name != null)
											<span>{{ $layout->language('assigned_to') }}: <a href="{{ url('data/'.$task->assigned_username) }}">{{ $task->assigned_name }}</a></span><br>
										@endif
										<span>{{ $layout->language('working_hour') }}: {{ $task->working_hours }}</span>
									</div>
								</div>
							</div>
						</div>
						<!-- new comment -->
						<form class="form-horizontal" role="form" method="POST" action="{{ url('/tasks/task/'.$task->id.'/addcomment') }}">
							<input type="hidden" name="_method" value="PUT">
							{!! csrf_field() !!}
							<div class="well">
								<div class="row">
									<div class="col-md-3">
										<span>{{ $layout->language('write_new_comment') }}:</span><br>
										<input type="submit" style="margin-top:10px;" class="form-control btn btn-primary" name="addCommentButton" value="{{ $layout->language('send') }}"></input>
									</div>
									<div class="col-md-9">
										<textarea class="form-control" name="commentText">{{ $layout->language('write_new_comment_description') }}</textarea>
									</div>
								</div>
							</div>
						</form>
						<!-- comments -->
						@foreach($comments as $comment)
							<div class="well">
								@if($layout->user()->permitted('tasks_admin') || $comment->poster_username === $layout->user()->user()->username)
								<a href="{{ url('/tasks/task/'.$task->id.'/removecomment/'.$comment->id) }}">
									<div style="width:20px;height:20px;overflow:hidden;float:right;">✖</div>
								</a>
								<div class="row" style="margin-right:20px;">
								@else
								<div class="row">
								@endif
									<div class="col-md-3">
										<span>{{ $layout->formatDate($comment->date) }}</span><br>
										<span><a href="{{ url('data/'.$comment->poster_username) }}">{{ $comment->poster }}</a></span>
									</div>
									<div class="col-md-9">
										<?php echo nl2br($comment->comment); ?>
									</div>
								</div>
							</div>
						@endforeach
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
