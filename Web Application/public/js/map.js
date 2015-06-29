var Map;
(function($, window){
	m = function(options){
		var self = this;
		locations = {
			all : {},
			checklist: {}
		};
		self.locations = {
			all: {},
			checklist: {},
			drawn: {}
		};
		self.currentMarker = 0;
		self.currentPosition = 0;
		self.locationCount = 0;
		self.locationCenters = {};
		self.options = options.mapOptions;
		self.nextLocationName = 0;
		var locArr = {};
		var myLoc = new google.maps.LatLng(52.407952, 4.854107);
		var drawing = [];
		var sv = new google.maps.StreetViewService();
		var route = {
			origin: new google.maps.LatLng(52.408116, 4.854061),
			waypoints: []
		};
		var geocoder = new google.maps.Geocoder();
		function initializeMap(mapID) {

			var mapOptions = {
			  center: { lat: 52.399055, lng: 4.815009},
			  zoom: 13,
			  mapTypeId: google.maps.MapTypeId.HYBRID,
			  disableDefaultUI: true
			};

			var mapOptions = $.extend(mapOptions, self.options);
			
			self.map = new google.maps.Map(document.getElementById(mapID),
				mapOptions);
			self.mapElement = $("#" + mapID);

			self.home = createMarker(route.origin, "Agidalex");
			self.directionsDisplay = new google.maps.DirectionsRenderer();
			self.directionsDisplay.setMap(self.map);
		}

		self.drawAll = function(callback){
			clearDrawings();
			$.each(locations.all, function(location, coords){
				var a = new google.maps.Polygon({
					paths: coords,
					strokeColor: "#fff",
					strokeOpacity: 0.8,
					strokeWeight: 2,
					fillColor: "#fff",
					fillOpacity: 0.35,
					map:map
				});

				drawing.push(a);
			});

			if ( typeof(callback) == "function" ){
				callback();
			}
		};

		self.drawLocations = function(locations, callback){
			$.each(locations, function(i, location){
				var area = location[1];
				location = location[0];
				var locationData = self.locations.all[location];
				var drawnLocation = self.locations.drawn[location];
				if( typeof(locationData) == "undefined" ){
					return;
				}
				// Get the color
				var color = getColor(area);
				// Get the coordinates
				var coords = locationData.coords;
				if( typeof( drawnLocation) != "undefined" ){
					drawnLocation.setMap(self.map);
				}
				else{
					// Create a new Polygon and draw it on the map
					var a = new google.maps.Polygon({
						paths: coords,
						strokeColor: color,
						strokeOpacity: 1,
						strokeWeight: 2,
						fillColor: color,
						fillOpacity: 0.25,
						map: self.map,
						title: location
					});
					// Save the drawing for removing
					drawing.push(a);
					// Save the drawing for hiding and cache
					self.locations.drawn[location] = a;
				}
			// Get the center of the locations
			var locationCenter = getLocationCenter(coords);
			var centerCoords = new google.maps.LatLng(locationCenter.y, locationCenter.x);
			self.locationCenters[location] = centerCoords;

			});
			if ( typeof(callback) == "function" ){
				callback();
			}
		}

		self.drawRoute = function(from, to){
			var ds = new google.maps.DirectionsService();
			var directionsOptions = {
				origin: from,
				destination: to,
				travelMode: google.maps.DirectionsTravelMode.DRIVING,
				optimizeWaypoints: true
			};
			ds.route(directionsOptions, function(response, status) {
			  	if (status == google.maps.DirectionsStatus.OK) {
					routePath = response.routes[0].overview_path;
					for(var a = 0; a< routePath.length; a++){
				  		// Your vector layer to render points with line
					}
					self.directionsDisplay.setDirections(response);
					self.directionsDisplay.setMap(self.map);
					self.mapElement.trigger("routeDrawn");
			  	}
			});
		}

		self.removeLocations = function(locations, callback){
			$.each(locations, function(i, location){
				location = location[0];
				var drawnLocation = self.locations.drawn[location];
				if( typeof(drawnLocation) != "undefined" ){
					drawnLocation.setMap(null);
					delete self.locationCenters[location];
				}
			});
			if ( typeof(callback) == "function" ){
				callback();
			}
		}

		self.removeLocation = function(location){
			var drawnLocation = self.locations.drawn[location];
			if( typeof(drawnLocation) != "undefined" ){
				drawnLocation.setMap(null);
				delete self.locations.drawn[location];
				delete self.locationCenters[location];
				self.locationCount--;
			}
		}

		self.getStreetView = function(){
			// Clear drawings if they exist
			clearDrawings();
			var l = getLocation();
			var point = l.coords[0];
			sv.getPanoramaByLocation(point, 50, function(data, status){
				var panOptions = {
					position: data.location.latLng,
					disableDefaultUI: true
				};
				var panorama = new google.maps.StreetViewPanorama(document.getElementById('view'), panOptions);
				map.setStreetView(panorama);
			});
		}

		self.drawLocation = function(name, callback){
			clearDrawings();
			getLocation(name);
			var l = self.toDraw;
			var draw = function(){
				var l = self.toDraw;
				var coords = l.coords;
				var a = new google.maps.Polygon({
					paths: l.coords,
					strokeColor: "#fff",
					strokeOpacity: 0.9,
					strokeWeight: 4,
					fillColor: "#fff",
					fillOpacity: 0,
					map: self.map
				});
				var x1 = l.coords[0].F;
				var x2 = l.coords[2].F;
				var y1 = l.coords[0].A;
				var y2 = l.coords[2].A;
				var center = {
					x : x1 + ((x2 - x1) / 2),
					y : y1 + ((y2 - y1) / 2)
				}
				self.map.setCenter(new google.maps.LatLng(center.y, center.x));
				self.map.setZoom(17);
				if( typeof( callback ) != "undefined" ){
					google.maps.event.addListenerOnce(self.map, 'idle', function(){
						// do something only the first time the map is loaded
						callback();
					});
				}
				drawing.push(a);
				self.toDraw = undefined;
			}
			var waitFor = setInterval(function(){
				if( typeof(self.toDraw) != "undefined"){
					clearInterval(waitFor);
					draw();
				}
			}, 100);
		}

		self.routeToNextLocations = function(callback){
			var currentLocation = navigator.geolocation.getCurrentPosition(function(data){
				var closest = findClosest(data);
				var from = new google.maps.LatLng(data.coords.latitude, data.coords.longitude);
				var to = closest[0];
				self.drawRoute(from, to);
			    self.nextLocationName = closest[1];
			    if( typeof(callback) != "undefined" ){
			    	// Execute callback function when the route is drawn on the map
			    	self.mapElement.on("routeDrawn", callback);
			    }
			});
		};

		self.getNextLocation = function(callback){
			var currentLocation = navigator.geolocation.getCurrentPosition(function(data){
				var closest = findClosest(data);
				if( closest != false ){
				    self.nextLocationName = closest[1];
				    self.map.setCenter(closest[0]);
				    self.map.setZoom(16);
				}
				if( typeof(callback) != "undefined" ){
			    	// Execute callback function when the route is drawn on the map
			    	callback();
			    }
			});
		}

		self.showCurrentPosition = function(callback){
			var currentLocation = navigator.geolocation.getCurrentPosition(function(data){
				var lat = data.coords.latitude;
				var lng = data.coords.longitude;
				if( (lat + ',' + lng ) == self.currentPosition ){
					return;
				}
				var currentPosition = new google.maps.LatLng(lat,lng);
				var image = 'img/location.svg';

				if( !self.currentMarker ){
					var currentMarker = new google.maps.Marker({
						position: currentPosition,
						title: "Hier ben jij",
						icon: image,
						map: self.map
					});

					self.currentMarker = currentMarker;
				}
				else{
					self.currentMarker.setPosition(currentPosition);
				}

				self.currentPosition = lat + ',' + lng;
				self.map.setCenter(currentPosition);

				if( typeof(callback) != "undefined" ){
			    	// Execute callback function when the route is drawn on the map
			    	callback();
			    }
			});
		}

		self.hideRoute = function(){
			self.directionsDisplay.setMap(null);
		}

		self.drawHeatmap = function(coords){
			var mapData = [];
			$.each(coords, function(i, coord){
				mapData.push(new google.maps.LatLng(coord.y, coord.x));
			});

			var mapDataArray = new google.maps.MVCArray(mapData);

			heatmap = new google.maps.visualization.HeatmapLayer({
				data: mapDataArray,
				radius: 20
			});

			heatmap.setMap(self.map);
		};

		function getAddress(coords, callback){
			geocoder.geocode({'latLng': coords}, function(results, status) {
			    if (status == google.maps.GeocoderStatus.OK) {
			      if (results[0]) {
			      	callback(results[0].geometry.location);
			      } else {
			        alert('No results found');
			      }
			    } else {
			      alert('Geocoder failed due to: ' + status);
			    }
		  });
		}

		function findClosest(position){
			function rad(x) {return x*Math.PI/180;}

		    var lat = position.coords.latitude;
		    var lng = position.coords.longitude;
		    var R = 6371; // radius of earth in km
		    var distances = [];
		    var closest = -1;
		    $.each(self.locationCenters, function(i, location){
		    	var mlat = location.A;
		        var mlng = location.F;
		        var dLat  = rad(mlat - lat);
		        var dLong = rad(mlng - lng);
		        var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
		            Math.cos(rad(lat)) * Math.cos(rad(lat)) * Math.sin(dLong/2) * Math.sin(dLong/2);
		        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
		        var d = R * c;
		        distances[i] = d;
		        if ( closest == -1 || d < distances[closest] ) {
		            closest = i;
		        }
		    });
		    if( closest != -1 ){
		   		return [self.locationCenters[closest], closest];
		   	}
		   	else{
		   		return false;
		   	}
		}

		function getLocationCenter(coords){
			var x1 = coords[0].F;
			var x2 = coords[2].F;
			var y1 = coords[0].A;
			var y2 = coords[2].A;
			var center = {
				x : x1 + ((x2 - x1) / 2),
				y : y1 + ((y2 - y1) / 2)
			}
			return center;
		}

		function createMarker(coords, title){
			var title = title || "Marker";

			new google.maps.Marker({
				position: coords,
				title: title,
				map: self.map
			});
		}

		function clearDrawings(){
			if( drawing.length ){
				$.each(drawing, function(d, draw){
					draw.setMap(null);
				});
				drawing = [];
			}
		}


		function getLocation(name){
			if( typeof(name) == "undefined"){
				name = getRandomLocation();
			}
			$.ajax({
				url: "location/name/" + name,
				type: "GET",
				dataType: "json",
				success: function(data){
					self.toDraw =  {
						name : name,
						coords: [
							data.top_left,
							data.top_right,
							data.bottom_right,
							data.bottom_left,
							data.top_left
						]
					}
					$.each(self.toDraw.coords, function(index, coord){
						coord = coord.split(',');
						self.toDraw.coords[index] = new google.maps.LatLng(parseFloat(coord[0]), parseFloat(coord[1]));
					});
				}
			});
		}

		self.getAllLocations = function(){
			$.ajax({
				url: "locations/all/",
				type: "GET",
				dataType: "json",
				success: function(data){
					$.each(data, function(d, loc){
						self.locations.all[loc.name] = {
							coords: [
								loc.top_left,
								loc.top_right,
								loc.bottom_right,
								loc.bottom_left,
								loc.top_left
							]
						};
						$.each(self.locations.all[loc.name].coords, function(index, coord){
							coord = coord.split(',');
							self.locations.all[loc.name].coords[index] = new google.maps.LatLng(parseFloat(coord[0]), parseFloat(coord[1]));
						});
					});
					$(self.mapElement).trigger('locations-loaded');
				}
			});
		}

		function getRandomLocation(){
			var list = locations.measure.areas;
			var locationsArr = $.map(list, function( val, key ){
				return val;
			});
			var n = Math.round(Math.random() * locationsArr.length) + 1;
			return locationsArr[n];
		}

		function getColor(area){
			var color = "#fd0000";
			switch(area){
					case "101":
						color = "#00ffea";
						break;
					case "102":
						color = "#00ff4e";
						break;
					case "103":
						color = "#aeff00";
						break;
					case "104":
						color = "#ffd200";
						break;
					case "105":
						color = "#ff9c00";
						break;
					case "106":
						color = "#ff4e00";
						break;
					case "107":
						color = "#ea00ff";
						break;
					case "108":
						color = "#1e00ff";
						break;
					case "109":
						color = "#ff00c6";
						break;
					case "110":
						color = "#3b3b3b";
						break;
					case "111":
						color = "#b2afb1";
						break;
					case "112A":
						color = "#44c2d2";
						break;
					case "112B":
						color = "#ff00f3";
						break;
					case "112C":
						color = "#60459b";
						break;
					case "112D":
						color = "#fdd900";
						break;
					case "112E":
						color = "#57b947";
						break;
					case "112F":
						color = "#e01e25";
						break;
					case "121":
						color = "#51b687";
						break;
					case "122":
						color = "#316a50";
						break;
					case "123":
						color = "#90b8a6";
						break;
				}
			return color;
		}
		if( typeof(options.elementId) !== "undefined" ){
			initializeMap(options.elementId);
		}
		else{
			console.log("NO ELEMENT SET");
		}
	};

	Map = m;

})(jQuery, window);