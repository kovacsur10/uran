@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('my_data') }}</div>
                <div class="panel-body">
					<div class="well well-sm">{{ $layout->language('name') }}: {{ $layout->user()->user()->name }}</div>
					<div class="well well-sm">{{ $layout->language('username') }}: {{ $layout->user()->user()->username }}</div>
					<div class="well well-sm">{{ $layout->language('email_address') }}: {{$layout->user()->user()->email}}</div>
					<div class="well well-sm">{{ $layout->language('registration_date') }}: {{ str_replace("-", ". ", str_replace(" ", ". ", $layout->user()->user()->registration_date)) }}</div>
					<div class="well well-sm">{{ $layout->language('address') }}: {{ $country }}, {{ $layout->user()->user()->city }} megye, {{ $layout->user()->user()->postalcode }} {{ $layout->user()->user()->city }}, {{ $layout->user()->user()->address }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
