<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<meta charset="UTF-8">

   	<title>Happy Brides - @yield('title')</title>

      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" >
		<link rel="stylesheet" href="@asset('css/materialize.css')" media="screen, projection">
		<link rel="stylesheet" href="@asset('css/jquery-ui.min.css')" media="screen, projection">

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="@asset('js/jquery-ui.min.js')"></script>

		<script>
			$(document).ready(function() {
				M.AutoInit();
			})
		</script>
	</head>
	
	<body>
		@yield('content')

		<div class="container">
			<div class="row">
				
			</div>

   		@yield('constrained-content')
		</div>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
		
		@yield('scripts')
	</body>
</html>