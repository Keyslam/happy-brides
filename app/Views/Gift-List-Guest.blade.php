@if(count($items) > 0)
	<br>

	<h5 style="text-align: center;">Unclaimed items</h5>

	<ul id="items" class="collection">
		@foreach ($items as $item)
			<li class="collection-item" data-id={{ $item['ID'] }}>
				{{ $item['name'] }}
				<i class="material-icons right valign-wrapper item-add">add_circle</i>
			</li>
		@endforeach
	<ul>

	<br>

	<h5 style="text-align: center;">Claimed items</h5>

	<ul id="claimed-items" class="collection">
		@foreach ($items_taken as $item)
			<li class="collection-item">
				{{ $item['name'] }}
				<div class="right valign-wrapper">{{ $item['taken_by'] }}</div>
			</li>
		@endforeach
	<ul>
@endif