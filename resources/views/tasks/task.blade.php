@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><a href="{{ url('tasks/list') }}">@lang('tasks.task')</a></div>
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
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/tasks/task/'.$task->id().'/modify') }}">
								{!! csrf_field() !!}
								
								<div class="panel panel-{{ $task->priority()->name() === 'high' ? 'warning' : ($task->priority()->name() === 'highest' ? 'danger' : 'default') }}">
									<div class="panel-heading">
										<div class="row">
											<div class="col-md-8">
												<input class="form-control" name="caption" type="text" value="{{ $layout->errors()->getOld('caption') === null ? $task->caption() : $layout->errors()->getOld('caption') }}" />
												@if ($layout->errors()->has('caption'))
													<span class="text-danger">
														<strong>{{ $layout->errors()->get('caption') }}</strong>
													</span>
												@endif
											</div>
											<div class="text-right col-md-4"><a href="{{ url('data/'.$task->creator()->username()) }}">{{ $task->creator()->name() }}</a></div>
										</div>
									</div>
									<div class="panel-body">
										<div class="row">
											<div class="well col-md-7">
												<textarea class="form-control" rows="6" name="text" cols="40" rows="5">{{ $layout->errors()->getOld('text') === null ? $task->text() : $layout->errors()->getOld('text') }}</textarea>
												@if ($layout->errors()->has('text'))
													<span class="text-danger">
														<strong>{{ $layout->errors()->get('text') }}</strong>
													</span>
												@endif
											</div>
											<div class="well col-md-4 col-md-offset-1">
												<span>@lang('tasks.date'): {{ $layout->formatDate($task->createdOn()) }}</span><br>
												<span>
													<label  class="control-label" for="deadline">@lang('tasks.deadline'):</label>
													<div class='input-group date' data-date-format="yyyy. mm. dd" id='datepicker_add_new_task'>
														<input type='text' readonly class="form-control" name="deadline" value="{{ $layout->errors()->getOld('deadline') === null ? ($task->deadline() === null ? '' : $layout->formatDate($task->deadline())) : $layout->errors()->getOld('deadline') }}" />
														<span class="input-group-addon">
															<span class="glyphicon glyphicon-calendar"></span>
														</span>
														@if ($layout->errors()->has('deadline'))
															<span class="text-danger">
																<strong>{{ $layout->errors()->get('deadline') }}</strong>
															</span>
														@endif
													</div>
												</span><br>
												<span>
													<label  class="control-label" for="priority">@lang('tasks.priority'):</label>
													<select class="form-control"  name="priority"  id="priority" required="true" autocomplete="off">
														@foreach($layout->tasks()->priorities() as $priority)
															<option value="{{ $priority->id() }}" {{ ($layout->errors()->getOld('priority') === $priority->id() || ($layout->errors()->getOld('priority') === null && $task->priority()->name() === $priority->name())) ? 'selected' : '' }}>@lang('tasks.'.$priority->name())</option>
														@endforeach
													</select>
													@if ($layout->errors()->has('priority'))
														<span class="text-danger">
															<strong>{{ $layout->errors()->get('priority') }}</strong>
														</span>
													@endif
												</span><br>
												<span>
													<label  class="control-label" for="status">@lang('tasks.status'):</label>
													<select class="form-control"  name="status"  id="status" required="true" autocomplete="off">
														@foreach($layout->tasks()->statusTypes() as $status)
															<option value="{{ $status->id() }}" {{ ($layout->errors()->getOld('status') === $status->id() || ($layout->errors()->getOld('status') === null && $task->status()->name() === $status->name())) ? 'selected' : '' }}>@lang('tasks.'.$status->name())</option>
														@endforeach
													</select>
													@if ($layout->errors()->has('status'))
														<span class="text-danger">
															<strong>{{ $layout->errors()->get('status') }}</strong>
														</span>
													@endif
												</span><br>
												@if($task->status()->name() === "closed")
													<span>@lang('tasks.closed_on_that_date'): {{ $layout->formatDate($task->closedOn()) }}</span><br>
												@endif
												<span>
													<label  class="control-label" for="type">@lang('tasks.type'):</label>
													<select class="form-control"  name="type"  id="type" required="true" autocomplete="off">
														@foreach($layout->tasks()->taskTypes() as $type)
															<option value="{{ $type->id() }}" {{ ($layout->errors()->getOld('type') === $type->id() || ($layout->errors()->getOld('type') === null && $task->type()->name() === $type->name())) ? 'selected' : '' }}>@lang('tasks.'.$type->name())</option>
														@endforeach
													</select>
													@if ($layout->errors()->has('type'))
														<span class="text-danger">
															<strong>{{ $layout->errors()->get('type') }}</strong>
														</span>
													@endif
												</span><br>
												<span>
													<label  class="control-label" for="assigned_username">@lang('tasks.assigned_to'):</label>
													<select class="form-control"  name="assigned_username"  id="type" required="true" autocomplete="off">
														<option value="admin" {{ ($layout->errors()->getOld('assigned_username') === null && $task->assignedTo() === null) ? 'selected' : '' }}>@lang('tasks.no_one_is_assigned')</option>
														@foreach($layout->user()->users(0, -1) as $user)
															<option value="{{ $user->username() }}" {{ ($layout->errors()->getOld('assigned_username') === $user->username() || ($layout->errors()->getOld('assigned_username') === null && ($task->assignedTo() !== null && $task->assignedTo()->username() === $user->username()))) ? 'selected' : '' }}>{{ $user->name() }} ({{ $user->username() }})</option>
														@endforeach
													</select>
													@if ($layout->errors()->has('assigned_username'))
														<span class="text-danger">
															<strong>{{ $layout->errors()->get('assigned_username') }}</strong>
														</span>
													@endif
												</span>
												<span>
													<label  class="control-label" for="working_hours">@lang('tasks.working_hour'):</label>
													<input class="form-control" name="working_hours" type="text" value="{{ $layout->errors()->getOld('working_hours') === null ? $task->workingHours() : $layout->errors()->getOld('working_hours') }}" />
													@if ($layout->errors()->has('working_hours'))
														<span class="text-danger">
															<strong>{{ $layout->errors()->get('working_hours') }}</strong>
														</span>
													@endif
												</span><br>
												<span>
													<input type="submit" class="form-control btn btn-primary" name="updateTask" value="@lang('tasks.modify')"></input>
												</span>
											</div>
										</div>
									</div>
								</div>
							</form>
						@else
							<div class="panel panel-{{ $task->priority()->name() === 'high' ? 'warning' : ($task->priority()->name() === 'highest' ? 'danger' : 'default') }}">
								<div class="panel-heading">
									<div class="row">
										<div class="col-md-8">{{ $task->caption() }}</div>
										<div class="text-right col-md-4"><a href="{{ url('data/'.$task->creator()->username()) }}">{{ $task->creator()->name() }}</a></div>
									</div>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="well col-md-7"><?php echo nl2br($task->text()); ?></div>
										<div class="well col-md-4 col-md-offset-1">
											<span>@lang('tasks.date'): {{ $layout->formatDate($task->createdOn()) }}</span><br>
											<span>@lang('tasks.deadline'): 
											@if($task->deadline() !== null)
												{{ $layout->formatDate($task->deadline()) }}
											@else
												@lang('tasks.not_set')
											@endif
											</span><br>
											<span>@lang('tasks.priority'): @lang('tasks.'.$task->priority()->name())</span><br>
											<span>@lang('tasks.status'): @lang('tasks.'.$task->status()->name())</span><br>
											@if($task->status()->name() === "closed")
												<span>{{ $task->closedOn() }}</span><br>
											@endif
											<span>@lang('tasks.type'): @lang('tasks.'.$task->type()->name())</span><br>
											@if($task->assignedTo() != null)
												<span>@lang('tasks.assigned_to') }}: <a href="{{ url('data/'.$task->assignedTo()->username()) }}">{{ $task->assignedTo()->username() }}</a></span><br>
											@else
												<span>@lang('tasks.assigned_to'): @lang('tasks.no_one_is_assigned')</span><br>
											@endif
											<span>@lang('tasks.working_hour'): {{ $task->workingHours() }}</span>
										</div>
									</div>
								</div>
							</div>
						@endif
						<!-- new comment -->
						@if($layout->user()->permitted('tasks_admin') || $layout->user()->permitted('tasks_add_comment'))
						<form class="form-horizontal" role="form" method="POST" action="{{ url('/tasks/task/'.$task->id().'/addcomment') }}">
							<input type="hidden" name="_method" value="PUT">
							{!! csrf_field() !!}
							<div class="well">
								<div class="row">
									<div class="col-md-3">
										<span>@lang('tasks.write_new_comment'):</span><br>
										<input type="submit" style="margin-top:10px;" class="form-control btn btn-primary" name="addCommentButton" value="@lang('tasks.send')"></input>
									</div>
									<div class="col-md-9">
										<textarea class="form-control" name="commentText" placeholder="@lang('tasks.write_new_comment_description')"></textarea>
									</div>
									@if ($errors->has('commentText'))
										<span class="text-danger">
											<strong>{{ $errors->first('commentText') }}</strong>
										</span>
									@endif
								</div>
							</div>
						</form>
						@endif
						<!-- comments -->
						@foreach($comments as $comment)
							<div class="well">
								@if($layout->user()->permitted('tasks_admin') || $comment->authorUsername() === $layout->user()->user()->username())
								<a href="{{ url('/tasks/task/'.$task->id().'/removecomment/'.$comment->id()) }}">
									<div style="width:20px;height:20px;overflow:hidden;float:right;">✖</div>
								</a>
								<div class="row" style="margin-right:20px;">
								@else
								<div class="row">
								@endif
									<div class="col-md-3">
										<span>{{ $layout->formatDate($comment->creationDate()) }}</span><br>
										<span><a href="{{ url('data/'.$comment->authorUsername()) }}">{{ $comment->authorName() }}</a></span>
									</div>
									<div class="col-md-9">
										<?php echo nl2br($comment->comment()); ?>
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

<!-- Datepicker script -->
<script type="text/javascript">
	$(function(){
		$('#datepicker_add_new_task').datepicker({
			format: 'yyyy. mm. dd',
			autoclose: true,
			clearBtn: true,
			startDate: "today"
		});
	});
</script>
@endsection
