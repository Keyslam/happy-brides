<form method="POST" action="{{ router()->getCurrentUrl() }}/User/Register">
	@csrf

	<div class="input-field">
		<input placeholder="Romeo & Juliet" id="couple-name" name="couple-name" type="text" class="validate">
		<label for="couple-name-name">Couple name</label>
	</div>

	<div class="input-field">
		<input placeholder="romeoandjuliet@dovemail.com" id="email-address" name="email-address" type="email" class="validate" required>
		<label for="email-address">Email address</label>
	</div>

	<div class="input-field">
		<input placeholder="Password" id="password" name="password" type="password" class="validate" required>
		<label for="password">Password</label>
	</div>

	<input class="btn" style="width: 100%;" id="submit-user" type="submit" class="validate" value="Register">
</form>