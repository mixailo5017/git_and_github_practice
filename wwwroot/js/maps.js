/*
	@TODO: Clean this up....
*/

$(function(){
	if ($(".map_box").length > 0) {
		/* Verify that there are coords to plot, kill the map if not */
		if ($(".map_box .coord").length === 0) {	
			$(".map_box").remove();
			return true;
		}
	
		/* Add Terrain Tiles */
		var terrain = new OpenLayers.Layer.XYZ(
		    "Terrain",
		    [
		        "http://a.tiles.mapbox.com/v3/visualchefs.map-0aompnax/${z}/${x}/${y}.png",
		        "http://b.tiles.mapbox.com/v3/visualchefs.map-0aompnax/${z}/${x}/${y}.png",
		        "http://c.tiles.mapbox.com/v3/visualchefs.map-0aompnax/${z}/${x}/${y}.png",
		        "http://d.tiles.mapbox.com/v3/visualchefs.map-0aompnax/${z}/${x}/${y}.png"
		    ], {
		        attribution: "Tiles &copy; <a href='http://mapbox.com/'>MapBox</a> | " + 
		            "Data &copy; <a href='http://www.openstreetmap.org/'>OpenStreetMap</a> " +
		            "and contributors, CC-BY-SA",
		        sphericalMercator: true,
		        wrapDateLine: true,
		        transitionEffect: "resize",
		        buffer: 1,
		        numZoomLevels: 4
		    }
		);

		/* Add Street Tiles */
		var street = new OpenLayers.Layer.XYZ(
		    "Streets",
		    [
		        "http://a.tiles.mapbox.com/v3/visualchefs.map-j3pzdtkb/${z}/${x}/${y}.png",
		        "http://b.tiles.mapbox.com/v3/visualchefs.map-j3pzdtkb/${z}/${x}/${y}.png",
		        "http://c.tiles.mapbox.com/v3/visualchefs.map-j3pzdtkb/${z}/${x}/${y}.png",
		        "http://d.tiles.mapbox.com/v3/visualchefs.map-j3pzdtkb/${z}/${x}/${y}.png"
		    ], {
		        attribution: "Tiles &copy; <a href='http://mapbox.com/'>MapBox</a> | " + 
		            "Data &copy; <a href='http://www.openstreetmap.org/'>OpenStreetMap</a> " +
		            "and contributors, CC-BY-SA",
		        sphericalMercator: true,
		        wrapDateLine: true,
		        transitionEffect: "resize",
		        buffer: 1,
		        numZoomLevels: 17,
		    }
		);
		
		/* Add Aerial Tiles*/
		var aerial = new OpenLayers.Layer.OSM(
			"Aerial",
			[
				"http://oatile1.mqcdn.com/tiles/1.0.0/sat/${z}/${x}/${y}.jpg",
				"http://oatile2.mqcdn.com/tiles/1.0.0/sat/${z}/${x}/${y}.jpg",
				"http://oatile3.mqcdn.com/tiles/1.0.0/sat/${z}/${x}/${y}.jpg",
				"http://oatile4.mqcdn.com/tiles/1.0.0/sat/${z}/${x}/${y}.jpg"
			], {
				attribution: "Tiles Courtesy of <a href='http://www.mapquest.com/'>MapQuest</a> <img src='http://developer.mapquest.com/content/osm/mq_logo.png'>",
		        sphericalMercator: true,
		        wrapDateLine: true
		    }			
		);
                        		
		/* Set Projection */
		proj = new OpenLayers.Projection("EPSG:4326");

		/* Add Map */
		var map = new OpenLayers.Map({
			sphericalMercator: true,
			maxExtent: new OpenLayers.Bounds(-20037508.34, -20037508.34, 20037508.34, 20037508.34),
		    div: "project-map",
		    layers: [street, terrain, aerial],
		    controls: [
		        new OpenLayers.Control.Attribution(),
		        new OpenLayers.Control.LayerSwitcher(),
		        new OpenLayers.Control.Navigation({
		            dragPanOptions: {
		                enableKinetic: true
		            },
		            zoomWheelEnabled: false
		        }),
		        new OpenLayers.Control.Zoom(),
		        new OpenLayers.Control.Permalink({anchor: true})
		    ],
		    zoom: 4
		});
		
		/* Add markers */
		var markers = new OpenLayers.Layer.Markers( "Markers" );
		map.addLayer(markers);
		
		var size = new OpenLayers.Size(25,41);
		var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
		var icon = new OpenLayers.Icon('http://leaflet.cloudmade.com/dist/images/marker-icon.png', size, offset);
		
		/* @TODO: Move map center outside of marker function */
		/* @TODO: Test with multiple markers */
		$(".map_box .coord").each(function(i, el) {
			lat = $(el).find(".latitude").text();
			lon = $(el).find(".longitude").text();
		
			markers.addMarker(new OpenLayers.Marker(new OpenLayers.LonLat(lon,lat).transform(proj, map.getProjectionObject()), icon.clone()));
			
			map.setCenter(new OpenLayers.LonLat(lon, lat).transform(proj, map.getProjectionObject()), 2);
		});
		
		/* Zoom to show all markers */
		/*var bounds = markers.getDataExtent();
		map.zoomToExtent(bounds, true);*/
		map.zoomOut();		
		
		/* Add click event to "map" links */
		$(".map_box").on("click", ".coord a", function(e){
			e.preventDefault();
			
			lat = $(this).closest(".coord").find(".latitude").text();
			lon = $(this).closest(".coord").find(".longitude").text();
									
			map.panTo(new OpenLayers.LonLat(lon, lat).transform(proj, map.getProjectionObject()), 13);
		});
		
		if ($("#OpenLayers_Control_MaximizeDiv_innerImage").length > 0) {
			$("#OpenLayers_Control_MaximizeDiv_innerImage").attr("src", "/images/site/map_control_closed.png");
		}
	}

	if ($(".points-map-itself").length > 0)
	{
		/* Add Street Tiles */
		var street = new OpenLayers.Layer.XYZ(
		    "Streets",
		    [
		        "http://a.tiles.mapbox.com/v3/visualchefs.map-j3pzdtkb/${z}/${x}/${y}.png",
		        "http://b.tiles.mapbox.com/v3/visualchefs.map-j3pzdtkb/${z}/${x}/${y}.png",
		        "http://c.tiles.mapbox.com/v3/visualchefs.map-j3pzdtkb/${z}/${x}/${y}.png",
		        "http://d.tiles.mapbox.com/v3/visualchefs.map-j3pzdtkb/${z}/${x}/${y}.png"
		    ], {
		        attribution: "Tiles &copy; <a href='http://mapbox.com/'>MapBox</a> | " + 
		            "Data &copy; <a href='http://www.openstreetmap.org/'>OpenStreetMap</a> " +
		            "and contributors, CC-BY-SA",
		        sphericalMercator: true,
		        wrapDateLine: true,
		        transitionEffect: "resize",
		        buffer: 1,
		        numZoomLevels: 17
		    }
		);
		
		/* Set Projection */
		proj = new OpenLayers.Projection("EPSG:4326");
		
		$('.points-map-itself').each(function(i) {
			var container = $(this).closest("li");
			
			/* Add Map */
			map = new OpenLayers.Map({
				sphericalMercator: true,
				maxExtent: new OpenLayers.Bounds(-20037508.34, -20037508.34, 20037508.34, 20037508.34),
			    div: this,
			    layers: [street],
			    controls: [
			        new OpenLayers.Control.Attribution(),
			        new OpenLayers.Control.Navigation({
			            dragPanOptions: {
			                enableKinetic: true
			            },
			            zoomWheelEnabled: false
			        }),
			        new OpenLayers.Control.Zoom(),
			        new OpenLayers.Control.Permalink({anchor: false})
			    ],
			    zoom: 3,
			    center: new OpenLayers.LonLat(-99.57875, 40.31723).transform(proj, 'EPSG:900913')
			});

			/* Add lat / long finder */
			map.events.register("click", map, function(e) {
				var position = map.getLonLatFromPixel(this.events.getMousePosition(e)).transform(map.getProjectionObject(), proj);
				
				$(container).find("input").eq(2).val(position.lat.toFixed(5));

				$(container).find("input").eq(3).val(position.lon.toFixed(5));
			});			
		});
	}
});