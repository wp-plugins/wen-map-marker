<div class="wrap">

  <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

  <div id="poststuff">

    <div id="post-body" class="metabox-holder columns-2">

      <!-- main content -->
      <div id="post-body-content">

        <div class="meta-box-sortables ui-sortable">

          <div class="postbox">

            <div class="inside">

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

            </div> <!-- .inside -->

          </div> <!-- .postbox -->

        </div> <!-- .meta-box-sortables .ui-sortable -->

      </div> <!-- post-body-content -->

      <!-- sidebar -->
      <div id="postbox-container-1" class="postbox-container">

        <?php require_once( WEN_MAP_MARKER_DIR . '/admin/partials/admin-sidebar.php' ); ?>

      </div> <!-- #postbox-container-1 .postbox-container -->

    </div> <!-- #post-body .metabox-holder .columns-2 -->

    <br class="clear">
  </div> <!-- #poststuff -->

</div> <!-- .wrap -->
