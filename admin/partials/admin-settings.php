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

  <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

  <?php settings_errors(); ?>

  <div id="poststuff">

    <div id="post-body" class="metabox-holder columns-2">

      <!-- main content -->
      <div id="post-body-content">

      <form action="options.php" method="post">

        <?php settings_fields( 'wen-map-marker-settings-group' ); ?>

          <div class="meta-box-sortables ui-sortable">

            <div class="postbox">

              <div class="inside">

                <?php do_settings_sections( 'wen-map-marker-settings-group' ); ?>

              </div> <!-- .inside -->

            </div> <!-- .postbox -->

          </div> <!-- .meta-box-sortables .ui-sortable -->

          <?php submit_button( __( 'Save Changes', 'wen-map-marker' ) ); ?>

          </form>

      </div> <!-- post-body-content -->

      <!-- sidebar -->
      <div id="postbox-container-1" class="postbox-container">

        <?php require_once( WEN_MAP_MARKER_DIR . '/admin/partials/admin-sidebar.php' ); ?>

      </div> <!-- #postbox-container-1 .postbox-container -->

    </div> <!-- #post-body .metabox-holder .columns-2 -->

    <br class="clear">
  </div> <!-- #poststuff -->

</div> <!-- .wrap -->
