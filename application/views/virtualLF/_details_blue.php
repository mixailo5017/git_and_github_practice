<?php
$features = array();
foreach($rows as $project => $orgexp)
{
    $features[] = array(
        'type' => 'Feature',
        'properties' => array('id' => $orgexp['pid'], 'jobs' =>  $model_obj->get_jobs_created($orgexp['pid']), 'location'=> $orgexp['location'], 'description'=> $orgexp['description'], 'projectname'=> $orgexp['projectname'], 'sector'=> $orgexp['sector'], 'stage'=> $orgexp['stage'], 'sponsor'=> $orgexp['sponsor'], 'subsector'=> $orgexp['subsector'], 'slug'=> $orgexp['slug'], 'projectphoto'=> $orgexp['projectphoto'], 'description'=> $orgexp['description'], 'country'=> $orgexp['country'], 'totalbudget'=> $orgexp['totalbudget']),
        'geometry' => array(
            'type' => 'Point',
            'coordinates' => array(
                $orgexp['lng'] + (rand(-1000,1000)*.00001),
                $orgexp['lat'] + (rand(-1000,1000)*.00001),
                1
            ),
        ),
    );
}
$new_data = array(
    'type' => 'FeatureCollection',
    'features' => $features,
);
$final_data = json_encode($new_data, JSON_PRETTY_PRINT);

?>


