@extends('layouts.app', ['logged' => $logged, 'user' => $user])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Adataim</div>
                <div class="panel-body">
					<div class="well well-sm">Név: {{ $user->user()->name }}</div>
					<div class="well well-sm">Felhasználói azonosító: {{ $user->user()->username }}</div>
					<div class="well well-sm">E-mail cím: {{$user->user()->email}}</div>
					<div class="well well-sm">Regisztráció időpontja: {{ str_replace("-", ". ", str_replace(" ", ". ", $user->user()->registration_date)) }}</div>
					<div class="well well-sm">Lakcím: {{ $country }}, {{ $user->user()->city }} megye, {{ $user->user()->postalcode }} {{ $user->user()->city }}, {{ $user->user()->address }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
