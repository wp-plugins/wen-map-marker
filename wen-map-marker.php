<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://wenthemes.com
 * @since             1.0.0
 * @package           wen_map_marker
 *
 * @wordpress-plugin
 * Plugin Name:       WEN Map Marker
 * Plugin URI:        https://wordpress.org/plugins/wen-map-marker/
 * Description:       An extremely easy way to add Google Map on the WordPress sites.
 * Version:           1.1
 * Author:            WEN Themes
 * Author URI:        http://wenthemes.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wen-map-marker
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Define
define( 'WEN_MAP_MARKER_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'WEN_MAP_MARKER_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
define( 'WEN_MAP_MARKER_BASENAME', basename( dirname( __FILE__ ) ) );
define( 'WEN_MAP_MARKER_BASE_FILE', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wen-map-marker-activator.php
 */
function activate_wen_map_marker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wen-map-marker-activator.php';
	WEN_Map_Marker_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wen-map-marker-deactivator.php
 */
function deactivate_wen_map_marker() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wen-map-marker-deactivator.php';
	WEN_Map_Marker_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wen_map_marker' );
register_deactivation_hook( __FILE__, 'deactivate_wen_map_marker' );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wen-map-marker.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wen_map_marker() {

	$plugin = new WEN_Map_Marker();
	$plugin->run();

}
run_wen_map_marker();
