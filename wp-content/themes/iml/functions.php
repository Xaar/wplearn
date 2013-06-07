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
	wp_enqueue_script( 'jQuery');

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
	register_post_type( 'news-events',
		array(
			'labels' => array(
				'name' => __( 'News & Events' ),
				'singular_name' => __( 'news_event' )
			),
			'public' => true,
			'taxonomies' => array('post_tag'),
			'has_archive' => true,
			'rewrite' => array('slug' => 'news-events', 'with_front' => FALSE),
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array('title', 'thumbnail')
		)
	);
        register_post_type( 'products',
                array(
                        'labels' => array(
                                'name' => __( 'Products' ),
                                'singular_name' => __( 'products' )
                        ),
                        'public' => true,
                        'taxonomies' => array('post_tag'),
                        'has_archive' => true,
                        'rewrite' => array('slug' => 'products', 'with_front' => FALSE),
                        'capability_type' => 'post',
                        'hierarchical' => false,
                        'supports' => array('title', 'thumbnail', 'custom-fields')
                )
        );
        register_post_type( 'faqs',
                array(
                        'labels' => array(
                                'name' => __( 'FAQ\'s' ),
                                'singular_name' => __( 'FAQ' )
                        ),
                        'public' => true,
                        'taxonomies' => array('post_tag'),
                        'has_archive' => true,
                        'rewrite' => array('slug' => 'products', 'with_front' => FALSE),
                        'capability_type' => 'post',
                        'hierarchical' => false,
                        'supports' => array('title')
                )
        );
}
add_action( 'init', 'create_post_type' );

function change_default_title( $title ){
     $screen = get_current_screen();
 
     if  ( 'faqs' == $screen->post_type ) {
          $title = 'Type question here...';
     }
 
     return $title;
}
 
add_filter( 'enter_title_here', 'change_default_title' );

/*function replace_content($content) {
	global $post;
	$content = get_post_meta($post->ID, "article", true);
	return $content;
}
add_filter('the_content', 'replace_content');
function custom_excerpt_length( $length ) {
	return 18;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );*/

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
add_shortcode('daterange', 'date_range');

function pods_faq_pick_data($data, $name, $value, $options, $pod, $id){
if ($name == "pods_field_teachers") {
foreach ($data as $id => &$value) {
$p = pods('teacher', $id);
$name = $p->display('name');
$city = $p->display('profile.city.name');
$value = $name . ' - ' . $city;
}
}
return $data;
}

add_filter('pods_field_pick_data', 'pods_faq_pick_data', 1, 6);
