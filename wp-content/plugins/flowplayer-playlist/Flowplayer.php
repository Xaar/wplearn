<?php

namespace Eye8\FlPlaylist;

class Flowplayer extends Plugin {
	
	const FLOWPLAYER_SCRIPT_PATH = 'flowplayer/flowplayer-3.2.12.min.js';
	const FLOWPLAYER_SWF_PATH = 'flowplayer/flowplayer-3.2.16.swf';
	const FLOWPLAYER_COMMERCIAL_SWF_PATH = 'flowplayer/flowplayer.commercial-3.2.16.swf';
	const FLOWPLAYER_YOUTUBE_PLUGIN_PATH = 'flowplayer/edlab.youtube-1.2.swf';


	public function bind(){
		
		add_action('wp_enqueue_scripts', 'Eye8\FlPlaylist\Flowplayer::flowplayer_scripts');
		
		return $this;
	}
	
	public function flowplayer_scripts() {
		
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'flowplayer', plugin_dir_url( __FILE__ ).self::FLOWPLAYER_SCRIPT_PATH, array('jquery'), '3.2.12' );
		
	}
	
	public function embed( $options = array() ){
		
		$flowplayer_key = get_option( 'flplaylist_flowplayer_license' );
		if( empty($flowplayer_key) && is_multisite() ) {
			global $blog_id;
			if( $blog_id != 1 ) {
				switch_to_blog( 1 );
				$flowplayer_key = get_option( 'flplaylist_flowplayer_license' );
				restore_current_blog();
			}
		}
		$use_commercial = empty( $flowplayer_key ) ? FALSE : TRUE;
		
		$autoBuffering = $options['autoBuffering'] ? 1 : 0;
		
		ob_start();
?>

		<div class='flplayer' 
			id='<?php echo $options['uniquedivid']; ?>' 
			style='display:block;width:<?php echo $options['width']; ?>px;height:<?php echo $options['height']; ?>px;'></div>
		
		<script type="text/javascript" defer="defer"><!--
		
		jQuery(document).ready(function($){
			
			flowplayer('<?php echo $options['uniquedivid']; ?>', '<?php 
					echo plugin_dir_url( __FILE__ );
					if( $use_commercial )
						echo self::FLOWPLAYER_COMMERCIAL_SWF_PATH;
					else 
						echo self::FLOWPLAYER_SWF_PATH;
			?>', {
			<?php
				if( $use_commercial )
					echo "key: '" . $flowplayer_key . "',";
			?>
				clip: {
					autoPlay: <?php echo $options['autoPlay'] ? 1 : 0; ?>,
				},
				playlist: [
				
				<?php
					
					$i = 0;
					
					foreach( $options['playlist'] as $vid ) {
				?>
				
					{
						
				<?php
						if( 'youtube' === self::check_video_provider( $vid ) ) {
				?>
						provider: "youtube",
						scaling: "scale",
						url: "<?php echo self::get_youtube_video_id( $vid ); ?>",
						autoBuffering: 0,
				<?php		
						} else {
						
							echo 'url: "' . $vid . '",';
							echo 'autoBuffering: ' . $autoBuffering . ',';
						
						}
						
						if( 0 != $i ) // autoPlay all clips except the first clip
							echo 'autoPlay: 1';
						
						$i++;
				?>
				
					},
					
				<?php
					} // end FOREACH
				?>
				],
				plugins: {
					youtube: { 
						url: "<?php echo plugin_dir_url( __FILE__ ).self::FLOWPLAYER_YOUTUBE_PLUGIN_PATH; ?>" 
					},
					controls: {
						playlist: true
					}
				}
				
			});
			
		});
		
		//--></script>

<?php	
		
		$output = ob_get_contents();
		
	    ob_end_clean(); 
	    
	    return $output;
		
	}
	
	function get_youtube_video_id( $vurl ){
		parse_str( parse_url( $vurl, PHP_URL_QUERY ), $parsed_url );
		return "api:" . $parsed_url['v'];
	}
	
	function check_video_provider( $vurl ){
		$pattern = '/^http:\/\/(?:www\.)?youtube.com\/watch\?(?=.*v=\w+)(?:\S+)?$/';
		preg_match( $pattern, $vurl, $matches, PREG_OFFSET_CAPTURE );
		if( count( $matches )>0 )
			return "youtube";
		else {
			return "http";
		}
	}
	
}
