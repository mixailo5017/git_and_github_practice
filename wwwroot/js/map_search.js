var mapBoxMap = function() {

    // map_init is a global variable bootstrapped straight from PHP.
    var defaults = {
        zoom: 5,
        lat: 39.3319,
        lng: -76.643,
        forum: false,
        mapSelector: 'p_e_map',
        wrapSelector: 'map_wrapper',
        projSelector: 'map_projects',
        expSelector: 'map_experts',
        searchSel: 'map_search',
        mapToggleSel: 'input[name="show_me"]:checked',
        mapContentTypeSel: 'input[name="content_type"]:checked',
        usePins: false,
        setDimensions: false
    };

    var map,
        options,
        markers = {
            "project": [],
            "expert": []
        },
        init_post_data = {
            'bounds': getBounds,
            'init': true
        };

    var $map_projects = $('#' + defaults.projSelector),
        $open_project = $('.open_project'),
        $map_no_results = $('.map_options.no_results'),
        $map_loading = $('.map_options.loading'),
        $map_experts = $('#' + defaults.expSelector),
        $map_search = $('#' + defaults.searchSel),
        $map_toggle = $('#f1, #f2'),
        $map_select_ctype = $('#content_type');

    var expertListItemTmpl, projectListItemTmpl;
    var projectTmpl = _.template($('#projectPopupTmpl').html());
    var expertTmpl = _.template($('#expertPopupTmpl').html());

    // Stuff to do as soon as the DOM is ready;
    $.ajaxSetup({
        type: 'GET',
        datatype: 'json',
        // for CodeIgniter CSRF protection...
        data: {
            csrf_vip: $.cookie('csrf_cookie_vip')
        },
        timeout: 20000
    });

    /**
     * Sets up the search fields when they're being used.
     * @return {[type]} [description]
     */
    function initMapSearchFields() {
        if (options.searchtype !== undefined) {

            // // used for old radio buttons
            // $('input[name="show_me"]').filter('[value="'+options.searchtype+'"]').prop('checked', true);
            // // used for new select drop-down
            $('select[name="content_type"]').val(options.searchtype);

        }



        // TODO - Map this into an iterator and match selects to the actual fields
        function d(v) {
            return v !== undefined;
        }

        if (options.filters !== undefined) {
            var mf = options.filters;

            if (mf.discipline) {
                $('select[name="expert_discipline"]').val(mf.discipline);
            }

            if (d(mf.sector)) {
                $('select[name="sector"]').val(mf.sector);
            }

            if (d(mf.country)) {
                $('select[name="country"]').val(mf.country);
            }

            if (d(mf.revenue)) {
                $('select[name="revenue"]').val(mf.revenue);
            }

            if (d(mf.budget)) {
                $('select[name="budget"]').val(mf.budget);
            }

            if (d(mf.stage)) {
                $('select[name="project_stage"]').val(mf.stage);
            }

        }
    }

    function buildSelectBoxes() {
        $('.form_control').each(function(i, form) {
            var $sel = $('select', form);
            var $newEle = $('<div>', {
                'class': 'select_list'
            });
            $newEle.append('<div class="select_control"><p><span class="text"></span></p></div>');

            $newEle.append($('<ul>'));
            var $ulList = $('ul', $newEle);

            $('option', $sel).each(function(i, ele) {
                var $ele = $(ele),
                    $item = $('<li>').html('<span class="text">' + $(ele).html() + '</span>'),
                    optClass = $ele.attr('class');

                $item.attr('data-option', $ele.val());

                if (optClass) {
                    $item.prepend('<span class="' + optClass + '">&nbsp;</span>');
                }

                if ($ele.is(':selected')) {
                    $item.addClass('active');
                }

                $ulList.append($item);

            });

            $('p', $newEle).html($('.active', $newEle).html());
            $('p', $newEle).after('<span class="map-dropdown">&nbsp;</span>');

            $sel.after($newEle);
            bindSelectBoxEvents(form);
        });
    }

    // Accpets the whole .form_control element and handles it's events
    function bindSelectBoxEvents(ele) {
        $ele = $(ele);
        $ele.on('click', '.select_control', function(e) {
            $(this).parents('.form_control').toggleClass('open');
        });

        $ele.on('mouseleave', function(e) {
            $(this).removeClass('open');
        });

        $ele.on('click', 'li', $.proxy(function(e) {
            $tar = $(e.currentTarget);
            $('.active', ele).removeClass('active');
            $tar.addClass('active');
            $('select', this).val($tar.data('option')).change();
            $('p', this).html($tar.html());
            $(this).removeClass('open');
        }, ele));

        var selectElement = $ele.find("select");
        if (selectElement.attr("id") !== $map_select_ctype.attr("id")) {
            $('select', $ele).change($.proxy(function(e) {
                updateMap();
            }, this));
        }
    }

    function initMapEvents() {
        // Map drag function
        map.on('dragend', function() {
            updateMap();
        });

        // Map zoom function
        map.on('zoomend', function() {
            updateMap();
        });
    }

    function initSidebarEvents() {
        // Sidebar project ACTIONS
        $map_projects.on('click', 'a', function() {
            peClick(this);
            return false;
        });

        // Sidebar expert ACTION
        $map_experts.on('click', 'a', function() {
            peClick(this);
            return false;
        });

        $map_experts.on('mouseover', 'a', function() {
            peHover(this);
            return false;
        });

        $map_projects.on('mouseover', 'a', function() {
            peHover(this);
            return false;
        });
    }

    function initSearchFormEvents() {
        // Form Submit ACTION
        $map_search.submit(function(e) {
            e.preventDefault();
            updateMap();
        });
    }

    function initExpertsProjectsToggle() {
        // Toggle between Experts and Projects and set it on page load.
        $map_toggle.on('click', function() {
            toggleExpertsProjects($(this));
        });

        $map_select_ctype.on("change", function() {
            toggleExpertsProjects($(this));
            var selection = $(this).val();
            if (selection == "companies") {
                //document.getElementById("lightning_sound").play();
            }
        });
    }

    /**
     * This creates the leaflet.js map object.
     *
     * @param		{string or dom}	selector		This can be either a string for an id selector or an actual dom element (must be a div?)
     * @param		{array}					map_center	[lat, lng]
     * @param		{number}				map_zoom		Zoom level, 1 through 10 is reasonable.
     * @return	{object}										Returns a new map object.
     *
     */
    function initMap(selector, map_center, map_zoom) {
        // Init map
        // Create the map, setView is Latitude, Longitude and zoom for starting point
        var thisMap = L.map('p_e_map', {
            center: map_center,
            zoom: map_zoom,
            minZoom: 3,
            worldCopyJump: true
        });

        // add an OpenStreetMap tile layer
        // Remove attribution and the map fails
        // http://b.tile2.opencyclemap.org/transport/{z}/{x}/{y}.png
        // http://{s}.tile.osm.org/{z}/{x}/{y}.png
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(thisMap);

        return thisMap;
    }

    function init(init_values) {

        options = _.defaults(init_values, defaults);

        if (options.setDimensions === true) {
            setMapDimensions();
        }

        map = initMap(options.mapSelector, [options.lat, options.lng], options.zoom);

        if (options.hasSearch === true) {
            initMapSearchFields();
            // Need to init here because these aren't found on other pages.
            expertListItemTmpl = _.template($('#expertListItemTmpl').html());
            projectListItemTmpl = _.template($('#projectListItemTmpl').html());
            initSidebarEvents();
            initSearchFormEvents();
            toggleExpertsProjects($map_select_ctype);
            buildSelectBoxes();
        } else {
            updateMap();
        }

        if (options.forum === true) {
            //initMapSearchFields();
            $('select[name="content_type"]').val(options.searchtype);
            toggleExpertsProjects($map_select_ctype);

            buildSelectBoxes();
        }

        initExpertsProjectsToggle();
        initMapEvents();
    }

    /**
     * Accepts an array of projects or experts and loads them into map
     * @param  {array} collection  [description]
     * @param  {string} t          expert or project.
     * @return {[type]}            [description]
     */
    function loadCollection(collection, t) {
        clearMarkers(t);

        if (t === undefined || (t !== 'expert' && t !== 'project')) {
            throw new Error('type is required for load a collection');
        }

        // Essentially bails out if the collection is empty
        if (typeof(collection) !== 'object' || collection.length === 0) return false;

        collection = scatterMarkers(collection, 5);

        var $list = t === 'expert' ? $map_experts : $map_projects;

        $list.html('');
        $('.new_map').removeClass('show_loading');
        // $list.show();
        $.each(collection, function(i, v) {

            var c = i + 1,
                uid = 'uid_' + t.charAt(0) + '_' + v.p_id;

            if (options.hasSearch) {
                appendToListView(uid, c, v, t);
            }
            var myIcon = createMyIcon(uid, c, v, t);
            var marker = createMarker(v, myIcon, uid, t);
            markers[t].push(marker);
        });

        return true;
    }

    function createMarker(v, icon, uid, t) {
        var marker = L.marker(
                [v.p_lat, v.p_lng], {
                    icon: icon,
                    riseOnHover: true,
                    type: t,
                    uid: uid
                })
            .addTo(map)
            .on('click', function(e) {
                markerClickAction(e.target.options.uid, e.latlng.lat, e.latlng.lng, e.target.options.type);
            }, this);

        return marker;
    }

    function createMyIcon(uid, c, v, t) {
        // Using a local hack because experts always have a pin atm
        usePins = options.usePins;
        if (t === 'expert') {
            usePins = true;
        }

        var new_class = uid + " " + (usePins ? t + "_pin" : "numberedMarker");
        var tmpl = t == 'project' ? projectTmpl : expertTmpl;
        var popup = tmpl({
            i: c,
            o: v,
            t: t
        });

        var iSize = usePins ? [38, 38] : [26, 26];
        var iAnchor = usePins ? [38, 38] : null;

        var myIcon = L.divIcon({
            html: popup,
            className: new_class,
            iconSize: iSize,
            iconAnchor: iAnchor,
            popupAnchor: [38, 38]
        });

        return myIcon;
    }

    function appendToListView(uid, c, v, type) {
        if (type === 'expert') {
            $map_experts.append(expertListItemTmpl({
                uid: uid,
                i: c,
                p: v
            }));
        } else {
            $map_projects.append(projectListItemTmpl({
                uid: uid,
                i: c,
                p: v
            }));
        }
    }

    /**
     * This updates the map. It doesn't take any arguments but that's ok.
     *
     * @return {[type]} [description]
     */
    function updateMap() {
        toggleMapEvents(false);
        //console.log(arguments.callee.caller.toString());
        //var search_type = $('input[name="show_me"]:checked').val(), // Specific to search bar
        var search_type = $map_select_ctype.val();
        post_data = {
            "bounds": getBounds(),
            zoom: map.getZoom(),
            lat: map.getCenter().lat,
            lng: map.getCenter().lng,
            type: search_type,
            forum: options.forum,
            forum_id: options.forum_id
        };

        // Specific to having the search bar.
        $('select', $map_search).each(function(i, v) {
            var $v = $(v),
                n = $v.attr('name'),
                v = $v.val();
            if (v !== '') {
                post_data[n] = v;
            }
        });

        mapSearch(post_data, search_type);
    }

    function mapSearch(post_data, search_type) {
        search_type = search_type || "";

        $.ajax({
            type: "POST",
            url: '/api/search/map_search/' + search_type,
            data: post_data,
            dataType: 'json',
            success: function(return_data) {
                if (return_data.debug !== undefined) {
                    console.log(return_data.debug);
                }

                var p = false,
                    e = false,
                    c = false,
                    m = false;

                $('.new_map').removeClass('show_no_results');

                //Load Projects
                if (return_data.projects !== 'undefined' && return_data.projects.length > 0) {
                    p = loadCollection(return_data.projects, 'project');
                }
                //Load Experts
                if (return_data.experts !== 'undefined' && return_data.experts.length > 0) {
                    e = loadCollection(return_data.experts, 'expert');
                }
                //load Companies. Companies are pretty much the same as experts (for now).
                if (return_data.companies !== 'undefined' && return_data.companies.length > 0) {
                    c = loadCollection(return_data.companies, 'expert');
                }
                //load My Projects. A subset of projects that a user owns or follows.
                if (return_data.myprojects !== 'undefined' && return_data.myprojects.length > 0) {
                    m = loadCollection(return_data.myprojects, 'project');
                }

                //change "View Expert" to "View Company" on the map display
                if (c === true) {
                    $(".view_expert a").each(function(index) {
                        $(this).html("View Company");
                    });
                }

                //if( !p && !e && c.length == 0){
                if (!p && !e && !c && !m) {
                    // $map_loading.hide();
                    // $map_no_results.show();
                    $('.new_map').removeClass('show_loading').addClass('show_no_results');
                    clearMarkers("expert");
                }
            },
            complete: function() {
                setTimeout(function() {
                    toggleMapEvents(true);
                }, 500);
            }
        });
    }

    // Function is called on marker click for projects
    // The parameter matches the number in the array on the left.
    function peClick(obj) {
        var $obj = $(obj),
            type = (obj.id).indexOf('_p_') !== -1 ? 'project' : 'expert',
            uid = obj.id.replace('link_', '');
        markerClickAction(uid, $obj.attr('data-lat'), $obj.attr('data-lng'), type);
    }

    function peHover(obj) {
        var $obj = $(obj),
            type = (obj.id).indexOf('_p_') !== -1 ? 'project' : 'expert',
            uid = obj.id.replace('link_', ''),
            index = $obj.data('index');

        $tarMarker = $('.p_e_map .' + uid);
        $tarMarker.toggleClass('active');
        $tarMarker.css('z-index', 10000);

        // Bind an event listener that fires only once.
        $obj.one('mouseout', {
            tar: $tarMarker
        }, function(e) {
            e.data.tar.css('z-index', 1);
            e.data.tar.removeClass('active');
        });
    }

    function toggleMapEvents(enable) {
        // if(enable) {
        // 	map.dragging.enable();
        // 	map.touchZoom.enable();
        // 	map.doubleClickZoom.enable();
        // 	map.scrollWheelZoom.enable();
        // 	map.boxZoom.enable();
        // } else {
        // 	map.dragging.disable();
        // 	map.touchZoom.disable();
        // 	map.doubleClickZoom.disable();
        // 	map.scrollWheelZoom.disable();
        // 	map.boxZoom.disable();
        // }
    }

    /**
     * This function handles toggling between different filters. Listening for changes
     * on the select list is done elsewhere and then passed to this function.
     *
     * This looks at the select list and figures out what to turn on and off.
     * 
     * The class .show_loading is used to hide the sidebar and show the loading spinner
     * The class .show_experts and .show_projects are used to control what filters are
     * displayed on the toolbar.
     *
     * Then goes and updatesMap()
     * @param  {[type]} target [description]
     * @return {[type]}        [description]
     */
    function toggleExpertsProjects(target) {
        $('.new_map, .my_vip.map_filter').addClass('show_loading');

        if (target.is("select")) {
            if (target.val() == "projects" || target.val() == "myprojects") {
                target.closest('.new_map, .my_vip.map_filter').removeClass('show_experts show_companies').addClass('show_projects');
            } else if (target.val() == "experts" || target.val() == "companies") {
                //companies are the same kind of a construct as experts.
                target.closest('.new_map, .my_vip.map_filter').addClass('show_experts').removeClass('show_projects show_companies');
            }
        }

        updateMap();
    }

    function clearMarkers(type) {
        for (var propt in markers) {
            $.each(markers[propt], function(i, v) {
                map.removeLayer(v);
            });
            markers[propt] = [];
        }
    }

    function getBounds() {
        var mapBounds = map.getBounds();
        return {
            'north': mapBounds.getNorth(),
            'east': mapBounds.getEast(),
            'south': mapBounds.getSouth(),
            'west': mapBounds.getWest()
        };
    }

    /**
     * scatterMarkers does EXACTLY what you would expect.
     * It takes an array of markers usually what is coming back
     * from the server and iterates over the group to find markers
     * that have the same location and then offsets within a square
     * area.
     * @param  {array}    markers Should be array of objects with lat/lngs.
     * @param  {integer}  offset  Indicate in miles how far you want to scatter the markers
     * @return {array}            Returns the same object just with modified lat/lngs
     */
    function scatterMarkers(markers, offset) {

        // Combine keys so we can quickly figure out the duplicates
        _.each(markers, function(ele, i, list) {
            list[i].latlng = ele.p_lat + ele.p_lng;
            // Save index for later ;)
            list[i].ind = i;
        });

        // Returns an array of arrays grouped by their latlng
        var reducedExp = _.groupBy(markers, 'latlng');

        // Go through each of our groups
        _.each(reducedExp, function(ele) {

            if (ele.length > 1) { // If the group is bigger then 1 meaning there are duplicates

                // Group through the group and set the a new lat/lng
                _.each(ele, function(e) {

                    // Set it straight to the markers using our stashed index
                    // This might be too clever.
                    markers[e.ind].p_lat = offsetLByRndDist(e.p_lat, offset);
                    markers[e.ind].p_lng = offsetLByRndDist(e.p_lng, offset);
                }, this);

            }

        }, this); // _.each's last argument can be the context. We pass it the whole
        // way in so we can modify the original markers object.

        return markers;
    }

    /**
     * This fun little function will take a lat or lng and a distance in miles
     * and randomly move it positive or negative by said distance.
     * @param  {string, int, float} latOrlng A degree
     * @param  {integer}            distance Integer in miles, does not need to be a whole number
     * @return {float}              Returns the modified
     */
    function offsetLByRndDist(latOrlng, distance) {
        // There are appx 60 miles to a degree, it varies depending on where
        // you are in the world but this is easier for now.
        var degreeOffset = Math.round((distance / 60) * 1000);

        // Fancy math, may not need to be so fancy.
        var transform = (_.random(degreeOffset) * 0.001) * (_.random(1) == 1 ? 1 : -1);

        return (parseFloat(latOrlng) + transform).toFixed(6);
    }

    function markerClickAction(uid, lat, lng, type) {

        var k = '.' + uid,
            obj = $(k);

        //console.log( type, obj.hasClass('open-leaflet') );

        if (!obj.hasClass('open-leaflet')) {
            // pans to center of map and offsets by 100 pixels
            // Does this by converting the center to pixel coords
            // subtracts a 100 pixels then converts back to a lat
            // lng
            var latlngPoint = map.latLngToLayerPoint([lat, lng]);
            latlngPoint.y = latlngPoint.y - 100;
            map.panTo(map.layerPointToLatLng(latlngPoint));

            //hides open popups
            $('.open_project, .open_expert').fadeOut().closest('.leaflet-marker-icon').removeClass('open-leaflet');

            //opens correct popup
            obj.addClass('open-leaflet');
            var klass = '.open_' + type;

            //console.log( obj.closest(klass) );

            obj.find(klass).fadeIn().on('click', 'button.close', function(event) {
                $('.open_project, .open_expert').fadeOut().closest('.leaflet-marker-icon').removeClass('open-leaflet');
                event.stopPropagation();
                return false;
            }).on('click', 'a', function(event) {
                //console.log( 'goto ' + $(this).attr('href') );
                event.stopPropagation();
            });
        }
    }

    /**
     * This sets the height of elements on the MyViP Dashboard page.
     */
    function setMapDimensions(mapElements) {
        var _map = document.getElementById(options.mapSelector),
            _wrap = document.getElementById(options.wrapSelector),
            _mp = document.getElementById(options.projSelector),
            _me = document.getElementById(options.expSelector),
            rect = _map.getBoundingClientRect(),
            _height = document.documentElement.clientHeight - rect.top;

        _map.style.height = _height + 'px';
        _wrap.style.height = _height + 'px';
        _mp.style.height = _height + 'px';
        _me.style.height = _height + 'px';
    }

    function getMap() {
        return map;
    }

    return {
        init: function(ini) {
            init(ini);
        },
        map: function() {
            return map;
        }
    };

};

