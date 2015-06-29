@extends('app')

@section('js')
	<script src="{{ asset('/js/map.js') }}"></script>
	<script src="{{ asset('/js/todo.js') }}"></script>
	<script src="{{ asset('/js/checklist.js') }}"></script>
@stop

@section('content')
	<div id="heat-map"></div>
	<div class="container content-container">
		<div class="row">
			<div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
				<p>Laatste controles</p>
				<div class="panel panel-default panel-todo">
					<div class="panel-heading clearfix">
						<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-6">
								Locatie
							</div>
							<div class="col-md-6 col-sm-6 col-xs-6">
								Datum
							</div>
						</div>
					</div>

					<div class="panel-body">
						@if( count($checklists) <= 0 )
							<h2>Er zijn geen controles</h2>
						@endif
						<ul class="collapse-list checklist-list">
							@foreach ($checklists as $checklist)
								@if( count($checklist->items) > 0 )
									<li>
										<div class="title clearfix">
											<div class="row">
												<div class="col-md-6 col-sm-6 col-xs-6">
													{{$checklist->location->name}}
												</div>
												<div class="col-md-6 col-sm-6 col-xs-6">
													{{$checklist->created_at->format('d-m-Y')}}
												</div>
											</div>
											<span class="toggle"><i class="fa fa-angle-down"></i></span>
										</div>
										
										<div class="treasure double">
											<ul class="items">
												@foreach ( $checklist->items as $item )
												<?php
													$acceptable_grade = $item->subcategorylocation->accepted_grade;
													$grade = $item->grade;
													$grades = ['AA', 'A', "B", "C", "D"];
													$status = array_search($grade, $grades) > array_search($acceptable_grade, $grades);
												?>
													<li data-name="{{$item->subcategory_id}}">
														@if( $status )
															<i class="fa fa-warning"></i>
														@endif

														{{$item->subcategory->name_nl}} : {{{$item->grade}}}
													</li>
												@endforeach
											</ul>
										</div>
										
									</li>
								@endif
							@endforeach
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	@include('templates.list-item-template')
@stop