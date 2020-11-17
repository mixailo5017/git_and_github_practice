<?php
$features = array();
$averagecoordsx = 0;
$averagecoordsy = 0;
$count = 0;

foreach($project['proj'] as $projkey=>$orgexp)
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

    $averagecoordsx += $orgexp['lng'];
    $averagecoordsy += $orgexp['lat'];
    $count++;
}

$new_data = array(
    'type' => 'FeatureCollection',
    'features' => $features,
);
$final_data = json_encode($new_data, JSON_PRETTY_PRINT);

$averagecoordsx = $averagecoordsx / $count;
$averagecoordsy = $averagecoordsy / $count;


?>


<!-- MAIN MAP (PROJECTS, EXPERTS)-->
<div>
    <head>
        <meta charset='utf-8' />
        <title>Points on a map</title>
        <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
        <script src="https://api.mapbox.com/mapbox-gl-js/v1.11.1/mapbox-gl.js"></script>
        <link href="https://api.mapbox.com/mapbox-gl-js/v1.11.1/mapbox-gl.css" rel="stylesheet" />
        <script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.3.0/mapbox-gl-geocoder.min.js'></script>
        <link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.3.0/mapbox-gl-geocoder.css' type='text/css' />
    
    </head>
    <div id='map' style='width: 100%; height: 50em; border-radius:10px;'></div>



    <?php  if(empty($features)){ ?>
        <script>
            mapboxgl.accessToken = 'pk.eyJ1Ijoiam9obmJyaXNiYW5lIiwiYSI6ImNrMDN5czNjNDJhYWgzb3FkdDJxM3JtcXoifQ.o4w_VxKKH6oH1IP9sygfYg'; // replace this with your access token
            var map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/johnbrisbane/ck9bswlro0e371ip7g2tuispy', // replace this with your style URL
                center: [<?php echo $averagecoordsx; ?>, <?php echo $averagecoordsy; ?>],
                zoom: 2
            });
        </script>
    <?php }
    else { ?>



        <script>
            mapboxgl.accessToken = 'pk.eyJ1Ijoiam9obmJyaXNiYW5lIiwiYSI6ImNrMDN5czNjNDJhYWgzb3FkdDJxM3JtcXoifQ.o4w_VxKKH6oH1IP9sygfYg';
            var map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v11',
                center: [<?php echo $averagecoordsx; ?>, <?php echo $averagecoordsy; ?>],
                minZoom: 2,
                zoom: 4
            });

            var geocoder = new MapboxGeocoder({
                accessToken: 'pk.eyJ1Ijoiam9obmJyaXNiYW5lIiwiYSI6ImNrMDN5czNjNDJhYWgzb3FkdDJxM3JtcXoifQ.o4w_VxKKH6oH1IP9sygfYg'
            });


            map.on('load', function() {
                map.addSource('places', {
                    type: 'geojson',
                    data:<?php echo $final_data;?>
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

                map.addControl(new mapboxgl.NavigationControl());
                map.addControl(new mapboxgl.FullscreenControl());


// Create a popup, but don't add it to the map yet.
                var popup = new mapboxgl.Popup({
                    closeButton: true,
                    closeOnClick: true,
                    maxWidth: '350px'
                });

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
                            +       "<h1 style=\"text-align: center; font-size: 15px\"> <strong>"
                            +       projectname
                            +       "</strong> </h1>"
                            +       "</a>"
                            +   "</div>"
                            +   "<div>"
                            +      "<img style=\"display: block; margin: auto; padding-top: 10px\" src=\'https://www.gvip.io/img/content_projects/" + projectphoto + "?crop=1&w=200&h=200\'>"
                            +   "</div>"
                            +   "<div>"
                            +       "<p> <strong>Country:</strong> " + country + "<strong>     Stage:</strong> " + stage + "</p>"
                            +       "<p> <strong> Sponsor: </strong>" + sponsor +  "<strong>     Value: </strong>" + totalbudget +  "M</p>"
                            +       "<a class=\"light_green\" href=\'/projects/" + slug + "\' role=\"button\">View Project</a>\n"
                            +"</div>")
                        .addTo(map);
                });

                map.on('mouseleave', 'places', function() {
                    map.getCanvas().style.cursor = '';
                });
                map.on('mouseenter', 'places', function() {
                    map.getCanvas().style.cursor = 'pointer';
                });

            });
        </script>

    <?php } ?>
</div>
