<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
    <script src="https://api.tiles.mapbox.com/mapbox-gl-js/v0.53.1/mapbox-gl.js"></script>
    <script src="https://d3js.org/d3.v4.min.js"></script>
    <link href="https://api.tiles.mapbox.com/mapbox-gl-js/v0.53.1/mapbox-gl.css" rel="stylesheet" />
    <link href="https://api.mapbox.com/mapbox-assembly/v0.23.2/assembly.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        #key {
            background-color: rgba(0, 0, 0, 0.8);
            width: 22.22%;
            height: auto;
            overflow: auto;
            position: absolute;
            top: 0;
            left: 1%;
            margin-top: 5%;
        }

        .total {
            font-family: 'Montserrat', sans-serif;
            font-weight: 800;
            font-size: 15px;
        }

        .table {
            font-family: 'Montserrat', sans-serif;
            color: white;
            border-collapse: collapse;
        }





        #map { border-left: 1px solid #fff;
            position: absolute;
            left: 25%;
            width: 75%;
            top: 0;
            bottom: 0;
            height: 720px
        }

        .sidebar {
            width: 25%;
        }


        .pad2 {
            padding: 20px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        form.example input[type=text] {
            padding: 10px;
            font-size: 17px;
            border: 1px solid grey;
            float: left;
            width: 70%;
            background: #f1f1f1;
        }

        form.example button {
            float: left;
            width: 20%;
            padding: 10px;
            background: #2196F3;
            color: white;
            font-size: 17px;
            border: 1px solid grey;
            border-left: none;
            cursor: pointer;
        }

        form.example button:hover {
            background: #0b7dda;
        }

        form.example::after {
            content: "";
            clear: both;
            display: table;
        }


    </style>
</head>

<body>

<div id="map"></div>
<div id="key"></div>


<div class='sidebar' style="height: 800px">
    <div style="padding: 25px" id="pop" >
    </div>
</div>

</html>

<?php
$features = array();
foreach($map['map_data'] as $key => $orgexp)
{
    $features[] = array(
        'type' => 'Feature',
        'properties' => array('id' => $orgexp['pid'],'location'=> $orgexp['location'], 'description'=> $orgexp['description'], 'projectname'=> $orgexp['projectname'], 'sector'=> $orgexp['sector'], 'stage'=> $orgexp['stage'], 'sponsor'=> $orgexp['sponsor'], 'subsector'=> $orgexp['subsector'], 'slug'=> $orgexp['slug'], 'projectphoto'=> $orgexp['projectphoto'], 'description'=> $orgexp['description'], 'country'=> $orgexp['country'], 'totalbudget'=> $orgexp['totalbudget']),
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

<script>
    mapboxgl.accessToken = 'pk.eyJ1IjoibG9iZW5pY2hvdSIsImEiOiJjajdrb2czcDQwcHR5MnFycmhuZmo4eWwyIn0.nUf9dWGNVRnMApuhQ44VSw';

    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/lobenichou/cjto9zfpj00jq1fs7gajbuaas?fresh=true',
        center: [-79.381000, 43.646000],
        zoom: 1.8,
        center: [0, 20]
    });

    const colors = ['#8dd3c7', '#ffffb3', '#bebada', '#fb8072', '#80b1d3', '#fdb462', '#b3de69', '#fccde5', '#d9d9d9', '#bc80bd', '#ccebc5'];

    const colorScale = d3.scaleOrdinal()
        .domain(["water", "transport", "energy", "logistics", "social", "oil", "mining", "technology", "tourism", "urban", "others"])
        .range(colors)

    const water = ['==', ['get', 'sector'], 'Water'];
    const transport = ['==', ['get', 'sector'], 'Transport'];
    const energy = ['==', ['get', 'sector'], 'Energy'];
    const logistics = ['==', ['get', 'sector'], 'Logistics'];
    const social = ['==', ['get', 'sector'], 'Social'];
    const oil = ['==', ['get', 'sector'], 'Oil & Gas'];
    const mining = ['==', ['get', 'sector'], 'Mining & Related'];
    const technology = ['==', ['get', 'sector'], 'Information & Communication Technologies'];
    const tourism = ['==', ['get', 'sector'], 'Tourism & Related'];
    const urban = ['==', ['get', 'sector'], 'Urban Planning & Design'];
    const others = ['any',
        ['==', ['get', 'sector'], 'Cogeneration'],
        ['==', ['get', 'sector'], 'Storage'],
        ['==', ['get', 'sector'], 'Other'],
        ['==', ['get', 'sector'], 'Wave and Tidel'],
        ['==', ['get', 'sector'], 'Petcoke'],
        ['==', ['get', 'sector'], '']
    ];

    map.on('load', () => {
        // add a clustered GeoJSON source for powerplant
        map.addSource('powerplants', {
            'type': 'geojson',
            'data': <?php print_r($final_data); ?>,
            'cluster': true,
            'clusterRadius': 50,
            'clusterProperties': { // keep separate counts for each fuel category in a cluster
                'water': ['+', ['case', water, 1, 0]],
                'transport': ['+', ['case', transport, 1, 0]],
                'energy': ['+', ['case', energy, 1, 0]],
                'logistics': ['+', ['case', logistics, 1, 0]],
                'social': ['+', ['case', social, 1, 0]],
                'oil': ['+', ['case', oil, 1, 0]],
                'mining': ['+', ['case', mining, 1, 0]],
                'technology': ['+', ['case', technology, 1, 0]],
                'tourism': ['+', ['case', tourism, 1, 0]],
                'urban': ['+', ['case', urban, 1, 0]],
                'others': ['+', ['case', others, 1, 0]]
            }
        });

        map.addLayer({
            'id': 'powerplant_individual',
            'type': 'circle',
            'source': 'powerplants',
            'filter': ['!=', ['get', 'cluster'], true],
            'paint': {
                'circle-color': ['case',
                    water, colorScale('water'),
                    transport, colorScale('transport'),
                    energy, colorScale('energy'),
                    logistics, colorScale('logistics'),
                    social, colorScale('social'),
                    oil, colorScale('oil'),
                    mining, colorScale('mining'),
                    technology, colorScale('technology'),
                    tourism, colorScale('tourism'),
                    urban, colorScale('urban'),
                    others, colorScale('others'), '#ffed6f'
                ],
                'circle-radius': 5
            }
        });

        map.addLayer({
            'id': 'powerplant_individual_outer',
            'type': 'circle',
            'source': 'powerplants',
            'filter': ['!=', ['get', 'cluster'], true],
            'paint': {
                'circle-stroke-color': ['case',
                    water, colorScale('water'),
                    transport, colorScale('transport'),
                    energy, colorScale('energy'),
                    logistics, colorScale('logistics'),
                    social, colorScale('social'),
                    oil, colorScale('oil'),
                    mining, colorScale('mining'),
                    technology, colorScale('technology'),
                    tourism, colorScale('tourism'),
                    urban, colorScale('urban'),
                    others, colorScale('others'), '#ffed6f'
                ],
                'circle-stroke-width': 2,
                'circle-radius': 10,
                'circle-color': "rgba(0, 0, 0, 0)"
            }
        });



        let markers = {};
        let markersOnScreen = {};
        let point_counts = [];
        let totals;

        const getPointCount = (features) => {
            features.forEach(f => {
                if (f.properties.cluster) {
                    point_counts.push(f.properties.point_count)
                }
            })

            return point_counts;
        };

        const updateMarkers = () => {
            document.getElementById('key').innerHTML = '';
            let newMarkers = {};
            const features = map.querySourceFeatures('powerplants');
            totals = getPointCount(features);
            features.forEach((feature) => {
                const coordinates = feature.geometry.coordinates;
                const props = feature.properties;

                if (!props.cluster) {
                    return;
                };


                const id = props.cluster_id;

                let marker = markers[id];
                if (!marker) {
                    const el = createDonutChart(props, totals);
                    marker = markers[id] = new mapboxgl.Marker({
                        element: el
                    })
                        .setLngLat(coordinates)
                }

                newMarkers[id] = marker;

                if (!markersOnScreen[id]) {
                    marker.addTo(map);
                }
            });

            for (id in markersOnScreen) {
                if (!newMarkers[id]) {
                    markersOnScreen[id].remove();
                }
            }
            markersOnScreen = newMarkers;
        };

        const createDonutChart = (props, totals) => {
            const div = document.createElement('div');
            const data = [{
                type: 'water',
                count: props.water
            },
                {
                    type: 'transport',
                    count: props.transport
                },
                {
                    type: 'energy',
                    count: props.energy
                },
                {
                    type: 'social',
                    count: props.social
                },
                {
                    type: 'logistics',
                    count: props.logistics
                },
                {
                    type: 'oil',
                    count: props.oil
                },
                {
                    type: 'mining',
                    count: props.mining
                },
                {
                    type: 'technology',
                    count: props.technology
                },
                {
                    type: 'tourism',
                    count: props.tourism
                },
                {
                    type: 'urban',
                    count: props.urban
                },
                {
                    type: 'others',
                    count: props.others
                },
            ];

            const thickness = 10;
            const scale = d3.scaleLinear()
                .domain([d3.min(totals), d3.max(totals)])
                .range([500, d3.max(totals)])

            const radius = Math.sqrt(scale(props.point_count));
            const circleRadius = radius - thickness;

            const svg = d3.select(div)
                .append('svg')
                .attr('class', 'pie')
                .attr('width', radius * 2)
                .attr('height', radius * 2);

            //center
            const g = svg.append('g')
                .attr('transform', `translate(${radius}, ${radius})`);

            const arc = d3.arc()
                .innerRadius(radius - thickness)
                .outerRadius(radius);

            const pie = d3.pie()
                .value(d => d.count)
                .sort(null);

            const path = g.selectAll('path')
                .data(pie(data.sort((x, y) => d3.ascending(y.count, x.count))))
                .enter()
                .append('path')
                .attr('d', arc)
                .attr('fill', (d) => colorScale(d.data.type))

            const circle = g.append('circle')
                .attr('r', circleRadius)
                .attr('fill', 'rgba(0, 0, 0, 0.7)')
                .attr('class', 'center-circle')

            const text = g
                .append("text")
                .attr("class", "total")
                .text(props.point_count_abbreviated)
                .attr('text-anchor', 'middle')
                .attr('dy', 5)
                .attr('fill', 'white')

            const infoEl = createTable(props);

            svg.on('click', () => {
                d3.selectAll('.center-circle').attr('fill', 'rgba(0, 0, 0, 0.7)')
                circle.attr('fill', 'rgb(71, 79, 102)')
                document.getElementById('pop').innerHTML = '';
                document.getElementById('key').innerHTML = '';
                document.getElementById('key').append(infoEl);
            })

            return div;
        }

        const createTable = (props) => {
            const getPerc = (count) => {
                return count / props.point_count;
            };

            const data = [{
                type: 'water',
                perc: getPerc(props.water)
            },
                {
                    type: 'transport',
                    perc: getPerc(props.transport)
                },
                {
                    type: 'energy',
                    perc: getPerc(props.energy)
                },
                {
                    type: 'social',
                    perc: getPerc(props.social)
                },
                {
                    type: 'logistics',
                    perc: getPerc(props.logistics)
                },
                {
                    type: 'oil',
                    perc: getPerc(props.oil)
                },
                {
                    type: 'mining',
                    perc: getPerc(props.mining)
                },
                {
                    type: 'technology',
                    perc: getPerc(props.technology)
                },
                {
                    type: 'tourism',
                    perc: getPerc(props.tourism)
                },
                {
                    type: 'urban',
                    perc: getPerc(props.urban)
                },
                {
                    type: 'others',
                    perc: getPerc(props.others)
                },
            ];

            const columns = ['type', 'perc']
            const div = document.createElement('div');
            const table = d3.select(div).append('table').attr('class', 'table')
            const thead = table.append('thead')
            const tbody = table.append('tbody');

            thead.append('tr')
                .selectAll('th')
                .data(columns).enter()
                .append('th')
                .text((d) => {
                    let colName = d === 'perc' ? '%' : 'Project Sector'
                    return colName;
                })

            const rows = tbody.selectAll('tr')
                .data(data.filter(i => i.perc).sort((x, y) => d3.descending(x.perc, y.perc)))
                .enter()
                .append('tr')
                .style('border-left', (d) => `20px solid ${colorScale(d.type)}`);

            // create a cell in each row for each column
            const cells = rows.selectAll('td')
                .data((row) => {
                    return columns.map((column) => {
                        let val = column === 'perc' ? d3.format(".2%")(row[column]) : row[column];
                        return {
                            column: column,
                            value: val
                        };
                    });
                })
                .enter()
                .append('td')
                .text((d) => d.value)
                .style('text-transform', 'capitalize')

            return div;
        }

        map.on('data', (e) => {
            if (e.sourceId !== 'powerplants' || !e.isSourceLoaded) return;

            map.on('move', updateMarkers);
            map.on('moveend', updateMarkers);
            updateMarkers();
        });

        // Change the cursor to a pointer when the mouse is over the places layer.
        map.on('mouseenter', 'powerplant_individual', function() {
            map.getCanvas().style.cursor = 'pointer';
        });

// Change it back to a pointer when it leaves.
        map.on('mouseleave', 'powerplant_individual', function() {
            map.getCanvas().style.cursor = '';
        });

        map.on('click', 'powerplant_individual', function(e) {
            document.getElementById('key').innerHTML = '';

            var coordinates = e.features[0].geometry.coordinates.slice();
            var projectname = e.features[0].properties.projectname;
            var projectphoto = e.features[0].properties.projectphoto;
            var slug = e.features[0].properties.slug;
            var location = e.features[0].properties.location;
            var stage = e.features[0].properties.stage;
            var sponsor = e.features[0].properties.sponsor;
            var description = e.features[0].properties.description;
            var subsector = e.features[0].properties.subsector;
            var totalbudget = e.features[0].properties.totalbudget;

            if (stage == "om") {
                stage = "Operation and Maintenance";
            }

            stage = stage.charAt(0).toUpperCase() + stage.slice(1);






// Ensure that if the map is zoomed out such that multiple
// copies of the feature are visible, the popup appears
// over the copy being pointed to.
            while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
                coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
            }
            document.getElementById('pop').innerHTML = '';
            document.getElementById('pop').innerHTML =
                    "<div style=\"height: 300px; width: 300px\">"
                    +   "<div>"
                    +       "<h1 style=\"text-align: center; font-size: 20px\"> <strong>"
                    +       projectname
                    +       "</strong> </h1>"
                    +   "</div>"
                    +   "<div>"
                    +      "<img style=\"display: block; margin: auto; padding-top: 10px\" src=\'https://www.gvip.io/img/content_projects/" + projectphoto + "?crop=1&w=250&h=250\'>"
                    +   "</div>"
                    +   "<div>"
                    +       "<h2 style=\"text-align: center; margin: 10px; font-size: 15px\">" + location + "</h2>"
                    +       "<p> <strong>Stage:</strong> " + stage +  "</p>"
                    +       "<p> <strong> Sponsor: </strong>" + sponsor +  "</p>"
                    +        "<p style='height: 200px; overflow: scroll; padding-top: 10px'>" + description +  "</p>"
                    +       "<a style='margin-left: 25%' class=\"light_green\" href=\'/projects/" + slug + "\' role=\"button\">View Project</a>\n"
                    +"</div>";

        });
    });
