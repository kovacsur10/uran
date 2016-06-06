@extends('layouts.app', ['logged' => $logged, 'user' => $user])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
				@if($logged)
                    You are logged in!
				@else
					Please log in!
				@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
