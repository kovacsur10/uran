@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Szobabeosztás</div>
                <div class="panel-body">
					<div style="position:relative;width:964px;height:527px;background-image: url('{{ url('images/level'.$level.'.png') }}')">
					@if($layout->user()->permitted('rooms_assign'))
						@if($level == 3)
							<?php
								$rooms = [
									'322' => 'left:26px;top:131px;width:88px;height:50px;',
									'323' => 'left:26px;top:80px;width:88px;height:50px;',
									'324' => 'left:26px;top:21px;width:88px;height:58px;',
									'325' => 'left:166px;top:21px;width:88px;height:58px;',
								];
							?>
						@elseif($level == 2)
							<?php
								$rooms = [
									'231' => 'left:26px;top:131px;width:88px;height:50px;',
									'232' => 'left:26px;top:80px;width:88px;height:50px;',
									'233' => 'left:26px;top:21px;width:88px;height:58px;',
									'234' => 'left:166px;top:21px;width:88px;height:58px;',
								];
							?>
						@endif
						@foreach($rooms as $roomNumber => $roomPosition)
							<a href="{{ url('rooms/room/'.$roomNumber) }}">
								<div data-toggle="tooltip" data-html="true" title="
								@if($layout->room()->getResidents($roomNumber) == null)
									Senki sem lakik a szobában!
								@else
									@foreach($layout->room()->getResidents($roomNumber) as $resident)
										{{ $resident->name }}<br>
									@endforeach
								@endif
								" data-placement="right"  style="position:absolute;{{ $roomPosition }}}">
								</div>
							</a>
						@endforeach
					@endif
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