</script>


<div class="clearfix" id="content">
		<!-- map -->

	<div class="column_1">
    <hr>
        <!-- Key Executives -->
        <section class="similar-experts group">
            <h2 class="shadow my_vip_header h2"><?php echo lang('MyVipKeyExecutives') ?></h2>
            <div>
                <ul class="reset">
                    <?php if (count($key_executives) == 0) { ?>
                        <li class="not_found">
                            <?php echo lang('MyVipKeyExecutivesNotFound'); ?>
                        </li>
                    <?php } ?>

                    <?php foreach($key_executives as $expert) { ?>
                    <?php $fullname = $expert['firstname'] . ' ' . $expert['lastname'] ?>
                    <li class="m_person">
                        <a href="/expertise/<?php echo $expert['uid'] ?>" class="image recommendation" data-recommendation-location="My GViP" data-recommendation-category="Expert" data-recommendation-section="Key Executives" data-recommendation-target-id="<?php echo $expert['uid'] ?>" data-recommendation-target-name="<?php echo $fullname ?>">
                            <img src="<?php echo expert_image($expert['userphoto']) ?>" alt="<?php echo $fullname ?>'s photo">
                        </a>
                        <p class="content">
                            <a href="/expertise/<?php echo $expert['uid'] ?>" class="recommendation" data-recommendation-location="My GViP" data-recommendation-category="Expert" data-recommendation-section="Key Executives" data-recommendation-target-id="<?php echo $expert['uid'] ?>" data-recommendation-target-name="<?php echo $fullname ?>"><?php echo $fullname ?></a>
                            <span class="title"><?php echo $expert['title'] ?></span>
                            <span class="title"><?php echo $expert['organization'] ?></span>
                        </p>
                    </li>
                <?php } ?>
                </ul>
            </div>
        </section>

        <!-- Similar Projects -->
        <section class="similar-projects group">
            <h2 class="shadow my_vip_header h2"><?php echo lang('MyVipSimilarProjects') ?></h2>
            <div>
                <?php if (count($similar_projects) == 0) { ?>
                    <p class="not_found">
                        <?php echo lang('MyVipSimilarProjectsNotFound'); ?>
                    </p>
                <?php } ?>
                <?php foreach ($similar_projects as $project) { ?>
                    <article class="m_project">
                        <div class="image">
                            <div class="image_wrap">
                                <a href="<?php echo '/projects/' . $project['id'] ?>" class="recommendation" data-recommendation-location="My GViP" data-recommendation-category="Project" data-recommendation-section="Similar Projects" data-recommendation-target-id="<?php echo $project['id'] ?>" data-recommendation-target-name="<?php echo $project['projectname'] ?>">
                                    <img src="<?php echo project_image($project['projectphoto']) ?>" alt="<?php echo $project['projectname'] . "'s photo" ?>">
                                </a>
                            </div>
                            <span class="ps_<?php echo project_stage_class($project['stage']) ?>"></span>
                            <span class="price"><?php echo format_budget($project['totalbudget']) ?></span>
                        </div>
                        <div class="content">
                            <h3 class="the_title"><a href="<?php echo '/projects/' . $project['id'] ?>" class="recommendation" data-recommendation-location="My GViP" data-recommendation-category="Project" data-recommendation-section="Similar Projects" data-recommendation-target-id="<?php echo $project['id'] ?>" data-recommendation-target-name="<?php echo $project['projectname'] ?>"><?php echo $project['projectname'] ?></a></h3>
                            <span class="type <?php echo project_sector_class($project['sector']) ?>"><?php echo ucfirst($project['sector']) ?></span>
                        </div>
                    </article>
                <?php } ?>
            </div>
        </section>

        <!-- My Projects -->
		<section class="my-projects group">
            <h2 class="shadow my_vip_header h2"><?php echo lang('MyVipMyProjects') ?></h2>
            <div>
                <?php if (count($my_projects) > 0) { ?>
                <?php foreach ($my_projects as $project) { ?>
                <article class="m_project">
                    <div class="image">
                        <div class="image_wrap">
                            <a href="<?php echo '/projects/' . $project['id'] ?>" class="recommendation" data-recommendation-location="My GViP" data-recommendation-category="Project" data-recommendation-section="My Projects" data-recommendation-target-id="<?php echo $project['id'] ?>" data-recommendation-target-name="<?php echo $project['projectname'] ?>">
                                <img src="<?php echo project_image($project['projectphoto']) ?>" alt="<?php echo $project['projectname'] . "'s photo" ?>">
                            </a>
                        </div>
                        <span class="ps_<?php echo project_stage_class($project['stage']) ?>"></span>
                        <span class="price"><?php echo format_budget($project['totalbudget']) ?></span>
                    </div>
                    <div class="content">
                        <h3 class="the_title"><a href="<?php echo '/projects/' . $project['id'] ?>" class="recommendation" data-recommendation-location="My GViP" data-recommendation-category="Project" data-recommendation-section="My Projects" data-recommendation-target-id="<?php echo $project['id'] ?>" data-recommendation-target-name="<?php echo $project['projectname'] ?>"><?php echo $project['projectname'] ?></a></h3>
                        <span class="type <?php echo project_sector_class($project['sector']) ?>"><?php echo ucfirst($project['sector']) ?></span>
                    </div>
                </article>
                <?php } ?>
                <div class="more_link">
                    <a href="/mygvip/myprojects"><?php echo lang('ViewMore') ?></a>
                </div>
                <?php } else { ?>
                    <p class="not_found">
                        <?php echo lang('MyVipMyProjectsNotFound'); ?>
                    </p>
                <?php } ?>
            </div>
        </section>

        <!-- My Experts -->
        <section class="similar-experts group">
            <h2 class="shadow my_vip_header h2"><?php echo lang('MyVipMyExperts') ?></h2>
            <div>
                <ul class="reset">
                    <?php foreach($my_experts as $expert) { ?>
                        <li class="m_person">
                            <a href="/expertise/<?php echo $expert['uid'] ?>" class="image recommendation" data-recommendation-location="My GViP" data-recommendation-category="Expert" data-recommendation-section="My Experts" data-recommendation-target-id="<?php echo $expert['uid'] ?>" data-recommendation-target-name="<?php echo $expert['fullname'] ?>">
                                <img src="<?php echo expert_image($expert['userphoto']) ?>" alt="<?php echo $expert['fullname'] ?>'s photo">
                            </a>
                            <p class="content">
                                <a href="/expertise/<?php echo $expert['uid'] ?>" class="recommendation" data-recommendation-location="My GViP" data-recommendation-category="Expert" data-recommendation-section="My Experts" data-recommendation-target-id="<?php echo $expert['uid'] ?>" data-recommendation-target-name="<?php echo $expert['fullname'] ?>"><?php echo $expert['fullname'] ?></a>
                                <span class="title"><?php echo $expert['title'] ?></span>
                                <span class="title"><?php echo $expert['organization'] ?></span>
                            </p>
                        </li>
                    <?php } ?>
                    <?php if (empty($my_experts)) { ?>
                        <li class="not_found m_person"><?php echo lang('MyVipMyExpertsNotFound') ?></li>
                    <?php } ?>
                </ul>
                <?php if (! empty($my_experts)) { ?>
                <div class="more_link">
                    <a href="/mygvip/myexperts"><?php echo lang('ViewMore') ?></a>
                </div>
                <?php } ?>
                <div class="more_link">
                    <a href="/mygvip/myfollowers"><?php echo lang('ViewMyFollowers') ?></a>
                </div>
            </div>
        </section>

        <!-- New Experts -->
        <section class="similar-experts group">
            <h2 class="shadow my_vip_header h2"><?php echo lang('MyVipNewExperts') ?></h2>
            <div>
                <ul class="reset">
                    <?php foreach($new_experts as $expert) { ?>
                        <?php $fullname = $expert['firstname'] . ' ' . $expert['lastname'] ?>
                        <li class="m_person">
                            <a href="/expertise/<?php echo $expert['uid'] ?>" class="image recommendation" data-recommendation-location="My GViP" data-recommendation-category="Expert" data-recommendation-section="New Experts" data-recommendation-target-id="<?php echo $expert['uid'] ?>" data-recommendation-target-name="<?php echo $fullname ?>">
                                <img src="<?php echo expert_image($expert['userphoto']) ?>" alt="<?php echo $fullname ?>'s photo">
                            </a>
                            <p class="content">
                                <a href="/expertise/<?php echo $expert['uid'] ?>" class="recommendation" data-recommendation-location="My GViP" data-recommendation-category="Expert" data-recommendation-section="New Experts" data-recommendation-target-id="<?php echo $expert['uid'] ?>" data-recommendation-target-name="<?php echo $fullname ?>"><?php echo $fullname ?></a>
                                <span class="title"><?php echo $expert['title'] ?></span>
                                <span class="title"><?php echo $expert['organization'] ?></span>
                            </p>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </section>

        <!-- My Discussions -->
        <?php if (! empty($my_discussions)) { ?>
        <section class="similar-experts group">
            <h2 class="shadow my_vip_header h2"><?php echo mb_convert_case(lang('MyVipMyDiscussions'), MB_CASE_UPPER) ?></h2>
            <div>
                <ul class="reset">
                    <?php foreach($my_discussions as $discussion) { ?>
                        <li class="m_person">
                            <a href="/projects/discussions/<?php echo $discussion['project_id'] ?>/<?php echo $discussion['id'] ?>" class="image recommendation" data-recommendation-location="My GViP" data-recommendation-category="Discussion" data-recommendation-section="My Discussions" data-recommendation-target-id="<?php echo $discussion['id'] ?>" data-recommendation-target-name="<?php echo $discussion['title'] ?>">
                                <img src="<?php echo safe_image(USER_NO_IMAGE_PATH, DISCUSSION_IMAGE_PLACEHOLDER, null, array('max' => 50)) ?>" alt="Discussion's photo">
                            </a>
                            <p class="content">
                                <a href="/projects/discussions/<?php echo $discussion['project_id'] ?>/<?php echo $discussion['id'] ?>" class="recommendation" data-recommendation-location="My GViP" data-recommendation-category="Discussion" data-recommendation-section="My Discussions" data-recommendation-target-id="<?php echo $discussion['id'] ?>" data-recommendation-target-name="<?php echo $discussion['title'] ?>"><?php echo $discussion['title'] ?></a>
                            </p>
                        </li>
                    <?php } ?>
                </ul>
                <div class="more_link">
                    <a href="/mygvip/mydiscussions"><?php echo lang('ViewMore') ?></a>
                </div>
            </div>
        </section>
        <?php } ?>

        <!-- gvip store -->
        <!--  If store items not found don't show the whole section -->
        <?php if (count($store_items) > 0) { ?>
		<section class="gvip-store group">
            <h2 class="shadow my_vip_header h2"><?php echo lang('MyVipGvipStore') ?></h2>
            <div>
                <ul class="m_store reset">
                    <?php foreach($store_items as $item) { ?>
                    <li class="item">
                        <a href="<?php echo $item['url'] ?>" class="recommendation" data-recommendation-location="My GViP" data-recommendation-category="Product" data-recommendation-section="GViP Store" data-recommendation-target-id="<?php echo $item['url'] ?>" data-recommendation-target-name="<?php echo $item['title'] ?>">
                            <img src="<?php echo store_item_image($item['photo'], 50) ?>" alt="Store item's photo">
                            <span><?php echo $item['title'] ?></span>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
		</section>
        <?php } ?>
    </div>
    <hr>
		<!-- news feed -->
		<section class="column_2">
            <h2 class="shadow my_vip_header h2"><?php echo lang('MyVipUpdatesTitle') ?></h2>

            <ul class="feed updates">
                <!-- populated from JS -->
            </ul>
            <div class="center">
                <?php echo form_open('updates/myvip', 'name="updates_view_more"'); ?>
                <input type="submit" class="view-more button" value="<?php echo lang('LoadMoreUpdates') ?>">
                <?php echo form_close() ?>
            </div>
		</section>
	</div>
</div>
