@extends("Layouts.BaseLayout");

@section("title", "Login");

@section("constrained-content")
	<div class="row">
		<div class="col s10 offset-s1">
			<ul class="tabs tabs-fixed-width">
				<li class="tab"><a href="#login-guest" class="active">Login Guest</a></li>
				<li class="tab"><a href="#login-user">Login Host</a></li>
				<li class="tab"><a href="#register-user">Register</a></li>
			</ul>

			<div id="login-guest">
				@include('login-guest')
			</div>

			<div id="login-user">
				@include('login-user')
			</div>

			<div id="register-user">
				@include('register-user')
			</div>

			@if(isset($errors))
				@foreach($errors as $error)
					<p>{{ $error }}</p>
				@endforeach
			@endif
		</div>
	</div>
@endsection