<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Urán - EJC</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
	<link href="/bootstrap-notifications.min.css" rel="stylesheet">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    Urán
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <!-- Authentication Links -->
                    @if (!$data->logged())
                        <li><a href="{{ url('/login') }}">Belépés</a></li>
                        <li><a href="{{ url('/register') }}">Regisztráció</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                ECNET <span class="caret"></span>
                            </a>
							<ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/ecnet/account') }}">Nyomtatószámla</a></li>
								<li><a href="{{ url('/ecnet/access') }}">Internet hozzáférés</a></li>
								<li><a href="{{ url('/ecnet/order') }}">MAC slot igénylése</a></li>
								@if($data->user()->permitted('ecnet_user_handling'))
								<li><a href="{{ url('/ecnet/users') }}">Felhasználók kezelése</a></li>
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
										<a href="{{ url('/notification/list/0') }}"><i class="glyphicon glyphicon-search"></i> Mutasd mindet</a>
									</div>
									<h3 class="dropdown-toolbar-title">Olvasatlan értesítések ({{ $data->user()->unseenNotificationCount() }})</h3>
								</div><!-- /dropdown-toolbar -->

								<ul class="dropdown-menu notifications">
								
									@if($data->user()->latestNotifications() == null)
										<li class="notification">
											<div class="media">
												<div class="media-body">
													<strong class="notification-title">Rendszer: Semmi probléma</strong>
													<p class="notification-desc">Jelenleg nincsenek értesítéseid!</p>

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
                                Profilom <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/data/show') }}">Adataim</a></li>
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Kilépés</a></li>
                            </ul>
                        </li>
                    @endif
				</ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
