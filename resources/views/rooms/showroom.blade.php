@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('rooms.room_assignment')</div>
                <div class="panel-body">
					@if($layout->user()->permitted('rooms_assign'))
					<div class="panel panel-default">
						<div class="panel-heading">@lang('general.admin_panel')</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="{{ url('/rooms/assign/'.$layout->room()->getGuard()) }}">
								{!! csrf_field() !!}
								
								<?php
									$j = 0;
								?>
								@foreach($layout->room()->getResidents($room) as $resident)
									<div class="form-group{{ $errors->has('resident'.$j) ? ' has-error' : '' }}">
										<label  class="col-md-4 control-label" for="resident_select{{ $j }}">Lakó</label>
										<div class="col-md-6">
											<select class="form-control"  name="resident{{ $j }}"  id="resident_select{{ $j }}">
												<option value="0">@lang('rooms.free_spot')</option>
												@foreach($layout->user()->users(0, -1) as $user)
													@if(!$layout->room()->userHasResidence($user->id()) || $user->name() == $resident->name())
														<option value="{{ $user->id() }}"
														@if($user->name() == $resident->name())
															selected
														@endif
														>{{ $user->name() }} ({{ $user->username() }})</option>
													@endif
												@endforeach
											</select>

											@if ($errors->has('resident'.$j))
												<span class="help-block">
													<strong>{{ $errors->first('resident'.$j) }}</strong>
												</span>
											@endif
										</div>
									</div>
									<?php
										$j++;
									?>
								@endforeach
								
								@for($i = 0; $i < $layout->room()->getFreePlaceCount($room); $i++)
									<div class="form-group{{ $errors->has('resident'.$i+$j) ? ' has-error' : '' }}">
										<label  class="col-md-4 control-label" for="resident_select{{ $i+$j }}">@lang('rooms.resident')</label>
										<div class="col-md-6">
											<select class="form-control"  name="resident{{ $i+$j }}"  id="resident_select{{ $i+$j }}">
												<option value="0" selected>@lang('rooms.free_spot')</option>
												@foreach($layout->user()->users(0, -1) as $user)
													@if(!$layout->room()->userHasResidence($user->id()))
														<option value="{{ $user->id() }}">{{ $user->name() }} ({{ $user->username() }})</option>
													@endif
												@endforeach
											</select>

											@if ($errors->has('resident'.$i+$j))
												<span class="help-block">
													<strong>{{ $errors->first('resident'.$i+$j) }}</strong>
												</span>
											@endif
										</div>
									</div>
								@endfor
								
								<input type="hidden" name="count" value="{{ $i+$j }}">
								<input type="hidden" name="room" value="{{ $room }}">
								
								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">@lang('rooms.modify')</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					
					<a href="{{ url('rooms/map/'.substr(strval($room),0,1)) }}">@lang('rooms.back_to_the_rooms_list')</a>
					@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
