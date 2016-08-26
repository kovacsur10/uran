@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('users_list') }}</div>
                <div class="panel-body">
				@if($layout->user()->permitted('user_handling'))
					@foreach($layout->user()->usersAllData(0, 3) as $user)
					<div class="panel panel-default">
						<div class="panel-heading"><a href="{{ url('/ecadmin/user/show/'.$user->id) }}">{{ $user->name }} - {{ $user->username }} - #{{ $user->id }}</a></div>
						<div class="panel-body">
							<span class="col-xs-12">
								Státusz: {{ $user->status_name }}
							</span>
							<span class="col-xs-12">
								Neptun: {{ $user->neptun }}
							</span>
						</div>
					</div>
					@endforeach
				@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
