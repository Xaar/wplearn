<?php

namespace Eye8\FlPlaylist;

class PlaylistManager extends Plugin {
	
	public static $playlist;
	public static $flowplayer;
	
	public function bind(){
		
		self::$flowplayer = 	Flowplayer::getInstance()->bind();
		
		return $this;
	}
	
	function flplaylist_shortcode( $atts ){
		
		if( empty( self::$flowplayer ) ) return 'Error: Flowplayer not installed';
		
		if( empty( $atts['id'] ) ) return 'Error: Invalid playlist id';
		
		self::$playlist = new Playlist( $atts['id'] );
		
		$options = array(
			'uniquedivid' => 'flplaylist_' . wp_generate_password( 6, false ),
			'playlist' => self::$playlist->getPlaylist(),
			'width' => self::$playlist->getWidth(),
			'height' => self::$playlist->getHeight(),
			'autoPlay' => self::$playlist->getAutoPlay(),
			'autoBuffering' => self::$playlist->getAutoBuffering()
		);
	
		return self::$flowplayer->embed( $options );
		
	}
}
