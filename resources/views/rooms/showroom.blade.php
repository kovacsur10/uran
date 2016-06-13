@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Szobabeosztás</div>
                <div class="panel-body">
					<a href="{{ url('rooms/map/'.substr(strval($room),0,1)) }}">Vissza a szobák listájához</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
