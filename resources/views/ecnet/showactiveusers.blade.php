@extends('layouts.app', ['logged' => $logged, 'user' => $user])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Admin panel - felhasználók kezelése</div>
                <div class="panel-body">				
					@if($user->permitted('ecnet_user_handling'))
						<ul>
						@foreach($user->eirUsers(0, 0) as $eirUser)
							@if($eirUser->valid_time > Carbon\Carbon::now()->toDateTimeString())
								<li>{{$eirUser->username}} ({{$eirUser->name}})</li>
							@endif
						@endforeach
						</ul>
					@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
