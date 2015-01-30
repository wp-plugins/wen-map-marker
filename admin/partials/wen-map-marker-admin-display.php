<?php

/**
 * Provide a dashboard view for the plugin
 *
 * @link       http://wenthemes.com
 * @since      1.0.0
 *
 * @package    WEN_Map_Marker
 * @subpackage WEN_Map_Marker/admin/partials
 */
?>


<div class="wrap">
	<h2><?php _e("WEN Map Marker Settings","wen-map-marker");?></h2>
	<?php settings_errors(); ?>
	<form method="post" action="options.php">
	<?php
	settings_fields( 'wen-map-marker-settings-group' );
	do_settings_sections( 'wen-map-marker-settings-group' );
	submit_button();
	?>
	</form>

<h3><?php _e("Generate Custom Map Marker Shortcode","wen-map-marker");?></h3>

<div class="">
	<div class="wen-map-marker-wrapper">
		<div class="wen-map-marker-search-bar">
			<a href="#" class="wen-map-marker-locate-user" title="<?php echo esc_attr( __( 'Find current location', 'wen-map-marker' ) ); ?>"><?php _e( 'Locate User', 'wen-map-marker' ); ?></a>
			<input type="text" id="wen-map-marker-search-custom"  />
		</div>
		<div id="wen-map-marker-canvas-custom"></div>
		<input type="hidden" id="wen-map-marker-address-custom" name="wen_map_marker_address_custom"  />
		<input type="hidden" id="wen-map-marker-lat-custom" name="wen_map_marker_lat_custom"  />
		<input type="hidden" id="wen-map-marker-lng-custom" name="wen_map_marker_lng_custom"  />
		<input type="text" id="wen-map-marker-shortcode-custom" name="wen_map_marker_shortcode_custom"  />
	</div>
</div>

</div>
