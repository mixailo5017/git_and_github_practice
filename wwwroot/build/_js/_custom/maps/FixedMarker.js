var FixedMarker = L.Evented.extend({

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
                csrf_vip: Cookies.get('csrf_cookie_vip'),
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

module.exports = FixedMarker;