(function($) {
    // extend jquery object
    $.extend({
        // define jMapify method
        jMapify: new function() {
            // ------------------------------ PROPERTIES
            // define plugin name
            var name = "mapify",
                obj = this,
                preZoomLeft = null,
                preZoomTop = null,
                preZoomZoomLevel = null,
                map = null,
                marker = null,
                initalMapPosition = null,
                // define the default settings
                settings = {
                    width:'100%',
                    height:'500',
                    lat: 27.7000,
                    lng: 85.3333,
                    zoom: 15,
                    type: "ROADMAP",
                    draggable: true,
                    zoomControl: true,
                    scrollwheel: true,
                    disableDoubleClickZoom: false,
                    showMarker: false,
                    showMarkerOnClick:false,
                    markerOptions: {
                        draggable: false,
                        raiseOnDrag: false
                    },
                    afterMarkerDrag: function() {},
                    autoLocate: false,
                    geoLocationButton: null,
                    searchInput: null
                };
            // ------------------------------ PRIVATE METHODS
            // overwrite the default settings, using passed arguments
            function setDefaults(obj) {
                for (var i in obj) {
                    settings[i] = obj[i];
                }
            }

            function setMarker(location) {
                var latlng = new google.maps.LatLng(location.lat, location.lng);
                if (marker == null) {
                    /*
                     * Creating the marker with the initial position
                     */
                    marker = new google.maps.Marker({
                        draggable: settings.markerOptions.draggable,
                        raiseOnDrag: settings.markerOptions.draggable,
                        map: map,
                        position: latlng,
                    });
                }
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({
                    'latLng': latlng
                }, function(results, status) {
                    // validate
                    if (status != google.maps.GeocoderStatus.OK) {
                        console.log('Geocoder failed due to: ' + status);
                        return;
                    }
                    if (!results[0]) {
                        console.log('No results found');
                        return;
                    }
                    marker.setPosition(latlng);
                    $formatted_address = results[0].formatted_address;
                    if (typeof location.address != "undefined" && '' != location.address) $formatted_address = location.address;
                    settings.afterMarkerDrag.apply(this, [{
                        "lat": location.lat,
                        "lng": location.lng,
                        "address": $formatted_address
                    }]);
                    map.setCenter(latlng);
                });
            }

            function drawMap(args) {

                args.el.wrap("<div id='jMapify-" + args.index + "' class='jMapify' style='position:relative'></div>").height(settings.height).width(settings.width);
                /*
                 * Define Initail map position
                 */
                initalMapPosition = new google.maps.LatLng(settings.lat, settings.lng);
                /* 
                 * Creating "map" object
                 * Creates a new map inside of the given HTML container
                 */
                map = new google.maps.Map(args.el[0], {
                    center: initalMapPosition,
                    zoom: settings.zoom,
                    mapTypeId: google.maps.MapTypeId[settings.type],
                    draggable: settings.draggable,
                    zoomControl: settings.zoomControl,
                    scrollwheel: settings.scrollwheel,
                    disableDoubleClickZoom: settings.disableDoubleClickZoom
                });
            }

            function showMarker(args) {
                
                // add dummy marker
                marker = new google.maps.Marker({
                    draggable   : settings.draggable,
                    raiseOnDrag : settings.raiseOnDrag,
                    map         : map,
                });

                if(settings.showMarker===true){
                    setMarker({lat:settings.lat,lng:settings.lng});
                }
                if (settings.showMarkerOnClick === true) {
                    google.maps.event.addListener( map, 'click', function( e ) {
                
                        // vars
                        var lat = e.latLng.lat(),
                            lng = e.latLng.lng();
                        
                        
                        setMarker({
                            lat: lat,
                            lng: lng
                        });
                    
                    });
                }
                if (settings.autoLocate === true) {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            setMarker({
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            });
                        });
                    } else {
                        // Browser doesn't support Geolocation
                        // handleNoGeolocation(false);
                    }
                }
                if (settings.geoLocationButton != null && $(settings.geoLocationButton).length > 0) {
                    $(settings.geoLocationButton).bind("click", function(e) {
                        // Try HTML5 geolocation
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(function(position) {
                                setMarker({
                                    lat: position.coords.latitude,
                                    lng: position.coords.longitude
                                });
                            });
                        } else {
                            // Browser doesn't support Geolocation
                            // handleNoGeolocation(false);
                        }
                    });
                }
            }

            function draggable() {
                if (settings.markerOptions.draggable === true) {
                    /*
                     * Get position (Lat, Lng, Address) of marker when the drag event ends
                     */
                    google.maps.event.addListener(marker, 'dragend', function() {
                        var position = marker.getPosition(),
                            lat = position.lat(),
                            lng = position.lng();
                       
                        setMarker({
                            lat: lat,
                            lng: lng
                        });
                    });
                    if (settings.searchInput != null && $(settings.searchInput).length > 0) {
                        /*
                        * Append style in head to remove the google logo from auto complete
                        */
                        var css = '.pac-container:after{background-image: none;}',
                        head = document.head || document.getElementsByTagName('head')[0],
                        style = document.createElement('style');

                        style.type = 'text/css';
                        if (style.styleSheet){
                            style.styleSheet.cssText = css;
                        } else {
                            style.appendChild(document.createTextNode(css));
                        }
                        head.appendChild(style);

                        /*
                        * Style append end
                        */

                        $(document).on('keydown', settings.searchInput, function( e ){
        
                            // prevent form from submitting
                            if( e.which == 13 )
                            {
                                return false;
                            }
                                
                        });

                        var autocomplete = new google.maps.places.Autocomplete($(settings.searchInput)[0]);
                        autocomplete.map = map;
                        autocomplete.bindTo('bounds', map);
                        google.maps.event.addListener(autocomplete, 'place_changed', function(e) {
                            // manually update address
                            var address = $(settings.searchInput).val();
                            // vars
                            var place = this.getPlace();
                            // console.log(place);
                            // validate
                            if (place.geometry) {
                                var lat = place.geometry.location.lat(),
                                    lng = place.geometry.location.lng();
                                setMarker({
                                    lat: lat,
                                    lng: lng,
                                    address: address
                                });
                            } else {
                                var geocoder = new google.maps.Geocoder();
                                // client hit enter, manulaly get the place
                                geocoder.geocode({
                                    'address': address
                                }, function(results, status) {
                                    // validate
                                    if (status != google.maps.GeocoderStatus.OK) {
                                        console.log('Geocoder failed due to: ' + status);
                                        return;
                                    }
                                    if (!results[0]) {
                                        console.log('No results found');
                                        return;
                                    }
                                    // get place
                                    place = results[0];
                                    var lat = place.geometry.location.lat(),
                                        lng = place.geometry.location.lng();
                                    setMarker({
                                        lat: lat,
                                        lng: lng,
                                        address: address
                                    });
                                });
                            }
                        });
                    }
                }
            }
            // ------------------------------ PUBLIC METHODS
            // init method, sets up the map functions and properties
            this.init = function(obj) {
                if (obj != null) {
                    setDefaults(obj);
                }
                $fields = this;
                // validate google
                if (typeof google === 'undefined') {
                    $.getScript('https://www.google.com/jsapi', function() {
                        google.load('maps', '3', {
                            other_params: 'sensor=false&libraries=places',
                            callback: function() {
                                // elementID = this[0].id;
                                return $fields.each(function(index) {
                                    drawMap({
                                        el: $(this),
                                        index: index
                                    });
                                    showMarker({
                                        index: index
                                    });
                                    draggable();
                                });
                            }
                        });
                    });
                } else {
                    google.load('maps', '3', {
                        other_params: 'sensor=false&libraries=places',
                        callback: function() {
                            // elementID = this[0].id;
                            return $fields.each(function(index) {
                                drawMap({
                                    el: $(this),
                                    index: index
                                });
                                showMarker({
                                    index: index
                                });
                                draggable();
                            });
                        }
                    });
                }
            }
        } // end jMapify
    }); // end extend
    // extend plugin scope
    $.fn.extend({
        jMapify: $.jMapify.init
    });
})(jQuery); // return jquery object