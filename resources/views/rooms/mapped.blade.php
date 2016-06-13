@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Szobabeosztás</div>
                <div class="panel-body">
					<img src="{{ url('images/level'.$level.'.png') }}" alt="Collegium térkép" usemap="#levelmap">
					@if($layout->user()->permitted('rooms_assign'))
						<map name="levelmap">
							<area shape="rect" coords="26,131,114,181" href="{{ url('rooms/room/322') }}" alt="322">
							<area shape="rect" coords="26,80,114,130" href="{{ url('rooms/room/323') }}" alt="323">
							<area shape="rect" coords="26,21,114,79" href="{{ url('rooms/room/324') }}" alt="324">
							<area shape="rect" coords="166,21,254,79" href="{{ url('rooms/room/325') }}" alt="325">
						</map>
					@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
