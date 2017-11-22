@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('ecnet.mac_slot_ordering')</div>
                <div class="panel-body">
                	@if($layout->errors()->has('ordering'))
                		<div class="alert alert-danger">
							{{$layout->errors()->get('ordering')}}
						</div>
					@elseif($layout->errors()->has('success_ordering'))
						<div class="alert alert-success">
							{{$layout->errors()->get('success_ordering')}}
						</div>
                	@endif
					<form class="form-horizontal" role="form" method="POST" action="{{ url('/ecnet/getslot') }}">
						{!! csrf_field() !!}
						
						<div class="form-group{{ $errors->has('reason') ? ' has-error' : '' }}">
							<label class="col-md-4 control-label">@lang('ecnet.reason_of_ordering')</label>

							<div class="col-md-6">
								<input type="text" class="form-control" name="reason" required="true" value="{{ old('reason') }}">

								@if ($errors->has('reason'))
									<span class="help-block">
										<strong>{{ $errors->first('reason') }}</strong>
									</span>
								@endif
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">@lang('ecnet.order_slot')</button>
							</div>
						</div>
					</form>
					<div class="alert alert-info">
						<strong>@lang('general.help'):</strong> @lang('ecnet.mac_slot_ordering_description') }}
					</div>
					
					@if($layout->user()->permitted('ecnet_slot_verify'))
					<div class="panel panel-default">
						<div class="panel-heading">@lang('general.admin_panel')</div>
						<div class="panel-body">
							@if($layout->errors()->has('order_allowing'))
		                		<div class="alert alert-danger">
									{{$layout->errors()->get('order_allowing')}}
								</div>
							@elseif($layout->errors()->has('success_order_allowing'))
								<div class="alert alert-success">
									{{$layout->errors()->get('success_order_allowing')}}
								</div>
		                	@endif
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/ecnet/allowordenyorder') }}">
								{!! csrf_field() !!}
								
								<div class="form-group{{ $errors->has('slot') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="slot_select">@lang('ecnet.request')</label>
									<div class="col-md-6">
										<select class="form-control"  name="slot"  id="slot_select" required="true">
											@foreach($layout->user()->getMacSlotOrders() as $order)
											<option value="{{ $order->id() }}">{{ $order->username() }} ({{ $order->time() }}: {{ $order->reason() }})</option>
											@endforeach
										</select>

										@if ($errors->has('slot'))
											<span class="help-block">
												<strong>{{ $errors->first('slot') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="radio">
									<label><input type="radio" name="optradio" value="deny">@lang('ecnet.deny_request')</label>
								</div>
								<div class="radio">
									<label><input type="radio" name="optradio" value="allow">@lang('ecnet.allow_request')</label>
								</div>
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">@lang('ecnet.approve')</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
