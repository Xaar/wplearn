<?php 
/*
Plugin Name:  Interactive Maps
Plugin URI: http://simplemaps.com/wordpress
Description:  Easily add a World Continent JavaScript-powered HTML5 interactive map to your WordPress site.
Author:  Simplemaps.com
Author URI: http://simplemaps.com
Version 0.1
*/

function plugin_test(){
	echo 'hello world'; exit;
}

// add_action('admin_head', 'plugin_test');

/***************************************************************************************
*global variables
***************************************************************************************/

$sm_prefix = "sm_";
$sm_plugin_name = "interactive-maps";
$sm_options = get_option('sm_settings');  //array of settings from administration page

/***************************************************************************************
*includes
***************************************************************************************/

include('includes/scripts.php');
include('includes/options.php');

function foobar_func($atts){
$mapdata_url = get_option('simplemaps_mapdata_url');
$mapfile_url = get_option('simplemaps_mapfile_url');	 
return '<script type="text/javascript" src="'. $mapdata_url .'"></script><script type="text/javascript" src="'.$mapfile_url.'"></script><div id="map"></div>';
}


add_shortcode('simplemap', 'foobar_func' );
add_shortcode('simplemaps', 'foobar_func' );

