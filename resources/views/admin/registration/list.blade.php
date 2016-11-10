@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('accept_user_registration') }}</div>
                <div class="panel-body">
				@if($layout->user()->permitted('accept_user_registration'))
					<div class="panel panel-default">
						<div class="panel-heading">{{ $layout->language('users_list') }}</div>
						<div class="panel-body">
							<div class="list-group">
								@foreach($layout->registrations()->get() as $regUser)
									<a class="list-group-item list-group-item-{{ $regUser->verified ? 'success' : 'danger' }}" href="{{ url('admin/registration/show/'.$regUser->id) }}">{{ $regUser->name }}</a>
								@endforeach
							</div>
						</div>
					</div>
				@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
