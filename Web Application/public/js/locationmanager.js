(function($, window){
	var $scrollContainer 	= $(".scroll-container");
	var $map 				= $(".map-container");
	var $empty 				= $(".empty");
	var map = new Map({
		elementId: "map",
		options:{
			scrollwheel: false,
		    navigationControl: false,
		    mapTypeControl: false,
		    scaleControl: false,
		    draggable: false,
		}
	});

	var search = (function(){
		var self = this;
		var $input = $(".search input");
		var loading = "loading";

		var initialize = function(){
			$input.autocomplete({
				serviceUrl: '/locations/search',
			    onSelect: getLocationData,
			    noCache: true,
			    autoSelectFirst: true,
			    triggerSelectOnValidInput: false
			});
		}

		var getLocationData = function(suggestion){
			var id = suggestion.data;
			var name = self.name = suggestion.value;
			$scrollContainer.removeClass("show");
			$map.removeClass("show");
			$empty.addClass("loading").removeClass("hidden");
			$.getJSON(
				"/location/subcategories/" + id ,
				showLocationData
			);
		}

		var showLocationData = function(data){
			map.drawLocation(self.name, function(){
				$html = $("#location-info").html();
				$template = Handlebars.compile($html);
				var context = {
					locationName: self.name,
					dateLastGraded: 0,
					averageGrade: 0,
					problemCount:0,
					categories: data
				};
				$html = $template(context);
				$(".location-info-container").html($html);
				$scrollContainer.addClass("show");
				$map.addClass("show");
				$empty.addClass("hidden");
			});
		}

		initialize();
	})();

})(jQuery, window);