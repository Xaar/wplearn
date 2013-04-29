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
/*	CarouFredSel: a circular, responsive jQuery carousel.
	Configuration created by the "Configuration Robot"
	at caroufredsel.dev7studios.com
*/

jQuery.noConflict();

jQuery(document).ready(function($) {

jQuery(".carousel-ul").carouFredSel({
	circular: true,
	infinite: true,
	width: "100%",
	height: 190,
	items: {
		visible: 5,
		width: 180,
		height: 175
	},
	scroll: {
		items: 1,
		 fx: "scroll",
		duration: 400
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

						  <li><a href ="<?php the_permalink(); ?>" ><img src="<?php echo $image_url ?>" width="166" height="150" /></a><a href ="<?php the_permalink(); ?>" ><h4><?php the_title(); ?></h4></a></li>

								 
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
	<div class="gateway-promo-wrapper row">
		<div class="gateway-promo">
			<h1>Discover <b>HeartWorks GateWay</b> - the most cost-effective way to purchase a HeartWorks Simulator</h1>
			<p>Get peace of mind with a 12 month or multi-year, fixed price software subscription and support service.</p>
	</div>

		<div class="gateway-promo-bullets">
			<ul class="bullets-left">
			 	<li>Pathology modules included.</li>
			 	<li>Version updates and anatomical updates included.</li>
			 	<li>Software support, maintenance and bug fix releases included.</li>
			 </ul>
			 <ul class="bullets-right">
			 	<li>Ensure your system remains up-to-date and supported.</li>
			 	<li>One annual purchase versus multiple purchases through the year.</li>
			 	<li>Significant cost savings versus separate purchases.</li>
			</ul>
			<div class="clearfix"></div>
		</div><!-- gateway-promo-bullets -->
	
		<button>Find out more about GateWay</button>
	</div><!-- gateway-promo-wrapper-->


			<div class="hw-endorsements-wrapper">

				<div class="hw-endorsements row">
					<h4>SEE ENDORSEMENTS OF HEARTWORKS PRODUCTS FROM LEADING PROFESSIONALS</h4>
					<!-- ENDORSEMENTS GALLERY ADD HERE -->

				</div> <!-- promo-band -->

			</div> <!-- promo-band-wrapper -->

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
				
			 


<?php get_footer(); ?>