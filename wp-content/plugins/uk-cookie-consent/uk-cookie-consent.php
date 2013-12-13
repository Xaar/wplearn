<?php
/*
Plugin Name: UK Cookie Consent
Plugin URI: http://catapultdesign.co.uk/plugin/uk-cookie-consent/
Description: Simple plug-in to help compliance with the UK interpretation of the EU regulations regarding usage of website cookies. A user to your site is presented with a clear yet unobtrusive notification that the site is using cookies and may then acknowledge and dismiss the notification or click to find out more. The plug-in does not disable cookies on your site or prevent the user from continuing to browse the site - it comes with standard wording on what cookies are and advice on how to disable them in the browser. The plug-in follows the notion of "implied consent" as described by the UK's Information Commissioner and makes the assumption that most users who choose not to accept cookies will do so for all websites.
Author: Catapult
Version: 1.7.1
Author URI: http://catapultdesign.co.uk/
*/

// Language
load_plugin_textdomain( 'uk-cookie-consent', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

//Add an option page for the settings
add_action('admin_menu', 'catapult_cookie_plugin_menu');
function catapult_cookie_plugin_menu() {
	add_options_page( __( 'Cookie Consent', 'uk-cookie-consent' ), __( 'Cookie Consent', 'uk-cookie-consent' ), 'manage_options', 'catapult_cookie_consent', 'catapult_cookie_options_page' );
}

function catapult_cookie_options_page() { ?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php _e( 'UK Cookie Consent', 'uk-cookie-consent' ); ?></h2>
		<div id="poststuff" class="metabox-holder has-right-sidebar">
			<div id="side-info-column" class="inner-sidebar">
				<div id="side-sortables" class="meta-box-sortables ui-sortable">
					<div class="postbox like-postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span>Did this plugin help you?</span></h3>
						<div class="inside">
							<div class="like-widget">
							<p>If this plugin helped you out in your project, please show your support:</p>
							<ul>
								<li><a target="_blank" href="http://wordpress.org/plugins/uk-cookie-consent/">Rate it</a></li>
								<li><a target="_blank" href="http://twitter.com/share?url=&amp;text=Check out the UK Cookie Consent plugin for WordPress from @_catapult_ - it's sweet: http://bit.ly/190GGXN">Tweet it</a></li>
							</ul>
							</div>
						</div>
					</div><!-- .postbox -->

					<div class="postbox like-postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span>Other plugins you might like</span></h3>
						<div class="inside">
							<div class="like-widget">
							<p>If you liked this plugin, you may care to try the following:</p>
							<ul>
								<li><a target="_blank" href="http://wordpress.org/plugins/wp-slide-out-tab/">Slide out tab</a>
								<p>Display a sliding tab for marketing, promotional or other content.</p>							
								</li>
							</ul>
							</div>
						</div>
					</div><!-- .postbox -->
					
					<div class="postbox rss-postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span>Cookie resources</span></h3>
						<div class="inside">
							<p><a href="http://www.ico.gov.uk/for_organisations/privacy_and_electronic_communications/the_guide/cookies.aspx"><?php _e( 'Information Commissioner\'s Office Guidance on Cookies', 'uk-cookie-consent' ); ?></a></p>
							<p><a href="http://www.aboutcookies.org/default.aspx">AboutCookies.org</a></p>
							<p><a href="http://catapultdesign.co.uk/uk-cookie-consent/"><?php _e( 'Our interpretation of the guidance', 'uk-cookie-consent' ); ?></a></p>
						</div>
					</div>
					
					<div class="postbox rss-postbox">
						<div class="handlediv" title="Click to toggle"><br></div>
						<h3 class="hndle"><span>Support</span></h3>
						<div class="inside">
							<div class="rss-widget">
								<?php
									wp_widget_rss_output(array(
										'url' => 'http://wordpress.org/support/rss/plugin/uk-cookie-consent',
										'title' => 'Latest from the support forum',
										'items' => 3,
										'show_summary' => 1,
										'show_author' => 0,
									'show_date' => 1,
									));
								?>
								<ul>
								</ul>
							</div>
							<p><a href="http://wordpress.org/support/plugin/wp-slide-out-tab" title="Forum">Check out the forum</a></p>
						</div>
					</div><!-- .postbox -->
													
				</div>
			</div><!-- #side-info-column -->
			
			<div id="post-body">
				<div id="post-body-content">
						<div class="meta-box-sortables">
						<div class="postbox">
							<h3 class="hndle"><?php _e( 'Your settings', 'uk-cookie-consent' ); ?></h3>
							<div class="inside">
								<form action="options.php" method="post">				
									<?php settings_fields('catapult_cookie_options'); ?>
									<?php do_settings_sections('catapult_cookie'); ?>
									<input name="cat_submit" type="submit" id="submit" class="button-primary" style="margin-top:30px;" value="<?php esc_attr_e( __( 'Save Changes', 'uk-cookie-consent' ) ); ?>" />
									<?php $options = get_option('catapult_cookie_options');
									$value = htmlentities ( $options['catapult_cookie_link_settings'], ENT_QUOTES );
									if ( !$value ) {
										$value = 'cookie-policy';
									} ?>
									<p><?php echo sprintf( __( 'Your Cookies Policy page is <a href="%s">here</a>. You may wish to create a menu item or other link on your site to this page.', 'uk-cookie-consent' ), home_url( $value ) ); ?></p>
								</form>
							</div>
						</div>
					</div>
					
					
				</div>
			</div>
		</div><!-- poststuff -->
	</div>
<?php }

add_action('admin_init', 'catapult_cookie_admin_init');

function catapult_create_policy_page() {
	//Check to see if the info page has been created
	$options = get_option('catapult_cookie_options');
	$pagename = __( 'Cookie Policy', 'uk-cookie-consent' );
	$cpage = get_page_by_title ( $pagename );
	if ( !$cpage ) {
		global $user_ID;
		$page['post_type']    = 'page';
		$page['post_content'] = '<p>' . __( 'This site uses cookies - small text files that are placed on your machine to help the site provide a better user experience. In general, cookies are used to retain user preferences, store information for things like shopping carts, and provide anonymised tracking data to third party applications like Google Analytics. As a rule, cookies will make your browsing experience better. However, you may prefer to disable cookies on this site and on others. The most effective way to do this is to disable cookies in your browser. We suggest consulting the Help section of your browser or taking a look at <a href="http://www.aboutcookies.org">the About Cookies website</a> which offers guidance for all modern browsers', 'uk-cookie-consent' ) . '</p>';
		$page['post_parent']  = 0;
		$page['post_author']  = $user_ID;
		$page['post_status']  = 'publish';
		$page['post_title']   = $pagename;
		$pageid = wp_insert_post ( $page );
	}
}
register_activation_hook( __FILE__, 'catapult_create_policy_page' );

function catapult_cookie_admin_init(){
	register_setting( 'catapult_cookie_options', 'catapult_cookie_options', 'catapult_cookie_options_validate' );
	add_settings_section('catapult_cookie_main', '', 'catapult_cookie_section_text', 'catapult_cookie', 'catapult_cookie_main' );
	add_settings_field('catapult_cookie_text', __( 'Notification text', 'uk-cookie-consent' ), 'catapult_cookie_text_settings', 'catapult_cookie', 'catapult_cookie_main' );
	add_settings_field('catapult_cookie_accept', __( 'Accept text', 'uk-cookie-consent' ), 'catapult_cookie_accept_settings', 'catapult_cookie', 'catapult_cookie_main' );
	add_settings_field('catapult_cookie_more', __( 'More info text', 'uk-cookie-consent' ), 'catapult_cookie_more_settings', 'catapult_cookie', 'catapult_cookie_main' );
	add_settings_field('catapult_cookie_link', __( 'Info page permalink', 'uk-cookie-consent' ), 'catapult_cookie_link_settings', 'catapult_cookie', 'catapult_cookie_main' );
	add_settings_field('catapult_cookie_text_colour', __( 'Text colour', 'uk-cookie-consent' ), 'catapult_cookie_text_colour_settings', 'catapult_cookie', 'catapult_cookie_main' );
	add_settings_field('catapult_cookie_link_colour', __( 'Link colour', 'uk-cookie-consent' ), 'catapult_cookie_link_colour_settings', 'catapult_cookie', 'catapult_cookie_main' );
	add_settings_field('catapult_cookie_bg_colour', __( 'Bar colour', 'uk-cookie-consent' ), 'catapult_cookie_bg_colour_settings', 'catapult_cookie', 'catapult_cookie_main' );
	add_settings_field('catapult_cookie_button_colour', __( 'Button colour', 'uk-cookie-consent' ), 'catapult_cookie_button_colour_settings', 'catapult_cookie', 'catapult_cookie_main' );
	add_settings_field('catapult_cookie_bar_position', __( 'Notification position', 'uk-cookie-consent' ), 'catapult_cookie_bar_position_settings', 'catapult_cookie', 'catapult_cookie_main' );
}

function catapult_cookie_section_text() {
	echo '<p>' . __( 'You can just use these settings as they are or update the text as you wish. We recommend keeping it brief.', 'uk-cookie-consent' ) . '</p>
		<p>' . __( 'The plug-in automatically creates a page called "Cookie Policy" and sets the default More Info link to yoursitename.com/cookie-policy.', 'uk-cookie-consent' ) . '</p>
		<p>' . __( 'If you find the page hasn\'t been created, hit the Save Changes button on this page.', 'uk-cookie-consent' ) . '</p>
		<p>' . __( 'If you would like to change the permalink, just update the Info page permalink setting, e.g. enter "?page_id=4" if you are using the default permalink settings (and 4 is the id of your new Cookie Policy page).', 'uk-cookie-consent' ) . '</p>
		<p>' . sprintf( __( 'For any support queries, please post on the <a href="%s">WordPress forum</a>.', 'uk-cookie-consent' ), 'http://wordpress.org/extend/plugins/uk-cookie-consent/' ) . '</p>
		<p><strong>' . sprintf( __( 'And if this plug-in has been helpful to you, then <a href="%s">please rate it</a>.', 'uk-cookie-consent' ), 'http://wordpress.org/extend/plugins/uk-cookie-consent/' ) . '</strong></p>';
}

