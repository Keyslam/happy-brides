@extends('Layouts.BaseLayout')

@section('title', 'Dashboard Guest')

@section('constrained-content')
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

@section('scripts')
<script>
	$(document).ready(function() {
		$.ajax({
			url: "http://localhost/happy_brides/Item/getListAsGuest",
			dataType: "html",
		})
		.done(renderList)
		.fail(failHandler);
	});

	function renderList(data) {
		$("#gift-list").html(data);

		$(".item-add").click(claimItem);
	}

	function claimItem(event) {
		var item_id = $(event.target).parent().data("id");

		$.ajax({
			method: "POST",
			url: "http://localhost/happy_brides/Item/Claim",
			data: {
				item_id: item_id
			},
			dataType: "html",
		})
		.done(renderList)
		.fail(failHandler);
	}

	function failHandler(data) {
		alert("Something went wrong.");
	}
</script>
@endsection