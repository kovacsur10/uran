<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@lang('menu.uran') - @lang('menu.ejc')</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/bootstrap-notifications.min.css" rel="stylesheet">
	<link href="/css/bootstrap-datepicker.min.css" rel="stylesheet">
	<link href="/css/validator.css" rel="stylesheet">
	<link href="/css/app.css" rel="stylesheet">

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
	<script type="text/javascript" src="/js/view/layout/app.js" charset="UTF-8"></script>
	<script type="text/javascript">
		@if($layout->lang() === 'en')
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
                <a class="navbar-brand" href="{{ url('/') }}">@lang('menu.uran')</a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <!-- Authentication Links -->
                    @if(!$data->logged())
                        <li><a href="{{ url('/login') }}">@lang('menu.login')</a></li>
                        <li><a href="{{ url('/register') }}">@lang('menu.registration')</a></li>
                    @else
						@if($data->modules()->isActivatedByName('ecnet'))
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">@lang('menu.ecnet') <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/ecnet/account') }}">@lang('menu.printing_account')</a></li>
								<li><a href="{{ url('/ecnet/access') }}">@lang('menu.internet_access')</a></li>
								<li><a href="{{ url('/ecnet/order') }}">@lang('menu.mac_slot_ordering')</a></li>
								@if($data->user()->permitted('ecnet_user_handling'))
								<li><a href="{{ url('/ecnet/users') }}">@lang('menu.user_administration')</a></li>
								@endif
                            </ul>
                        </li>
						@endif
						@if($layout->user()->isLivingIn())
							<li class="dropdown">
	                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">@lang('menu.interns') <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									@if($data->modules()->isActivatedByName('rooms'))
										@if($data->user()->permitted('rooms_observe_assignment'))
										<li><a href="{{ url('/rooms/map') }}">@lang('menu.room_assignment')</a></li>
										@endif
									@endif
	                            </ul>
	                        </li>
	                    @endif
						@if($layout->user()->isCollegist())
							<li class="dropdown">
	                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">@lang('menu.collegist') <span class="caret"></span>
	                            </a>
								<ul class="dropdown-menu" role="menu">
									@if($layout->modules()->isActivatedByName('tasks'))
									<li><a href="{{ url('/tasks/list') }}">@lang('menu.task_manager')</a></li>
									@endif
									@if($layout->modules()->isActivatedByName('ecouncil') && $data->user()->permitted('record_read'))
									<li><a href="{{ url('/ecouncil/records/list') }}">@lang('ecouncil.record_list')</a></li>
									@endif
	                            </ul>
	                        </li>
	                    @endif
						<li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">@lang('menu.ecadmin') <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								@if($data->user()->permitted('user_handling'))
								<li><a href="{{ url('/ecadmin/user/list') }}">@lang('menu.user_administration')</a></li>
								@endif
								@if($data->user()->permitted('mailing_lists_handling'))
								<li><a href="{{ url('/ecadmin/maillist/list') }}">@lang('menu.mailing_lists')</a></li>
								@endif
                            </ul>
                        </li>
                        @if($data->user()->permitted('permission_admin') || $data->user()->permitted('module_admin'))
							<li class="dropdown">
	                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">@lang('menu.admin') <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									@if($data->user()->permitted('permission_admin'))
									<li><a href="{{ url('/admin/permissions') }}">@lang('menu.permissions_handling')</a></li>
									<li><a href="{{ url('/admin/groups/list') }}">@lang('menu.permission_group_handling')</a></li>
									@endif
									@if($data->user()->permitted('module_admin'))
									<li><a href="{{ url('/admin/modules') }}">@lang('menu.modules_handling')</a></li>
									@endif
									@if($data->user()->permitted('accept_user_registration'))
									<li><a href="{{ url('/admin/registration/show') }}">@lang('menu.accept_user_registration')</a></li>
									@endif
	                            </ul>
	                        </li>
						@endif
                    @endif
                </ul>
				
				<!-- Right Side Of Navbar -->
				<ul class="nav navbar-nav navbar-right">
					@if (!$data->logged())
                    @else
                    	<li class="hidden-sm hidden-md hidden-lg"><a href="{{ url('/notification/list/0') }}">@lang('notifications.notifications')</a></li>
						<li class="hidden-xs dropdown">
							<div class="btn-group dropdown-notifications sw-open">
								<button style="margin-top:8px;" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
									@if($data->user()->unreadNotificationCount() > 0)
										<i data-count="{{ $data->user()->unreadNotificationCount() }}" class="glyphicon glyphicon-envelope notification-icon"></i>
									@else
										<i class="glyphicon glyphicon-envelope"></i>
									@endif
									<span class="caret"></span>
								</button>
	
								<div class="dropdown-container">
									<div class="dropdown-toolbar">
										<div class="dropdown-toolbar-actions">
											<a href="{{ url('/notification/list/0') }}"><i class="glyphicon glyphicon-search"></i> @lang('notifications.show_all')</a>
										</div>
										<h3 class="dropdown-toolbar-title" data-toggle="dropdown">@lang('notifications.unread') ({{ $data->user()->unreadNotificationCount() }})</h3>
									</div><!-- /dropdown-toolbar -->
	
									<ul class="dropdown-menu notifications">
									
										@if($data->user()->notifications() === null)
											<li class="notification">
												<div class="media">
													<div class="media-body">
														<strong class="notification-title">@lang('notifications.system_no_problem')</strong>
														<p class="notification-desc">@lang('notifications.no_notification_to_show')</p>
	
														<div class="notification-meta">
															<small class="timestamp"></small>
														</div>
													</div>
												</div>
											</li>
										@else
											@foreach($data->user()->notifications() as $notification)
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
								</div>
							</div>
						</li>				
					
                        <li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">@lang('menu.my_profile') <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/data/show') }}">@lang('menu.my_data')</a></li>
								<li><a href="{{ url('/data/languageexam/upload') }}">@lang('menu.upload_language_exam')</a></li>
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>@lang('menu.logout')</a></li>
                            </ul>
                        </li>
                    @endif
					<li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">@lang('menu.choose_language') <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('lang/set/hu').'?page='.$layout->getRoute() }}">@lang('menu.language_hu')</a></li>
                                <li><a href="{{ url('lang/set/en').'?page='.$layout->getRoute() }}">@lang('menu.language_en')</a></li>
                            </ul>
                        </li>
					</li>
				</ul>
            </div>
        </div>
    </nav>

    @yield('content')
</body>
</html>
