@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
			
			<!--PAGINATION-->
			<div class="row">
				<div class="col-sm-3" style="margin: 20px 0px;">
					<div class="dropdown">
						<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">@lang('ecouncil.choose_visible_row_count')
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<li><a href="{{ url('ecouncil/records/10/'.$firstRecord) }}">10</a></li>
							<li><a href="{{ url('ecouncil/records/20/'.$firstRecord) }}">20</a></li>
							<li><a href="{{ url('ecouncil/records/50/'.$firstRecord) }}">50</a></li>
							<li><a href="{{ url('ecouncil/records/100/'.$firstRecord) }}">100</a></li>
						</ul>
					</div>
				</div>
				<div class="col-sm-6">
					<ul class="pagination">
						@if(0 < $firstRecord)
							<li><a href="{{ url('ecouncil/records/'.$recordsToShow.'/'.($firstRecord- $recordsToShow >= 0 ? $firstRecord - $recordsToShow : 0)) }}">&laquo;</a></li>
						@else
							<li class="disabled"><a href="#">&laquo;</a></li>
						@endif
						@foreach($layout->base()->getPagination($firstRecord, $recordsToShow, count($layout->records()->getRecords())) as $id => $page)
							@if($page === 'middle')
								<li class="active"><span>{{ $id }}</span></li>
							@elseif($page === 'disabled')
								<li class="disabled"><span>&nbsp;&nbsp;&nbsp;</span></li>
							@else
								<li><a href="{{ url('ecouncil/records/'.$recordsToShow.'/'.$page) }}">{{ $id < 10 ? '&nbsp;'.$id : $id }}</a></li>
							@endif
						@endforeach
						@if($firstRecord+$recordsToShow < count($layout->records()->getRecords()))
							<li><a href="{{ url('ecouncil/records/'.$recordsToShow.'/'.($firstRecord+$recordsToShow)) }}">&raquo;</a></li>
						@else
							<li class="disabled"><a href="#">&raquo;</a></li>
						@endif
					</ul>
				</div>
			</div>
			
			<!--CONTENT -->
            <div class="panel panel-default">
                <div class="panel-heading">@lang('ecouncil.record_list') </div>
                <div class="panel-body">
					
					<div class="well well-sm">
						<div class="row">
							<div class="col-sm-4">@lang('ecouncil.file_name') </div>
							<div class="col-sm-2">@lang('ecouncil.uploader') </div>
							<div class="col-sm-2">@lang('ecouncil.committee') </div>
							<div class="col-sm-2">@lang('ecouncil.meeting_date') </div>
							<div class="col-sm-2">@lang('ecouncil.upload_date') </div>
						</div>
					</div>
					@if($layout->records()->recordsToPages($firstRecord, $recordsToShow) != null)
						@foreach($layout->records()->recordsToPages($firstRecord, $recordsToShow) as $record)
						<div class="well well-sm">
							<a href="{{ url('ecouncil/records/view/' . $record->file_name()) }}" target="_blank" >
								<div class="row" >
									<div class="col-sm-4">{{$record->filename()}}</div>
									<div class="col-sm-2">{{$layout->user()->getUserData($record->uploader_id())->name() }}</div>
									<div class="col-sm-2">{{$record->committee()->name()}}</div>
									<div class="col-sm-2">{{substr($record->meeting_date(), 0, 10)}}</div>
									<div class="col-sm-2">{{$record->upload_date()}}</div>
								</div>
							</a>
						</div>
						@endforeach
					@endif	
                </div>
            </div>
			
			<!--PAGINATION-->
			<div class="row">
				<div class="col-sm-3" style="margin: 20px 0px;">
					<div class="dropdown">
						<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"> @lang('ecouncil.choose_visible_row_count') 
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<li><a href="{{ url('ecouncil/records/10/'.$firstRecord) }}">10</a></li>
							<li><a href="{{ url('ecouncil/records/20/'.$firstRecord) }}">20</a></li>
							<li><a href="{{ url('ecouncil/records/50/'.$firstRecord) }}">50</a></li>
							<li><a href="{{ url('ecouncil/records/100/'.$firstRecord) }}">100</a></li>
						</ul>
					</div>
				</div>
				<div class="col-sm-6">
					<ul class="pagination">
						@if(0 < $firstRecord)
							<li><a href="{{ url('ecouncil/records/'.$recordsToShow.'/'.($firstRecord- $recordsToShow >= 0 ? $firstRecord - $recordsToShow : 0)) }}">&laquo;</a></li>
						@else
							<li class="disabled"><a href="#">&laquo;</a></li>
						@endif
						@foreach($layout->base()->getPagination($firstRecord, $recordsToShow, count($layout->records()->getRecords())) as $id => $page)
							@if($page === 'middle')
								<li class="active"><span>{{ $id }}</span></li>
							@elseif($page === 'disabled')
								<li class="disabled"><span>&nbsp;&nbsp;&nbsp;</span></li>
							@else
								<li><a href="{{ url('ecouncil/records/'.$recordsToShow.'/'.$page) }}">{{ $id < 10 ? '&nbsp;'.$id : $id }}</a></li>
							@endif
						@endforeach
						@if($firstRecord+$recordsToShow < count($layout->records()->getRecords()))
							<li><a href="{{ url('ecouncil/records/'.$recordsToShow.'/'.($firstRecord+$recordsToShow)) }}">&raquo;</a></li>
						@else
							<li class="disabled"><a href="#">&raquo;</a></li>
						@endif
					</ul>
				</div>
			</div>
			@if($layout->user()->permitted('tasks_admin'))
            <div class="panel panel-default">
				<div class="panel-heading">@lang('ecouncil.add_new_record')  </div>
                <div class="panel-body">
					<form class="form-horizontal" role="form" method="POST" action="{{ url('ecouncil/records') }}" enctype="multipart/form-data">
						<input type="hidden" name="_method" value="PUT">
						{!! csrf_field() !!}
						<div class="row">
							<div class="col-md-3">
								<label  class="control-label" for="file_name">@lang('ecouncil.record')</label>
							</div>
							<div class="col-md-3">
								<label  class="control-label" for="file_name">@lang('ecouncil.file_name')</label>
							</div>
							<div class="col-md-3">
								<label  class="control-label" for="meeting_date">@lang('ecouncil.meeting_date')</label></div>
							<div class="col-md-3">
								<label  class="control-label" for="committee">@lang('ecouncil.committee')</label></div>
						</div>
						<div class="row" style="margin-top:10px;">
							<div class="col-md-3">
								<input type="file"  name="file_to_upload" id="file_to_upload">
							</div>
							<div class="col-md-3">
								<input class="form-control" type="text" name="file_name" id="file_name">
							</div>
							<div class="col-md-3">
								<div class='input-group date' data-date-format="yyyy. mm. dd" id='datepicker_add_new_task'>
									<input type='text' readonly class="form-control" name="meeting_date" />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
							</div>
							<div class="col-md-3">
								<select class="form-control" name="committee" id="committee">
									@foreach($layout->records()->getCommittees() as $committee)
										<option name="{{$committee->id() }}" id="{{$committee->id() }}" value="{{$committee->id() }}">{{$committee->name() }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="row" style="margin-top:10px;">
							<div class="col-md-5"></div>
							<div class="col-md-2"><input type="submit" style="margin-top:10px;" class="form-control btn btn-primary" name="sendValueButton" value="@lang('ecouncil.send') "></input></div>
							<div class="col-md-5"></div>
						</div>
					</form>
				</div>
			</div>
			@endif
			
        </div>
    </div>
</div>

<!-- Datepicker script -->
<script type="text/javascript">
	$(function(){
		$('#datepicker_add_new_task').datepicker({
			format: 'yyyy-mm-dd',
			autoclose: true,
			clearBtn: true
		});
	});
</script>
@endsection
