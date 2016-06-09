@extends('layouts.app', ['logged' => $logged, 'user' => $user])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Admin panel - felhasználók kezelése</div>
                <div class="panel-body">				
					@if($user->permitted('ecnet_user_handling'))
<<<<<<< HEAD
						@foreach($user->eirUsers() as $eirUser)
<<<<<<< HEAD
						<div class="panel panel-default {{ $eirUser->valid_time > Carbon\Carbon::now()->toDateTimeString() ? 'panel-success' : 'panel-danger' }}">
							<div class="panel-heading">{{ $eirUser->name }} - {{ $eirUser->username }} - id: {{ $eirUser->id }}</div>
=======
						<div class="panel panel-default">
							<div class="panel-heading">Felhasználó keresése</div>
>>>>>>> 39d2186... ECNET userhandling: filter is working.
							<div class="panel-body">
								<form class="form-horizontal" role="form" method="POST" action="{{ url('/ecnet/users') }}">
									{!! csrf_field() !!}
									
									<div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
										<label class="col-md-4 control-label">Felhasználói név</label>

										<div class="col-md-6">
											<input type="date" class="form-control" name="username" value="{{ $user->getUsernameFilter() }}">

											@if ($errors->has('username'))
												<span class="help-block">
													<strong>{{ $errors->first('username') }}</strong>
												</span>
											@endif
										</div>
									</div>
																	
									<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
										<label class="col-md-4 control-label">Név</label>

										<div class="col-md-6">
											<input type="date" class="form-control" name="name" value="{{ $user->getNameFilter() }}">

											@if ($errors->has('name'))
												<span class="help-block">
													<strong>{{ $errors->first('name') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Keres
											</button>
											<a href="{{ url('ecnet/users/resetfilter') }}" class="btn btn-danger" role="button">Szűrés törlése</a>
										</div>
									</div>
<<<<<<< HEAD
								@endif
								</p>
=======
						<div class="panel panel-default {{ $eirUser->valid_time > Carbon\Carbon::now()->toDateTimeString() ? "panel-success" : "panel-danger" }}">
							<div class="panel-heading">{{ $eirUser->name }} - {{ $eirUser->username }} - id: {{ $eirUser->id }}</div>
							<div class="panel-body">
								<p>Egyenleg: {{ $eirUser->money }} <i class="fa fa-btn fa-money"></i></p>
								<p>Érvényességi idő: {{ $eirUser->valid_time }} <i class="fa fa-btn {{ $eirUser->valid_time > Carbon\Carbon::now()->toDateTimeString() ? "fa-calendar-check-o" : "fa-calendar-times-o" }}"></i></p>
								<p>MAC slotok száma: {{ $eirUser->mac_slots }} darab</p>
>>>>>>> 5ea7d77... Eir user administration was added.
=======
								</form>
>>>>>>> 39d2186... ECNET userhandling: filter is working.
							</div>
						</div>
						
						<div class="panel panel-default">
							<div class="panel-heading">Internettel rendelkezők listázása</div>
							<div class="panel-body">
								<form class="form-horizontal" role="form" method="POST" action="{{ url('/ecnet/users/listactives') }}">
									{!! csrf_field() !!}
									
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												Listáz
											</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					
						@if($user->eirUsers($firstUser, $usersToShow) != null)
							@foreach($user->eirUsers($firstUser, $usersToShow) as $eirUser)
							<div class="panel panel-default {{ $eirUser->valid_time > Carbon\Carbon::now()->toDateTimeString() ? 'panel-success' : 'panel-danger' }}">
								<div class="panel-heading">{{ $eirUser->name }} - {{ $eirUser->username }} - #{{ $eirUser->id }}</div>
								<div class="panel-body">
									<p>Egyenleg: {{ $eirUser->money }} <i class="fa fa-btn fa-money"></i></p>
									<p>Érvényességi idő: {{ $eirUser->valid_time }} <i class="fa fa-btn {{ $eirUser->valid_time > Carbon\Carbon::now()->toDateTimeString() ? 'fa-calendar-check-o' : 'fa-calendar-times-o' }}"></i></p>
									<p>MAC slotok száma: {{ $eirUser->mac_slots }} darab
									@if($user->hasMACSlotOrder($eirUser->id))
										- <i style="color:gold;" class="fa fa-btn fa-flash"></i><a href="{{ url('ecnet/order') }}">SLOT kérelem</a> <i style="color:gold;" class="fa fa-btn fa-flash"></i>
									@endif
									@if(count($user->macAddresses($eirUser->id)) < $eirUser->mac_slots)
										- <span style="color:red;">KEVESEBB SLOTOT HASZNÁL (diff: {{ $eirUser->mac_slots - count($user->macAddresses($eirUser->id)) }})</span>
									@endif
									@if($eirUser->mac_slots != 0)						
										<div class="dropdown">
											<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Regisztrált MAC címek
											<span class="caret"></span></button>
											<ul class="dropdown-menu">
											@foreach($user->macAddresses($eirUser->id) as $macAddress)
												<li>{{ $macAddress->mac_address }}</li>
											@endforeach
											</ul>
										</div>
									@endif
									</p>
								</div>
							</div>
							@endforeach
						@endif
					@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
