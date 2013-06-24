<?php

namespace Eye8\FlPlaylist;

class Playlist {
	
	public static $playlist_meta;
	
	function setPlaylist( $p ) {
		self::$playlist_meta["video_urls"] = $p;
	}
	
	function getPlaylist() {
		return self::$playlist_meta["video_urls"];
	}
	
	function setWidth( $w ) {
		self::$playlist_meta["video_width"] = $w;
	}
	
	function getWidth() {
		return self::$playlist_meta["video_width"];
	}
	
	function setHeight( $h ) {
		self::$playlist_meta["video_height"] = $h;
	}
	
	function getHeight() {
		return self::$playlist_meta["video_height"];
	}
	
	function setAutoPlay( $v ) {
		self::$playlist_meta["autoPlay"] = $v;
	}
	
	function getAutoPlay() {
		return self::$playlist_meta["autoPlay"];
	}
	
	function setAutoBuffering( $v ) {
		self::$playlist_meta["autoBuffering"] = $v;
	}
	
	function getAutoBuffering() {
		return self::$playlist_meta["autoBuffering"];
	}
	
	function getDummyPlaylistMeta(){
		return array(
				"video_width" => Constants::DEFAULT_WIDTH,
				"video_height" => Constants::DEFAULT_HEIGHT,
				"video_urls" => array(),
				"autoPlay" => false,
				"autoBuffering" => false
			);
	}
	
	function fixPlaylistMeta( $playlistMeta ){
		
		return array_merge( self::getDummyPlaylistMeta(), $playlistMeta );
	}
	
	function __construct( $post_id=NULL ) {
		
		if( empty( $post_id ) ) {
			self::$playlist_meta = getDummyPlaylistMeta();
		} else {
			$playlist_meta = get_post_meta( $post_id, "flplaylist_meta" );
			self::$playlist_meta = empty( $playlist_meta[0] ) ? self::getDummyPlaylistMeta() : self::fixPlaylistMeta( $playlist_meta[0] );
			
		}
		
	}
}
