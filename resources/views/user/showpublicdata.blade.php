@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('my_data') }}</div>
                <div class="panel-body">
					<div class="well well-sm">{{ $layout->language('name') }}: {{ $target->user()->name() }}</div>
					<div class="well well-sm">{{ $layout->language('registration_date') }}: {{ $layout->formatDate($target->user()->registrationDate()) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
