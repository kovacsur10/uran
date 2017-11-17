@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('error') }}</div>
                <div class="panel-body">
					<p>@lang($message_indicator)</p>
					<p><a href="{{ url($url) }}">{{ $layout->language('back_to_last_page') }}</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
