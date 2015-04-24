(function($) {
    var jMapifyId = 0;
    $.jMapify = {};
    $.fn.jMapify = function(options) {
        return this.each(function(index) {
            var goMapBase = $.extend(true, {}, $.jMapifyBase);
            goMapBase.init(this, options);
            jMapifyId++;
            $.jMapify = goMapBase;
        });
    };
    // extend jquery object
    $.jMapifyBase = {
        // ------------------------------ PROPERTIES
        // define plugin name
        map: null,
        marker: null,
        initalMapPosition: null,
        // define the default settings
        settings: {
            width: '100%',
            height: '500',
            lat: 27.7000,
            lng: 85.3333,
            zoom: 15,
            type: "ROADMAP",
            draggable: true,
            zoomControl: true,
            scrollwheel: true,
            disableDoubleClickZoom: false,
            showMarker: false,
            showMarkerOnClick: false,
            markerOptions: {
                draggable: false,
                raiseOnDrag: true
            },
            afterMarkerDrag: function() {},
            autoLocate: false,
            geoLocationButton: null,
            searchInput: null
        },
        init: function(el, options) {
            var opts = $.extend(true, {}, $.jMapifyBase.settings, options);
            args = {
                el: el,
                settings: opts
            };
            this.drawMap(args);
            this.showMarker(args);
            this.draggable(args);
            return this;
        },
        drawMap: function(args) {
            $(args.el).wrap("<div id='jMapify-"+jMapifyId+"' class='jMapify' style='position:relative'></div>").height(args.settings.height).width(args.settings.width);
            /*
             * Define Initail map position
             */
            this.initalMapPosition = new google.maps.LatLng(args.settings.lat, args.settings.lng);
            /* 
             * Creating "map" object
             * Creates a new map inside of the given HTML container
             */
            this.map = new google.maps.Map(args.el, {
                center: this.initalMapPosition,
                zoom: args.settings.zoom,
                mapTypeId: google.maps.MapTypeId[args.settings.type],
                draggable: args.settings.draggable,
                zoomControl: args.settings.zoomControl,
                scrollwheel: args.settings.scrollwheel,
                disableDoubleClickZoom: args.settings.disableDoubleClickZoom
            });
        },
        showMarker: function(args) {
            var jMapify = this;
            // add dummy marker
            this.marker = new google.maps.Marker({
                draggable: args.settings.markerOptions.draggable,
                raiseOnDrag: args.settings.raiseOnDrag,
                map: this.map,
            });
            if (args.settings.showMarker === true) {
                this.setMarker({
                    lat: args.settings.lat,
                    lng: args.settings.lng
                }, args.settings);
            }
            if (args.settings.showMarkerOnClick === true) {
                google.maps.event.addListener(this.map, 'click', function(e) {
                    // vars
                    var lat = e.latLng.lat(),
                        lng = e.latLng.lng();
                    jMapify.setMarker({
                        lat: lat,
                        lng: lng
                    }, args.settings);
                });
            }
            if (args.settings.autoLocate === true) {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        jMapify.setMarker({
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        }, args.settings);
                    });
                } else {
                    // Browser doesn't support Geolocation
                    // handleNoGeolocation(false);
                }
            }
            if (args.settings.geoLocationButton != null && $(args.settings.geoLocationButton).length > 0) {
                $(args.settings.geoLocationButton).bind("click", function(e) {
                    // Try HTML5 geolocation
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            jMapify.setMarker({
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            }, args.settings);
                        });
                    } else {
                        // Browser doesn't support Geolocation
                        // handleNoGeolocation(false);
                    }
                });
            }
        },
        setMarker: function(location, settings) {
            var jMapify = this;
            var $marker = this.marker;
            var $map = this.map;
            this.marker.setVisible(true);
            var latlng = new google.maps.LatLng(location.lat, location.lng);
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
                $marker.setPosition(latlng);
                $formatted_address = results[0].formatted_address;
                if (typeof location.address != "undefined" && '' != location.address) $formatted_address = location.address;
                settings.afterMarkerDrag.apply(jMapify, [{
                    "lat": location.lat,
                    "lng": location.lng,
                    "address": $formatted_address,
                    "zoom" : $map.getZoom()
                }]);
                $map.setCenter(latlng);
            });
        },
        draggable: function (args) {
          var jMapify = this;

          if (args.settings.markerOptions.draggable === true) {
              /*
               * Get position (Lat, Lng, Address) of marker when the drag event ends
               */
              google.maps.event.addListener(this.marker, 'dragend', function() {
                  var position = jMapify.marker.getPosition(),
                      lat = position.lat(),
                      lng = position.lng();
                 
                  jMapify.setMarker({
                      lat: lat,
                      lng: lng
                  }, args.settings);
              });
              if (args.settings.searchInput != null && $(args.settings.searchInput).length > 0) {
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

                  $(document).on('keydown', args.settings.searchInput, function( e ){
  
                      // prevent form from submitting
                      if( e.which == 13 )
                      {
                          return false;
                      }
                          
                  });

                  var autocomplete = new google.maps.places.Autocomplete($(args.settings.searchInput)[0]);
                  autocomplete.map = this.map;
                  autocomplete.bindTo('bounds', this.map);
                  google.maps.event.addListener(autocomplete, 'place_changed', function(e) {
                      // manually update address
                      var address = $(args.settings.searchInput).val();
                      // vars
                      var place = this.getPlace();
                      // console.log(place);
                      // validate
                      if (place.geometry) {
                          var lat = place.geometry.location.lat(),
                              lng = place.geometry.location.lng();
                          jMapify.setMarker({
                              lat: lat,
                              lng: lng,
                              address: address
                          }, args.settings);
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
                              jMapify.setMarker({
                                  lat: lat,
                                  lng: lng,
                                  address: address
                              }, args.settings);
                          });
                      }
                  });
              }

              // Check zoom level
              google.maps.event.addListener(jMapify.map,'zoom_changed', function ()
              {
                  var markerPositon = jMapify.marker.getPosition();
                  if( markerPositon !== undefined ){

                    var newCenter1 = jMapify.marker.getPosition();
                    var latlng = new google.maps.LatLng(newCenter1.lat(), newCenter1.lng() );
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
                        jMapify.marker.setPosition(latlng);
                        $formatted_address = results[0].formatted_address;
                        
                        args.settings.afterMarkerDrag.apply(jMapify, [{
                            "lat": newCenter1.lat(),
                            "lng": newCenter1.lng(),
                            "address": $formatted_address,
                            "zoom" : jMapify.map.getZoom()
                        }]);
                        jMapify.map.setCenter(latlng);
                    });

                  }
              });

          }
      },
      removeMarker: function(){
        this.marker.setVisible(false);
      }
    }; // end extend
})(jQuery); // return jquery object