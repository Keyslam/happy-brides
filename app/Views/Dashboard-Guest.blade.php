@extends("Layouts.BaseLayout")

@section("title", "Dashboard Guest")

@section("constrained-content")
	<div class="row">
		<div class="col s12">
			<div class="card">
				<div id="gift-list">
					
				</div>
			</div>

			<form method="POST" action="{{ router()->getCurrentUrl() }}/Guest/Logout">
				<button class="btn waves-effect waves-light red submit">
					Logout
				</button>
			</form>
		</div>
	</div>
@endsection()

@section("scripts")
<script>
	$(document).ready(function() {
		updateList();
	});

	function updateList() {
		$.ajax({
			url: "http://localhost/happy_brides/Item/GetListAsGuest",
			dataType: "html",
		})
		.done(function(data) {
			$("#gift-list").html(data);

			$(".gift-add").click(claimItem);
		})
		.fail(failHandler);
	}              

	function claimItem(event) {
		var giftID = $(event.target).parent().data("id");

		$.ajax({
			method: "POST",
			url: "http://localhost/happy_brides/Item/Claim",
			data: {
				gift_id: giftID
			},
			dataType: "html",
		})
		.done(updateList)
		.fail(failHandler);
	}

	function failHandler(data) {
		alert("Something went wrong.");
	}
</script>
@endsection