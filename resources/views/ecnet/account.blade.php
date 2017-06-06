@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('printing_account') }}</div>
                <div class="panel-body">
                	@if($layout->errors()->has('add_money'))
                		<div class="alert alert-danger">
							{{$layout->errors()->get('add_money')}}
						</div>
					@elseif($layout->errors()->has('success_add_money'))
						<div class="alert alert-success">
							{{$layout->errors()->get('success_add_money')}}
						</div>
					@elseif($layout->errors()->has('add_freepages'))
						<div class="alert alert-danger">
							{{$layout->errors()->get('add_freepages')}}
						</div>
					@elseif($layout->errors()->has('success_add_freepages'))
						<div class="alert alert-success">
							{{$layout->errors()->get('success_add_freepages')}}
						</div>
                	@endif
					<div class="well well-sm">{{ $layout->language('available_money') }}: {{ $layout->user()->ecnetUser()->money() }} HUF</div>
					<div class="alert alert-info">
						<strong>{{ $layout->language('note') }}:</strong> {{ $layout->language('money_upload_note_description') }}
					</div>
					
					<div class="panel panel-default">
						<div class="panel-heading">{{ $layout->language('available_free_pages') }}</div>
	                	<div class="panel-body">
							@if($layout->user()->ecnetUser()->freePages() !== [])
								<ul class="list-group">
								@foreach($layout->user()->ecnetUser()->freePages() as $freePage)
									<li class="list-group-item">{{ $layout->formatDate($freePage->until()) }} <span class="badge">{{ $freePage->count() }}</span></li>
								@endforeach
								</ul> 
							@else
								<div class="well well-sm">{{ $layout->language('no_free_pages_left') }}</div>
							@endif
							<div class="alert alert-info">
								<strong>{{ $layout->language('note') }}:</strong> {{ $layout->language('free_pages_note_description') }}
							</div>
						</div>
					</div>
					
					@if($layout->user()->permitted('ecnet_set_print_account'))
					<div class="panel panel-default">
						<div class="panel-heading">{{ $layout->language('admin_panel') }}</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/ecnet/addmoney') }}">
								{!! csrf_field() !!}
								
								<div class="form-group{{ $errors->has('money') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('money_to_add') }}</label>

									<div class="col-md-6">
										<input type="number" class="form-control" min="0" step="1" value="0" name="money" required="true" value="{{ old('money') }}">

										@if ($errors->has('money'))
											<span class="help-block">
												<strong>{{ $errors->first('money') }}</strong>
											</span>
										@endif
									</div>
								</div>
									
								<div class="form-group{{ $errors->has('reset') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('money_on_account') }}</label>

									<div class="col-md-6">
										<input type="number" class="form-control" min="0" step="1" value="0" name="reset" required="true" value="{{ old('reset') }}">

										@if ($errors->has('reset'))
											<span class="help-block">
												<strong>{{ $errors->first('reset') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('account') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="country_select">{{ $layout->language('user') }}</label>
									<div class="col-md-6">
										<select class="form-control"  name="account"  id="country_select" required="true">
											@foreach($users as $us)
											<option value="{{ $us->id() }}">{{ $us->name() }} ({{ $us->username() }})</option>
											@endforeach
										</select>

										@if ($errors->has('account'))
											<span class="help-block">
												<strong>{{ $errors->first('account') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											<i class="fa fa-btn fa-money"></i>{{ $layout->language('modify') }}
										</button>
									</div>
								</div>
							</form>
							
							<div class="alert alert-warning">
								{{ $layout->language('money_add_admin_note_description') }}
							</div>
						</div>
					</div>
					
					<div class="panel panel-default">
						<div class="panel-heading">{{ $layout->language('admin_panel') }}</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/ecnet/addfreepages') }}">
								{!! csrf_field() !!}
								
								<div class="form-group{{ $errors->has('pages') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('pages_to_add') }}</label>

									<div class="col-md-6">
										<input type="number" class="form-control" min="0" step="1" value="0" name="pages" required="true" value="{{ old('pages') }}">

										@if ($errors->has('pages'))
											<span class="help-block">
												<strong>{{ $errors->first('pages') }}</strong>
											</span>
										@endif
									</div>
								</div>
									
								<div class="form-group{{ $errors->has('valid_date') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('validation_date') }}</label>

									<div class="col-md-6">
										<div class='input-group date' data-date-format="yyyy.mm.dd." id='datepicker_valid_date'>
											<input type="text" readonly class="form-control" name="valid_date" value="{{ old('valid_date') }}">
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>

										@if ($errors->has('valid_date'))
											<span class="help-block">
												<strong>{{ $errors->first('valid_date') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('account') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="country_select">{{ $layout->language('user') }}</label>
									<div class="col-md-6">
										<select class="form-control"  name="account"  id="country_select" required="true">
											@foreach($users as $us)
											<option value="{{ $us->id() }}">{{ $us->name() }} ({{ $us->username() }})</option>
											@endforeach
										</select>

										@if ($errors->has('account'))
											<span class="help-block">
												<strong>{{ $errors->first('account') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											<i class="fa fa-btn fa-file-text-o"></i>{{ $layout->language('modify') }}
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					@endif
					<!-- TODO: history -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Datepicker script -->
<script type="text/javascript">
	$(function(){
		$('#datepicker_valid_date').datepicker({
			format: 'yyyy.mm.dd.',
			autoclose: true,
			clearBtn: true,
			todayHighlight: true
		});
	});
</script>
@endsection
