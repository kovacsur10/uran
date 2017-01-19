@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $layout->language('notifications') }}</div>
                <div class="panel-body">
                    <ul class="notifications">	
						@if($layout->user()->notifications($notificationId, 10) === null)
							<li class="notification">
								<div class="media">
									<div class="media-body">
										<strong class="notification-title">{{ $layout->language('system_no_problem') }}</strong>
										<p class="notification-desc">{{ $layout->language('no_notification_to_show') }}</p>

										<div class="notification-meta">
											<small class="timestamp"></small>
										</div>
									</div>
								</div>
							</li>
						@else
							@foreach($layout->user()->notifications($notificationId, 10) as $notification)
							<li style="{{ $notification->isSeen() ? '' : 'background-color:#E0FFFF;' }}" class="notification">
								<div style="cursor: pointer;" class="media" onclick="window.location='{{ url('notification/show/'.$notification->id()) }}';">
									<div class="media-body">
										<strong class="notification-title"><a href="{{ url('/data/'.$notification->username()) }}">{{ $notification->name() }}</a>: {{ $notification->subject() }}</strong>
										<p class="notification-desc">{{ $notification->message() }}</p>

										<div class="notification-meta">
											<small class="timestamp">{{ str_replace("-", ". ", str_replace(" ", ". ", $notification->time())) }}</small>
										</div>
									</div>
								</div>
							</li>
							@endforeach
						@endif
					</ul>

					<nav class="col-md-10 col-md-offset-1">
						<ul class="pager">
							@if(0 < $notificationId)
								<li class="previous"><a href="{{ url('notification/list/'.($notificationId - 10 >= 0 ? $notificationId - 10 : 0)) }}">{{ $layout->language('newer_notifications') }}</a></li>
							@else
								<li class="previous disabled"><a href="#">{{ $layout->language('newer_notifications') }}</a></li>
							@endif
							@if($notificationId+10 < $layout->user()->notificationCount())
								<li class="next"><a href="{{ url('notification/list/'.($notificationId + 10)) }}">{{ $layout->language('older_notifications') }}</a></li>
							@else
								<li class="next disabled"><a href="#">{{ $layout->language('older_notifications') }}</a></li>
							@endif
						</ul>
					</nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
