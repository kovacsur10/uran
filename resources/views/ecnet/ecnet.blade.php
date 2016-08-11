@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('internet_access') }}</div>
                <div class="panel-body">
					@if($active)
					<div class="alert alert-success">
						<p>{{ $layout->language('internet_is_active') }}</p>
						<p>{{ $layout->language('expiration_date') }}: {{ $layout->formatDate($layout->user()->ecnetUser()->valid_time) }}</p>
					</div>
					@else
					<div class="alert alert-danger">
						<p>{{ $layout->language('internet_in_not_active') }}</p>
					</div>
					@endif
					<div class="alert alert-info">
						<strong>{{ $layout->language('note') }}:</strong> {{ $layout->language('internet_registartion_description') }}
					</div>
					
					@if($layout->user()->permitted('ecnet_set_valid_time'))
					<div class="panel panel-default">
						<div class="panel-heading">{{ $layout->language('admin_panel') }} - {{ $layout->language('validation') }}</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/ecnet/activate') }}">
								{!! csrf_field() !!}
								
								<div class="form-group{{ $errors->has('custom_valid_date') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('custom_validation_date') }}</label>
									
									<div class="col-md-6">
										<div class='input-group date' data-date-format="yyyy.mm.dd." id='datepicker_custom_valid_date'>
											<input type="text" readonly class="form-control" name="custom_valid_date" value="{{ old('custom_valid_date') }}">
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>

										@if ($errors->has('custom_valid_date'))
											<span class="help-block">
												<strong>{{ $errors->first('custom_valid_date') }}</strong>
											</span>
										@endif
									</div>
								</div>
																
								<div class="form-group{{ $errors->has('account') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="user_select">{{ $layout->language('user') }}</label>
									<div class="col-md-6">
										<select class="form-control"  name="account"  id="user_select" required="true">
											@foreach($users as $us)
											<option value="{{ $us->id }}">{{ $us->name }} ({{ $us->username }})</option>
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
											{{ $layout->language('validate') }}
										</button>
									</div>
								</div>
							</form>
							
							<div class="alert alert-warning">
								<p>{{ $layout->language('validation_time_set_admin_description') }}</p>
								<p>{{ $layout->language('default_time_set_note_description') }}</p>
								<p>{{ $layout->language('default_time_now_description') }}: <strong>
								@if($layout->user()->validationTime() != null)
									{{ $layout->formatDate($layout->user()->validationTime()->valid_date) }}
								@else
									{{ $layout->language('error_no_default_time_description') }}
								@endif
								</strong></p>
							</div>
						</div>
					</div>
					
					<div class="panel panel-default">
						<div class="panel-heading">{{ $layout->language('admin_panel') }} - {{ $layout->language('default_time') }}</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/ecnet/setvalidtime') }}">
								{!! csrf_field() !!}
								
								<div class="form-group{{ $errors->has('new_valid_date') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('validation_date') }}</label>

									<div class="col-md-6">
										<div class='input-group date' data-date-format="yyyy.mm.dd." id='datepicker_new_valid_date'>
											<input type="text" readonly class="form-control" name="new_valid_date" value="{{ old('new_valid_date') }}">
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>

										@if ($errors->has('new_valid_date'))
											<span class="help-block">
												<strong>{{ $errors->first('new_valid_date') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											{{ $layout->language('modify') }}
										</button>
									</div>
								</div>
							</form>
							<div class="alert alert-warning">
								<p>{{ $layout->language('default_time_now_description') }}: <strong>
								@if($layout->user()->validationTime() != null)
									{{ $layout->formatDate($layout->user()->validationTime()->valid_date) }}
								@else
									{{ $layout->language('error_no_default_time_description') }}
								@endif
								</strong></p>
								<p>{{ $layout->language('default_time_set_note_description') }}</p>
							</div>
						</div>
					</div>
					@endif
					
					<div class="panel panel-default">
						<div class="panel-heading">{{ $layout->language('mac_addresses') }}</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/ecnet/setmacs') }}">
								{!! csrf_field() !!}
								
								@foreach($layout->user()->macAddresses() as $address)
								<div class="form-group{{ $errors->has('mac_address_'.$address->id) ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('mac_address') }}</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="mac_address_{{ $address->id }}" value="{{ old('mac_address_'.$address->id) != null ? old('mac_address_'.$address->id) : $address->mac_address }}">

										@if ($errors->has('mac_address_'.$address->id))
											<span class="help-block">
												<strong>{{ $errors->first('mac_address_'.$address->id) }}</strong>
											</span>
										@endif
									</div>
								</div>
								@endforeach
								
								@for($i = 0; $i < $layout->user()->ecnetUser()->mac_slots - count($layout->user()->macAddresses()); $i++)
								<div class="form-group{{ $errors->has('new_mac_address_'.$i) ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">{{ $layout->language('mac_address') }}</label>

									<div class="col-md-6">
										<input type="text" class="form-control" name="new_mac_address_{{ $i }}" value="{{ old('new_mac_address_'.$i) }}">

										@if ($errors->has('new_mac_address_'.$i))
											<span class="help-block">
												<strong>{{ $errors->first('new_mac_address_'.$i) }}</strong>
											</span>
										@endif
									</div>
								</div>							
								@endfor
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											{{ $layout->language('set_mac_address') }}
										</button>
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
		$('#datepicker_custom_valid_date').datepicker({
			format: 'yyyy.mm.dd.',
			autoclose: true,
			clearBtn: true,
			todayHighlight: true
		});
	});
	
	$(function(){
		$('#datepicker_new_valid_date').datepicker({
			format: 'yyyy.mm.dd.',
			autoclose: true,
			clearBtn: true,
			todayHighlight: true
		});
	});
</script>
@endsection
