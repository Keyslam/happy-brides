@extends("Layouts.BaseLayout")

@section("title", "Dashboard User")

@section("constrained-content")
	<div class="row">
		<div class="col s10">
			<h4>Welcome {{ $couple_name }} - {{ $access_code }}</h4>

			<form id="gift-form">
				<label for="input-gift-name">Item</label>
				<input id="input-gift-name" name="input-gift" type="text" required>

				<button class="btn waves-effect waves-light submit" id="gift-add" type="submit">
					Add
					<i class="material-icons right">add</i>
				</button>

				
			</form>

			<br>

			<div class="card">
				<div id="gift-list">
					
				</div>
			</div>
			<p>
				<b>Drag and drop gifts to order them by priority!</b>
			</p>

			<form method="POST" action="{{ router()->getCurrentUrl() }}/User/Logout">
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

		// Setup add gift button
		$("#gift-form").on("submit", function(ev) {
			ev.preventDefault();

			var giftName = $("#input-gift-name").val();
			$("#input-gift-name").val("");

			addGift(giftName);
		});
	});

	function onUpdate(event, ui) {
		var giftID = $(ui.item).data("id");

		var newIndex = ui.item.index();

		$.ajax({
			method: "POST",
			url: "http://localhost/happy_brides/Gift/Move",
			data: {
				gift_id: giftID,
				new_priority: newIndex
			},
			dataType: "html",
		})
		.done(updateList)
		.fail(failHandler);
	}

	function removeGift(event) {
		var giftID = $(event.target).parent().data("id");

		$.ajax({
			method: "POST",
			url: "http://localhost/happy_brides/Gift/Delete",
			data: {
				gift_id: giftID
			},
			dataType: "html",
		})
		.done(updateList)
		.fail(failHandler);
	}

	function addGift(giftName) {
		$.ajax({
			method: "POST",
			url: "http://localhost/happy_brides/Gift/Add",
			data: {
				gift_name: giftName
			},
			dataType: "html",
		})
		.done(updateList)
		.fail(failHandler);
	}

	function updateList() {
		$.ajax({
			url: "http://localhost/happy_brides/Gift/GetList",
			dataType: "html",
		})
		.done(function(data) {
			$("#gift-list").html(data);

			$("#gifts").sortable({
				update: onUpdate,
			});
			$("#gifts").disableSelection();

			$(".gift-remove").click(removeGift);
			})
		.fail(failHandler);
	}                          

	function failHandler(data) {
		alert("Something went wrong.");
	}
</script>
@endsection