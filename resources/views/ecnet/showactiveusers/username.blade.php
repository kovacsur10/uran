@extends('ecnet.showactiveusers.layout', ['data' => $layout])

@section('listcontent')
				
@if($layout->user()->permitted('ecnet_user_handling'))
	<ul>
	@foreach($layout->user()->ecnetUsers(0, 0) as $ecnetUser)
		@if($ecnetUser->valid_time > Carbon\Carbon::now()->toDateTimeString())
			<li>{{$ecnetUser->username}}</li>
		@endif
	@endforeach
	</ul>
@endif

@endsection
