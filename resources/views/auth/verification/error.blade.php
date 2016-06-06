@extends('layouts.app', ['logged' => false, 'user' => null])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Regisztráció megerősítése</div>
                <div class="panel-body">
                    Hiba lépett fel a megerősítésnél! Probléma esetén keresse fel az oldal üzemeltetőjét.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
