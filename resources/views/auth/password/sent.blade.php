@extends('layouts.app', ['logged' => $logged, 'user' => $user])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Jelszó módosítása</div>

                <div class="panel-body">
                    <p>A további teendőkről elküldünk egy e-mailt a megadott címre.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