function catapult_cookie_text_settings() {
	$options = get_option( 'catapult_cookie_options' );
	$value = $options['catapult_cookie_text_settings'];
	if ( !$value ) {
		$value = __( 'This site uses cookies', 'uk-cookie-consent' );
	}
	echo '<input id="catapult_cookie_text_settings" name="catapult_cookie_options[catapult_cookie_text_settings]" size="50" type="text" value="' . esc_attr( $value ) . '" />';
}
function catapult_cookie_accept_settings() {
	$options = get_option('catapult_cookie_options');
	$value = $options['catapult_cookie_accept_settings'];
	if ( !$value ) {
		$value = __( 'No problem', 'uk-cookie-consent' );
	}
	echo '<input id="catapult_cookie_accept_settings" name="catapult_cookie_options[catapult_cookie_accept_settings]" size="50" type="text" value="' . esc_attr( $value ) . '" />';
}
function catapult_cookie_more_settings() {
	$options = get_option('catapult_cookie_options');
	$value = $options['catapult_cookie_more_settings'];
	if ( !$value ) {
		$value = __( 'More info', 'uk-cookie-consent' );
	}
	echo '<input id="catapult_cookie_more_settings" name="catapult_cookie_options[catapult_cookie_more_settings]" size="50" type="text" value="' . esc_attr( $value ) . '" />';
}
function catapult_cookie_link_settings() {
	$options = get_option('catapult_cookie_options');
	$value = $options['catapult_cookie_link_settings'];
	if ( !$value ) {
		$value = __( 'cookie-policy', 'uk-cookie-consent' );
	}
	echo '<input id="catapult_cookie_link_settings" name="catapult_cookie_options[catapult_cookie_link_settings]" size="50" type="text" value="' . esc_attr( $value ) . '" />';
}
function catapult_cookie_text_colour_settings() {
	$options = get_option('catapult_cookie_options');
	$value = $options['catapult_cookie_text_colour_settings']; 
	if ( !$value ) {
		$value = '#dddddd';
	} ?>
	<input type="text" id="catapult_cookie_text_colour" name="catapult_cookie_options[catapult_cookie_text_colour_settings]" value="<?php echo $value; ?>" class="my-color-field" />
<?php }
function catapult_cookie_link_colour_settings() {
	$options = get_option('catapult_cookie_options');
	$value = $options['catapult_cookie_link_colour_settings']; 
	if ( !$value ) {
		$value = '#dddddd';
	} ?>
	<input type="text" name="catapult_cookie_options[catapult_cookie_link_colour_settings]" value="<?php echo $value; ?>" class="my-color-field" />
<?php }
function catapult_cookie_bg_colour_settings() {
	$options = get_option('catapult_cookie_options');
	$value = $options['catapult_cookie_bg_colour_settings']; 
	if ( !$value ) {
		$value = '#464646';
	} ?>
	<input type="text" name="catapult_cookie_options[catapult_cookie_bg_colour_settings]" value="<?php echo $value; ?>" class="my-color-field" />
<?php }
function catapult_cookie_button_colour_settings() {
	$options = get_option('catapult_cookie_options');
	$value = $options['catapult_cookie_button_colour_settings']; 
	if ( !$value ) {
		$value = '#45AE52';
	} ?>
	<input type="text" name="catapult_cookie_options[catapult_cookie_button_colour_settings]" value="<?php echo $value; ?>" class="my-color-field" />
<?php }
function catapult_cookie_bar_position_settings() {
	$options = get_option('catapult_cookie_options');
	$value = $options['catapult_cookie_bar_position_settings']; 
	if ( !$value ) {
		$value = 'top';
	} ?>
	<select id="catapult_cookie_bar_position_settings" name="catapult_cookie_options[catapult_cookie_bar_position_settings]" >';
		<option value="top" <?php if ( $value == 'top' ) { ?> selected="selected" <?php } ?>>Top</option>;
		<option value="bottom" <?php if ( $value == 'bottom' ) { ?> selected="selected" <?php } ?>>Bottom</option>;
	</select>
<?php }

