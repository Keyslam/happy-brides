@if(count($gifts) > 0)
	<ul id="gifts" class="collection">
		@foreach ($gifts as $gift)
			<li class="collection-item" data-id={{ $gift["ID"] }}>
				{{ $gift["name"] }}
				<i class="material-icons right valign-wrapper gift-remove">delete_forever</i>
			</li>
		@endforeach
	<ul>
@endif