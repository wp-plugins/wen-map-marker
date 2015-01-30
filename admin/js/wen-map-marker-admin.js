(function( $ ) {
	'use strict';

	$(function(){
		
		$(".wen-map-marker-locate-user").click(function(){
			return false;
		});

    /*
    * For Custom Map Marker
    * For WEN Option page.
    */
    $("#wen-map-marker-canvas-custom").jMapify({
      showMarker:false,
      showMarkerOnClick:true,
      markerOptions:{
        draggable:true
      },
      autoLocate:false,
      geoLocationButton:".wen-map-marker-locate-user",
      searchInput:"#wen-map-marker-search-custom",
      afterMarkerDrag:function(response){
        // console.log(response);
        $("#wen-map-marker-lat-custom").val(response.lat);
        $("#wen-map-marker-lng-custom").val(response.lng);
        $("#wen-map-marker-address-custom").val(response.address);
        $("#wen-map-marker-search-custom").val(response.address);
        $("#wen-map-marker-shortcode-custom").val('[WMM lat="'+response.lat+'" lng="'+response.lng+'" zoom="'+response.zoom+'"]');
      }
    });

    $("#wen-map-marker-shortcode-custom").focus(function() { $(this).select(); } );

	});

})( jQuery );
