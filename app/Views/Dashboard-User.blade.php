@extends("Layouts.BaseLayout")

@section("title", "Dashboard User")

@section("constrained-content")
	<div class="row">
		<div class="col s10">
			<h4>Welcome {{ $couple_name }} - {{ $access_code }}</h4>

			<form id="item-form">
				<label for="input-item-name">Item</label>
				<input id="input-item-name" name="input-item" type="text" required>

				<button class="btn waves-effect waves-light submit" id="item-add" type="submit">
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

		// Setup add item button
		$("#item-form").on("submit", function(ev) {
			ev.preventDefault();

			var giftName = $("#input-item-name").val();
			$("#input-item-name").val("");

			addItem(giftName);
		});
	});

	function onUpdate(event, ui) {
		var giftID = $(ui.item).data("id");

		var newIndex = ui.item.index();

		$.ajax({
			method: "POST",
			url: "http://localhost/happy_brides/Item/Move",
			data: {
				gift_id: giftID,
				new_priority: newIndex
			},
			dataType: "html",
		})
		.done(updateList)
		.fail(failHandler);
	}

	function removeItem(event) {
		var giftID = $(event.target).parent().data("id");

		$.ajax({
			method: "POST",
			url: "http://localhost/happy_brides/Item/Delete",
			data: {
				gift_id: giftID
			},
			dataType: "html",
		})
		.done(updateList)
		.fail(failHandler);
	}

	function addItem(giftName) {
		$.ajax({
			method: "POST",
			url: "http://localhost/happy_brides/Item/Add",
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
			url: "http://localhost/happy_brides/Item/GetList",
			dataType: "html",
		})
		.done(function(data) {
			$("#gift-list").html(data);

			$("#items").sortable({
				update: onUpdate,
			});
			$("#items").disableSelection();

			$(".item-remove").click(removeItem);
			})
		.fail(failHandler);
	}                          

	function failHandler(data) {
		alert("Something went wrong.");
	}
</script>
@endsection