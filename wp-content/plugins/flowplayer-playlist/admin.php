<?php
namespace Eye8\FlPlaylist;

define( 'FLPLAYLIST_NONCE_KEYWORD', 'flplaylist_update_settings' );

function flplaylist_admin_init() {
    
    if( isset($_POST["submit"]) && isset($_POST[FLPLAYLIST_NONCE_KEYWORD]) ){
      
        check_admin_referer( basename( __FILE__ ), FLPLAYLIST_NONCE_KEYWORD );
        
        update_option("flplaylist_flowplayer_license", $_POST["flowplayer_license"]);
		
?>		
        
        <div class="updated fade" style="padding:6px;">
          <?php _e("Settings Updated", "flplaylist"); ?>.
        </div>

<?php
    }
	
}
add_action('admin_init', 'Eye8\FlPlaylist\flplaylist_admin_init');

function flplaylist_add_pages() {
   
	add_submenu_page( 'edit.php?post_type=flplaylist', 'FlPlaylist Configuration', 'Configuration', 'manage_options', 'flplaylist-configuration-page', 'Eye8\FlPlaylist\flplaylist_print_configuration' );

}
add_filter( "admin_menu", 'Eye8\FlPlaylist\flplaylist_add_pages' );

function flplaylist_print_configuration() {
	  
?>
  
  <div class='wrap'>
      <h2>FlPlaylist Configuration</h2>
        
        <form method="post">
          
          <?php wp_nonce_field( basename( __FILE__ ), FLPLAYLIST_NONCE_KEYWORD ); ?>
          
          <table class="form-table" style="width:640px;">
              <tbody>
                  <tr valign="top">
                      <th scope="row">
                          <label for="flowplayer_license">Flowplayer License Key</label>
                        </th>
                        <td>
                            <input id="flowplayer_license" name="flowplayer_license" style="width:350px;" value="<?php echo get_option('flplaylist_flowplayer_license'); ?>" />
                        		<br />
                        		<span style="color:#999;">
                        			Purchase a <a href="http://flash.flowplayer.org/download/">Flowplayer license key</a> for your website to remove the Flowplayer logo on the video.<br />
                        			Note: You must purchase a license key for the Flash-based instead of the HTML5-based version.<br />
                        			<i>Disclaimer: I don't work for Flowplayer. It's up to you if you want to purchase the license or continue using the free version with logo.</i>
                        		</span>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <br />
            
            <p>
              <input type="submit" name="submit" value="Save Settings" class="button-primary gf_settings_savebutton" />
            </p>
        </form>
    </div>
    
<?php
}

function flplaylist_register_cb(){
	add_meta_box( 'flplaylist_metabox', 'Playlist Settings', 'Eye8\FlPlaylist\flplaylist_print_metaboxes', 'flplaylist' );
}