function catapult_cookie_options_validate($input) {
	$options = get_option( 'catapult_cookie_options' );
	$options['catapult_cookie_text_settings'] = trim($input['catapult_cookie_text_settings']);
	$options['catapult_cookie_accept_settings'] = trim($input['catapult_cookie_accept_settings']);
	$options['catapult_cookie_more_settings'] = trim($input['catapult_cookie_more_settings']);
	$options['catapult_cookie_link_settings'] = trim($input['catapult_cookie_link_settings']);
	$options['catapult_cookie_text_colour_settings'] = trim($input['catapult_cookie_text_colour_settings']);
	$options['catapult_cookie_link_colour_settings'] = trim($input['catapult_cookie_link_colour_settings']);
	$options['catapult_cookie_bg_colour_settings'] = trim($input['catapult_cookie_bg_colour_settings']);
	$options['catapult_cookie_button_colour_settings'] = trim($input['catapult_cookie_button_colour_settings']);
	$options['catapult_cookie_bar_position_settings'] = trim($input['catapult_cookie_bar_position_settings']);
	return $options;
}
//Enqueue color-picker script for admin
function catapult_color_picker() {
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'uk-cookie-consent-colour-picker', plugins_url ( 'js/colour-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
//	wp_enqueue_script( 'dashboard' );
}
add_action( 'admin_enqueue_scripts', 'catapult_color_picker' );


//Enqueue jquery
function catapult_cookie_jquery() {
    wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'uk-cookie-consent-js', plugins_url ( 'js/uk-cookie-consent-js.js', __FILE__ ), array ( 'jquery' ) );
}
add_action('wp_enqueue_scripts', 'catapult_cookie_jquery');


