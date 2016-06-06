@extends('layouts.app', ['logged' => $logged, 'user' => $user])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Hiba</div>
                <div class="panel-body">
                    <p>Ajajj, valami baj van!</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
