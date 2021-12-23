<!DOCTYPE html>
<html lang="ru" dir="ltr">
	<head>
		<title>@yield('title')</title>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="ie=edge" />
		<meta http-equiv="Content-Language" content="ru" />
		<meta name="msapplication-TileColor" content="#596be7" />
		<meta name="theme-color" content="#596be7" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="mobile-web-app-capable" content="yes" />
		<meta name="HandheldFriendly" content="True" />
		<meta name="MobileOptimized" content="320" />
		<meta name="format-detection" content="telephone=no">
		<meta name="robots" content="index, follow">
		@yield('meta')

		<link rel="stylesheet" href="/css/tabler.min.css">
		<link rel="stylesheet" href="/css/admin.min.css">
		<link rel="preload" href="/css/fontawesome.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
		
		@yield('styles')


		<script type="text/javascript" src="/js/jquery.min.js"></script>
		<script type="text/javascript" src="/js/axios.min.js"></script>
		<script type="text/javascript">
			axios.defaults.withCredentials = true;
		</script>
		<script type="text/javascript" src="/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/js/bootbox.min.js"></script>
		<script type="text/javascript" src="/js/boottoast5.min.js"></script>

		<script type="text/javascript" src="/js/app.min.js"></script>

{{-- 
		<script type="text/javascript" src="/js/bootbox.min.js"></script>
		<script type="text/javascript" src="/js/jquery.maskedinput.min.js"></script>
		<script type="text/javascript" src="/js/selectize.min.js"></script> --}}
		@yield('scripts')
	</head>
	<body class="antialiased subpixel-antialiased">
		<div class="page">
			<div class="content">
				<div class="container-fluid">
					@yield('content')
				</div>
			</div>
		</div>
	</body>
</html>