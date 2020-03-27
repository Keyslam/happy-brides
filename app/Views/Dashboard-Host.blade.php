@extends('Layouts.BaseLayout')

@section('title', 'Dashboard Host')

@section('constrained-content')
	<div class="row">
		<div class="col s12">
			<h4>Welcome {{ $couple_name }}</h4>

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

			<form method="POST" action="{{ router()->getCurrentUrl() }}/Host/Logout">
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
			url: "http://localhost/happy_brides/Item/GetList",
			dataType: "html",
		})
		.done(renderList)
		.fail(failHandler);

		// Setup add item button
		$("#item-form").on("submit", function(ev) {
			ev.preventDefault();

			var itemName = $("#input-item-name").val();

			addItem(itemName);
		});
	});

	function onUpdate(event, ui) {
		var item_id = $(ui.item).data("id");

		var new_index = ui.item.index();

		$.ajax({
			method: "POST",
			url: "http://localhost/happy_brides/Item/Move",
			data: {
				item_id: item_id,
				new_priority: new_index
			},
			dataType: "html",
		})
		.done(renderList)
		.fail(failHandler);
	}

	function removeItem(event) {
		var item_id = $(event.target).parent().data("id");

		$.ajax({
			method: "POST",
			url: "http://localhost/happy_brides/Item/Delete",
			data: {
				item_id: item_id
			},
			dataType: "html",
		})
		.done(renderList)
		.fail(failHandler);
	}

	function addItem(itemName) {
		$.ajax({
			method: "POST",
			url: "http://localhost/happy_brides/Item/Add",
			data: {
				itemName: itemName
			},
			dataType: "html",
		})
		.done(renderList)
		.fail(failHandler);
	}

	function renderList(data) {
		$("#gift-list").html(data);

		$("#items").sortable({
			update: onUpdate,
		});
		$("#items").disableSelection();

		$(".item-remove").click(removeItem);
	}

	function failHandler(data) {
		alert("Something went wrong.");
	}
</script>
@endsection