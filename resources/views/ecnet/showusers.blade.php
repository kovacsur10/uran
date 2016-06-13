@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Admin panel - felhasználók kezelése</div>
                <div class="panel-body">				
<<<<<<< HEAD
					@if($user->permitted('ecnet_user_handling'))
<<<<<<< HEAD
						@foreach($user->eirUsers() as $eirUser)
<<<<<<< HEAD
						<div class="panel panel-default {{ $eirUser->valid_time > Carbon\Carbon::now()->toDateTimeString() ? 'panel-success' : 'panel-danger' }}">
							<div class="panel-heading">{{ $eirUser->name }} - {{ $eirUser->username }} - id: {{ $eirUser->id }}</div>
=======
=======
					@if($layout->user()->permitted('ecnet_user_handling'))
>>>>>>> d8c9872... LayoutData data handling was added to the project.
						<div class="panel panel-default">
							<div class="panel-heading">Felhasználó keresése</div>
>>>>>>> 39d2186... ECNET userhandling: filter is working.
							<div class="panel-body">
								<form class="form-horizontal" role="form" method="POST" action="{{ url('/ecnet/users') }}">
									{!! csrf_field() !!}
									
									<div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
										<label class="col-md-4 control-label">Felhasználói név</label>

										<div class="col-md-6">
											<input type="date" class="form-control" name="username" value="{{ $layout->user()->getUsernameFilter() }}">

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
											<input type="date" class="form-control" name="name" value="{{ $layout->user()->getNameFilter() }}">

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
								<div class="row">
									<div class="col-md-2 col-md-offset-2">
										<a href="{{ url('ecnet/users/listactives/name') }}" class="btn btn-primary" role="button">Csak nevek</a>
									</div>
									<div class="col-md-2 col-md-offset-1">
										<a href="{{ url('ecnet/users/listactives/username') }}" class="btn btn-primary" role="button">Csak felh. nevek</a>
									</div>
									<div class="col-md-2 col-md-offset-1">
										<a href="{{ url('ecnet/users/listactives/both') }}" class="btn btn-primary" role="button">Mindkettő adat</a>
									</div>
								</div>
							</div>
						</div>
					
						<ul class="list-inline">
							<li><a href="{{ url('ecnet/users/10/'.$firstUser) }}" class="btn btn-primary" role="button">10</a></li>
							<li><a href="{{ url('ecnet/users/20/'.$firstUser) }}" class="btn btn-primary" role="button">20</a></li>
							<li><a href="{{ url('ecnet/users/50/'.$firstUser) }}" class="btn btn-primary" role="button">50</a></li>
							<li><a href="{{ url('ecnet/users/100/'.$firstUser) }}" class="btn btn-primary" role="button">100</a></li>
							<li><a href="{{ url('ecnet/users/500/'.$firstUser) }}" class="btn btn-primary" role="button">500</a></li>
						</ul>
					
						<nav>
							<ul class="pager">
								@if(0 < $firstUser)
									<li class="previous"><a href="{{ url('ecnet/users/'.$usersToShow.'/'.($firstUser - $usersToShow >= 0 ? $firstUser - $usersToShow : 0)) }}">Előző oldal</a></li>
								@else
									<li class="previous disabled"><a href="#">Előző oldal</a></li>
								@endif
								@if($firstUser+$usersToShow < count($layout->user()->eirUsers()))
									<li class="next"><a href="{{ url('ecnet/users/'.$usersToShow.'/'.($firstUser+$usersToShow)) }}">Következő oldal</a></li>
								@else
									<li class="next disabled"><a href="#">Következő oldal</a></li>
								@endif
							</ul>
						</nav>
					
						@if($layout->user()->eirUsers($firstUser, $usersToShow) != null)
							@foreach($layout->user()->eirUsers($firstUser, $usersToShow) as $eirUser)
							<div class="panel panel-default {{ $eirUser->valid_time > Carbon\Carbon::now()->toDateTimeString() ? 'panel-success' : 'panel-danger' }}">
								<div class="panel-heading">{{ $eirUser->name }} - {{ $eirUser->username }} - #{{ $eirUser->id }}</div>
								<div class="panel-body">
									<p>Egyenleg: {{ $eirUser->money }} <i class="fa fa-btn fa-money"></i></p>
									<p>Érvényességi idő: {{ $eirUser->valid_time }} <i class="fa fa-btn {{ $eirUser->valid_time > Carbon\Carbon::now()->toDateTimeString() ? 'fa-calendar-check-o' : 'fa-calendar-times-o' }}"></i></p>
									<p>MAC slotok száma: {{ $eirUser->mac_slots }} darab
									@if($layout->user()->hasMACSlotOrder($eirUser->id))
										- <i style="color:gold;" class="fa fa-btn fa-flash"></i><a href="{{ url('ecnet/order') }}">SLOT kérelem</a> <i style="color:gold;" class="fa fa-btn fa-flash"></i>
									@endif
									@if(count($layout->user()->macAddresses($eirUser->id)) < $eirUser->mac_slots)
										- <span style="color:red;">KEVESEBB SLOTOT HASZNÁL (diff: {{ $eirUser->mac_slots - count($layout->user()->macAddresses($eirUser->id)) }})</span>
									@endif
									@if($eirUser->mac_slots != 0)						
										<div class="dropdown">
											<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Regisztrált MAC címek
											<span class="caret"></span></button>
											<ul class="dropdown-menu">
											@foreach($layout->user()->macAddresses($eirUser->id) as $macAddress)
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
						
						<nav>
							<ul class="pager">
								@if(0 < $firstUser)
									<li class="previous"><a href="{{ url('ecnet/users/'.$usersToShow.'/'.($firstUser - $usersToShow >= 0 ? $firstUser - $usersToShow : 0)) }}">Előző oldal</a></li>
								@else
									<li class="previous disabled"><a href="#">Előző oldal</a></li>
								@endif
								@if($firstUser+$usersToShow < count($layout->user()->eirUsers()))
									<li class="next"><a href="{{ url('ecnet/users/'.$usersToShow.'/'.($firstUser+$usersToShow)) }}">Következő oldal</a></li>
								@else
									<li class="next disabled"><a href="#">Következő oldal</a></li>
								@endif
							</ul>
						</nav>
						
						<ul class="list-inline">
							<li><a href="{{ url('ecnet/users/10/'.$firstUser) }}" class="btn btn-primary" role="button">10</a></li>
							<li><a href="{{ url('ecnet/users/20/'.$firstUser) }}" class="btn btn-primary" role="button">20</a></li>
							<li><a href="{{ url('ecnet/users/50/'.$firstUser) }}" class="btn btn-primary" role="button">50</a></li>
							<li><a href="{{ url('ecnet/users/100/'.$firstUser) }}" class="btn btn-primary" role="button">100</a></li>
							<li><a href="{{ url('ecnet/users/500/'.$firstUser) }}" class="btn btn-primary" role="button">500</a></li>
						</ul>
						
					@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