//Add CSS and JS
//Add some JS to the header to test whether the cookie option has been set
function catapult_add_cookie_css() {
	$options = get_option( 'catapult_cookie_options' );
	if ( $options['catapult_cookie_text_colour_settings'] ) {
		$text_colour = $options['catapult_cookie_text_colour_settings'];
	} else {
		$text_colour = "#ddd";
	}
	if ( $options['catapult_cookie_link_colour_settings'] ) {
		$link_colour = $options['catapult_cookie_link_colour_settings'];
	} else {
		$link_colour = "#fff;";
	}
	if ( $options['catapult_cookie_bg_colour_settings'] ) {
		$bg_colour = $options['catapult_cookie_bg_colour_settings'];
	} else {
		$bg_colour = "#464646";
	}
	if ( $options['catapult_cookie_button_colour_settings'] ) {
		$button_colour = $options['catapult_cookie_button_colour_settings'];
	} else {
		$button_colour = "#45AE52";
	}
	if ( $options['catapult_cookie_bar_position_settings'] ) {
		$position = $options['catapult_cookie_bar_position_settings'];
	} else {
		$position = "top";
	}
	echo '
		<style type="text/css" media="screen">
			#catapult-cookie-bar {
				display: none;
				direction: ltr;
				color: ' . $text_colour . ';
				min-height: 30px;
				position: fixed;
				left: 0;
				' . $position . ': 0;
				width: 100%;
				z-index: 99999;
				padding:6px 20px 4px;
				background-color: ' . $bg_colour . ';
				text-align:left;
			}
			#catapult-cookie-bar a {
				color: ' . $link_colour . ';
			}
			button#catapultCookie {
				margin:0 20px;
				line-height:20px;
				background:' . $button_colour . ';
				border:none;
				color: ' . $link_colour . ';
				padding:4px 12px;
				border-radius: 3px;
				cursor: pointer;
				font-size: 13px;
				font-weight: bold;
			}
		</style>';
}
add_action ( 'wp_head', 'catapult_add_cookie_css' );

