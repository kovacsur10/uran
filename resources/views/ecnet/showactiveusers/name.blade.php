@extends('ecnet.showactiveusers.layout', ['data' => $layout])

@section('listcontent')
				
@if($layout->user()->permitted('ecnet_user_handling'))
	<ul>
	@foreach($layout->user()->ecnetUsers(0, 10000) as $ecnetUser)
		@if($ecnetUser->valid() > Carbon\Carbon::now()->toDateTimeString())
			<li>{{$ecnetUser->name()}}</li>
		@endif
	@endforeach
	</ul>
@endif

@endsection
