@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('registration') }}</div>
                <div class="panel-body">
                	<div class="alert alert-info">
                		<h4 class="alert-heading">{{ $layout->language('registration_todos') }}</h4>
                		<p>{{ $layout->language('registration_users_have_to_fill_in_description') }}</p>
						<hr>
						<p class="mb-0">{{ $layout->language('registration_users_have_to_verify_email_description') }}</p>
						<hr>
						<p class="mb-0">{{ $layout->language('registration_admins_have_to_accept_description') }}</p>
                	</div>
                    <a href="{{ url('register/member') }}">
						<div class="jumbotron">
							<p><b>{{ $layout->language('registration_for_collegists') }}</b></p>
						</div>
					</a>
					<a href="{{ url('register/guest') }}">
						<div class="jumbotron">
							<p><b>{{ $layout->language('registration_for_guests') }}</b></p>
						</div>
					</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
