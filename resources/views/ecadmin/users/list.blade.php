@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('ecadmin.users_list')</div>
                <div class="panel-body">
				@if($layout->user()->permitted('user_handling'))
					@foreach($layout->user()->users(0, 3) as $user)
					<div class="panel panel-default">
						<div class="panel-heading"><a href="{{ url('/ecadmin/user/show/'.$user->id()) }}">{{ $user->name() }} - {{ $user->username() }} - #{{ $user->id() }}</a></div>
						<div class="panel-body">
							<span class="col-xs-12">
								Státusz: {{ $user->status()->statusName() }}
							</span>
							<span class="col-xs-12">
								Neptun: {{ $user->collegistData() !== null ? $user->collegistData()->neptun() : __('TODO') }}
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