// Hide/show the fixed marker on disable/enable
// Update the location of the marker on save

L.Util.VIPUtils = L.Util.extend({

    layerToJson: function(layer) {
        return jQuery.toJSON(layer.toGeoJSON());
    },

    parseRawReverseGeoCode: function(response) {
        var location = response.results[0].locations[0];
        var html = "";

        if (location.street !== "") {
            html = location.street;
        }

        if (location.adminArea5 !== "") {
            if (html !== "") {
                html += ", ";
            }
            html += location.adminArea5;
        }

        // County, doesn't seem to be necesary
        if (location.adminArea4 !== "") {
            html += ", " + location.adminArea4;
        }

        if (location.adminArea3 !== "") {
            html += ", " + location.adminArea3;
        }

        if (location.adminArea1 !== "") {
            html += ", " + location.adminArea1;
        }

        return html;
    },

    // Reverse geocodes an address
    reverseGeocode: function(lat, lng, callback) {
        $.ajax({
            type: 'GET',
            url: '/api/search/reverse_geocode',
            context: this,
            data: {
                'lat': lat,
                'lng': lng
            },
            dataType: 'json',
            success: callback,
            error: function(jqXHR, textStatus, error) {
                throw new Error('Error fetching address');
            }
        });
    }
});

var AdvancedMapDraw = L.Class.extend({

    includes: L.Mixin.FixedMarker,

    options: {
        slug: '',
        mapData: []
    },

    initialize: function(map, options) {
        L.Util.setOptions(this, options);

        this.drawnItems = new L.featureGroup();
        this._map = map;
        this.wicketUtil = new Wkt.Wkt();
        this.slug = this.options.slug;
        this.mapData = this.options.mapData;
        this.$editLocation = $('a.edit_location');
        this.$cancelEditLocation = $('a.cancel_location');

        var myIcon = L.icon({
            iconUrl: '/images/map/marker-gray.png',
            iconSize: [26, 41]
                // Other options to consider adding in.
                // iconAnchor: [22, 94],
                // popupAnchor: [-3, -76],
                // shadowUrl: 'my-icon-shadow.png',
                // shadowRetinaUrl: 'my-icon-shadow@2x.png',
                // shadowSize: [68, 95],
                // shadowAnchor: [22, 94]
        });

        this.fm = new FixedMarker(map, {
            projectLocation: mapCoords,
            enabled: false
        });

        // We create the edit this project location popup for the fixed marker.
        // This should be rolled into the FixedMarker class and turned into a function
        this.popup = L.popup({
            closeButton: false,
            closeOnClick: false
        }).setContent('<a class="toggleEdit">' + lang.EditProjectLocation + '</a>');

        this.fm.marker.bindPopup(this.popup).openPopup();

        // Init our our drawing thingy
        this.drawControl = new L.Control.Draw({
            draw: {
                position: 'topleft',
                polygon: {
                    //  title: 'Draw a sexy polygon!',
                    allowIntersection: true,
                    drawError: {
                        color: '#b00b00',
                        timeout: 1000
                    },
                    shapeOptions: {
                        color: '#0033ff'
                    },
                    showArea: true
                },
                polyline: {
                    metric: false,
                    shapeOptions: {
                        color: '#0033ff'
                    },
                },
                circle: false,
                rectangle: {
                    shapeOptions: {
                        color: '#0033ff'
                    },
                },
            },
            edit: {
                featureGroup: this.drawnItems
            }
        });
        this._map.addControl(this.drawControl);

        this.drawnItems.addTo(this._map);

        /* When I created this last check it seemed logical but now it isn't
         * Ima leave it here in case it does come in handy.
         *
        // Create some polygons from database data and enable events
        if (this.mapData instanceof Array && this.mapData.length > 0) {
        	this.addItemsToMap(this.mapData)._eventsOn();
        } else {
        	// bail out
        	this.drawControl.removeFrom(this._map);
        	this._map.closePopup();
        	this.$editLocation.remove();
        }
        */
        this.addItemsToMap(this.mapData)._eventsOn();
    },

    // Handles switching between the two modes
    _togglProjectEdit: function(e) {
        if (this.fm._state() === false) {
            this._map.panTo(this.fm.marker.getLatLng());
            this.fm.marker.closePopup();
            this.$editLocation.hide();
            this.$cancelEditLocation.show();
        } else {
            this.fm.marker.openPopup();
            this.$editLocation.show();
            this.$cancelEditLocation.hide();
        }

        this.fm.toggle(false);

        return this;
    },

    // Loads initial dataset from map_geom
    addItemsToMap: function(data) {
        for (var i = 0; i < data.length; i++) {
            try { // Catch any malformed WKT strings
                this.wicketUtil.read(JSON.stringify(data[i].geom));
                var obj = this.wicketUtil.toObject(this._map.defaults);
                obj.dataId = data[i].id;
                this.drawnItems.addLayer(obj);
            } catch (e) {
                // Don't throw an exception here as it will break out of the loop. Just supress
                // the problem and move along.
                // throw new Error('Wicket could not understand the WKT string you entered.');
            }
        }

        return this;
    },

    /**
     * Prepares data for being posted, throws errors if unhappy.
     * @param  {[type]} action Can be the creation or editing of a polygon
     * @param  {[type]} layer  A single layer
     * @return {[type]}        Nothing at the momement
     */
    preparePostData: function(action, layer) {
        var dataId = null;

        if (L.LayerGroup.prototype.isPrototypeOf(layer)) {
            throw new Error("preparePostData cannot handle layerGroups");
        }

        try {
            this.wicketUtil.fromObject(layer);
        } catch (e) {
            throw new Error("Could not parse the layer data");
        }

        if (layer.hasOwnProperty('dataId') && layer.dataId !== null) {
            dataId = layer.dataId;
        }

        var post_data = {
            id: dataId,
            action: action,
            data: {
                geojson: jQuery.toJSON(layer.toGeoJSON()),
                geom: this.wicketUtil.write()
            }
        };

        return post_data;
    },

    postProjectDrawdata: function(slug, data) {
        return $.ajax({
            type: 'POST',
            url: '/projects/update_map_draw/' + slug,
            dataType: 'html',
            data: data,
            context: this
        });
    },

    _handleLayerCreated: function(e) {
        var layer = e.layer;
        this.drawnItems.addLayer(layer);

        try {
            var post_data = this.preparePostData('update', layer);
            this.postProjectDrawdata(this.slug, post_data)
                .done(function(resp) {
                    layer.dataId = JSON.parse(resp).gid;
                });
        } catch (err) {
            /*
            	Need to handle user feedback for errors here.
             */
        }
    },

    _handleLayerEdited: function(e) {
        e.layers.eachLayer(function(layer) {
            try {
                var post_data = this.preparePostData('update', layer);
                this.postProjectDrawdata(this.slug, post_data).done(function(resp) {
                    // do nothing;
                });
            } catch (e) {
                /*
                	Need to handle user feedback for errors here.
                 */
            }
        }, this);
    },

    _handleLayerDeleted: function(e) {
        e.layers.eachLayer(function(layer) {
            try {
                if (layer.dataId !== null) {
                    var post_data = this.preparePostData('delete', layer);
                    this.postProjectDrawdata(this.slug, post_data).done(function(resp) {
                        // do nothing
                    });
                }
            } catch (e) {
                /*
                	Need to handle user feedback for errors here.
                 */
            }
        }, this);
    },

    _eventsOn: function() {
        // leaflet events
        this._map.on('draw:created', this._handleLayerCreated, this);
        this._map.on('draw:edited', this._handleLayerEdited, this);
        this._map.on('draw:deleted', this._handleLayerDeleted, this);
        this.fm.on('savedaddress', this._togglProjectEdit, this);

        // jQuery events
        $('.map_box').on('click', '.toggleEdit', $.proxy(this._togglProjectEdit, this));
        $('#profile_tabs').on('tabsshow', function() {
        	thisMap.invalidateSize();
        	if (am.fm.enabled === false) {
        		am.fm._disable(false);
        	}
        	am.fm.marker.getPopup().update();
        });
    },

    _eventsOff: function() {
        // leaflet events
        this._map.off('draw:created', this._handleLayerCreated, this);
        this._map.off('draw:edited', this._handleLayerEdited, this);
        this._map.off('draw:deleted', this._handleLayerDeleted, this);

        // jQuery events
        $('.map_box').off('click', '.toggleEdit', $.proxy(this._togglProjectEdit, this));
    },

});

