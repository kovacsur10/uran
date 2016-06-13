@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Siker</div>
                <div class="panel-body">
					<p>{{ $message }}</p>
					<p><a href="{{ url($url) }}">Vissza az előző oldalra</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
