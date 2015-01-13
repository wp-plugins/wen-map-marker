<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://wenthemes.com
 * @since      1.0.0
 *
 * @package    WEN_Map_Marker
 * @subpackage WEN_Map_Marker/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    WEN_Map_Marker
 * @subpackage WEN_Map_Marker/admin
 * @author     WEN Themes <info@wenthemes.com>
 */
class WEN_Map_Marker_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $wen_map_marker    The ID of this plugin.
	 */
	private $wen_map_marker;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $wen_map_marker       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $wen_map_marker, $version ) {

		$this->wen_map_marker = $wen_map_marker;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		$screen = get_current_screen();
		$wen_map_marker_settings = get_option('wen_map_marker_settings');
		$wen_map_marker_settings['post_types'][] = 'toplevel_page_wen-map-marker';

		if(!isset($wen_map_marker_settings['post_types']) || empty( $wen_map_marker_settings['post_types'] ))
			return;

		if( is_array($wen_map_marker_settings['post_types']) and !in_array($screen->id,$wen_map_marker_settings['post_types']))
			return;

		wp_enqueue_style( $this->wen_map_marker, plugin_dir_url( __FILE__ ) . 'css/wen-map-marker-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		$screen = get_current_screen();
		$wen_map_marker_settings = get_option('wen_map_marker_settings');
		$wen_map_marker_settings['post_types'][] = 'toplevel_page_wen-map-marker';

		if(!isset($wen_map_marker_settings['post_types']) || empty( $wen_map_marker_settings['post_types'] ))
			return;

		if( is_array($wen_map_marker_settings['post_types']) and !in_array($screen->id,$wen_map_marker_settings['post_types']))
			return;

		wp_enqueue_script( 'google-map-api', 'http://maps.google.com/maps/api/js?sensor=false&libraries=places', array( 'jquery' ), $this->version );
		wp_enqueue_script( 'jquery-jMapify', plugin_dir_url(__FILE__) . '../public/js/jquery.jMapify.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->wen_map_marker, plugin_dir_url( __FILE__ ) . 'js/wen-map-marker-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add Meta Boxes for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function add_meta_boxes() {

		$wen_map_marker_settings = get_option('wen_map_marker_settings');
		if( isset($wen_map_marker_settings['post_types']) and  !empty( $wen_map_marker_settings['post_types'])){
			foreach ($wen_map_marker_settings['post_types'] as $key => $postType) {
				add_meta_box( 'wen-map-marker-meta-box', __( 'WEN Map Marker', 'wen-map-marker' ), array(&$this,'meta_box_output'), $postType, 'normal', 'high' );
			}
		}

	}

	/**
	 * Output the Metabox for the dashboard.
	 *
	 * @since    1.0.0
	 */

	function meta_box_output( $post ) {
		// create a nonce field
		wp_nonce_field( 'wen_map_marker_meta_box_nonce', 'wen_map_marker_meta_box_nonce' ); ?>
		<div class="wen-map-marker-wrapper">
			<div class="wen-map-marker-search-bar">
				<a href="javascript:void(0);" class="clear-marker" title="<?php echo esc_attr( __( 'Clear location', 'wen-map-marker' ) ); ?>"><?php _e( 'Clear marker', 'wen-map-marker' ); ?></a>
				<a href="#" class="wen-map-marker-locate-user" title="<?php echo esc_attr( __( 'Find current location', 'wen-map-marker' ) ); ?>"><?php _e( 'Locate User', 'wen-map-marker' ); ?></a>
				<input type="text" id="wen-map-marker-search"  value="<?php echo $this->get_custom_field_value("wen_map_marker_address");?>"/>
			</div>
			<div id="wen-map-marker-canvas"></div>
			<input type="hidden" id="wen-map-marker-address" name="wen_map_marker_address" value="<?php echo $this->get_custom_field_value("wen_map_marker_address");?>" />
			<input type="hidden" id="wen-map-marker-lat" name="wen_map_marker_lat" value="<?php echo $this->get_custom_field_value("wen_map_marker_lat");?>" />
			<input type="hidden" id="wen-map-marker-zoom" name="wen_map_marker_zoom" value="<?php echo $this->get_custom_field_value("wen_map_marker_zoom");?>" />
			<input type="hidden" id="wen-map-marker-lng" name="wen_map_marker_lng"  value="<?php echo $this->get_custom_field_value("wen_map_marker_lng");?>" />
	    </div>
	    <p>
			<label for="wen_map_marker_content_append"><strong><?php _e( 'Show Map', 'wen-map-marker' ); ?></strong></label>
			<?php $wen_map_marker_content_append = $this->get_custom_field_value("wen_map_marker_content_append");?>
			<select name="wen_map_marker_content_append" id="wen_map_marker_content_append">
				<option value="after_content" <?php selected('after_content',$wen_map_marker_content_append);?>><?php _e( 'After Content', 'wen-map-marker' ); ?></option>
				<option value="before_content" <?php selected('before_content',$wen_map_marker_content_append);?>><?php _e( 'Before Content', 'wen-map-marker' ); ?></option>
				<option value="" <?php selected('',$wen_map_marker_content_append);?>><?php _e( 'Do not append', 'wen-map-marker' ); ?></option>
			</select>
			<p><strong><?php echo _x( 'OR', 'Map Metabox', 'wen-map-marker' ); ?></strong></p>
			<p class="description">
			<?php
				echo sprintf(__('Use Shortcode %s in editor.'), '<code>[WMM]</code>' );
			 ?>
			</p>
		</p>
		<?php
	}

	/**
	 * Returns the value for the custom field
	 *
	 * @since    1.0.0
	 */
	private function get_custom_field_value( $key ) {
		global $post;

	    $custom_field = get_post_meta( $post->ID, $key, true );
	    if ( !empty( $custom_field ) )
		    return is_array( $custom_field ) ? stripslashes_deep( $custom_field ) : stripslashes( wp_kses_decode_entities( $custom_field ) );

	    return false;
	}

	/**
	 * Save the Metabox values
	 *
	 * @since    1.0.0
	 */
	function meta_box_save( $post_id ) {
		// Stop the script when doing autosave
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

		// Verify the nonce. If insn't there, stop the script
		if( !isset( $_POST['wen_map_marker_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['wen_map_marker_meta_box_nonce'], 'wen_map_marker_meta_box_nonce' ) ) return;

		// Stop the script if the user does not have edit permissions
		if( ! current_user_can( 'edit_post', $post_id ) ) return;

	    // Save the textfield
		if( isset( $_POST['wen_map_marker_address'] ) )
			update_post_meta( $post_id, 'wen_map_marker_address', esc_attr( $_POST['wen_map_marker_address'] ) );

	    // Save the textarea
		if( isset( $_POST['wen_map_marker_zoom'] ) )
			update_post_meta( $post_id, 'wen_map_marker_zoom', esc_attr( $_POST['wen_map_marker_zoom'] ) );

		// Save the textarea
		if( isset( $_POST['wen_map_marker_lat'] ) )
			update_post_meta( $post_id, 'wen_map_marker_lat', esc_attr( $_POST['wen_map_marker_lat'] ) );

		// Save the textarea
		if( isset( $_POST['wen_map_marker_lng'] ) )
			update_post_meta( $post_id, 'wen_map_marker_lng', esc_attr( $_POST['wen_map_marker_lng'] ) );

		// Save the textarea
		if( isset( $_POST['wen_map_marker_content_append'] ) )
			update_post_meta( $post_id, 'wen_map_marker_content_append', esc_attr( $_POST['wen_map_marker_content_append'] ) );
	}

	/**
	 * Load Script on admin head
	 *
	 * @since    1.0.0
	 */
	function admin_head() {

		$screen = get_current_screen();
		$wen_map_marker_settings = get_option('wen_map_marker_settings');
		$post_types = array();
		if ( isset( $wen_map_marker_settings['post_types'] ) ) {
			$post_types = (array)$wen_map_marker_settings['post_types'];
		}
		$post_types[] = 'toplevel_page_wen-map-marker';

		if ( ! in_array( $screen->id, $post_types ) ) {
			return;
		}

		$map_options = array( 'showMarker' => false,
				'showMarkerOnClick' => true,
				'markerOptions'     => array(
					'draggable' => true
				),
				'autoLocate'        => false,
				'geoLocationButton' => ".wen-map-marker-locate-user",
				'searchInput'       => "#wen-map-marker-search",
				'afterMarkerDrag'   => 'function(response){console.log(response)
					$("#wen-map-marker-zoom").val(response.zoom);
					$("#wen-map-marker-lat").val(response.lat);$("#wen-map-marker-lng").val(response.lng);$("#wen-map-marker-address").val(response.address);$("#wen-map-marker-search").val(response.address);}'
			);

		$jquery_mapify_helper = new jquery_mapify_helper();

		if(isset($_GET['action'])){

			global $post;


			$wen_map_marker_lat = $this->get_custom_field_value("wen_map_marker_lat");
			$wen_map_marker_lng = $this->get_custom_field_value("wen_map_marker_lng");
			$wen_map_marker_zoom = $this->get_custom_field_value("wen_map_marker_zoom");


			if($wen_map_marker_lat != "" and $wen_map_marker_lng != "" ){
				$map_options['showMarker'] = true;
				$map_options['lat']        = $wen_map_marker_lat;
				$map_options['lng']        = $wen_map_marker_lng;
				$map_options['zoom']        = (int)$wen_map_marker_zoom;
				echo $jquery_mapify_helper->create( $map_options, 'wen-map-marker-canvas', false);
	        }
	        else{

				echo $jquery_mapify_helper->create( $map_options, 'wen-map-marker-canvas', false);
	        }

		}
		else{
			echo $jquery_mapify_helper->create( $map_options, 'wen-map-marker-canvas', false);
		}

		echo '<script>
		jQuery(function($){
			$(".clear-marker").click(function(){
				$.jMapify.removeMarker();
				$("#wen-map-marker-zoom").val("");
				$("#wen-map-marker-lat").val("");
				$("#wen-map-marker-lng").val("");
				$("#wen-map-marker-address").val("");
				$("#wen-map-marker-search").val("");
			});
		});</script>';

	}

	function setup_menu(){
	    add_menu_page( __('WEN Map Marker',"wen-map-marker"), __('WEN Map Marker',"wen-map-marker"), 'manage_options', 'wen-map-marker', array(&$this,'option_page_init') );
	    add_action( 'admin_init', array(&$this,'register_settings' ));
	}

	function option_page_init(){
	    include(sprintf("%s/partials/wen-map-marker-admin-display.php",dirname(__FILE__)));
	}

	/**
	 * register our settings
	 *
	 * @since    1.0.0
	 */
	function register_settings() {
		register_setting( 'wen-map-marker-settings-group', 'wen_map_marker_settings' );

		add_settings_section(
			'wen_map_marker_setting_post_type_section',
			__( 'Post Type Options', 'wen-map-marker' ),
			'__return_false',
			'wen-map-marker-settings-group'
		);

		add_settings_field(
			'wen_map_marker_setting_post_types',
			__( 'Select Post Types', 'wen-map-marker' ),
			array(&$this,'checkbox_field_render'),
			'wen-map-marker-settings-group',
			'wen_map_marker_setting_post_type_section'
		);
	}

	/**
	 * Section callback function
	 *
	 * @since    1.0.0
	 */
	function checkbox_field_render()
	{
		$post_types = get_post_types(array(   'public'   => true ));
        $wen_map_marker_settings = get_option('wen_map_marker_settings');
		foreach ($post_types as $key => $post_type) {
            if('attachment' != $key){
                $checked = ( isset($wen_map_marker_settings['post_types']) and is_array($wen_map_marker_settings['post_types']) and in_array($key,$wen_map_marker_settings['post_types']))?"checked='checked'":"";
                echo '<label for="post_type_'.$key.'">
                        <input name="wen_map_marker_settings[post_types][]" type="checkbox" '.$checked.' value="'.$key.'" id="post_type_'.$key.'"  />
                        <span>'.ucfirst($post_type).'</span></label><br />';
            }
        }

	}

	/**
	 * Add the settings link to the plugins page
	 *
	 * @since    1.0.0
	 */
	function plugin_settings_link($links)
	{
		$settings_link = '<a href="admin.php?page=wen-map-marker">'.__('Settings',"wen-map-marker").'</a>';
		array_unshift($links, $settings_link);
		return $links;
	}

	/**
	 * Init the tinymce button
	 *
	 * @since    1.0.0
	 */
	function tinymce_button_init() {

	     if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
	          add_filter( 'mce_buttons', array(&$this,'register_tinymce_button' ));
	          add_filter( 'tiny_mce_before_init', array(&$this,'add_tinymce_button' ));
	     }
	}

	/**
	 * Register tinymce button
	 *
	 * @since    1.0.0
	 */
	function register_tinymce_button( $buttons ) {

		$screen = get_current_screen();
		$wen_map_marker_settings = get_option('wen_map_marker_settings');

		if(!isset($wen_map_marker_settings['post_types']) || empty( $wen_map_marker_settings['post_types'] ))
			return;

		if( is_array($wen_map_marker_settings['post_types']) and !in_array($screen->id,$wen_map_marker_settings['post_types']))
			return $buttons;

		array_push( $buttons, '|', 'WEN' );
			return $buttons;

	}

	/**
	 * Add tinymce button callback function
	 *
	 * @since    1.0.0
	 */
	function add_tinymce_button( $initArray )
	{
		$icon_path = plugin_dir_url( __FILE__ ) . 'images/map_button.png';
		$title = __("Add WEN Map Marker Shortcode","wen-map-marker");
      $initArray['setup'] = <<<JS
[function(ed) {
    ed.addButton('WEN', {
        title : '$title',
        image : '$icon_path',
        onclick : function() {
           /*var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
     W = W - 80;
     H = H - 84;
     tb_show( '$title', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=WMM-popup-form' );*/
	
	var shortcode = '[WMM';
	shortcode += ']';
	// inserts the shortcode into the active editor
	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);

        }
    });
}][0]
JS;
    return $initArray;

    }

	/**
	 * Add Popup for the editor button.
	 *
	 * @since    1.0.0
	 */
	/*function tinymce_popup($links)
	{
		global $post;
		$screen = get_current_screen();
		$wen_map_marker_settings = get_option('wen_map_marker_settings');

		if(!isset($wen_map_marker_settings['post_types']) || empty( $wen_map_marker_settings['post_types'] ))
			return;

		if( is_array($wen_map_marker_settings['post_types']) and !in_array($screen->id,$wen_map_marker_settings['post_types']))
			return;
		$wen_map_marker_zoom = get_post_meta( $post->ID, "wen_map_marker_zoom",true );
	  ?>
	  <div id="WMM-popup-form" style="display:none">
	    <div>
	    <p>
	      <label for="wmm-zoom"><strong><?php _e("Enter zoom","wen-map-marker");?></strong></label>
	      <input type="text" name="wmm-zoom" id="wmm-zoom" value="<?php echo (''!=$wen_map_marker_zoom)?$wen_map_marker_zoom:15;?>" />
	    </p>
	      <?php
	      	submit_button( __('Insert', 'wen-map-marker'), 'primary', 'submit', true, array( 'id' => 'WMM-submit' ) );
      	?>

	    </div>
	  </div><!-- #WMM-popup-form -->
	      <script type="text/javascript">

	      jQuery(document).ready(function($){
	        $('#WMM-submit').click(function(e){
	          e.preventDefault();

	          var wmm_zoom = $('#wmm-zoom').val();

	          if('' == wmm_zoom){
	            $('#wmm-zoom').addClass('error');
	            return false;
	          }
	          else if( parseInt(wmm_zoom) != wmm_zoom ){
	            $('#wmm-zoom').addClass('error');
	            return false;
	          }
	          else {
	            $('#wmm-zoom').removeClass('error');
	            var shortcode = '[WMM';
	            shortcode += ' zoom="'+wmm_zoom+'"';
	            shortcode += ']';
	            // inserts the shortcode into the active editor
	            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);

	            // closes Thickbox
	            tb_remove();
	          }


	        });
	      });


	         </script>
	  <?php

	}*/

}
