<?php
/*
Template Name: Heartworks
*/
?>

<?php
define("THISPAGE", "heartworks");
?>
<?php get_header(); ?>
<script type="text/javascript">
/* CarouFredSel: a circular, responsive jQuery carousel.
Configuration created by the "Configuration Robot"
at caroufredsel.dev7studios.com
*/

$(window).load(function() {

$(".carousel-ul").carouFredSel({
circular: true,
infinite: true,
width: "100%",
height: 200,
items: {
visible: 4,
width: 180,
height: 200
},
scroll: {
items: 1,
fx: "scroll",
duration: "auto"
},
auto: false,
prev: {
button: ".previous",
key: "left"
},
next: {
button: ".next",
key: "right"
}
});
});

</script>


<div id="content" class="hero-content row" role="main">
<?php the_post(); ?>	
<?php the_content(); ?>
<?php echo get_new_royalslider(2); ?>

</div><!-- hero-content -->



<div id="products" class="product-carousel row">
<div class="previous">
</div>
<div class="next">
</div>
<ul class="carousel-ul">
<?php
// The Query
$the_query_prod_carousel = new WP_Query( 'category_name=product&posts_per_page=10' );
// The Loop
while ( $the_query_prod_carousel->have_posts() ) : $the_query_prod_carousel->the_post();
?>
<?php
$image_id = get_post_thumbnail_id();
$image_url = wp_get_attachment_image_src($image_id);
$image_url = $image_url[0];
?>
<?php

$url = wp_get_attachment_thumb_url();
?>

<li><img src="<?php echo $url ?>" width="180" height="200" /><?php the_title(); ?></li>

<?php
endwhile;

// Reset Post Data
wp_reset_postdata();

?>
</ul>
<div class="clearfix"></div>
</div>



<div class="promo-band-wrapper">

<div class="promo-band row">
<!-- ENDORSEMENTS GALLERY ADD HERE -->

</div> <!-- promo-band -->

</div> <!-- promo-band-wrapper -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
