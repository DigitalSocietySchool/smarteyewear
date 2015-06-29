<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>GlassKit</title>

	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/font-awesome.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/bootstrap-override.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/glass.css') }}" rel="stylesheet">

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,400italic,600italic' rel='stylesheet' type='text/css'>
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
</head>
<body>
	<nav class="navbar navbar-default navbar-custom">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="{{ url('/') }}"><img src="{{asset('img/logo.png')}}" width="150" /></a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				@if ( !Auth::guest() )
					<ul class="nav navbar-nav nav-pills">
						<li class="presentation {{Request::path() == 'todo' ? 'active' : ''}}" ><a href="{{ url('/todo') }}">To do</a></li>
						{{-- <li class="presentation" ><a href="{{ url('/') }}">Beheer</a></li> --}}
						<li class="presentation {{Request::path() == 'meetlocaties' ? 'active' : ''}}" ><a href="{{ url('/meetlocaties') }}">
						Meetlocaties</a></li>
						{{-- <li class="presentation {{Request::path() == 'checklist' ? 'active' : ''}}" ><a href="{{ url('/checklist') }}">
						Controles</a></li> --}}
						<!--<li class="presentation {{Request::path() == 'stats' ? 'active' : ''}}" ><a href="{{ url('/') }}">Statistieken</a></li>-->
					</ul>
				@endif

				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">Login</a></li>
						<li><a href="{{ url('/auth/register') }}">Register</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><!--{{ Auth::user()->name }} --><span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/auth/logout') }}">Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>

	@yield('templates')

	@yield('content')

	<!-- Scripts -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script type="text/javascript"
		src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCDqt2P318aPhaJqZB-WMlP-TqfcITdz9w&libraries=geometry,visualization">
    </script>
    <script src="https://cdn.socket.io/socket.io-1.3.4.js"></script>
    <script src="{{ asset('/js/handlebars-v3.0.3.js')}}"></script>
    <script src="{{ asset('/js/autocomplete.js')}}"></script>
	@yield('js')
</body>
</html>
