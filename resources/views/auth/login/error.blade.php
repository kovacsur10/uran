@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('logging_in') }}</div>
                <div class="panel-body">
                    {{ $layout->language('unsuccessful_login') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
