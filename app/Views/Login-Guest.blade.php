<form method="POST" action="{{ router()->getCurrentUrl() }}/Guest/Login">
	@csrf
	
	<div class="input-field">
		<input placeholder="Your name" id="name" name="name" type="text" class="validate" maxlength="30" required>
		<label for="name">Your name</label>
	</div>

	<div class="input-field">
		<input placeholder="0000" id="code" name="code" type="password" class="validate" minlength="4" maxlength="4" required>
		<label for="code">Your 4-digit code</label>
	</div>

	<button class="btn waves-effect waves-light" style="width: 100%;" type="submit" name="action">Login
		<i class="material-icons right">send</i>
	</button>
</form>