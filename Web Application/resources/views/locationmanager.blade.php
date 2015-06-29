@extends('app')

@section('js')
	<script src="{{ asset('/js/map.js') }}"></script>
	<script src="{{ asset ('js/locationmanager.js') }}"></script>
@stop

@section('content')
<header class="page">
	<div class='container'>
		{{-- <div class="col-md-4 col-sm-6 col-xs-6 col-lg-4">
			<div class="page-header">
				<h1>Meetlocaties</h1>
			</div>
		</div> --}}
		<div class="col-md-4 col-sm-4 col-xs-8 col-lg-4">
			<div class="search">
				<i class="fa fa-search"></i>
					<input type="text" name="location" id="location" placeholder="Zoek locatie">
				<i class="fa fa-circle-o-notch fa-spin"></i>
			</div>
		</div>
	</div>
</header>
<div class="container empty">
	<div class="col-md-8 col-md-offset-2">
		<div class="no-results">
			<h3>Geen resultaten</h3>
		</div>
		<div class="loading">
			<i class="fa fa-circle-o-notch fa-spin"></i>
		</div>
	</div>
</div>
<div class="map-container">
	<div id="map"></div>
</div>
<div class="scroll-container">
	<div class="container">
		<section class="location-info">
			<div class="col-md-8 col-md-offset-4 col-sm-8 col-sm-offset-4 location-info-container">
				<header class="content-header row">
					<div class="col-md-10 col-md-offset-1 border">
						<div class="col-md-4">
							<h2><i class="fa fa-map-marker"></i> 66zz</h2>
						</div>
						<!--<div class="col-md-8">
							<div class="col-md-4 location-stats">
								<p class="info-title">Laatste beoordeling</p>
								{{-- <p class="info"></p> --}}
								<button class="detail">21-03-2015 <span class="icon"><i class="fa fa-angle-right"></i></span></button>
							</div>
							<div class="col-md-4 location-stats">
								<p class="info-title">Gemiddelde kwaliteit</p>
								{{-- <p class="info"></p> --}}
								<button class="detail">A <span class="icon"><i class="fa fa-angle-right"></i></span></button>
							</div>
							<div class="col-md-4 location-stats">
								<p class="info-title">Aantal meldingen</p>
								{{-- <p class="info"></p> --}}
								<button class="detail">4 <span class="icon"><i class="fa fa-angle-right"></i></span></button>
							</div>
						</div>-->
					</div>
				</header>
				<div class="col-md-10 col-md-offset-1">
					<div class="location-subcategories">
						<header>
							<p>Besteksposten</p>
							<button class="btn add-item">Bestekspost toevoegen <i class="fa fa-plus"></i></button>
						</header>
						<ul class="items">
							<li><i class="fa fa-ellipsis-v move-item"></i><p>Groen-gras</p><i class="fa fa-trash-o delete-item"></i></li>
							<li><i class="fa fa-ellipsis-v move-item"></i><p>Groen-gras</p><i class="fa fa-trash-o delete-item"></i></li>
							<li><i class="fa fa-ellipsis-v move-item"></i><p>Groen-gras</p><i class="fa fa-trash-o delete-item"></i></li>
							<li><i class="fa fa-ellipsis-v move-item"></i><p>Groen-gras</p><i class="fa fa-trash-o delete-item"></i></li>
						</ul>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>
@include('templates.location-info-template')
{{-- <div class="container search-container">
	<div class="col-md-6 col-sm-8 col-xs-8 col-lg-6 col-md-offset-3 col-sm-offset-2 col-lg-offset-3 col-xs-offset-2">
		<div class="search">
			<i class="fa fa-search"></i>
			<input type="text" name="location" id="location" placeholder="Zoek locatie">
			<i class="fa fa-circle-o-notch fa-spin"></i>
		</div>
	</div>
</div> --}}
@stop
