@if(count($gifts_unclaimed) > 0 || count($gifts_claimed) > 0)
	<br>

	<h5 style="text-align: center;">Unclaimed Gifts</h5>

	<ul id="gifts-unclaimed" class="collection">
		@foreach ($gifts_unclaimed as $gift)
			<li class="collection-item" data-id={{ $gift['ID'] }}>
				{{ $gift["name"] }}
				<i class="material-icons right valign-wrapper gift-add">add_circle</i>
			</li>
		@endforeach
	<ul>

	<br>

	<h5 style="text-align: center;">Claimed Gifts</h5>

	<ul id="gifts-claimed" class="collection">
		@foreach ($gifts_claimed as $gift)
			<li class="collection-item">
				{{ $gift["name"] }}
				<div class="right valign-wrapper">{{ $gift['claimed_by'] }}</div>
			</li>
		@endforeach
	<ul>
@endif