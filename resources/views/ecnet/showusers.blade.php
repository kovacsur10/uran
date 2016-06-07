@extends('layouts.app', ['logged' => $logged, 'user' => $user])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Admin panel - felhasználók kezelése</div>
                <div class="panel-body">				
					@if($user->permitted('ecnet_user_handling'))
						@foreach($user->eirUsers() as $eirUser)
<<<<<<< HEAD
						<div class="panel panel-default {{ $eirUser->valid_time > Carbon\Carbon::now()->toDateTimeString() ? 'panel-success' : 'panel-danger' }}">
							<div class="panel-heading">{{ $eirUser->name }} - {{ $eirUser->username }} - id: {{ $eirUser->id }}</div>
							<div class="panel-body">
								<p>Egyenleg: {{ $eirUser->money }} <i class="fa fa-btn fa-money"></i></p>
								<p>Érvényességi idő: {{ $eirUser->valid_time }} <i class="fa fa-btn {{ $eirUser->valid_time > Carbon\Carbon::now()->toDateTimeString() ? 'fa-calendar-check-o' : 'fa-calendar-times-o' }}"></i></p>
								<p>MAC slotok száma: {{ $eirUser->mac_slots }} darab
								@if($user->hasMACSlotOrder($eirUser->id))
									- <i style="color:gold;" class="fa fa-btn fa-flash"></i>SLOT kérelem <i style="color:gold;" class="fa fa-btn fa-flash"></i>
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
=======
						<div class="panel panel-default {{ $eirUser->valid_time > Carbon\Carbon::now()->toDateTimeString() ? "panel-success" : "panel-danger" }}">
							<div class="panel-heading">{{ $eirUser->name }} - {{ $eirUser->username }} - id: {{ $eirUser->id }}</div>
							<div class="panel-body">
								<p>Egyenleg: {{ $eirUser->money }} <i class="fa fa-btn fa-money"></i></p>
								<p>Érvényességi idő: {{ $eirUser->valid_time }} <i class="fa fa-btn {{ $eirUser->valid_time > Carbon\Carbon::now()->toDateTimeString() ? "fa-calendar-check-o" : "fa-calendar-times-o" }}"></i></p>
								<p>MAC slotok száma: {{ $eirUser->mac_slots }} darab</p>
>>>>>>> 5ea7d77... Eir user administration was added.
							</div>
							<div class="panel-footer">Panel Footer</div>
						</div>
						@endforeach
					@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
