@extends('ecnet.showactiveusers.layout', ['logged' => $logged, 'user' => $user])

@section('listcontent')
				
@if($user->permitted('ecnet_user_handling'))
	<ul>
	@foreach($user->eirUsers(0, 0) as $eirUser)
		@if($eirUser->valid_time > Carbon\Carbon::now()->toDateTimeString())
			<li>{{$eirUser->username}}</li>
		@endif
	@endforeach
	</ul>
@endif

@endsection
