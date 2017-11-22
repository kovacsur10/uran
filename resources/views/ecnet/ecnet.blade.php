@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('ecnet.internet_access')</div>
                <div class="panel-body">
					@if($active)
					<div class="alert alert-success">
						<p>@lang('ecnet.internet_is_active')</p>
						<p>@lang('ecnet.expiration_date'): {{ $layout->formatDate($layout->user()->ecnetUser()->valid()) }}</p>
					</div>
					@else
					<div class="alert alert-danger">
						<p>@lang('ecnet.internet_in_not_active')</p>
					</div>
					@endif
					<div class="alert alert-info">
						<strong>@lang('general.note'):</strong> @lang('ecnet.internet_registartion_description')
					</div>
					
					@if($layout->user()->permitted('ecnet_set_valid_time'))
					<div class="panel panel-default">
						<div class="panel-heading">@lang('general.admin_panel') - @lang('ecnet.validation')</div>
						<div class="panel-body">
							@if($layout->errors()->has('activate'))
		                		<div class="alert alert-danger">
									{{$layout->errors()->get('activate')}}
								</div>
							@elseif($layout->errors()->has('success_activate'))
								<div class="alert alert-success">
									{{$layout->errors()->get('success_activate')}}
								</div>
		                	@endif
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/ecnet/activate') }}">
								{!! csrf_field() !!}
								
								<div class="form-group{{ $errors->has('custom_valid_date') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">@lang('ecnet.custom_validation_date')</label>
									
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
									<label  class="col-md-4 control-label" for="user_select">@lang('user.user')</label>
									<div class="col-md-6">
										<select class="form-control"  name="account"  id="user_select" required="true">
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
										<button type="submit" class="btn btn-primary">@lang('ecnet.validate')</button>
									</div>
								</div>
							</form>
							
							<div class="alert alert-warning">
								<p>@lang('ecnet.validation_time_set_admin_description')</p>
								<p>@lang('ecnet.default_time_set_note_description')</p>
								<p>@lang('ecnet.default_time_now_description'): <strong>
								@if($layout->user()->validationTime() !== null)
									{{ $layout->formatDate($layout->user()->validationTime()) }}
								@else
									@lang('ecnet.error_no_default_time_description')
								@endif
								</strong></p>
							</div>
						</div>
					</div>
					
					<div class="panel panel-default">
						<div class="panel-heading">@lang('general.admin_panel') - @lang('ecnet.default_time')</div>
						<div class="panel-body">
							@if($layout->errors()->has('update_validation_time'))
		                		<div class="alert alert-danger">
									{{$layout->errors()->get('update_validation_time')}}
								</div>
							@elseif($layout->errors()->has('success_update_validation_time'))
								<div class="alert alert-success">
									{{$layout->errors()->get('success_update_validation_time')}}
								</div>
		                	@endif
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/ecnet/setvalidtime') }}">
								{!! csrf_field() !!}
								
								<div class="form-group{{ $errors->has('new_valid_date') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">@lang('ecnet.validation_date')</label>

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
										<button type="submit" class="btn btn-primary">@lang('ecnet.modify')</button>
									</div>
								</div>
							</form>
							<div class="alert alert-warning">
								<p>@lang('ecnet.default_time_now_description'): <strong>
								@if($layout->user()->validationTime() !== null)
									{{ $layout->formatDate($layout->user()->validationTime()) }}
								@else
									@lang('ecnet.error_no_default_time_description')
								@endif
								</strong></p>
								<p>@lang('ecnet.default_time_set_note_description')</p>
							</div>
						</div>
					</div>
					@endif
					
					<div class="panel panel-default">
						<div class="panel-heading">@lang('ecnet.mac_addresses')</div>
						<div class="panel-body">
							@if($layout->errors()->has('setmac'))
		                		<div class="alert alert-danger">
									{{$layout->errors()->get('setmac')}}
								</div>
							@elseif($layout->errors()->has('success_setmac'))
								<div class="alert alert-success">
									{{$layout->errors()->get('success_setmac')}}
								</div>
		                	@endif
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/ecnet/setmacs') }}">
								{!! csrf_field() !!}
								
								<?php $i = 0; ?>
								@foreach($layout->user()->ecnetUser()->macAddresses() as $address)
								<div class="form-group{{ $errors->has('mac_address_'.$i) ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">@lang('ecnet.mac_address')</label>

									<div class="col-md-6">
										<input type="text" class="form-control mac-address-check" name="mac_address_{{ $i }}" value="{{ old('mac_address_'.$i) !== null ? old('mac_address_'.$i) : $address->address() }}">

										@if ($errors->has('mac_address_'.$i))
											<span class="help-block">
												<strong>{{ $errors->first('mac_address_'.$i) }}</strong>
											</span>
										@endif
									</div>
								</div>
								<?php $i++; ?>
								@endforeach
								
								@for(; $i < $layout->user()->ecnetUser()->maximumMacSlots(); $i++)
								<div class="form-group{{ $errors->has('mac_address_'.$i) ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">@lang('ecnet.mac_address')</label>

									<div class="col-md-6">
										<input type="text" class="form-control mac-address-check" name="mac_address_{{ $i }}" value="{{ old('mac_address_'.$i) }}">

										@if ($errors->has('mac_address_'.$i))
											<span class="help-block">
												<strong>{{ $errors->first('mac_address_'.$i) }}</strong>
											</span>
										@endif
									</div>
								</div>							
								@endfor
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" id="setMacAddressesButton" class="btn btn-primary">@lang('ecnet.set_mac_address')</button>
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
<script type="text/javascript" src="/js/view/ecnet/ecnet.js"></script>
@endsection
