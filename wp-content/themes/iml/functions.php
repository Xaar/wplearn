<?php
/**
 * IML functions and definitions
 *
 * @package IML
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

/*
 * Load Jetpack compatibility file.
 */
require( get_template_directory() . '/inc/jetpack.php' );

if ( ! function_exists( 'IML_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function IML_setup() {

	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Custom functions that act independently of the theme templates
	 */
	require( get_template_directory() . '/inc/extras.php' );

	/**
	 * Customizer additions
	 */
	require( get_template_directory() . '/inc/customizer.php' );

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on IML, use a find and replace
	 * to change 'IML' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'IML', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'IML' ),
	) );

	/**
	 * Enable support for Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );
}
endif; // IML_setup
add_action( 'after_setup_theme', 'IML_setup' );

/**
 * Setup the WordPress core custom background feature.
 *
 * Use add_theme_support to register support for WordPress 3.4+
 * as well as provide backward compatibility for WordPress 3.3
 * using feature detection of wp_get_theme() which was introduced
 * in WordPress 3.4.
 *
 * @todo Remove the 3.3 support when WordPress 3.6 is released.
 *
 * Hooks into the after_setup_theme action.
 */
function IML_register_custom_background() {
	$args = array(
		'default-color' => 'ffffff',
		'default-image' => '',
	);

	$args = apply_filters( 'IML_custom_background_args', $args );

	if ( function_exists( 'wp_get_theme' ) ) {
		add_theme_support( 'custom-background', $args );
	} else {
		define( 'BACKGROUND_COLOR', $args['default-color'] );
		if ( ! empty( $args['default-image'] ) )
			define( 'BACKGROUND_IMAGE', $args['default-image'] );
		add_custom_background();
	}
}
add_action( 'after_setup_theme', 'IML_register_custom_background' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function IML_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'IML' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'IML_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function IML_scripts() {
	wp_enqueue_style( 'IML-style', get_stylesheet_uri() );

	wp_enqueue_script( 'IML-scripts', get_template_directory_uri() . '/js/iml.js', array(), '20130404', true );   

	wp_enqueue_script( 'IML-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'IML-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	$jQuery = "http://code.jquery.com/jquery-latest.min.js";
	wp_deregister_script( 'jQuery' );
	wp_register_script( 'jQuery', $jQuery);
	wp_register_script('jquerytools', 'http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js', array('jQuery'));
	wp_enqueue_script( 'jQuery');
	wp_enqueue_script( 'jquerytools');

  wp_enqueue_script( 'jquery-touchSwipe', get_template_directory_uri() . '/js/jquery.touchSwipe.min.js' , array(), '163', true );

  wp_enqueue_script( 'raphaeljs', get_template_directory_uri() . '/js/raphael-min.js' , array(), '210', true );

  wp_enqueue_script( 'jqueryvisible', get_template_directory_uri() . '/js/jquery.visible.min.js' , array(), '1', true );

  wp_enqueue_script( 'jquerysmoothscroll', get_template_directory_uri() . '/js/jquery.smooth-scroll.min.js' , array(), '1411', true );

	$carouFredSel= get_bloginfo('template_directory') . "/js/jquery.carouFredSel-6.2.1.js";
	wp_deregister_script( 'carouFredSel' );
	wp_register_script( 'carouFredSel', $carouFredSel);
	wp_enqueue_script( 'carouFredSel');

}
add_action( 'wp_enqueue_scripts', 'IML_scripts' );


/**
 * Implement the Custom Header feature
 */
//require( get_template_directory() . '/inc/custom-header.php' );

/*
*Implement custom slider theme
*/

add_filter('new_royalslider_skins', 'new_royalslider_add_custom_skin', 10, 2);

function new_royalslider_add_custom_skin($skins) {
      $skins['imlSliderSkin'] = array(
           'label' => 'IML slider skin',
           'path' => get_template_directory_uri() . '/imlSliderSkin.css'
      );
      $skins['imlPromoSkin'] = array(
           'label' => 'IML promo skin',
           'path' => get_template_directory_uri() . '/imlPromoSkin.css'
      );
       $skins['imlProductSkin'] = array(
           'label' => 'IML product skin',
           'path' => get_template_directory_uri() . '/imlProductSkin.css'
      );
      return $skins;
}

register_new_royalslider_files(2);


if ( function_exists( 'add_image_size' ) ) { 
	add_image_size( 'sixteen-nine-large', 850, 478, true ); 
	add_image_size( 'sixteen-nine-medium', 700, 394, true ); //(cropped)
}

// Add custom post types
function create_post_type() {
	register_post_type( 'news',
		array(
			'labels' => array(
				'name' => __( 'News' ),
				'singular_name' => __( 'news' )
			),
			'public' => true,
			'taxonomies' => array('post_tag','category'),
			'has_archive' => true,
			'rewrite' => array('slug' => 'news', 'with_front' => FALSE),
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array('title', 'thumbnail')
		)
	);
	register_post_type( 'events',
		array(
			'labels' => array(
				'name' => __( 'Events' ),
				'singular_name' => __( 'event' )
			),
			'public' => true,
			'taxonomies' => array('post_tag','category'),
			'has_archive' => true,
			'rewrite' => array('slug' => 'events', 'with_front' => FALSE),
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array('title', 'thumbnail')
		)
	);
        register_post_type( 'products',
                array(
                        'labels' => array(
                                'name' => __( 'Products' ),
                                'singular_name' => __( 'product' )
                        ),
                        'public' => true,
			'taxonomies' => array('post_tag','category'),
                        'has_archive' => true,
                        'rewrite' => array('slug' => 'products', 'with_front' => FALSE),
                        'capability_type' => 'post',
                        'hierarchical' => false,
                        'supports' => array('title', 'thumbnail', 'custom-fields')
                )
        );
           register_post_type( 'featured-products',
                array(
                        'labels' => array(
                                'name' => __( 'Featured Products' ),
                                'singular_name' => __( 'featured_product' )
                        ),
                        'public' => true,
                        'taxonomies' => array('post_tag','category'),
                        'has_archive' => true,
                        'capability_type' => 'post',
                        'hierarchical' => false,
                        'supports' => array('title','editor','custom-fields','thumbnail')
                )
        );
        register_post_type( 'faqs',
                array(
                        'labels' => array(
                                'name' => __( 'FAQ\'s' ),
                                'singular_name' => __( 'FAQ' )
                        ),
			'show_in_menu' => 'edit.php?post_type=products',
                        'public' => false,
			'show_ui' => true,
                        'taxonomies' => array('post_tag'),
                        'has_archive' => true,
                        'capability_type' => 'post',
                        'hierarchical' => false,
                        'supports' => array('title')
                )
        );
        register_post_type( 'pathologies',
                array(
                        'labels' => array(
                                'name' => __( 'Pathologies' ),
                                'singular_name' => __( 'Pathology' ),
				'add_new' => __( 'Add new module' )
                        ),
                        'show_in_menu' => 'edit.php?post_type=products',
                        'public' => false,
			'show_ui' => true,
                        'taxonomies' => array('post_tag'),
                        'has_archive' => true,
                        'capability_type' => 'post',
                        'hierarchical' => false,
                        'supports' => array('title')
                )
        );
}
add_action( 'init', 'create_post_type' );

if ( function_exists('register_sidebar') ) {

   register_sidebar('heartworksNav');
   register_sidebar('upcoming-events');
   register_sidebar('carousel');
   register_sidebar('products');
   register_sidebar('quicklinks');
   register_sidebar('news');
   register_sidebar('past-events');
   register_sidebar('facebook');
   register_sidebar('gateway');
}

function change_default_title( $title ){
     $screen = get_current_screen();
 
     if  ( 'faqs' == $screen->post_type ) {
          $title = 'Type question here...';
     }
 
     return $title;
}
 
add_filter( 'enter_title_here', 'change_default_title' );
// Format dates to a range
function date_range(){
global $post;

	$s_date = get_post_meta($post->ID, "event_start_date", true);
	$e_date = get_post_meta($post->ID, "event_end_date", true);
        $sdate = new DateTime($s_date);
        $s_date = date_format($sdate, 'jS F Y');
        $edate = new DateTime($e_date);
        $e_date = date_format($edate, 'jS F Y');

        list($s_day, $s_month, $s_year) = sscanf($s_date, "%s %s %d");;
        list($e_day, $e_month, $e_year) = sscanf($e_date, "%s %s %d");;

        if($s_year == $e_year){
                // same year, either 1st or 2nd form
                if($s_month == $e_month){
                        // same year, same month, 1st form - DAY. - DAY. MONTH YEAR
                        return "$s_day - $e_day $e_month $e_year";
                } else {
                        // same year, different month, 2nd form - DAY. MONTH - DAY. MONTH YEAR
                        return "$s_day $s_month - $e_day $e_month $e_year";
                }
        } else {
                // different year - 3rd form - DAY. MONTH YEAR - DAY. MONTH YEAR
                return "$s_day # $s_month # $s_year - $e_day # $e_month # $e_year <br> $s_date";
        }
}

function insert_videos($ids) {
  $return = "      <div id=\"modal-wrapper\">\n";
  foreach($ids as $id) {
    $return .= "        <div id=\"mov-modal-$id\" class='movie-single'>".do_shortcode("[flplaylist id=\"$id\"]")."</div>\n";
  }
  $return .= "      </div>\n";
  return $return;
}

function insert_thumbs($ids) {
  foreach($ids as $id) {
    $cp = get_post_custom($id);
    $x = $cp['thumbnail'];
    $desc = $cp['description'];
    $thumb = wp_get_attachment_image_src( $x[0], 'thumbnail' );
    $url = $thumb[0];
    $return .= "        <div class=\"product-vid-wrapper\">
          <div class=\"flow-single product-video-thumb\">
          <div class=\"video-play-btn open-mov-modal-$id\">
          </div>
          <img src=\"$url\" class=\"open-mov-modal-$id\" />
        </div>
        <p>$desc[0]</p>
        </div>
        <script>
          jQuery('#mov-modal-$id').easyModal({
            overlay : 0.4,
            overlayClose: false
          });
          jQuery('.open-mov-modal-$id').on(\"click\", function(e){
            $('#mov-modal-$id').trigger('openModal');
            e.preventDefault();
          });
        </script>\n";
    }
  return $return;
}

function insert_thumbs_endorsements($ids) {
  foreach($ids as $id) {
    $cp = get_post_custom($id);
    $x = $cp['thumbnail'];
    $desc = $cp['description'];
    $thumb = wp_get_attachment_image_src( $x[0], 'thumbnail' );
    $url = $thumb[0];
    $return .= "        <div class=\"endorsement-vid-wrapper\">
          <div class=\"flow-single endorsement-video-thumb\">
          <div class=\"video-play-btn open-mov-modal-$id\">
          </div>
          <img src=\"$url\" class=\"open-mov-modal-$id\" />
        </div>
        <p>$desc[0]</p>
        </div>
        <script>
          jQuery('#mov-modal-$id').easyModal({
            overlay : 0.4,
            overlayClose: false
          });
          jQuery('.open-mov-modal-$id').on(\"click\", function(e){
            $('#mov-modal-$id').trigger('openModal');
            e.preventDefault();
          });
        </script>\n";
    }
  return $return;
}
