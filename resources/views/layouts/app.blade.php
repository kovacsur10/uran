<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $layout->language('uran') }} - {{ $layout->language('ejc') }}</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/bootstrap-notifications.min.css" rel="stylesheet">
	<link href="/css/bootstrap-datepicker.min.css" rel="stylesheet">
	<link href="/css/validator.css" rel="stylesheet">
	<link href="/css/app.css" rel="stylesheet">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
	
	<!-- Scripts -->
	<script type="text/javascript" src="/js/jquery-2.2.4.min.js"></script>
	<script type="text/javascript" src="/js/nod.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
	<script type="text/javascript" src="/js/view/layout/app.js" charset="UTF-8"></script>
	<script type="text/javascript">
		@if($layout->lang() === 'en_US')
			var language = english;
		@else
			var language = hungarian;
		@endif
	</script>
	<script type="text/javascript" src="/js/bootstrap-datepicker.min.js"></script>
	<script type="text/javascript">
		$('document').ready(function(){
			$('[data-toggle="tooltip"]').tooltip();
		});
	</script>
</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ $layout->language('uran') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <!-- Authentication Links -->
                    @if(!$data->logged())
                        <li><a href="{{ url('/login') }}">{{ $layout->language('login') }}</a></li>
                        <li><a href="{{ url('/register') }}">{{ $layout->language('registration') }}</a></li>
                    @else
						@if($data->modules()->isActivatedByName('ecnet'))
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ $layout->language('ecnet') }} <span class="caret"></span>
                            </a>
							<ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/ecnet/account') }}">{{ $layout->language('printing_account') }}</a></li>
								<li><a href="{{ url('/ecnet/access') }}">{{ $layout->language('internet_access') }}</a></li>
								<li><a href="{{ url('/ecnet/order') }}">{{ $layout->language('mac_slot_ordering') }}</a></li>
								@if($data->user()->permitted('ecnet_user_handling'))
								<li><a href="{{ url('/ecnet/users') }}">{{ $layout->language('user_administration') }}</a></li>
								@endif
                            </ul>
                        </li>
						@endif
						@if($data->modules()->isActivatedByName('rooms'))
						<li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                TODO <span class="caret"></span>
                            </a>
							<ul class="dropdown-menu" role="menu">
								@if($data->user()->permitted('rooms_observe_assignment'))
								<li><a href="{{ url('/rooms/map/2') }}">{{ $layout->language('room_assignment') }}</a></li>
								@endif
                            </ul>
                        </li>
						@endif
						@if($data->user()->permitted('permission_admin') || $data->user()->permitted('module_admin'))
						<li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ $layout->language('admin') }} <span class="caret"></span>
                            </a>
							<ul class="dropdown-menu" role="menu">
								@if($data->user()->permitted('permission_admin'))
								<li><a href="{{ url('/admin/permissions') }}">{{ $layout->language('permissions_handling') }}</a></li>
								<li><a href="{{ url('/admin/permissions/default') }}">{{ $layout->language('default_permissions_handling') }}</a></li>
								@endif
								@if($data->user()->permitted('module_admin'))
								<li><a href="{{ url('/admin/modules') }}">{{ $layout->language('modules_handling') }}</a></li>
								@endif
								@if($data->user()->permitted('accept_user_registration'))
								<li><a href="{{ url('/admin/registration/show') }}">{{ $layout->language('accept_user_registration') }}</a></li>
								@endif
                            </ul>
                        </li>
						@endif
						<li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
							{{ $layout->language('administration') }} <span class="caret"></span>
                            </a>
							<ul class="dropdown-menu" role="menu">
								@if($layout->modules()->isActivatedByName('tasks'))
								<li><a href="{{ url('/tasks/list') }}">{{ $layout->language('task_manager') }}</a></li>
								@endif
                            </ul>
                        </li>
						<li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
							{{ $layout->language('TODO') }} <span class="caret"></span>
                            </a>
							<ul class="dropdown-menu" role="menu">
								@if($data->user()->permitted('user_handling'))
								<li><a href="{{ url('/ecadmin/user/list') }}">{{ $layout->language('TODO') }}</a></li>
								@endif
                            </ul>
                        </li>
                    @endif
                </ul>
				
				<!-- Right Side Of Navbar -->
				<ul class="nav navbar-nav navbar-right">
					@if (!$data->logged())
                    @else
						
						<li class="btn-group dropdown dropdown-notifications sw-open">
							<button style="margin-top:8px;" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
								@if($data->user()->unseenNotificationCount() > 0)
									<i data-count="{{ $data->user()->unseenNotificationCount() }}" class="glyphicon glyphicon-envelope notification-icon"></i>
								@else
									<i class="glyphicon glyphicon-envelope"></i>
								@endif
								<span class="caret"></span>
							</button>

							<div class="dropdown-container">
								<div class="dropdown-toolbar">
									<div class="dropdown-toolbar-actions">
										<a href="{{ url('/notification/list/0') }}"><i class="glyphicon glyphicon-search"></i> {{ $layout->language('show_all') }}</a>
									</div>
									<h3 class="dropdown-toolbar-title">{{ $layout->language('unread_notifications') }} ({{ $data->user()->unseenNotificationCount() }})</h3>
								</div><!-- /dropdown-toolbar -->

								<ul class="dropdown-menu notifications">
								
									@if($data->user()->latestNotifications() == null)
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
										@foreach($data->user()->latestNotifications() as $notification)
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
							</div>
						</li>				
					
                        <li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ $layout->language('my_profile') }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/data/show') }}">{{ $layout->language('my_data') }}</a></li>
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>{{ $layout->language('logout') }}</a></li>
                            </ul>
                        </li>
                    @endif
					<li>
						@if($layout->lang() !== 'hu_HU')
							<a style="padding: 0px 0px;" href="{{ url('lang/set/hu_HU').'?page='.$layout->getRoute() }}"><img style="height:18px;" src="/images/lang_hu.png" alt="Hungarian Flag"></a>
						@elseif($layout->lang() !== 'en_US')
							<a style="padding: 0px 0px;" href="{{ url('lang/set/en_US').'?page='.$layout->getRoute() }}"><img style="height:18px;" src="/images/lang_en.png" alt="UK Flag"></a>
						@endif
					</li>
				</ul>
            </div>
        </div>
    </nav>

    @yield('content')
</body>
</html>
