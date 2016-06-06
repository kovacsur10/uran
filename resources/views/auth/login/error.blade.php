@extends('layouts.app', ['logged' => false, 'user' => null])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Bejelentkezés</div>
                <div class="panel-body">
                    Sikertelen bejelentkezés!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
