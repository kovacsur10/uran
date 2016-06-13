@extends('ecnet.showactiveusers.layout', ['data' => $layout])

@section('listcontent')
				
@if($layout->user()->permitted('ecnet_user_handling'))
	<ul>
	@foreach($layout->user()->eirUsers(0, 0) as $eirUser)
		@if($eirUser->valid_time > Carbon\Carbon::now()->toDateTimeString())
			<li>{{$eirUser->username}}</li>
		@endif
	@endforeach
	</ul>
@endif

@endsection
