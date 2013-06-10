<?php
/*
Template Name: News-Events
*/
?>

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
			<h3><?=(get_post_meta($post->ID, ('product_type'), true)=='Simulator') ? 'Heartworks Simulator' : 'x'; ?></h3>
			<h2><?=get_post_meta($post->ID, ('subtitle'), true); ?></h2>
			<p><?=get_post_meta($post->ID, ('product_description'), true); ?></p>
			<img class='alignRight' src="<?=wp_get_attachment_url(get_post_meta($post->ID, ('product_image'), true));?>"/>
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
			<li><a href="#">FAQ</a></li>
			<li><a class='xl' href="<?=get_permalink(get_post_meta($post->ID, ('how_to_buy'), true)); ?>">How to Buy</a></li>
		</ul>

		<!-- tab "panes" -->
		<div class="panes">
			<div>
				<h2><?=get_post_meta($post->ID, ('product_type'), true); ?></h2>
				<p><?=get_post_meta($post->ID, ('product_overview'), true); ?></p>
				<p>ask_a_question = <a href="<?=get_permalink(get_post_meta($post->ID, ('ask_a_question'), true)); ?>">link</a></p>
                               	<img src="<?=wp_get_attachment_url(get_post_meta($post->ID, ('overview_image'), true));?>"/>
			</div>
			<div>
<?php
$questions = get_post_meta($post->ID, ('frequently_asked_questions'), true);
foreach($questions as $var => $val) {
	echo "<strong>". get_the_title($val) . "</strong> " . get_post_meta($val, 'answer', true) . "<br>";
}?>
			</div>
			<div>
				<p>pathologies = <?=get_post_meta($post->ID, ('pathologies'), true); ?></p>
			</div>
		</div>
 	</div>



	<div id="products" class="product-carousel row">
		<div class="previous">
		</div>
		<div class="next">
		</div>
		<ul class="carousel-ul">
<?php
// The Query
$the_query_prod_carousel = new WP_Query( 'post_type=products&posts_per_page=10' );
// The Loop
while ( $the_query_prod_carousel->have_posts() ) : $the_query_prod_carousel->the_post();
	$image_id = get_post_meta($post->ID, ('product_thumbnail'), true);
	$url = wp_get_attachment_thumb_url($image_id);
?>
<li><a href="<?php the_permalink();?>"/><img src="<?php echo $url ?>" width="180" height="200" /><?php the_title(); ?></a></li>
<?php
endwhile;

// Reset Post Data
wp_reset_postdata();
?>
		</ul>
		<div class="clearfix"></div>
		<div class="previous"></div>
                <div class="next"></div>
		<div class="prod-carousel-title"><h4>Explore Heartworks Products</h4></div>
	</div>

</div><!-- page-wrapper -->




	<div class="quick-links-wrapper">
		<div class="quick-links row">
			<h4>Quick Links</h4>
			<hr />
		</div>

		<div class="quick-links row">
			<div class="quick-link col">
				<img src="<?php bloginfo('template_directory'); ?>/images/link-pathology.png">
				<h2><a href="">New Pathology Modules</a></h2>
				<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.</p>
				<button>Learn more &raquo;</button>
			</div>
			<div class="quick-link col">
				<img src="<?php bloginfo('template_directory'); ?>/images/link-gateway.png">
				<h2><a href="">HeartWorks GateWay</a></h2>
				<p>Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage.</p>
				<button>Learn more &raquo;</button>
			</div>
			<div class="quick-link col">
				<img src="<?php bloginfo('template_directory'); ?>/images/link-sales.png">
				<h2><a href="">Sales &amp; Support</a></h2>
				<p>Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC.</p>
				<button>Learn more &raquo;</button>
			</div>
		</div> <!-- .quick-links row -->
	</div><!--  .quick-links-wrapper -->
</div>

<?php get_footer(); ?>
