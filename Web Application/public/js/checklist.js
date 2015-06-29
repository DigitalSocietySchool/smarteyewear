(function($, window){

	var listItem = function(data, callback){
		var html = $("#list-item-template").html();
		var template = Handlebars.compile(html);
		var content = data;
		var $list = $(".checklist-list");

		var create = (function(){
			var item = template(content);
			var $item = $(item);
			$list.prepend($item);


			if( typeof(callback) != "undefined" ){
				callback($list);
			}
		})();
	};

	var socket = io.connect(window.location.origin + ':8890');
    socket.on('checklist', function (data) {
    	var jsonData = $.parseJSON(data);
    	listItem(jsonData, function($list){
    		$(".panel-todo .items").text($list.find('>li').length);
    	});
    });

    var controls = (function(){
		var $item = $(".collapse-list");

		var bind = function(){
			$item.on('click', 'li .toggle', toggleCollapse);
		};


		var toggleCollapse = function(event){
			var $toggle 		= $(event.currentTarget);
			var $currentItem 	= $toggle.parents('li');
			$currentItem.toggleClass("open");
			if( $currentItem.hasClass("open") ){
				$toggle.find("i").addClass("fa-flip-vertical");
			}
			else{
				$toggle.find("i").removeClass("fa-flip-vertical");
			}
		};

		bind();
	})();
	
	var heatmap = (function(){
		var self = this;
		// Initialize the map
		this.map = new Map({
			elementId: "heat-map",
			mapTypeId: google.maps.MapTypeId.SATELLITE
		});
		// Get the data
		$.getJSON('/checklistproblems/', function(data){
			self.map.drawHeatmap(data);
		})

		// When ready draw the heatmap on the map
	})();

})(jQuery, window);