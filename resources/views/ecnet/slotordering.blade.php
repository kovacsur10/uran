@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('mac_slot_ordering') }}</div>
                <div class="panel-body">
					<form class="form-horizontal" role="form" method="POST" action="{{ url('/ecnet/getslot') }}">
						{!! csrf_field() !!}
						
						<div class="form-group{{ $errors->has('reason') ? ' has-error' : '' }}">
							<label class="col-md-4 control-label">{{ $layout->language('reason_of_ordering') }}</label>

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
								<button type="submit" class="btn btn-primary">
									{{ $layout->language('order_slot') }}
								</button>
							</div>
						</div>
					</form>
					<div class="alert alert-info">
						<strong>{{ $layout->language('help') }}:</strong> {{ $layout->language('mac_slot_ordering_description') }}
					</div>
					
					@if($layout->user()->permitted('ecnet_slot_verify'))
					<div class="panel panel-default">
						<div class="panel-heading">{{ $layout->language('admin_panel') }}</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/ecnet/allowordenyorder') }}">
								{!! csrf_field() !!}
								
								<div class="form-group{{ $errors->has('slot') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="slot_select">{{ $layout->language('request') }}</label>
									<div class="col-md-6">
										<select class="form-control"  name="slot"  id="slot_select" required="true">
											@foreach($layout->user()->getMacSlotOrders() as $order)
											<option value="{{ $order->id }}">{{ $order->username }} ({{ $order->order_time }}: {{ $order->reason }})</option>
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
									<label><input type="radio" name="optradio" value="deny">{{ $layout->language('deny_request') }}</label>
								</div>
								<div class="radio">
									<label><input type="radio" name="optradio" value="allow">{{ $layout->language('allow_request') }}</label>
								</div>
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											{{ $layout->language('approve') }}
										</button>
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
