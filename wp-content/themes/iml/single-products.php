<?php

define("THISPAGE", "products");

get_header();
get_sidebar();
?>
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

$(function() {
    // setup ul.tabs to work as tabs for each div directly under div.panes
    $("ul.tabs").tabs("div.panes > div");
});
</script>

<div class="page-wrapper site-content">
	<div class="page-title row">
		<h1><?php the_title(); ?></h1>
	</div>

	<div class="hero-product-content row">
		<div class="hero-product-listing-text">
			<h3><?=(get_post_meta($post->ID, ('product_type'), true)=='Simulator') ? 'Heartworks Simulator' : get_post_meta($post->ID, ('product_type'), true); ?></h3>
			<h2><?=get_post_meta($post->ID, ('subtitle'), true); ?></h2>
			<p><?=get_post_meta($post->ID, ('product_description'), true); ?>
				<img class='right' src="<?=wp_get_attachment_url(get_post_meta($post->ID, ('product_image'), true));?>"/>
			</p>
		</div>
	</div>


        <div class="hw-endorsements-wrapper">
                <div class="hw-endorsements row">
			<h3><?=get_post_meta($post->ID, ('quote'), true); ?></h3>
                </div> <!-- promo-band -->
        </div> <!-- promo-band-wrapper -->


	<div class="product-quote-listing">
	</div>

	<div class="product-overview">
		<ul class="tabs">
			<li><a href="#">Product Overview</a></li>
<?php
// Check if patholgies, and loop through each module if so
if(get_post_meta($post->ID, ('product_type'), true)=='Pathologies') {
	$pathologies = get_post_meta($post->ID, ('pathologies'), true);
	foreach($pathologies as $module) {?>
			<li><a href="#"><?=get_the_title($module);?></a></li>
<?php	}
}		
?>
			<li><a href="#">FAQ</a></li>
			<button class='right'>How to Buy</button>
		</ul>

		<!-- tab "panes" -->
		<div class="panes">
			<div>
				<p>
       
<!--                        		<img class="right" src="<?=wp_get_attachment_url(get_post_meta($post->ID, ('overview_image'), true));?>"/>
-->
<?php

$custom_fields = get_post_custom($post->ID);
$x =  $custom_fields['test'];
$gallery = maybe_unserialize($x[0]);
$thumb = wp_get_attachment_image_src( $gallery[0], 'medium' );
foreach($gallery as $id) {
$x = wp_get_attachment_image_src( $id, 'large' );
$url[] = $x[0];
}

foreach($url as $src) {
  $i++;
  $visible = ($i=='1') ? " " : "style='display:none' ";
  echo "<a href='$src' class='right' rel='lightbox[test]' $visible><img src='$thumb[0]'/></a><br>";
}

?>

<div class="clearfix"></div>


					<strong><?=get_post_meta($post->ID, ('product_type'), true); ?></strong>
					<?=get_post_meta($post->ID, ('product_overview'), true); ?>
					<br>
					<a href="<?=get_permalink(get_post_meta($post->ID, ('ask_a_question'), true)); ?>">Ask a Question</a>
				</p>
			</div>
<?php
// Check if patholgies, and loop through each module if so
if(get_post_meta($post->ID, ('product_type'), true)=='Pathologies') {
        $pathologies = get_post_meta($post->ID, ('pathologies'), true);
        foreach($pathologies as $module) {?>
                        <div>
				<h2><?=get_the_title($module);?></h2>
				<p><?=get_post_meta($post->ID, ('content'), true); ?></p>				
			</div>
<?php   }
}
?>
                        <div>
<?php
$questions = get_post_meta($post->ID, ('frequently_asked_questions'), true);
foreach($questions as $var => $val) {
        echo "<strong>". get_the_title($val) . "</strong> " . get_post_meta($val, 'answer', true) . "<br>";
}?>
                        </div>
		</div>
 	</div>

<?php get_sidebar('products'); ?>

</div><!-- page-wrapper -->

<?php get_sidebar('quicklinks'); ?>

</div>

<?php get_footer(); ?>