<!-- MAIN MAP (PROJECTS, EXPERTS)-->
<div>
    <head>
        <meta charset='utf-8' />
        <title>Points on a map</title>
        <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
        <script src="https://api.mapbox.com/mapbox-gl-js/v1.9.1/mapbox-gl.js"></script>
        <link href="https://api.mapbox.com/mapbox-gl-js/v1.9.1/mapbox-gl.css" rel="stylesheet" />
        <script src="https://npmcdn.com/@turf/turf@5.1.6/turf.min.js"></script>
        <script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.3.0/mapbox-gl-geocoder.min.js'></script>
        <link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.3.0/mapbox-gl-geocoder.css' type='text/css' />
        <style>
            body {
                margin: 0;
                padding: 0;
            }

            #menu {
                background: #fff;
                position: absolute;
                z-index: 1;
                top: 100px;
                left: 10px;
                border-radius: 3px;
                width: 120px;
                border: 1px solid rgba(0, 0, 0, 0.4);
                font-family: 'Open Sans', sans-serif;
            }

            #menu a {
                font-size: 13px;
                color: #404040;
                display: block;
                margin: 0;
                padding: 0;
                padding: 10px;
                text-decoration: none;
                border-bottom: 1px solid rgba(0, 0, 0, 0.25);
                text-align: center;
            }

            #menu a:last-child {
                border: none;
            }

            #menu a:hover {
                background-color: #f8f8f8;
                color: #404040;
            }

            #menu a.active {
                background-color: #3887be;
                color: #ffffff;
            }

            #menu a.active:hover {
                background: #3074a4;
            }
            .map-overlay {
                font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
                background-color: #fff;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
                border-radius: 3px;
                position: absolute;
                width: 16%;
                top: 50px;
                left: 16%;
                padding: 10px;
            }
            .map-overlay2 {
                font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
                background-color: #fff;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
                border-radius: 3px;
                position: absolute;
                width: 16%;
                top: 50px;
                left: 0px;
                padding: 10px;
            }
            .map-overlay3 {
                font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
                background-color: #fff;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
                border-radius: 3px;
                width: 50%;
                top: 50px;
                right: 0px;
                padding: 10px;
                position: absolute;
                z-index: 10;
            }
        </style>
    </head>
    <div id='map' style='width: 100%; height: 650px'></div>
    <nav id="menu"></nav>
    <div id="map-overlay" class="map-overlay"></div>
    <div id="map-overlay2" class="map-overlay2"></div>


    <?php  if(empty($features)){ ?>
        <script>
            mapboxgl.accessToken = 'pk.eyJ1Ijoiam9obmJyaXNiYW5lIiwiYSI6ImNrMDN5czNjNDJhYWgzb3FkdDJxM3JtcXoifQ.o4w_VxKKH6oH1IP9sygfYg'; // replace this with your access token
            var map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/johnbrisbane/ck9bswlro0e371ip7g2tuispy', // replace this with your style URL
                center: [-90.661557, 30.893748],
                zoom: 2.5
            });
        </script>
    <?php }
    else { ?>



        <script>
            mapboxgl.accessToken = 'pk.eyJ1Ijoiam9obmJyaXNiYW5lIiwiYSI6ImNrMDN5czNjNDJhYWgzb3FkdDJxM3JtcXoifQ.o4w_VxKKH6oH1IP9sygfYg';
            var map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v11',
                center: [-98, 38.88],
                minZoom: 2,
                zoom: 3
            });

            var geocoder = new MapboxGeocoder({
                accessToken: 'pk.eyJ1Ijoiam9obmJyaXNiYW5lIiwiYSI6ImNrMDN5czNjNDJhYWgzb3FkdDJxM3JtcXoifQ.o4w_VxKKH6oH1IP9sygfYg'
            });


            map.on('load', function() {
                map.addSource('places', {
                    'type': 'geojson',
                    'data': <?php print_r($final_data); ?>
                });

                map.addSource('counties', {
                    'type': 'vector',
                    'url': 'mapbox://mapbox.82pkq93d'
                });

                map.addSource('election', {
                    'type': 'vector',
                    'url': 'mapbox://mapbox.hist-pres-election-county'
                });


                // Add a layer showing the places.
                // Add a layer showing the places.
                map.addLayer({
                    'id': 'places',
                    'type': 'circle',
                    'source': 'places',
                    paint: {
                        'circle-radius': 5,
                        'circle-color': 'white',
                        'circle-stroke-width': 4,
                        'circle-opacity': 0.5,
                        'circle-stroke-color': [
                            'match',
                            ['get', 'sector'],
                            'Water',
                            '#0000ff',
                            'Energy',
                            '#00FF00',
                            'Transport',
                            '#e55e5e',
                            'Information & Communication Technologies',
                            '#000000',
                            'Logistics',
                            '#ffffff',
                            /* other */ '#FFFF99'
                        ]
                    }

                });

                map.addLayer(
                    {
                        'id': 'election',
                        'type': 'fill',
                        'source': 'election',
                        'source-layer': 'original',
                        'paint': {
                            'fill-outline-color': '#484896',
                            'fill-color': [
                                'match',
                                ['get', '2016_winner'],
                                'dem',
                                '#0000FF',
                                'rep',
                                '#FF0000',
                                /* other */ '#FFFF99'
                            ],
                            'fill-opacity': 0.75
                        }
                    });

                map.addLayer(
                    {
                        'id': 'counties-highlighted-jobs',
                        'type': 'fill',
                        'source': 'counties',
                        'source-layer': 'original',
                        'paint': {
                            'fill-outline-color': '#484896',
                            'fill-color': '#6e599f',
                            'fill-opacity': 0.75
                        },
                        'filter': ['in', 'FIPS', '']
                    },
                    'settlement-label'
                ); // Place polygon under these labels.
                map.addLayer(
                    {
                        'id': 'counties-highlighted-jobs-country',
                        'type': 'fill',
                        'source': 'counties',
                        'source-layer': 'original',
                        'paint': {
                            'fill-outline-color': '#484896',
                            'fill-color': '#6e599f',
                            'fill-opacity': 0.75
                        },
                        'filter': ['in', 'FIPS', '']
                    },
                    'settlement-label'
                ); // Place polygon under these labels.

                map.addLayer(
                    {
                        'id': 'counties',
                        'type': 'fill',
                        'source': 'counties',
                        'source-layer': 'original',
                        'paint': {
                            'fill-outline-color': 'rgba(0,0,0,0.1)',
                            'fill-color': 'rgba(0,0,0,0.1)'
                        }
                    },
                    'settlement-label'
                ); // Place polygon under these labels.

                map.addLayer(
                    {
                        'id': 'counties-highlighted',
                        'type': 'fill',
                        'source': 'counties',
                        'source-layer': 'original',
                        'paint': {
                            'fill-outline-color': '#484896',
                            'fill-color': '#6e599f',
                            'fill-opacity': 0.75
                        },
                        'filter': ['in', 'population', '']
                    },
                    'settlement-label'
                ); // Place polygon under these labels.

                map.addControl(new mapboxgl.NavigationControl());
                map.addControl(new mapboxgl.FullscreenControl());
                map.scrollZoom.disable();


// Create a popup, but don't add it to the map yet.
                var popup = new mapboxgl.Popup({
                    closeButton: true,
                    closeOnClick: true,
                    maxWidth: '350px'
                });

                var overlay = document.getElementById('map-overlay');
                var overlay2 = document.getElementById('map-overlay2');


                map.on('click', 'places', function(e) {
// Change the cursor style as a UI indicator.
                    map.getCanvas().style.cursor = 'pointer';

                    var coordinates = e.features[0].geometry.coordinates.slice();
                    var description = e.features[0].properties.description;
                    var projectname = e.features[0].properties.projectname;
                    var country = e.features[0].properties.country;
                    var stage = e.features[0].properties.stage;
                    var sponsor = e.features[0].properties.sponsor;
                    var totalbudget = e.features[0].properties.totalbudget;
                    var slug = e.features[0].properties.slug;
                    var projectphoto = e.features[0].properties.projectphoto;


// Ensure that if the map is zoomed out such that multiple
// copies of the feature are visible, the popup appears
// over the copy being pointed to.
                    while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
                        coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
                    }

// Populate the popup and set its coordinates
// based on the feature found.
                    popup
                        .setLngLat(coordinates)
                        .setHTML("<div class='card' style=\"width: 300px\">"
                            +   "<div>"
                            +       "<a href=\'/projects/" + slug + "\'>"
                            +       "<h1 style=\"text-align: center; font-size: 20px\"> <strong>"
                            +       projectname
                            +       "</strong> </h1>"
                            +       "</a>"
                            +   "</div>"
                            +   "<div>"
                            +      "<img style=\"display: block; margin: auto; padding-top: 10px\" src=\'https://www.gvip.io/img/content_projects/" + projectphoto + "?crop=1&w=250&h=200\'>"
                            +   "</div>"
                            +   "<div>"
                            +       "<p> <strong>Country:</strong> " + country +  "</p>"
                            +       "<p> <strong>Stage:</strong> " + stage +  "</p>"
                            +       "<p> <strong> Sponsor: </strong>" + sponsor +  "</p>"
                            +       "<p> <strong> Value: </strong>" + totalbudget +  "M</p>"
                            +       "<a class=\"light_green\" href=\'/projects/" + slug + "\' role=\"button\">View Project</a>\n"
                            +"</div>")
                        .addTo(map);
                });

                map.on('mousemove', 'counties', function(e) {
// Change the cursor style as a UI indicator.
                    map.getCanvas().style.cursor = 'pointer';

// Single out the first found feature.
                    var feature = e.features[0];

// Query the counties layer visible in the map. Use the filter
// param to only collect results that share the same county name.
                    var relatedFeatures = map.querySourceFeatures('counties', {
                        sourceLayer: 'original',
                        filter: ['in', 'COUNTY', feature.properties.COUNTY]
                    });

// Render found features in an overlay.
                    overlay.innerHTML = '';

                    var title = document.createElement('strong');
                    title.textContent =
                        feature.properties.COUNTY;

                    var population = document.createElement('div');
                    population.textContent =
                        'Total population: ' + feature.properties.population;

                    overlay.appendChild(title);
                    overlay.appendChild(population);
                    overlay.style.display = 'block';

// Add features that share the same county name to the highlighted layer.
                    map.setFilter('counties-highlighted', [
                        'in',
                        'population',
                        feature.properties.population
                    ]);

                });

                map.on('mouseleave', 'counties', function() {
                    map.getCanvas().style.cursor = '';
                    map.setFilter('counties-highlighted', ['in', 'population', '']);
                    overlay.style.display = 'none';
                });


                map.on('mouseenter', 'places', function(e) {

                    overlay2.innerHTML = '';

                    var title = document.createElement('strong');
                    title.textContent =
                        e.features[0].properties.projectname;

                    var population = document.createElement('div');
                    population.textContent =
                        'Total Jobs Created: ' + e.features[0].properties.jobs;

                    overlay2.appendChild(title);
                    overlay2.appendChild(population);
                    overlay2.style.display = 'block';



                    var d = e.features[0].properties.jobs;
                    var constant = 1/d*5000000000;

                    var searchResult = e.features[0].geometry.coordinates;

                    map.setFilter('places', [
                        'in',
                        'id',
                        e.features[0].properties.id
                    ]);

                    var features = map.querySourceFeatures('counties', {
                        sourceLayer: 'original',
                    });

                    function getDistance(lat1, lon1, lat2, lon2, unit) {
                        if ((lat1 == lat2) && (lon1 == lon2)) {
                            return 0;
                        }
                        else {
                            var radlat1 = Math.PI * lat1/180;
                            var radlat2 = Math.PI * lat2/180;
                            var theta = lon1-lon2;
                            var radtheta = Math.PI * theta/180;
                            var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
                            if (dist > 1) {
                                dist = 1;
                            }
                            dist = Math.acos(dist);
                            dist = dist * 180/Math.PI;
                            dist = dist * 60 * 1.1515;
                            if (unit=="K") { dist = dist * 1.609344 }
                            if (unit=="N") { dist = dist * 0.8684 }
                            return dist;
                        }
                    }

                    var i;
                    var countycoords;
                    var projectcoords;
                    var arr = [];
                    var d;

                    for (i = 0; i < features.length; i++) {

                        countycoords = features[i].geometry.coordinates[0][0];
                        projectcoords = searchResult;
                        d = getDistance(countycoords[1], countycoords[0], projectcoords[1], projectcoords[0],'M')

                        if (d > 300 && d < 500 && features[i].properties.population > 500000){
                            arr.push(features[i].properties.FIPS);
                        }
                        else if (d < 300 && d > 200 && features[i].properties.population > 300000){
                            arr.push(features[i].properties.FIPS);
                        }
                        else if (d < 200 && d > 150 && features[i].properties.population > 160000){
                            arr.push(features[i].properties.FIPS);
                        }
                        else if (d < 150 && d > 100 && features[i].properties.population > 80000){
                            arr.push(features[i].properties.FIPS);
                        }
                        else if (d < 100 && d > 50 && features[i].properties.population > 40000){
                            arr.push(features[i].properties.FIPS);
                        }
                        else if (d < 50){
                            arr.push(features[i].properties.FIPS);
                        }
                    }


                    function buildFilter(arr) {
                        var filter = ['in', 'FIPS'];

                        if (arr.length === 0) {
                            return filter;
                        }

                        for(var i = 0; i < arr.length; i += 1) {
                            filter.push(arr[i]);
                        }

                        return filter;
                    }

                    var filterBy = arr;
                    var myFilter = buildFilter(filterBy);

                    map.setFilter('counties-highlighted-jobs', myFilter);
                    map.setFilter('counties-highlighted-jobs-country',['>', 'population', constant]);



                });

                map.on('mouseleave', 'places', function() {
                    map.getCanvas().style.cursor = '';
                    map.setFilter('counties-highlighted-jobs', ['in', 'FIPS', '']);
                    map.setFilter('counties-highlighted-jobs-country',['in', 'FIPS', '']);
                    map.setFilter('places', undefined)
                    overlay2.innerHTML = '';
                });
                map.on('mouseenter', 'places', function() {
                    map.getCanvas().style.cursor = 'pointer';
                });



//Start of Toggle Layers
// enumerate ids of the layers
                /* var toggleableLayerIds = ['places', 'election'];
 // set up the corresponding toggle button for each layer
                 for (var i = 0; i < toggleableLayerIds.length; i++) {
                     var id = toggleableLayerIds[i];
                     var link = document.createElement('a');
                     link.href = '#';
                     link.className = 'active';
                     link.textContent = id;
                     link.onclick = function (e) {
                         var clickedLayer = this.textContent;
                         e.preventDefault();
                         e.stopPropagation();
                         var visibility = map.getLayoutProperty(clickedLayer, 'visibility');
 // toggle layer visibility by changing the layout object's visibility property
                         if (visibility === 'visible') {
                             map.setLayoutProperty(clickedLayer, 'visibility', 'none');
                             this.className = '';
                         } else {
                             this.className = 'active';
                             map.setLayoutProperty(clickedLayer, 'visibility', 'visible');
                         }
                     };
                     var layers = document.getElementById('menu');
                     layers.appendChild(link);
                 }
                 */

            });
        </script>

    <?php } ?>
</div>
