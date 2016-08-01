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
					<!-- ERRORS SECTION -->
					@if($layout->errors()->has('comment_not_exists'))
						<div class="alert alert-danger">
							{{ $layout->errors()->get('comment_not_exists') }}
						</div>
					@endif
					@if($layout->errors()->has('permission'))
						<div class="alert alert-danger">
							{{ $layout->errors()->get('permission') }}
						</div>
					@endif
					
					<!-- DATA SECTION -->
					<div class="col-md-8 col-md-offset-2">
						@if($layout->tasks()->canModify())
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/tasks/new') }}">
								{!! csrf_field() !!}
								
								<div class="panel panel-{{ $task->priority === 'high' ? 'warning' : ($task->priority === 'highest' ? 'danger' : 'default') }}">
									<div class="panel-heading">
										<div class="row">
											<div class="col-md-8"><input class="form-control" name="caption" type="text" value="{{ old('caption') === null ? $task->caption : old('caption') }}" /></div>
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
												<span>
													<label  class="control-label" for="priority">{{ $layout->language('priority') }}:</label>
													<select class="form-control"  name="priority"  id="priority" required="true" autocomplete="off">
														@foreach($layout->tasks()->priorities() as $priority)
															<option value="{{ $priority->id }}" {{ (old('priority') === $priority->id || (old('priority') === null && $task->priority === $priority->name)) ? 'selected' : '' }}>{{ $layout->language($priority->name) }}</option>
														@endforeach
													</select>
												</span><br>
												<span>
													<label  class="control-label" for="status">{{ $layout->language('status') }}:</label>
													<select class="form-control"  name="status"  id="status" required="true" autocomplete="off">
														@foreach($layout->tasks()->statusTypes() as $status)
															<option value="{{ $status->id }}" {{ (old('status') === $status->id || (old('status') === null && $task->status === $status->status)) ? 'selected' : '' }}>{{ $layout->language($status->status) }}</option>
														@endforeach
													</select>
												</span><br>
												@if($task->status == "Closed")
													<span>{{ $task->closed }}</span><br>
												@endif
												<span>
													<label  class="control-label" for="type">{{ $layout->language('type') }}:</label>
													<select class="form-control"  name="type"  id="type" required="true" autocomplete="off">
														@foreach($layout->tasks()->taskTypes() as $type)
															<option value="{{ $type->id }}" {{ (old('type') === $type->id || (old('type') === null && $task->type === $type->type)) ? 'selected' : '' }}>{{ $layout->language($type->type) }}</option>
														@endforeach
													</select>
												</span><br>
												@if($task->assigned_name != null)
													<span>{{ $layout->language('assigned_to') }}: <a href="{{ url('data/'.$task->assigned_username) }}">{{ $task->assigned_name }}</a></span><br>
												@endif
												<span>
													<label  class="control-label" for="working_hours">{{ $layout->language('working_hour') }}:</label>
													<input class="form-control" name="working_hours" type="text" value="{{ old('working_hours') === null ? $task->working_hours : old('working_hours') }}" />
												</span>
											</div>
										</div>
									</div>
								</div>
							</form>
						@else
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
						@endif
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
