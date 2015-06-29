(function($, window){
	// Init map overlays
	var mapOverlay  = (function(){
		var self = {};
		var $element 	= $(".map-panel .overlay");
		var $text 		= $element.find(".content");
		self.show = function(){
			$element.addClass('visible');
		};
		self.hide = function(){
			$element.removeClass('visible');
		}
		self.setContent = function(content){
			$text.text(content);
		}
		return self;
	})();

	// Init controls
	var controls = (function(){
		var $item = $(".areas");
		var $bottom = $(".bottom-bar");

		var bind = function(){
			$item.on('click', '> li', toggleCheck);
			$('#map-panel').on('locations-loaded', initLocations);
			$('#map-panel')
		};

		var initLocations = function(){
			var list = [];
			$.each($item.find('> li'), function(i, it){
				$it = $(it);
				if ( $it.hasClass('checked') ){
					var items = $it.find(".area-title").data('locations').split(',');
					var area =  $it.data('area');
					$.each(items, function(i, location){
						list.push([location, area]);
					});
				}
			});

			toggleBottom();

			drawer(list, 'add');
			mapOverlay.hide();
		};

		var toggleCheck = function(event){
			var $toggle 		= $(event.currentTarget);
			var $currentItem 	= $toggle.parents('li');
			var list = [];
			var area = $toggle.data('area');

			$toggle.toggleClass("checked");

			$.each($toggle.find('.area-title').data('locations').split(','), function(i, location ){
				list.push([location, area]);
			});

			if( $toggle.hasClass("checked") ){
				$toggle.find('i').addClass("fa-check-square");
				$toggle.find('i').removeClass("fa-square-o");
				drawer(list, 'add');
			}
			else{
				$toggle.find('i').removeClass("fa-check-square");
				$toggle.find('i').addClass("fa-square-o");
				drawer(list, 'remove');
			}

			toggleBottom();
		};

		var drawer = function(list, action){
			var toDo = list;
			// $.each(list, function(i, list_item){
			// 	toDo.push([$(list_item).data('name'),$(list_item).parents('li').data('area')]);
			// });
			if( action == "add"){
				map.drawLocations(toDo);
			}

			else if ( action == "remove"){
				map.removeLocations(toDo);
			}

			map.locationCount = toDo.length;
		}

		var toggleBottom = function(list){
			var show = $item.find("> .checked").length;

			if( show ){
				$bottom.addClass("show");
			}
			else{
				$bottom.removeClass("show");
			}
		}

		bind();
	})();
	

	// Initialize Map
	if( $("#map-panel").length ){
		var map = new Map({
			elementId: "map-panel"//,
			// options: {
			// 	mapTypeId: google.maps.MapTypeId.ROADMAP
			// }
		});
		// Show loader
		mapOverlay.setContent("Locaties laden");
		mapOverlay.show();
		map.getAllLocations();
		var nextLocationName = 0;
		// Init poller for new directions
		var poller = setInterval(function(){
			$.getJSON('/randomlist/updated/', {"locationCount": map.locationCount}, function(data){
				if ( data.updated ){
					mapOverlay.setContent("Route berekenen");
					mapOverlay.show();
					map.removeLocation(data.location);
					map.routeToNextLocations(function(){
						setTimeout(function(){
							mapOverlay.hide();
						}, 1000);
					});
				}
			});
			// Set next location name
			if( map.nextLocationName != 0 && map.nextLocationName != nextLocationName ){
				nextLocationName = map.nextLocationName;
				$(".next-location-info .location").text(nextLocationName);
			}
		}, 2000);
	};

	$(".toggle-route").on('click', function(event){
		//map.drawRoute();
		var $target = $(event.target);
		var $current = $(event.currentTarget);

		if( $target.hasClass("show-route") ){
			mapOverlay.setContent("Route berekenen");
			mapOverlay.show();
			map.routeToNextLocations(function(){
				mapOverlay.hide();
				nextLocationName = map.nextLocationName;
				$(".next-location-info .location").text(nextLocationName);
			});
		}
		else{
			map.hideRoute();
		}

		$current.find("> button").toggleClass("btn-primary btn-default");
	});

	$(".next-location").on("click", function(){
		mapOverlay.setContent("Volgende locatie zoeken");
		mapOverlay.show();
		map.getNextLocation(function(){
			mapOverlay.hide();
			$(".next-location-info .location").text(map.nextLocationName);
		});
	});

	$(".my-location").on("click", "button", function(event){
		var $this = $(this);
		mapOverlay.setContent("Huidige positie zoeken");
		mapOverlay.show();
		map.showCurrentPosition(function(){
			mapOverlay.hide();
			$this.css({color: "#3fbc5c"});
			setInterval( function(){
				map.showCurrentPosition();
			}, 2000);
		});
	});

})(jQuery, window);