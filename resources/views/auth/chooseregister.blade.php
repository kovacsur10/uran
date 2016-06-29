@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('registration') }}</div>
                <div class="panel-body">
                    <a href="{{ url('register/collegist') }}">
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
