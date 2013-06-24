<?php
/*
Plugin Name: Flowplayer Playlist
Plugin URI: http://eye8.me/flplaylist
Description: Embed Flash video in Wordpress using Flowplayer v.3 (Flash).
Version: 0.2
Author: Eye8
Author URI: http://eye8.me

Copyright (C) 2013 Eye8

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

namespace Eye8\FlPlaylist;

define( 'FLPLAYLIST_DIR', plugin_dir_path(__FILE__) );  
define( 'FLPLAYLIST_URL', plugin_dir_url(__FILE__) );  

if ( !function_exists( 'add_action' ) ) {
  echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
  exit;
}  

require_once( FLPLAYLIST_DIR . 'Constants.php' );
require_once( FLPLAYLIST_DIR . 'admin.php' );


if( !is_admin() ) :
	
	require_once( FLPLAYLIST_DIR . 'Plugin.php' );       
	require_once( FLPLAYLIST_DIR . 'Playlist.php' );
	require_once( FLPLAYLIST_DIR . 'PlaylistManager.php' );       
	require_once( FLPLAYLIST_DIR . 'Flowplayer.php' );
		
	$playlistManager = PlaylistManager::getInstance()->bind();
	add_shortcode( 'flplaylist', array( $playlistManager, 'flplaylist_shortcode' ) );

endif;


function flplaylist_post_type(){
	register_post_type( 'flplaylist',
	    array(
	      'labels' => array(
	        'name' => __( 'FlPlaylist' ),
	        'singular_name' => __( 'FlPlaylist' )
	      ),
	      'public' => true,
	      'has_archive' => false,
	      'supports' => array( 'title' ),
	      'register_meta_box_cb' => 'Eye8\FlPlaylist\flplaylist_register_cb',
	      'rewrite' => array( 
	        'slug' => 'flplaylist'
	      ),
	    )
	  );
}
add_action( 'init', 'Eye8\FlPlaylist\flplaylist_post_type' );