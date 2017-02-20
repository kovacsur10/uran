@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('error') }}</div>
                <div class="panel-body">
                    <p>{{ $layout->language('error_you_should_not_wait_too_long_description') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
