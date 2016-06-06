@extends('layouts.app', ['logged' => $logged, 'user' => $user])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Adataim</div>
                <div class="panel-body">
					<div class="well well-sm">Név: {{ $target->user()->name }}</div>
					<div class="well well-sm">Felhasználói azonosító: {{ $target->user()->username }}</div>
					<div class="well well-sm">Regisztráció időpontja: {{ str_replace("-", ". ", str_replace(" ", ". ", $target->user()->registration_date)) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
