@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Jelszó módosítása</div>

                <div class="panel-body">
                    <p>Egy hiba miatt nem sikerült módosítani a jelszót!</p>
					<p><a href="{{ url('/password/reset') }}">Próbálkozz újra!</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