function flplaylist_print_metaboxes(){
	
	global $post;
	$playlist_meta = get_post_meta( $post->ID, "flplaylist_meta" );
	$playlist_meta = empty( $playlist_meta ) ? array() : $playlist_meta[0];
	wp_nonce_field( basename( __FILE__ ), FLPLAYLIST_NONCE_KEYWORD );
?>
	<div style="padding:10px 0;">
		<label for="flplaylist_post_id" style="display:inline-block;width:10em;text-align:left">Playlist ID :</label>
		<input id="flplaylist_post_id" name="flplaylist_post_id" style="width:30px" 
			value="<?php echo $post->ID; ?>" disabled="disabled" /> 
		&nbsp;<span style="color:#999;">Playlist ID cannot be changed</span>
	</div>
	<div style="padding:10px 0;">
		<label for="flplaylist_video_width" style="display:inline-block;width:10em;text-align:left">Video Size :</label>
		<input id="flplaylist_video_width" name="flplaylist_video_width" style="width:50px" 
			value="<?php if( !empty( $playlist_meta["video_width"] ) ) echo $playlist_meta["video_width"]; ?>" /> x 
		<input id="flplaylist_video_height" name="flplaylist_video_height" style="width:50px" 
			value="<?php if( !empty( $playlist_meta["video_height"] ) ) echo $playlist_meta["video_height"]; ?>"/> pixels
	</div>
	<div style="padding:10px 0;">
		<label style="display:inline-block;width:10em;text-align:left">Auto Play :</label>
		<input type="radio" id="flplaylist_autoplay_1" name="flplaylist_autoplay" value="1" 
			 <?php if( !empty( $playlist_meta["autoPlay"] ) ) echo 'checked="checked"'; ?> />
		<label for="flplaylist_autoplay_1">Yes</label>&nbsp;&nbsp;
		<input type="radio" id="flplaylist_autoplay_0" name="flplaylist_autoplay" value="0" 
			 <?php if( empty( $playlist_meta["autoPlay"] ) ) echo 'checked="checked"'; ?> />
		<label for="flplaylist_autoplay_0">No</label>
	</div>
	<div style="padding:10px 0;">
		<label style="display:inline-block;width:10em;text-align:left">Auto Buffering :</label>
		<input type="radio" id="flplaylist_autobuffering_1" name="flplaylist_autobuffering" value="1" 
			 <?php if( !empty( $playlist_meta["autoBuffering"] ) ) echo 'checked="checked"'; ?> />
		<label for="flplaylist_autobuffering_1">Yes</label>&nbsp;&nbsp;
		<input type="radio" id="flplaylist_autobuffering_0" name="flplaylist_autobuffering" value="0" 
			 <?php if( empty( $playlist_meta["autoBuffering"] ) ) echo 'checked="checked"'; ?> />
		<label for="flplaylist_autobuffering_0">No</label>
	</div>
	<div style="padding:10px 0;">
		<label for="flplaylist_video_urls" style="display:inline-block;width:10em;text-align:left">Video URLs :</label><br />
		<textarea id="flplaylist_video_urls" name="flplaylist_video_urls" style="width:500px;height:200px;"><?php if( !empty( $playlist_meta["video_urls"] ) ) echo implode("\n", $playlist_meta["video_urls"] ); ?></textarea>
		<br /><span style="color:#999;">Place the URLs of each video in a separate line. <br />Supports the following video formats: .flv, .mp4, .mov, .m4v., .f4v. See <a href="http://flash.flowplayer.org/documentation/installation/formats.html" target="_blank">Flowplayer Supported Formats</a></span>
	</div>
	<div style="padding:10px 0;">
		To embed this playlist in your post, use the following shortcode: <span style="padding:5px 10px; background-color:#CCC; color:#0000FF;">[flplaylist id="<?php echo $post->ID; ?>"]</span>
	</div>
<?php
}

function flplaylist_save_metabox( $post_id, $post ) {
	if ( !isset( $_POST[FLPLAYLIST_NONCE_KEYWORD] ) || !wp_verify_nonce( $_POST[FLPLAYLIST_NONCE_KEYWORD], basename( __FILE__ ) ) )
		return $post_id;
	
	$video_width = !empty( $_POST['flplaylist_video_width'] ) ? intval( $_POST['flplaylist_video_width'] ) : Constants::DEFAULT_WIDTH;
	$video_height = !empty( $_POST['flplaylist_video_height'] ) ? intval( $_POST['flplaylist_video_height'] ) : Constants::DEFAULT_HEIGHT;
	$video_urls = !empty( $_POST['flplaylist_video_urls'] ) ? trim( $_POST['flplaylist_video_urls'] ) : "";
	$autoPlay = $_POST['flplaylist_autoplay'];
	$autoBuffering = $_POST['flplaylist_autobuffering'];
	
	
	if( empty( $video_urls ) ) { // if video urls are not given, remove post meta
		delete_post_meta( $post_id, "flplaylist_meta" );
	} else {
		
		$url_array = explode( "\n", $video_urls );
				
		foreach( $url_array as $i=>$url ) {
			$url = trim( $url );
			if( empty( $url ) ) 
				unset( $url_array[$i] );
			else 
				$url_array[$i] = esc_url_raw( $url );			
		}
		
		$success = update_post_meta( $post_id, "flplaylist_meta", array(
			"video_width" => $video_width,
			"video_height" => $video_height,
			"video_urls" => $url_array,
			"autoPlay" => $autoPlay,
			"autoBuffering" => $autoBuffering
		));
	}
}
add_action( 'save_post', 'Eye8\FlPlaylist\flplaylist_save_metabox', 10, 2 );