var FixedMarker = L.Class.extend({

    includes: L.Mixin.Events,

    options: {
        enabled: true,
        markerOptions: {
            opacity: 0,
            clickable: false
                //,
                // iconSize: [26, 41]
        },
        projectLocation: [38.906653, -77.042783]
    },

    initialize: function(map, options) {
        L.Util.setOptions(this, options);
        this._map = map;

        // Fixed marker is a fake. Just pretends to be an actual marker
        this.$fixedMarker = $('<div/>').addClass('fixed_marker').appendTo(this._map.getContainer()),
            this.$fixedMarkerTooltip = $('<div/>').html(lang.DragToSetLocation).addClass('fixed_marker_tooltip').hide().appendTo(this._map.getContainer());
        this.projectLocation = this.options.projectLocation;
        this.enabled = this.options.enabled;

        this.$address = $('span.address'),
            this.$saveLocation = $('a.save_location');

        // Init values.
        this.latestGeocode = false;
        this.currentAddress = this.$address.html();

        this.marker = L.marker(this.projectLocation, this.options.markerOptions).addTo(this._map);

        if (this.enabled !== undefined && this.enabled === false) {
            this._disable(false);
        } else {
            this._enable(false);
        }
    },

    // Updates the address when the user drags the map
    // this should be abstracted out and let this plugin
    // just handle showing a fixed marker on the map
    _updateAddress: function() {
        var mapCenter = this._map.getCenter();

        this.$address.html('<img src="/images/ajax-loader.gif" style="width: 14px; height: 14px;" />');
        this.$saveLocation.hide();
        this.$fixedMarkerTooltip.hide();

        try {
            L.Util.VIPUtils.reverseGeocode(mapCenter.lat, mapCenter.lng, _.bind(function(resp) {
                this.latestGeocode = resp;
                this.currentAddress = L.Util.VIPUtils.parseRawReverseGeoCode(resp);

                this.$address.html(this.currentAddress);
                this.$fixedMarkerTooltip.html(lang.SaveProjectLocation).show();
                this.$saveLocation.html(lang.Save);
                this.$saveLocation.show();
                this.fireEvent('updatedaddress', {
                    currentAddress: this.currentAddress
                });
            }, this));

        } catch (err) {
            this.currentAddress = lang.ErrorFetchingAddress;
            this.latestGeocode = false;
            this.$address.html(this.currentAddress);
            this.$fixedMarkerTooltip.html(lang.DragToSetLocation).show();
            this.$saveLocation.html('');
        }
    },

    getCityState: function() {
        var location = this.latestGeocode.results[0].locations[0];
        var cityState = "";

        if (location.adminArea5 !== "") {
            cityState += location.adminArea5;
        }

        // County, doesn't seem to be necesary
        // if (location.adminArea4 !== "") {
        // 	cityState += ", " + location.adminArea4;
        // }

        if (location.adminArea3 !== "") {
            cityState += ", " + location.adminArea3;
        }

        return cityState;
    },

    // Turns plugin off.
    _disable: function(fade) {
        var fadeDuration = 400;

        if (fade === false) {
            fadeDuration = 0;
        }

        this.enabled = false;

        this.$fixedMarker.fadeOut(fadeDuration);
        this.$fixedMarkerTooltip.fadeOut(fadeDuration);
        this.$saveLocation.fadeOut(fadeDuration);

        this._eventsOff();
        this.marker.setOpacity(1.0);

        return this;
    },

    // Turns this plugin on.
    _enable: function(fade) {
        var fadeDuration = 400;

        if (fade === false) {
            fadeDuration = 0;
        }

        this.enabled = true;

        if (this.$fixedMarkerTooltip.html() == lang.Saved) {
            this.$fixedMarkerTooltip.html(lang.DragToSetLocation);
            this.$saveLocation.html(lang.Save);
        }

        this.$fixedMarker.fadeIn(fadeDuration);
        this.$fixedMarkerTooltip.fadeIn(fadeDuration);

        if (this.latestGeocode !== false) {
            this.$saveLocation.fadeIn(fadeDuration);
        }

        this.marker.setOpacity(0);

        this._eventsOn();

        return this;
    },

    _eventsOn: function() {
        this._map.on('dragend zoomend', this._updateAddress, this);

        // jQuery events
        this.$fixedMarkerTooltip.on('click', $.proxy(this._updateProjectLocation, this));
        this.$saveLocation.on('click', $.proxy(this._updateProjectLocation, this));

        return this;
    },

    _eventsOff: function() {
        this._map.off('dragend zoomend', this._updateAddress, this);

        // jQuery events
        this.$fixedMarkerTooltip.off('click', $.proxy(this._updateProjectLocation, this));
        this.$saveLocation.off('click', $.proxy(this._updateProjectLocation, this));

        return this;
    },

    // Called when updating the database
    _updateProjectLocation: function(e) {
        if (this.latestGeocode === undefined || this.latestGeocode === false) {
            return;
        }

        // If so continue and notify the user we are doing something.
        $(e.currentTarget).html('<img src="/images/ajax-loader.gif" style="width: 14px; height: 14px;" />');

        var mapCenter = this._map.getCenter();
        this.projectLocation = mapCenter;

        this._postProjectLocation(mapCenter.lat, mapCenter.lng, this.latestGeocode, this.currentAddress, slug, this._savedLocation);
    },

    // Handles AJAX post to database
    _postProjectLocation: function(lat, lng, lastGeocode, currAddress, slug, callback) {
        $.ajax({
            type: 'POST',
            url: '/projects/update_project_location/' + slug,
            dataType: 'json',
            context: this,
            data: {
                csrf_vip: $.cookie('csrf_cookie_vip'),
                'project_lat': lat,
                'project_lng': lng,
                'project_location': currAddress,
                'project_geocode': JSON.stringify(lastGeocode)
            },
            success: callback
        });
    },

    // Called when successfully saved a location
    _savedLocation: function(response, status, jqXHR) {
        //lat, lng, lastGeocode
        $('.save_location, .fixed_marker_tooltip').html(lang.Saved);

        // Make sure the marker has been udpated.
        this.fireEvent('savedaddress', {
            currentAddress: this.currentAddress,
            latLng: this.projectLocation,
            cityState: this.getCityState()
        });

        this.marker.setLatLng(this.projectLocation);
    },

    _state: function() {
        return this.enabled ? true : false;
    },

    toggle: function(fade) {
        fade = fade || true;
        if (this._state() === true) {
            this._disable(fade);
        } else {
            this._enable(fade);
        }

        return this;
    }
});

