<?php

/**
 * The file that helps to create map output
 *
 * A class definition that includes function that creates map
 *
 * @link       http://wenthemes.com
 * @since      1.0.0
 *
 * @package    WEN_Map_Marker
 * @subpackage WEN_Map_Marker/includes
 */

class jquery_mapify_helper {


	public function create( $new_settings = array(),$canvas_id = NULL, $show_canvas=true ){
		$default_settings = array(
			'width'                  => '100%',
			'height'                 => '500',
			'lat'                    => 27.7000,
			'lng'                    => 85.3333,
			'zoom'                   => 15,
			'type'                   => 'ROADMAP',
			'draggable'              => true,
			'zoomControl'            => true,
			'scrollwheel'            => true,
			'disableDoubleClickZoom' => false,
			'showMarker'             => false,
			'showMarkerOnClick'      =>false,
			'markerOptions'          => array(
				'draggable'   => false,
				'raiseOnDrag' => false
			),
			// 'afterMarkerDrag' => function() {},
			'autoLocate'        => false,
			'geoLocationButton' => null,
			'searchInput'       => null
		);
		if(isset($new_settings['afterMarkerDrag'])){
			$afterMarkerDrag = $new_settings['afterMarkerDrag'];
			unset($new_settings['afterMarkerDrag']);
		}
		$canvas_name = ($canvas_id==NULL)?'wen_map_marker_options_'.rand():str_replace("-","_",$canvas_id);
		$canvas_id = ($canvas_id==NULL)?'wen_map_marker_options_'.rand():$canvas_id;
		$merged_settings = array_merge( $default_settings, $new_settings );

		$script = '<script type="text/javascript">'."\n";
		$script .= 'var '.$canvas_name.'=null;'."\n";
		$script .= 'jQuery(function($){'."\n";
		$script .=''.$canvas_name.'='.json_encode($merged_settings).';'."\n";
		if(isset($afterMarkerDrag)){
			$script .=''.$canvas_name.'.afterMarkerDrag='.$afterMarkerDrag.';'."\n";
		}
		$script .= $canvas_name.'=$("#'.$canvas_id.'").jMapify('.$canvas_name.');'."\n";
		$script .= ' });'."\n";
		$script .= '</script>';

		$map = '<div id="'.$canvas_id.'"></div>';

		return ($show_canvas)?$script.$map:$script;
	}
}
