@extends('layouts.app', ['logged' => false, 'user' => null])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Regisztráció</div>
                <div class="panel-body">
                    Kérjük erősítse meg e-mail címét, ehhez kiküldtünk egy levelet a megadott postafiókba.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