var pathname = window.location.pathname;
var thisMap, am;

$(function(window) {
    if (typeof mapCoords !== "undefined" && mapCoords instanceof Array) {

        // Default location is CGLA
        if (mapCoords.length === 1) {
            mapCoords = [38.906653, -77.042783];
        }

        thisMap = L.map('project-map', {
            center: mapCoords,
            zoom: 10,
            minZoom: 3,
            worldCopyJump: true
        });

        // add an OpenStreetMap tile layer
        // Remove attribution and the map fails
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(thisMap);

        // Stuff to do as soon as the DOM is ready;
        $.ajaxSetup({
            type: 'GET',
            datatype: 'json',
            // for CodeIgniter CSRF protection...
            timeout: 20000
        });

        // Either add a marker or setup all the admin stuff.
        // TODO Switch back to false when done
        if (isAdmin === false) {
            var marker = L.marker(mapCoords).addTo(thisMap);
            var wicketUtil = new Wkt.Wkt();
            var layerGroup = L.featureGroup();

            for (var i = 0; i < map_geom.length; i++) {
                try { // Catch any malformed WKT strings
                    wicketUtil.read(JSON.stringify(map_geom[i].geom));

                    var obj = wicketUtil.toObject(thisMap.defaults);
                    layerGroup.addLayer(obj);
                } catch (e) {
                    // Don't throw an exception here as it will break out of the loop. Just supress
                    // the problem and move along.
                    // throw new Error('Wicket could not understand the WKT string you entered.');
                }
            }
            layerGroup.addTo(thisMap);

        } else {

            if (pathname.indexOf('/edit') > 0) {
                am = new AdvancedMapDraw(thisMap, {
                    slug: slug,
                    mapData: map_geom
                });
            } else {
                var fm = new FixedMarker(thisMap, {
                    projectLocation: mapCoords
                });

                fm.on('savedaddress', function(e) {
                    $('.city_state').html(e.cityState);
                });

                // Hackety hack, shouldn't have to do this twice but whatevs
                var wicketUtil = new Wkt.Wkt();
                var layerGroup = L.featureGroup();

                for (var i = 0; i < map_geom.length; i++) {
                    try { // Catch any malformed WKT strings
                        wicketUtil.read(JSON.stringify(map_geom[i].geom));

                        var obj = wicketUtil.toObject(thisMap.defaults);
                        layerGroup.addLayer(obj);
                    } catch (e) {
                        // Don't throw an exception here as it will break out of the loop. Just supress
                        // the problem and move along.
                        // throw new Error('Wicket could not understand the WKT string you entered.');
                    }
                }
                layerGroup.addTo(thisMap);
            }

        }

        // L.control.fullscreen({
        // 	position: 'topright',
        // 	title: 'Fullscreen'
        // }).addTo(thisMap);
    }
});