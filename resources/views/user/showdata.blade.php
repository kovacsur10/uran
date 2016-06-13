@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Adataim</div>
                <div class="panel-body">
					<div class="well well-sm">Név: {{ $layout->user()->user()->name }}</div>
					<div class="well well-sm">Felhasználói azonosító: {{ $layout->user()->user()->username }}</div>
					<div class="well well-sm">E-mail cím: {{$layout->user()->user()->email}}</div>
					<div class="well well-sm">Regisztráció időpontja: {{ str_replace("-", ". ", str_replace(" ", ". ", $layout->user()->user()->registration_date)) }}</div>
					<div class="well well-sm">Lakcím: {{ $country }}, {{ $layout->user()->user()->city }} megye, {{ $layout->user()->user()->postalcode }} {{ $layout->user()->user()->city }}, {{ $layout->user()->user()->address }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
