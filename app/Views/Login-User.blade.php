<form method="POST" action="{{ router()->getCurrentUrl() }}/User/Login">
	<div class="input-field">
		<input placeholder="romeoandjuliet@dovemail.com" id="email-address" name="email-address" type="email" class="validate" required>
		<label for="email-address">Email address</label>
	</div>

	<div class="input-field">
		<input placeholder="Password" id="password" name="password" type="password" class="validate" required>
		<label for="password">Password</label>
	</div>

	<input class="btn" style="width: 100%;" id="submit-user" type="submit" class="validate" value="Log in">
</form>