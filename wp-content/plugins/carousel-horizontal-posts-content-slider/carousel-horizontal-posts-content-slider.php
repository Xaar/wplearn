<?php /*
Plugin Name: Carousel Horizontal Posts Content Slider
Plugin URI: http://www.backraw.com/plugins/carousel-horizontal-posts-content-slider.zip
Description: This is  horizontal posts content slider designed using carousel jquery library.
Version: 1.0
Author: subhansanjaya
Author URI: http://www.backraw.com
License:  GPL2*/
global $wpdb, $wp_version;
//add shortcode
add_shortcode( 'carousel-horizontal-posts-content-slider', 'Carousel_shortcode' );
//page function
function TCHPCSCarousel()
{
	echo Carousel_shortcode();
}
function Carousel_shortcode()
{
	global $wpdb;
	$displayimage = get_option('tchpcs_displayimage');
	$word_imit = get_option('tchpcs_word_limit');
	$qp_showposts = get_option('tchpcs_query_posts_showposts');
	$qp_orderby= get_option('tchpcs_query_posts_orderby');
	$qp_order= get_option('tchpcs_query_posts_order');
	$qp_category= get_option('tchpcs_query_posts_category');

	//Image slider
	global $post;

	$slider_gallery.= '<div class="image_carousel">';
	$slider_gallery.='<div id="foo1">';

	$args = array( 'numberposts' => $qp_showposts,  'category' => $qp_category, 'order'=> $qp_order, 'orderby' => $qp_orderby );
	$myposts = get_posts( $args );
	foreach( $myposts as $post ){

		$post_title = $post->post_title;
		$post_link =  get_permalink($post->ID);
		$post_content = $post->post_content;
		$displaydesc= $word_imit;

		$slider_gallery.= '<div id="foo_content">';

		if($displayimage=="YES"){
		
		
			 if (has_post_thumbnail( $post->ID ) ): 
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); 
			endif; 
			

			$featured_img = "<img width='115' height='115' src='". $image[0] . "' " . $attributes . " />";
			

			
			$slider_gallery.= '<a href="'.$post_link.'">'.$featured_img.'</a>';
		}
		
		
		$slider_gallery.= '<br/><h2>'.$post_title.'</h2><br/>';
		$slider_gallery.= '<p><span class="foo_con">'.tchpcs_clean($post_content, $displaydesc).'</span></p>...';

		$slider_gallery.= '<br/><span class="more"><a href="'.$post_link.'">read more</a></span>';
		$slider_gallery.= '</div>';

	}


	$slider_gallery.='</div>';
	$slider_gallery.='<div class="clearfix"></div>';
	$slider_gallery.='<a class="prev" id="foo1_prev" href="#"><span>prev</span></a>';
	$slider_gallery.='<a class="next" id="foo1_next" href="#"><span>next</span></a>';
	$slider_gallery.='</div>';


	return $slider_gallery;

}

//add js and css files
function Carousel_add_javascript_files()
{
	if (!is_admin())
	{
		
		wp_register_style('css_file', plugins_url('/css/custom-style.css',__FILE__ ));
		wp_enqueue_style('css_file');

		wp_enqueue_script('jquery');

		wp_register_script( 'tiny_js', plugins_url('/inc/jquery.carouFredSel-6.1.0.js',__FILE__ ));
		wp_enqueue_script('tiny_js');

		wp_register_script( 'custom_js', plugins_url('/inc/custom.js',__FILE__ ));
		wp_enqueue_script('custom_js');

	}
}

add_action('init', 'Carousel_add_javascript_files');

//Post image attachment (sizes: thumbnail, medium, full)

function attachment_image_filter($postid=0, $size='thumbnail', $attributes='') {
	if ($postid<1) $postid = get_the_ID();
	if ($images = get_children(array(
			'post_parent' => $postid,
			'post_type' => 'attachment',
			'numberposts' => 1,
			'post_mime_type' => 'image',)))
		foreach($images as $image)
		{
			$attachment=wp_get_attachment_image_src($image->ID, $size);

			//custermize css, foo_content size accordint to your image with and height, ex img width= 140 height=140, css file- foo_content{ width 160px }
				
			return "<img width='115' height='115' src='". $attachment[0] . "' " . $attributes . " />";
		}
}

//limit words
function tchpcs_clean($excerpt, $substr) {
	$string = $excerpt;
	$string = substr($string, 0, $substr);

	return $string;
}



function tchpcs_admin_options()
{
	include_once("slider-management.php");
}

function tchpcs_add_to_menu()
{
	add_options_page('Carousel Horizontal Posts Content Slider', 'Carousel Horizontal Posts Content Slider', 'manage_options', __FILE__, 'tchpcs_admin_options' );
}

if (is_admin())
{
	add_action('admin_menu', 'tchpcs_add_to_menu');
}

function tchpcs_deactivation()
{
}
register_activation_hook(__FILE__, 'tchpcs_install');
register_deactivation_hook(__FILE__, 'tchpcs_deactivation');

//installation default value
function tchpcs_install()
{
	add_option('tchpcs_displayimage', "YES");
	add_option('tchpcs_word_limit', "55");
	add_option('tchpcs_query_posts_showposts', "5");
	add_option('tchpcs_query_posts_orderby', "rand");
	add_option('tchpcs_query_posts_order', "DESC");
	add_option('tchpcs_query_posts_category', "1");
}
?>