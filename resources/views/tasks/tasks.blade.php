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
					
					<div class="panel panel-default">
						<div class="panel-heading" style="cursor: pointer;" data-toggle="collapse" data-target="#filterPanelBody">{{ $layout->language('data_filtering') }} - {{ $layout->language('openable') }}</div>
						<div class="panel-body collapse" id="filterPanelBody">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/tasks/tasks') }}">
							{!! csrf_field() !!}
							
								<div class="form-group{{ $errors->has('caption') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('task') }}</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="caption" value="{{ $layout->tasks()->getCaptionFilter() }}">

										@if ($errors->has('caption'))
											<span class="help-block">
												<strong>{{ $errors->first('caption') }}</strong>
											</span>
										@endif
									</div>
								</div>
											
								<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('status') }}</label>

									<div class="col-md-6">
										<select class="form-control" name="status"> 	
											<option class="form-control" name="status" value=""></option>
											@foreach($layout->tasks()->statusTypes() as $status)
												<option class="form-control" name="status" value="{{ $status->id }}" {{ $layout->tasks()->getStatusFilter() == $status->id ? "selected" : "" }}>{{ $layout->language($status->status) }}</option>
											@endforeach
										</select>
										
										@if ($errors->has('status'))
											<span class="help-block">
												<strong>{{ $errors->first('status') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('priority') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('priority') }}</label>

									<div class="col-md-6">
										<select class="form-control" name="priority"> 	
											<option class="form-control" name="priority" value=""></option>
											@foreach($layout->tasks()->priorities() as $priority)
												<option class="form-control" name="priority" value="{{ $priority->id }}"  {{ $layout->tasks()->getPriorityFilter() == $priority->id ? "selected" : "" }}>{{ $layout->language($priority->name) }}</option>
											@endforeach
										</select>
										
										@if ($errors->has('priority'))
											<span class="help-block">
												<strong>{{ $errors->first('priority') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('myTasks') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('myTasks') }}</label>

									<div class="col-md-6">
										<input type="checkbox" name="myTasks" value="myTasks" {{ $layout->tasks()->getMyTasksFilter() == 1 ? "checked" : "" }}>

										@if ($errors->has('myTasks'))
											<span class="help-block">
												<strong>{{ $errors->first('myTasks') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('hide_closed') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('hide_closed') }}</label>

									<div class="col-md-6">
										<input type="checkbox" name="hide_closed" value="hide_closed" {{ $layout->tasks()->getHideClosedFilter() == 1 ? "checked" : "" }}>

										@if ($errors->has('hide_closed'))
											<span class="help-block">
												<strong>{{ $errors->first('hide_closed') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											{{ $layout->language('find') }}
										</button>
										<a href="{{ url('tasks/resetfilter') }}" class="btn btn-danger" role="button">{{ $layout->language('delete_filter') }}</a>
									</div>
								</div>
							</form>
						</div>
					</div>
				
					<div class="row">
						<div class="col-sm-3" style="margin: 20px 0px;">
							<div class="dropdown">
								<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">{{ $layout->language('choose_visible_row_count') }}
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu">
									<li><a href="{{ url('tasks/tasks/10/'.$firstTask) }}">10</a></li>
									<li><a href="{{ url('tasks/tasks/20/'.$firstTask) }}">20</a></li>
									<li><a href="{{ url('tasks/tasks/50/'.$firstTask) }}">50</a></li>
									<li><a href="{{ url('tasks/tasks/100/'.$firstTask) }}">100</a></li>
								</ul>
							</div>
						</div>
						<div class="col-sm-6">
							<ul class="pagination">
								@if(0 < $firstTask)
									<li><a href="{{ url('tasks/tasks/'.$tasksToShow.'/'.($firstTask - $tasksToShow >= 0 ? $firstTask - $tasksToShow : 0)) }}">&laquo;</a></li>
								@else
									<li class="disabled"><a href="#">&laquo;</a></li>
								@endif
								@foreach($layout->base()->getPagination($firstTask, $tasksToShow, count($layout->tasks()->get())) as $id => $page)
									@if($page === 'middle')
										<li class="active"><span>{{ $id }}</span></li>
									@elseif($page === 'disabled')
										<li class="disabled"><span>&nbsp;&nbsp;&nbsp;</span></li>
									@else
										<li><a href="{{ url('tasks/tasks/'.$tasksToShow.'/'.$page) }}">{{ $id < 10 ? '&nbsp;'.$id : $id }}</a></li>
									@endif
								@endforeach
								@if($firstTask+$tasksToShow < count($layout->tasks()->get()))
									<li><a href="{{ url('tasks/tasks/'.$tasksToShow.'/'.($firstTask+$tasksToShow)) }}">&raquo;</a></li>
								@else
									<li class="disabled"><a href="#">&raquo;</a></li>
								@endif
							</ul>
						</div>
						<div class="col-sm-3" style="margin: 20px 0px;">
							@if($layout->user()->permitted('tasks_add'))
							<div class="pull-right">
								<a href="{{ url('tasks/new') }}" class="btn btn-primary">{{ $layout->language('create_new_task') }}</a>
							</div>
							@endif
						</div>
					</div>
					
					<div class="well well-sm">
						<div class="row">
							<div class="col-sm-6">{{ $layout->language('caption') }}</div>
							<div class="col-sm-2">{{ $layout->language('user') }}</div>
							<div class="col-sm-2">{{ $layout->language('status') }}</div>
							<div class="col-sm-2">{{ $layout->language('date') }}</div>
						</div>
					</div>
					@if($layout->tasks()->tasksToPages($firstTask, $tasksToShow) != null)
						@foreach($layout->tasks()->tasksToPages($firstTask, $tasksToShow) as $task)
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
					@endif
					
					<div class="row">
						<div class="col-sm-3" style="margin: 20px 0px;">
							<div class="dropdown">
								<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">{{ $layout->language('choose_visible_row_count') }}
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu">
									<li><a href="{{ url('tasks/tasks/10/'.$firstTask) }}">10</a></li>
									<li><a href="{{ url('tasks/tasks/20/'.$firstTask) }}">20</a></li>
									<li><a href="{{ url('tasks/tasks/50/'.$firstTask) }}">50</a></li>
									<li><a href="{{ url('tasks/tasks/100/'.$firstTask) }}">100</a></li>
								</ul>
							</div>
						</div>
						<div class="col-sm-6">
							<ul class="pagination">
								@if(0 < $firstTask)
									<li><a href="{{ url('tasks/tasks/'.$tasksToShow.'/'.($firstTask - $tasksToShow >= 0 ? $firstTask - $tasksToShow : 0)) }}">&laquo;</a></li>
								@else
									<li class="disabled"><a href="#">&laquo;</a></li>
								@endif
								@foreach($layout->base()->getPagination($firstTask, $tasksToShow, count($layout->tasks()->get())) as $id => $page)
									@if($page === 'middle')
										<li class="active"><span>{{ $id }}</span></li>
									@elseif($page === 'disabled')
										<li class="disabled"><span>&nbsp;&nbsp;&nbsp;</span></li>
									@else
										<li><a href="{{ url('tasks/tasks/'.$tasksToShow.'/'.$page) }}">{{ $id < 10 ? '&nbsp;'.$id : $id }}</a></li>
									@endif
								@endforeach
								@if($firstTask+$tasksToShow < count($layout->tasks()->get()))
									<li><a href="{{ url('tasks/tasks/'.$tasksToShow.'/'.($firstTask+$tasksToShow)) }}">&raquo;</a></li>
								@else
									<li class="disabled"><a href="#">&raquo;</a></li>
								@endif
							</ul>
						</div>
						<div class="col-sm-3" style="margin: 20px 0px;">
							@if($layout->user()->permitted('tasks_add'))
							<div class="pull-right">
								<a href="{{ url('tasks/new') }}" class="btn btn-primary">{{ $layout->language('create_new_task') }}</a>
							</div>
							@endif
						</div>
					</div>
						
                </div>
            </div>
			
        </div>
    </div>
</div>
@endsection
