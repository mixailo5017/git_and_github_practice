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
                geojson: JSON.stringify(layer.toGeoJSON()),
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

module.exports = AdvancedMapDraw;