@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('general.admin_panel') - @lang('ecnet.user_administration_LC')</div>
                <div class="panel-body">				
					@if($layout->user()->permitted('ecnet_user_handling'))
						<div class="panel panel-default">
							<div class="panel-heading">@lang('ecnet.find_user')</div>
							<div class="panel-body">
								<form class="form-horizontal" role="form" method="POST" action="{{ url('/ecnet/users') }}">
									{!! csrf_field() !!}
									
									<div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
										<label class="col-md-4 control-label">@lang('user.username')</label>

										<div class="col-md-6">
											<input type="text" class="form-control" name="username" value="{{ $layout->user()->getUsernameFilter() }}">

											@if ($errors->has('username'))
												<span class="help-block">
													<strong>{{ $errors->first('username') }}</strong>
												</span>
											@endif
										</div>
									</div>
																	
									<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
										<label class="col-md-4 control-label">@lang('user.name')</label>

										<div class="col-md-6">
											<input type="text" class="form-control" name="name" value="{{ $layout->user()->getNameFilter() }}">

											@if ($errors->has('name'))
												<span class="help-block">
													<strong>{{ $errors->first('name') }}</strong>
												</span>
											@endif
										</div>
									</div>
									
									<div class="form-group">
										<div class="col-md-6 col-md-offset-4">
											<button type="submit" class="btn btn-primary">@lang('ecnet.find')</button>
											<a href="{{ url('ecnet/users/resetfilter') }}" class="btn btn-danger" role="button">@lang('ecnet.delete_filter')</a>
										</div>
									</div>
								</form>
							</div>
						</div>
						
						<div class="panel panel-default">
							<div class="panel-heading">@lang('ecnet.list_internet_users')</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-md-2 col-md-offset-2">
										<a href="{{ url('ecnet/users/listactives/name') }}" class="btn btn-primary" role="button">@lang('ecnet.list_internet_users_only_name')</a>
									</div>
									<div class="col-md-2 col-md-offset-1">
										<a href="{{ url('ecnet/users/listactives/username') }}" class="btn btn-primary" role="button">@lang('ecnet.list_internet_users_only_username')</a>
									</div>
									<div class="col-md-2 col-md-offset-1">
										<a href="{{ url('ecnet/users/listactives/both') }}" class="btn btn-primary" role="button">@lang('ecnet.list_internet_users_both')</a>
									</div>
								</div>
							</div>
						</div>
					
						<ul class="list-inline">
							<li><a href="{{ url('ecnet/users/10/0') }}" class="btn btn-primary" role="button">10</a></li>
							<li><a href="{{ url('ecnet/users/20/0') }}" class="btn btn-primary" role="button">20</a></li>
							<li><a href="{{ url('ecnet/users/50/0') }}" class="btn btn-primary" role="button">50</a></li>
							<li><a href="{{ url('ecnet/users/100/0') }}" class="btn btn-primary" role="button">100</a></li>
							<li><a href="{{ url('ecnet/users/500/0') }}" class="btn btn-primary" role="button">500</a></li>
						</ul>
					
						<nav>
							<ul class="pager">
								@if(0 < $firstUser)
									<li class="previous"><a href="{{ url('ecnet/users/'.$usersToShow.'/'.($firstUser - $usersToShow >= 0 ? $firstUser - $usersToShow : 0)) }}">@lang('ecnet.previous_page')</a></li>
								@else
									<li class="previous disabled"><a href="#">@lang('ecnet.previous_page')</a></li>
								@endif
								@if($firstUser+$usersToShow < count($layout->user()->ecnetUsers()))
									<li class="next"><a href="{{ url('ecnet/users/'.$usersToShow.'/'.($firstUser+$usersToShow)) }}">@lang('ecnet.next_page')</a></li>
								@else
									<li class="next disabled"><a href="#">@lang('ecnet.next_page')</a></li>
								@endif
							</ul>
						</nav>
					
						@foreach($layout->user()->ecnetUsers($firstUser, $usersToShow) as $ecnetUser)
						<div class="panel panel-default {{ $ecnetUser->valid() > Carbon\Carbon::now()->toDateTimeString() ? 'panel-success' : 'panel-danger' }}">
							<div class="panel-heading">{{ $ecnetUser->name() }} - {{ $ecnetUser->username() }} - #{{ $ecnetUser->id() }}</div>
							<div class="panel-body">
								<p>@lang('ecnet.balance'): {{ $ecnetUser->money() }} <i class="fa fa-btn fa-money"></i></p>
								<p>@lang('ecnet.validation_date'): {{ $layout->formatDate($ecnetUser->valid()) }} <i class="fa fa-btn {{ $ecnetUser->valid() > Carbon\Carbon::now()->toDateTimeString() ? 'fa-calendar-check-o' : 'fa-calendar-times-o' }}"></i></p>
								<p>@lang('ecnet.mac_slots_count'): {{ $ecnetUser->maximumMacSlots() }} @lang('ecnet.count')
								@if($layout->user()->hasMACSlotOrder($ecnetUser->id()))
									- <i style="color:gold;" class="fa fa-btn fa-flash"></i><a href="{{ url('ecnet/order') }}">@lang('ecnet.mac_slot_order')</a> <i style="color:gold;" class="fa fa-btn fa-flash"></i>
								@endif
								@if(($ecnetUser->valid() > Carbon\Carbon::now()->toDateTimeString()) && (count($ecnetUser->macAddresses()) < $ecnetUser->maximumMacSlots()))
									- <span style="color:red;">@lang('ecnet.low_mac_slot_usage') (diff: {{ $ecnetUser->maximumMacSlots() - count($ecnetUser->macAddresses()) }})</span>
								@endif
								@if($ecnetUser->maximumMacSlots() != 0)						
									<div class="dropdown">
										<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">@lang('ecnet.registrated_mac_addresses')
										<span class="caret"></span></button>
										<ul class="dropdown-menu">
										@foreach($ecnetUser->macAddresses() as $macAddress)
											<li>{{ $macAddress->address() }}</li>
										@endforeach
										</ul>
									</div>
								@endif
								</p>
							</div>
						</div>
						@endforeach
						
						<nav>
							<ul class="pager">
								@if(0 < $firstUser)
									<li class="previous"><a href="{{ url('ecnet/users/'.$usersToShow.'/'.($firstUser - $usersToShow >= 0 ? $firstUser - $usersToShow : 0)) }}">@lang('ecnet.previous_page')</a></li>
								@else
									<li class="previous disabled"><a href="#">@lang('ecnet.previous_page')</a></li>
								@endif
								@if($firstUser+$usersToShow < count($layout->user()->ecnetUsers()))
									<li class="next"><a href="{{ url('ecnet/users/'.$usersToShow.'/'.($firstUser+$usersToShow)) }}">@lang('ecnet.next_page')</a></li>
								@else
									<li class="next disabled"><a href="#">@lang('ecnet.next_page')</a></li>
								@endif
							</ul>
						</nav>
						
						<ul class="list-inline">
							<li><a href="{{ url('ecnet/users/10/0') }}" class="btn btn-primary" role="button">10</a></li>
							<li><a href="{{ url('ecnet/users/20/0') }}" class="btn btn-primary" role="button">20</a></li>
							<li><a href="{{ url('ecnet/users/50/0') }}" class="btn btn-primary" role="button">50</a></li>
							<li><a href="{{ url('ecnet/users/100/0') }}" class="btn btn-primary" role="button">100</a></li>
							<li><a href="{{ url('ecnet/users/500/0') }}" class="btn btn-primary" role="button">500</a></li>
						</ul>
						
					@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
