@extends('layouts.app', ['logged' => $logged, 'user' => $user])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">MAC slot igénylése</div>
                <div class="panel-body">
					<form class="form-horizontal" role="form" method="POST" action="{{ url('/ecnet/getslot') }}">
						{!! csrf_field() !!}
						
						<div class="form-group{{ $errors->has('reason') ? ' has-error' : '' }}">
							<label class="col-md-4 control-label">Igénylés oka</label>

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
									Slot igénylése
								</button>
							</div>
						</div>
					</form>
					<div class="alert alert-info">
						<strong>Segítség:</strong> Vezetékes internet regisztrációhoz lehet igényelni még további számítógép MAC cím helyeket. Az okot kérjük írjad le, az elfogadásához egy rendszergazda szükséges, erről értesítést fogsz kapni.
					</div>
					
					@if($user->permitted('ecnet_slot_verify'))
					<div class="panel panel-default">
						<div class="panel-heading">Admin panel</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/ecnet/allowordenyorder') }}">
								{!! csrf_field() !!}
								
								<div class="form-group{{ $errors->has('slot') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="slot_select">Kérelem</label>
									<div class="col-md-6">
										<select class="form-control"  name="slot"  id="slot_select" required="true">
											@foreach($orders as $order)
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
									<label><input type="radio" name="optradio" value="deny">Kérelem elutasítása</label>
								</div>
								<div class="radio">
									<label><input type="radio" name="optradio" value="allow">Kérelem elfogadása</label>
								</div>
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											Jóváhagy
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
