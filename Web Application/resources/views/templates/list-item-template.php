<script id="list-item-template" type="text/x-handlebars-template">
	<li class="new">
		<div class="area-title clearfix">
			<div class="row">
				<div class="col-md-3 col-sm-3 col-xs-3">
					{{name}}
				</div>
				<div class="col-md-3 col-sm-3 col-xs-3">
					{{created_at}}
				</div>
				<div class="col-md-3 col-sm-3 col-xs-3">
					{{checklistItemsCount}}
				</div>
			</div>
			<span class="toggle"><i class="fa fa-angle-down"></i></span>
		</div>
		
		<div class="area-locations double">
			<ul class="locations">
				{{#each checklistItems}}
					<li data-name="{{subcategory_id}}">
						{{#if status }}
							<i class="fa fa-warning"></i>
						{{/if}}

						{{name_nl}} : {{{grade}}}
					</li>
				{{/each}}
			</ul>
		</div>
	</li>
</script>