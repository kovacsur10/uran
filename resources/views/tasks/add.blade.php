@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><a href="{{ url('tasks/list') }}">{{ $layout->language('task') }}</a></div>
                <div class="panel-body">
					<div class="col-md-8 col-md-offset-2">
						<div class="panel panel-default">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/tasks/new') }}">
								<input type="hidden" name="_method" value="PUT">
								{!! csrf_field() !!}

								<div class="panel-heading">
									<div class="row">
										<div class="col-md-8"><input type="text" class="form-control" name="caption" value="{{ old('caption') == null ? $layout->language('write_task_caption_description') : old('caption') }}" required="true"></div>
										<div class="text-right col-md-4"></div>
									</div>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="well col-md-7"><textarea class="form-control" rows="6" name="text" cols="40" rows="5">{{ old('text') == null ? $layout->language('write_task_text_description') : old('text') }}</textarea></div>
										<div class="well col-md-4 col-md-offset-1">
											<span>
												<label  class="control-label" for="priority">{{ $layout->language('priority') }}:</label>
												<select class="form-control"  name="priority"  id="priority" required="true">
													@foreach($layout->tasks()->priorities() as $priority)
														<option value="{{ $priority->id }}" {{ old('priority') == $priority->id ? 'selected' : '' }}>{{ $layout->language($priority->name) }}</option>
													@endforeach
												</select>
											</span><br>
											<span>
												<label  class="control-label" for="type">{{ $layout->language('type') }}:</label>
												<select class="form-control"  name="type"  id="type" required="true">
													@foreach($layout->tasks()->taskTypes() as $type)
														<option value="{{ $type->id }}" {{ old('type') == $type->id ? 'selected' : '' }}>{{ $layout->language($type->type) }}</option>
													@endforeach
												</select>
											</span><br>
											<span>
												<label  class="control-label" for="priority">{{ $layout->language('deadline') }}:</label>
												<input type="date" id="datepicker_add_new_task" class="form-control" name="deadline" value="{{ old('deadline') }}">
											</span><br>
											<span>
												<input type="submit" class="form-control btn btn-primary" name="addTaskButton" value="{{ $layout->language('add_task') }}"></input>
											</span>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
