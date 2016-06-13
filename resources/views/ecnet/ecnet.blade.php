@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Internet hozzáférés</div>
                <div class="panel-body">
					@if($active)
					<div class="alert alert-success">
						<p>Az interneted aktív!</p>
						<p>Lejárati dátum: {{ str_replace("-", ". ", str_replace(" ", ". ", $layout->user()->eirUser()->valid_time)) }}</p>
					</div>
					@else
					<div class="alert alert-danger">
						<p>Az interneted nem aktív!</p>
					</div>
					@endif
					<div class="alert alert-info">
						<strong>Megjegyzés:</strong> Az internet regisztrációról érdeklődj egy rendszergazdánál! 
					</div>
					
					@if($layout->user()->permitted('ecnet_set_valid_time'))
					<div class="panel panel-default">
						<div class="panel-heading">Admin panel - érvényesítés</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/ecnet/activate') }}">
								{!! csrf_field() !!}
								
								<div class="form-group{{ $errors->has('custom_valid_date') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">Egyedi érvényességi dátum</label>

									<div class="col-md-6">
										<input type="date" class="form-control" name="custom_valid_date" value="{{ old('custom_valid_date') }}">

										@if ($errors->has('custom_valid_date'))
											<span class="help-block">
												<strong>{{ $errors->first('custom_valid_date') }}</strong>
											</span>
										@endif
									</div>
								</div>
																
								<div class="form-group{{ $errors->has('account') ? ' has-error' : '' }}">
									<label  class="col-md-4 control-label" for="user_select">Felhasználó</label>
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
											Érvényesít
										</button>
									</div>
								</div>
							</form>
							
							<div class="alert alert-warning">
								<p>Ha a dátum nincsen kitöltve, akkor az alapértelmezett dátum lesz az érvényesség vége!</p>
								<p>Hajnali 5 óra állítódik be időként!</p>
								<p>Az alapértelmezett dátum jelenleg: <strong>
								@if($layout->user()->validationTime() != null)
									{{ $layout->user()->validationTime()->valid_date }}
								@else
									Hiba! Nincsen beállított alapértelmezett idő!
								@endif
								</strong></p>
							</div>
						</div>
					</div>
					
					<div class="panel panel-default">
						<div class="panel-heading">Admin panel - alapértelmezett időpont</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/ecnet/setvalidtime') }}">
								{!! csrf_field() !!}
								
								<div class="form-group{{ $errors->has('new_valid_date') ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">Érvényességi dátum</label>

									<div class="col-md-6">
										<input type="date" class="form-control" name="new_valid_date" value="{{ old('new_valid_date') }}">

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
											Módosítás
										</button>
									</div>
								</div>
							</form>
							<div class="alert alert-warning">
								<p>Az alapértelmezett dátum jelenleg: <strong>
								@if($layout->user()->validationTime() != null)
									{{ $layout->user()->validationTime()->valid_date }}
								@else
									Hiba! Nincsen beállított alapértelmezett idő!
								@endif
								</strong></p>
								<p>Hajnali 5 óra állítódik be időként!</p>
							</div>
						</div>
					</div>
					@endif
					
					<div class="panel panel-default">
						<div class="panel-heading">MAC címek</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/ecnet/setmacs') }}">
								{!! csrf_field() !!}
								
								@foreach($layout->user()->macAddresses() as $address)
								<div class="form-group{{ $errors->has('mac_address_'.$address->id) ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">MAC cím</label>

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
								
								@for($i = 0; $i < $layout->user()->eirUser()->mac_slots - count($layout->user()->macAddresses()); $i++)
								<div class="form-group{{ $errors->has('new_mac_address_'.$i) ? ' has-error' : '' }}">
									<label class="col-md-4 control-label">MAC cím</label>

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
											MAC címek beállítása
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
@endsection
