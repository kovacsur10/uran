@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><a href="{{ url('/admin/permissions') }}">Jogok kezelése</a></div>
                <div class="panel-body">
				@if($layout->user()->permitted('permission_admin'))
					<div class="panel panel-default">
						<div class="panel-heading">{{ $permission }}</div>
						<div class="panel-body">
							<ul>
								@foreach($users as $user)
									<li>{{ $user->name }} ({{ $user->username }})</li>
								@endforeach
							</ul>
						</div>
					</div>
				@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
