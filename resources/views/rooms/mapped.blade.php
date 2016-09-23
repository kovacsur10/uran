@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('room_assignment') }}</div>
                <div class="panel-body">
					@if($layout->user()->permitted('rooms_assign'))
						<div class="panel panel-default">
							<div class="panel-heading">{{ $layout->language('admin_panel') }} - {{ $layout->language('choose_table') }}</div>
							<div class="panel-body">
								<div class="well">
									{{ $layout->language('current_rooms_active_table') }}: {{ $layout->room()->activeTable() }}
								</div>
								<form class="form-horizontal" role="form" method="POST" action="{{ url('/rooms/tables/select') }}">
									{!! csrf_field() !!}

									<div class="form-group{{ $errors->has('table_version') ? ' has-error' : '' }}">
										<label  class="col-md-4 control-label" for="table_version_select">{{ $layout->language('table_version') }}</label>
										<div class="col-md-6">
											<select class="form-control"  name="table_version"  id="table_version_select" required="true">
												@foreach($layout->room()->getTables() as $table)
													<option value="{{ $table->id }}" {{ old('table_version') == $table->id ? 'selected' : '' }}>{{ $table->table_name }}</option>
												@endforeach
											</select>
											
											@if ($errors->has('table_version'))
												<span class="help-block">
													<strong>{{ $errors->first('table_version') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												<i class="fa fa-btn fa-user"></i>{{ $layout->language('activate') }}
											</button>
										</div>
									</div>
								</form>
							</div>
						</div>
						<div class="panel panel-default">
							<div class="panel-heading">{{ $layout->language('admin_panel') }} - {{ $layout->language('delete_table') }}</div>
							<div class="panel-body">
								<form class="form-horizontal" role="form" method="POST" action="{{ url('/rooms/tables/remove') }}">
									{!! csrf_field() !!}

									<div class="form-group{{ $errors->has('table_version') ? ' has-error' : '' }}">
										<label  class="col-md-4 control-label" for="table_version_select">{{ $layout->language('table_version') }}</label>
										<div class="col-md-6">
											<select class="form-control"  name="table_version"  id="table_version_select" required="true">
												@foreach($layout->room()->getTables() as $table)
													<option value="{{ $table->id }}" {{ old('table_version') == $table->id ? 'selected' : '' }}>{{ $table->table_name }}</option>
												@endforeach
											</select>
											
											@if ($errors->has('table_version'))
												<span class="help-block">
													<strong>{{ $errors->first('table_version') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												<i class="fa fa-btn fa-user"></i>{{ $layout->language('delete_table') }}
											</button>
										</div>
									</div>
									
									<div class="alert alert-warning">
										{{ $layout->language('last_table_cannot_be_deleted_description') }}
									</div>
								</form>
							</div>
						</div>
						<div class="panel panel-default">
							<div class="panel-heading">{{ $layout->language('admin_panel') }} - {{ $layout->language('add_new_table') }}</div>
							<div class="panel-body">
								<form class="form-horizontal" role="form" method="POST" action="{{ url('/rooms/tables/add') }}">
									{!! csrf_field() !!}

									<div class="form-group{{ $errors->has('newTableName') ? ' has-error' : '' }}">
			                            <label class="col-md-4 control-label">{{ $layout->language('new_table_identifier') }}</label>
			
			                            <div class="col-md-6">
			                                <input type="text" class="form-control" id="newTableName" name="newTableName" value="{{ old('newTableName') }}" required="true">
			
			                                @if ($errors->has('newTableName'))
			                                    <span class="help-block">
			                                        <strong>{{ $errors->first('newTableName') }}</strong>
			                                    </span>
			                                @endif
			                            </div>
			                        </div>
									
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">
												<i class="fa fa-btn fa-user"></i>{{ $layout->language('add') }}
											</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					@endif
					@if($layout->user()->permitted('rooms_observe_assignment'))
						<div style="position:relative;width:964px;height:527px;background-image: url('{{ url('images/level'.$level.'.png') }}')">
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
										'205a' => 'left:710px;top:21px;width:88px;height:58px;',
										'205b' => 'left:850px;top:21px;width:88px;height:58px;',
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
								@if($layout->user()->permitted('rooms_assign'))
								<a href="{{ url('rooms/room/'.$roomNumber) }}">
								@endif
									<div data-toggle="tooltip" data-html="true" title="{{ $layout->room()->getRoomResidentListText($roomNumber) }}" data-placement="{{ substr($roomNumber,1,2) > 15 ? 'right' : 'left' }}"  style="position:absolute;{{ $roomPosition }}"></div>
								@if($layout->user()->permitted('rooms_assign'))
								</a>
								@endif
							@endforeach
								
							<div style="text-align:right;position:absolute;left:314px;top:233px;width:100px;height:80px;">
								@if($level == -2)
									{{ $layout->language('cellar') }}
								@else
									<a href="{{ url('rooms/map/-2') }}">{{ $layout->language('cellar') }}</a>
								@endif
								<br>
								@if($level == -1)
									{{ $layout->language('basement') }}
								@else
									<a href="{{ url('rooms/map/-1') }}">{{ $layout->language('basement') }}</a>
								@endif
								<br>
								@if($level == 0)
									{{ $layout->language('ground_floor') }}
								@else
									<a href="{{ url('rooms/map/0') }}">{{ $layout->language('ground_floor') }}</a>
								@endif
							</div>
							<div style="position:absolute;left:550px;top:233px;width:100px;height:80px;">
								@if($level == 1)
									1. {{ $layout->language('floor') }}
								@else
									<a href="{{ url('rooms/map/1') }}">1. {{ $layout->language('floor') }}</a>
								@endif
								<br>
								@if($level == 2)
									2. {{ $layout->language('floor') }}
								@else
									<a href="{{ url('rooms/map/2') }}">2. {{ $layout->language('floor') }}</a>
								@endif
								<br>
								@if($level == 3)
									3. {{ $layout->language('floor') }}
								@else
									<a href="{{ url('rooms/map/3') }}">3. {{ $layout->language('floor') }}</a>
								@endif
							</div>
						</div>
						
						<div class="panel panel-default">
							<div class="panel-heading text-right"><a href="{{ url('/rooms/download') }}" target="_blank">{{ $layout->language('download_table_as_csv') }}</a></div>
							<div class="panel-body">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>Szobaszám</th>
											<th>Lakó 1</th>
											<th>Lakó 2</th>
											<th>Lakó 3</th>
											<th>Lakó 4</th>
										</tr>
									</thead>
									<tbody>
										@foreach($layout->room()->rooms() as $room)
											<tr>
												<td>{{ $room->room }}</td>
												@foreach($layout->room()->getResidents($room->room) as $resident)
													<td>{{ $resident->name }}</td>
												@endforeach
												@for($i = 0; $i < $layout->room()->getFreePlaceCount($room->room); $i++)
													<td style="background-color:lightgray;"></td>
												@endfor
												<?php /*@for($i = 0; $i < 4 - count($layout->room()->getResidents($room->room)) - $layout->room()->getFreePlaceCount($room->room); $i++)
													<td></td>
												@endfor */?>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
