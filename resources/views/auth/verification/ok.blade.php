@extends('layouts.app', ['logged' => false, 'user' => null])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Regisztráció megerősítése</div>
                <div class="panel-body">
                    Sikeresen megerősítette az e-mail címét! A regisztráció elfogadásakor e-mailben értesítjük!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
