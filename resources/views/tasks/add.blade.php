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
										<div class="col-md-8"><input type="text" class="form-control" name="caption" value="{{ $layout->errors()->getOld('caption') === null ? $layout->language('write_task_caption_description') : $layout->errors()->getOld('caption') }}" required="true"></div>
										<div class="text-right col-md-4"></div>
									</div>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="well col-md-7">
											<textarea class="form-control" rows="6" name="text" cols="40" rows="5" required>{{ $layout->errors()->getOld('text') === null ? $layout->language('write_task_text_description') : $layout->errors()->getOld('text') }}</textarea>
											@if ($layout->errors()->has('text'))
												<span class="text-danger">
													<strong>{{ $layout->errors()->get('text') }}</strong>
												</span>
											@endif
										</div>
										<div class="well col-md-4 col-md-offset-1">
											<span>
												<label  class="control-label" for="priority">{{ $layout->language('priority') }}:</label>
												<select class="form-control"  name="priority"  id="priority" required="true" autocomplete="off">
													@foreach($layout->tasks()->priorities() as $priority)
														<option value="{{ $priority->id() }}" {{ $layout->errors()->getOld('priority') == $priority->id() ? 'selected' : '' }}>{{ $layout->language($priority->name()) }}</option>
													@endforeach
												</select>
												@if ($layout->errors()->has('priority'))
													<span class="text-danger">
														<strong>{{ $layout->errors()->get('priority') }}</strong>
													</span>
												@endif
											</span><br>
											<span>
												<label  class="control-label" for="type">{{ $layout->language('type') }}:</label>
												<select class="form-control"  name="type"  id="type" required="true" autocomplete="off">
													@foreach($layout->tasks()->taskTypes() as $type)
														<option value="{{ $type->id() }}" {{ $layout->errors()->getOld('type') == $type->id() ? 'selected' : '' }}>{{ $layout->language($type->name()) }}</option>
													@endforeach
												</select>
												@if ($layout->errors()->has('type'))
													<span class="text-danger">
														<strong>{{ $layout->errors()->get('type') }}</strong>
													</span>
												@endif
											</span><br>
											<label  class="control-label" for="deadline">{{ $layout->language('deadline') }}:</label>
											<div class='input-group date' data-date-format="yyyy. mm. dd" id='datepicker_add_new_task'>
												<input type='text' readonly class="form-control" name="deadline" value="{{ $layout->errors()->getOld('deadline') }}" />
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
												@if ($layout->errors()->has('deadline'))
													<span class="text-danger">
														<strong>{{ $layout->errors()->get('deadline') }}</strong>
													</span>
												@endif
											</div><br>
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
