<script id="location-info" type="text/x-handlebars-template">
	<div class="row">
		<header class="content-header row">
			<div class="col-md-10  col-sm-10 col-sm-offset-1 col-md-offset-1 border">
				<div class="col-md-4">
					<h2><i class="fa fa-map-marker"></i> {{locationName}}</h2>
				</div>
				<!--<div class="col-md-8">
					<div class="col-md-4 location-stats">
						<p class="info-title">Laatste beoordeling</p>
						<button class="detail">{{dateLastGraded}} <span class="icon"><i class="fa fa-angle-right"></i></span></button>
					</div>
					<div class="col-md-4 location-stats">
						<p class="info-title">Gemiddelde kwaliteit</p>
						<button class="detail">{{averageGrade}} <span class="icon"><i class="fa fa-angle-right"></i></span></button>
					</div>
					<div class="col-md-4 location-stats">
						<p class="info-title">Aantal meldingen</p>
						<button class="detail">{{problemCount}}<span class="icon"><i class="fa fa-angle-right"></i></span></button>
					</div>
				</div>-->
			</div>
		</header>
		<div class="col-md-10 col-sm-10 col-sm-offset-1 col-md-offset-1">
			<div class="location-subcategories">
				<header>
					<p>Besteksposten</p>
					<button class="btn add-item">Bestekspost toevoegen <i class="fa fa-plus"></i></button>
				</header>
				<ul class="items">
					{{#each categories}}
						<li data-id="{{id}}" class="category"><p>{{name_nl}}</p><i class="fa fa-trash-o delete-item"></i></li>
						{{#each subcategories}}
							<li data-id="{{id}}"><p>{{name_nl}}</p></li>
						{{/each}}
					{{/each}}
				</ul>
			</div>
		</div>
	</div>
</script>