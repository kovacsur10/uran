@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Szobabeosztás</div>
                <div class="panel-body">
					@if($layout->user()->permitted('rooms_observe_assignment'))
						<div style="position:relative;width:964px;height:527px;background-image: url('{{ url('images/level'.$level.'.png') }}')">
							@if($layout->user()->permitted('rooms_assign'))
								@if($level == 3)
									<?php
										$rooms = [
											'304' => 'left:710px;top:131px;width:88px;height:50px;',
											'305' => 'left:710px;top:80px;width:88px;height:50px;',
											'306' => 'left:710px;top:21px;width:88px;height:58px;',
											'307' => 'left:850px;top:21px;width:88px;height:58px;',
											'308' => 'left:850px;top:80px;width:88px;height:50px;',
											'309' => 'left:850px;top:131px;width:88px;height:50px;',
											'310' => 'left:850px;top:182px;width:88px;height:58px;',
											'311' => 'left:850px;top:241px;width:88px;height:50px;',
											'312' => 'left:850px;top:292px;width:88px;height:59px;',
											'313' => 'left:871px;top:396px;width:67px;height:43px;',
											'314' => 'left:850px;top:440px;width:88px;height:66px;',
											'315' => 'left:799px;top:426px;width:50px;height:80px;',
											'316' => 'left:115px;top:426px;width:50px;height:80px;',
											'317' => 'left:26px;top:440px;width:88px;height:66px;',
											'318' => 'left:26px;top:396px;width:67px;height:43px;',
											'319' => 'left:26px;top:292px;width:88px;height:59px;',
											'320' => 'left:26px;top:241px;width:88px;height:50px;',
											'321' => 'left:26px;top:182px;width:88px;height:58px;',
											'322' => 'left:26px;top:131px;width:88px;height:50px;',
											'323' => 'left:26px;top:80px;width:88px;height:50px;',
											'324' => 'left:26px;top:21px;width:88px;height:58px;',
											'325' => 'left:166px;top:21px;width:88px;height:58px;',
											'326' => 'left:166px;top:80px;width:88px;height:50px;',
											'327' => 'left:166px;top:131px;width:88px;height:50px;',
										];
									?>
								@elseif($level == 2)
									<?php
										$rooms = [
											'204' => 'left:710px;top:80px;width:88px;height:58px;',
											'205' => 'left:710px;top:21px;width:228px;height:58px;',
											'206' => 'left:850px;top:80px;width:88px;height:58px;',
											'207' => 'left:850px;top:139px;width:88px;height:50px;',
											'208' => 'left:850px;top:190px;width:88px;height:50px;',
											'209' => 'left:850px;top:241px;width:88px;height:59px;',
											'210' => 'left:850px;top:301px;width:88px;height:50px;',
											'211' => 'left:850px;top:396px;width:88px;height:43px;',
											'212' => 'left:850px;top:440px;width:88px;height:66px;',
											'213' => 'left:799px;top:426px;width:50px;height:80px;',
											'214' => 'left:748px;top:396px;width:50px;height:82px;',
											'215' => 'left:704px;top:396px;width:43px;height:82px;',
											'216' => 'left:660px;top:396px;width:43px;height:82px;',
											'217' => 'left:616px;top:396px;width:43px;height:82px;',
											'218' => 'left:565px;top:396px;width:50px;height:82px;',
											'220' => 'left:349px;top:396px;width:50px;height:82px;',
											'221' => 'left:305px;top:396px;width:43px;height:82px;',
											'222' => 'left:261px;top:396px;width:43px;height:82px;',
											'223' => 'left:217px;top:396px;width:43px;height:82px;',
											'224' => 'left:166px;top:396px;width:50px;height:82px;',
											'225' => 'left:115px;top:426px;width:50px;height:80px;',
											'226' => 'left:26px;top:440px;width:88px;height:66px;',
											'227' => 'left:26px;top:396px;width:88px;height:43px;',
											'228' => 'left:26px;top:292px;width:88px;height:59px;',
											'229' => 'left:26px;top:241px;width:88px;height:50px;',
											'230' => 'left:26px;top:182px;width:88px;height:58px;',
											'231' => 'left:26px;top:131px;width:88px;height:50px;',
											'232' => 'left:26px;top:80px;width:88px;height:50px;',
											'233' => 'left:26px;top:21px;width:88px;height:58px;',
											'234' => 'left:166px;top:21px;width:88px;height:58px;',
											'235' => 'left:166px;top:80px;width:88px;height:50px;',
										];
									?>
								@elseif($level == -1)
									<?php
										$rooms = [
											'010' => 'left:685px;top:396px;width:113px;height:82px;',
										];
									?>
								@else
									<?php $rooms = []; ?>
								@endif
								@foreach($rooms as $roomNumber => $roomPosition)
									<a href="{{ url('rooms/room/'.$roomNumber) }}">
										<div data-toggle="tooltip" data-html="true" title="
										@if($layout->room()->getResidents($roomNumber) !== null)
											@foreach($layout->room()->getResidents($roomNumber) as $resident)
												{{ $resident->name }}<br>
											@endforeach
										@endif
										@for($i = 0; $i < $layout->room()->getFreePlaceCount($roomNumber); $i++)
											Szabad hely<br>
										@endfor
										" data-placement="{{ substr($roomNumber,1,2) > 15 ? 'right' : 'left' }}"  style="position:absolute;{{ $roomPosition }}}">
										</div>
									</a>
								@endforeach
							@endif
							<div style="text-align:right;position:absolute;left:314px;top:233px;width:100px;height:80px;">
								@if($level == -2)
									Pince
								@else
									<a href="{{ url('rooms/map/-2') }}">Pince</a>
								@endif
								<br>
								@if($level == -1)
									Alagsor
								@else
									<a href="{{ url('rooms/map/-1') }}">Alagsor</a>
								@endif
								<br>
								@if($level == 0)
									Földszint
								@else
									<a href="{{ url('rooms/map/0') }}">Földszint</a>
								@endif
							</div>
							<div style="position:absolute;left:550px;top:233px;width:100px;height:80px;">
								@if($level == 1)
									1. emelet
								@else
									<a href="{{ url('rooms/map/1') }}">1. emelet</a>
								@endif
								<br>
								@if($level == 2)
									2. emelet
								@else
									<a href="{{ url('rooms/map/2') }}">2. emelet</a>
								@endif
								<br>
								@if($level == 3)
									3. emelet
								@else
									<a href="{{ url('rooms/map/3') }}">3. emelet</a>
								@endif
							</div>
						</div>
						
						@if($layout->user()->permitted('rooms_assign'))
							<div class="panel panel-default">
								<div class="panel-heading">Admin panel - Szabad helyek</div>
								<div class="panel-body">
									<?php
										$i = 0;
										$places = $layout->room()->getFreePlaces();
									?>
									<ul class="col-md-2 col-md-offset-2 list-group">
									@for(; $i < count($places) / 3; $i++)
										<li class="list-group-item"><span class="badge"> {{ $places[$i][1] }}</span> {{ $places[$i][0] }}</li>
									@endfor
									</ul>
									<ul class="col-md-2 col-md-offset-1 list-group">
									@for(; $i < 2* count($places) / 3; $i++)
										<li class="list-group-item"><span class="badge"> {{ $places[$i][1] }}</span> {{ $places[$i][0] }}</li>
									@endfor
									</ul>
									<ul class="col-md-2 col-md-offset-1 list-group">
									@for(; $i < count($places); $i++)
										<li class="list-group-item"><span class="badge"> {{ $places[$i][1] }}</span> {{ $places[$i][0] }}</li>
									@endfor
									</ul>
								</div>
							</div>
						@endif
					@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
