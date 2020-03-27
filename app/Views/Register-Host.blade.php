<form method="POST" action="{{ router()->getCurrentUrl() }}/Host/Register">
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
		<input placeholder="" id="password" name="password" type="password" class="validate" required>
		<label for="password">Password</label>
	</div>

	<div class="input-field">
		<input placeholder="" id="end-date" name="end-date" type="date" class="validate" required>
		<label for="end-date">Marriage date</label>
	</div>

	<input class="btn" style="width: 100%;" id="submit-user" type="submit" class="validate" value="Register">
</form>

@section('scripts')
	<script>
		$(document).ready(function() {
			var today = new Date();

			var year  = today.getFullYear();
			var month = ("0" + (today.getMonth() + 1)).slice(-2);
			var day   = ("0" + (today.getDate()  + 1)).slice(-2);

			var minDate = (year + "-" + month + "-" + day);

			$('#end-date').attr("min", minDate);
		});
	</script>
@endsection()