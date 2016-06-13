@extends('layouts.app', ['data' => $layout])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Értesítések</div>
                <div class="panel-body">
                    <ul class="notifications">	
						@if($layout->user()->notifications($notificationId, 10) == null)
							<li class="notification">
								<div class="media">
									<div class="media-body">
										<strong class="notification-title">Rendszer: Semmi probléma</strong>
										<p class="notification-desc">Nincsen megjeleníthető értesítés!</p>

										<div class="notification-meta">
											<small class="timestamp"></small>
										</div>
									</div>
								</div>
							</li>
						@else
							@foreach($layout->user()->notifications($notificationId, 10) as $notification)
							<li style="{{ $notification->seen ? '' : 'background-color:#E0FFFF;' }}" class="notification">
								<div style="cursor: pointer;" class="media" onclick="window.location='{{ url('notification/show/'.$notification->id) }}';">
									<div class="media-body">
										<strong class="notification-title"><a href="{{ url('/data/'.$notification->username) }}">{{ $notification->name }}</a>: {{ $notification->subject }}</strong>
										<p class="notification-desc">{{ $notification->message }}</p>

										<div class="notification-meta">
											<small class="timestamp">{{ str_replace("-", ". ", str_replace(" ", ". ", $notification->time)) }}</small>
										</div>
									</div>
								</div>
							</li>
							@endforeach
						@endif
					</ul>
<<<<<<< HEAD
					
					<nav class="col-md-10 col-md-offset-1">
						<ul class="pager">
							@if(0 < $notificationId)
								<li class="previous"><a href="{{ url('notification/list/'.($notificationId - 10 >= 0 ? $notificationId - 10 : 0)) }}">Frissebb értesítések</a></li>
							@else
								<li class="previous disabled"><a href="#">Frissebb értesítések</a></li>
							@endif
							@if($notificationId+10 < $user->notificationCount())
								<li class="next"><a href="{{ url('notification/list/'.($notificationId + 10)) }}">Régebbi értesítések</a></li>
							@else
								<li class="next disabled"><a href="#">Régebbi értesítések</a></li>
							@endif
						</ul>
					</nav>
=======
					<div class="row">
						@if(0 < $notificationId)
							<div class="col-md-6"><a href="{{ url('notification/list/'.($notificationId - 10 >= 0 ? $notificationId - 10 : 0)) }}">Frissebb értesítések</a></div>
						@else
							<div class="col-md-6"></div>
						@endif
						@if($notificationId+10 < $layout->user()->notificationCount())
							<div style="text-align:right;" class="col-md-6"><a href="{{ url('notification/list/'.($notificationId + 10)) }}">Korábbi értesítések</a></div>
						@else
							<div class="col-md-6"></div>
						@endif
					</div>
>>>>>>> d8c9872... LayoutData data handling was added to the project.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
