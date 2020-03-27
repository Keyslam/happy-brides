@if(count($items) > 0)
	<ul id="items" class="collection">
		@foreach ($items as $item)
			<li class="collection-item" data-id={{ $item['ID'] }}>
				{{ $item['name'] }}
				<i class="material-icons right valign-wrapper item-remove">delete_forever</i>
			</li>
		@endforeach
	<ul>
@endif