function catapult_add_cookie_js() { ?>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			if(!catapultReadCookie("catAccCookies")){//If the cookie has not been set
				jQuery("#catapult-cookie-bar").show();
				jQuery("html").css("margin-top","0");
			}
		});
	</script>
<?php }
add_action ( 'wp_footer', 'catapult_add_cookie_js' );

//Add the notification bar
function catapult_add_cookie_bar() {
	$options = get_option('catapult_cookie_options');
	if ( $options['catapult_cookie_text_settings'] ) {
		$current_text = $options['catapult_cookie_text_settings'];
	} else {
		$current_text = "This site uses cookies";
	}
	if ( $options['catapult_cookie_accept_settings'] ) {
		$accept_text = $options['catapult_cookie_accept_settings'];
	} else {
		$accept_text = "Okay, thanks";
	}
	if ( $options['catapult_cookie_more_settings'] ) {
		$more_text = $options['catapult_cookie_more_settings'];
	} else {
		$more_text = "Find out more";
	}
	if ( $options['catapult_cookie_link_settings'] ) {
		$link_text = strtolower ( $options['catapult_cookie_link_settings'] );
	} else {
		$link_text = "cookie-policy";
	}
	$content = sprintf( '<div id="catapult-cookie-bar">%s<button id="catapultCookie" tabindex=1 onclick="catapultAcceptCookies();">%s</button><a tabindex=1 href="%s">%s</a></div>', htmlspecialchars( $current_text ), htmlspecialchars( $accept_text ), home_url( $link_text ), htmlspecialchars( $more_text ) );
		echo apply_filters( 'catapult_cookie_content', $content, $options );
}
add_action ( 'wp_footer', 'catapult_add_cookie_bar', 1000 );