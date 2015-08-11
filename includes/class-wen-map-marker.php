<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://wenthemes.com
 * @since      1.0.0
 *
 * @package    WEN_Map_Marker
 * @subpackage WEN_Map_Marker/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    WEN_Map_Marker
 * @subpackage WEN_Map_Marker/includes
 * @author     WEN Themes <info@wenthemes.com>
 */
class WEN_Map_Marker {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WEN_Map_Marker_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $wen_map_marker    The string used to uniquely identify this plugin.
	 */
	protected $wen_map_marker;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->wen_map_marker = 'wen-map-marker';
		$this->version = '1.2';

		$this->load_dependencies();
    $this->set_locale();
		$this->set_default_options();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WEN_Map_Marker_Loader. Orchestrates the hooks of the plugin.
	 * - WEN_Map_Marker_i18n. Defines internationalization functionality.
	 * - WEN_Map_Marker_Admin. Defines all hooks for the dashboard.
	 * - WEN_Map_Marker_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for intracting with jQuery Mapify Plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-jquery-mapify-helper.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wen-map-marker-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wen-map-marker-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wen-map-marker-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wen-map-marker-public.php';

		$this->loader = new WEN_Map_Marker_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WEN_Map_Marker_i18n class in order to set the domain and to register
	 * the hook with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new WEN_Map_Marker_i18n();
		$plugin_i18n->set_domain( $this->get_wen_map_marker() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new WEN_Map_Marker_Admin( $this->get_wen_map_marker(), $this->get_version() );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'setup_menu' );
		$this->loader->add_action( 'admin_head', $plugin_admin, 'admin_head' );
		// $this->loader->add_action( 'admin_footer', $plugin_admin, 'tinymce_popup' );
		// $this->loader->add_action( 'admin_init', $plugin_admin, 'tinymce_button_init' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'meta_box_save' );
		$this->loader->add_filter( 'plugin_action_links_'.$this->wen_map_marker."/".$this->wen_map_marker.".php", $plugin_admin, 'plugin_settings_link' );

		// Button in toolbar
		$this->loader->add_action( 'admin_init', $plugin_admin, 'tinymce_button' );

		// Tinymce language
		$this->loader->add_filter( 'mce_external_languages', $plugin_admin, 'tinymce_external_language' );


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new WEN_Map_Marker_Public( $this->get_wen_map_marker(), $this->get_version() );

    $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

    // Load CSS
		$this->loader->add_action( 'wp_head', $plugin_public, 'load_css', 99 );

		$this->loader->add_filter( 'the_content', $plugin_public, 'append_map' );

		// Enable shortcode in Text widget
		add_filter( 'widget_text', 'do_shortcode');
		add_filter( 'widget_text', 'shortcode_unautop');

		add_shortcode( 'WMM', array( $plugin_public, 'map_shortcode' ) );
	}

  /**
   * Set default plugin options.
   *
   * @since    1.1
   */
  public function set_default_options() {

    $default_options = array(
      'post_types' => array( 'post' ),
    );
    if ( ! get_option( 'wen_map_marker_settings' ) ) {
      update_option( 'wen_map_marker_settings', $default_options );
    }

  }

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_wen_map_marker() {
		return $this->wen_map_marker;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    WEN_Map_Marker_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
