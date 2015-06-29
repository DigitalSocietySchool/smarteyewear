@extends('app')

@section('js')	
	<script src="{{ asset('/js/map.js') }}"></script>
	<script src="{{ asset('/js/todo.js') }}"></script>
@stop

@section('content')
{{-- <header class="page">
	<div class='container'>
		<div class="page-header">
			<h1>To Do</h1>
		</div>
	</div>
</header> --}}
<div class="container-full content-container">
<!-- Comment -->
		<div class="map-panel">
			<!-- Map container -->
			<div id="map-panel"></div>

			<!-- Controls container -->
			<div class="map-controls">

				<!-- Top bar -->
				<div class="top-bar">
					<ul class="areas">
						@foreach ($todo_list as $area=>$list)
							<li data-area="{{{$area}}}" class="area-{{{$area}}} area checked">
								<?php
									$loc_data = [];
									foreach ( $list as $location ){
										$loc_data[] = $location->name;
									}
									$loc_list = implode(',',$loc_data);
								?>
								<div class="area-title" data-locations="{{{$loc_list}}}">
									<span class="check"><i class="fa fa-check-square"></i></span>
									{{$area}}
									{{-- <span class="toggle"><i class="fa fa-angle-down"></i></span> --}}
								</div>
							</li>
						@endforeach
					</ul>
					<div class="my-location">
						<button><i class="fa fa-location-arrow"></i></button>
					</div>
				</div>

				<div class="bottom-bar">
					<div class="left">
						<div class="btn-group toggle-route" role="group" aria-label="Route">
						  <button type="button" class="btn btn-primary hide-route">Verberg route</button>
						  <button type="button" class="btn btn-default show-route">Toon route</button>
						</div>
					</div>

					<div class="right">
						<div class="next-location-info">
							<p>Volgende meetlocatie</p>
							<p class="location"><button class="btn btn-default next-location">Zoeken</button></p>
						</div>
					</div>
				</div>
			</div>

			<!-- Overlay for loading etc -->
			<div class="overlay">
				<div class="col-md-6 col-md-offset-3 text">
					<div><span class="content"></span><i class="fa fa-refresh fa-spin"></i></div>
				</div>
			</div>
		</div>
</div>
@endsection
