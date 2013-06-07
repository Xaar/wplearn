<?php
/*
Template Name: IML Home
*/
?>

<?php
define("THISPAGE", "home");
?>

<?php get_header(); ?>


		<div id="content" class="hero-content row" role="main">
			

			<?php if (have_posts()) : while (have_posts()) : the_post();

the_content();

endwhile; endif; ?>

		</div><!-- hero-content -->
	
			<div class="promo-band-wrapper">

				<div class="promo-band row">
					<div class="promo elearn-home col">
						<h4>e-learning</h4>
						<?php echo get_new_royalslider(3); ?>
					</div>
					<div class="promo simulator-home col">
						<h4>simulators</h4>
						<?php echo get_new_royalslider(3); ?>
					</div>
					<div class="promo news-home col">
						<h4>news &amp; events</h4>
						<?php echo get_new_royalslider(3); ?>
					</div>
				</div> <!-- promo-band -->

			</div> <!-- promo-band-wrapper -->


					<div id="featured-product-home" class="featured-product row">
						<?php
						// The Query
						$the_query_home2 = new WP_Query( 'category_name=featured_product&posts_per_page=1' );
						// The Loop
						while ( $the_query_home2->have_posts() ) : $the_query_home2->the_post();
						?>
						
								 <div class="heroTxtContent featured-prod-txt col">
								 <h3>Featured Product</h3>	
								 <h2><?php the_title(); ?></h2>
								 <?php the_excerpt(); ?>
								 <?php the_content(); ?>
								</div>
								 <?php if ( has_post_thumbnail()) : ?>
						 <div class="featured-prod-img">
						   <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
						   <?php the_post_thumbnail(); ?>
						   </a></div>
						 <?php endif; ?>
						<?php
						endwhile;

						// Reset Post Data
						wp_reset_postdata();

						?>

					</div> <!-- #featured-product-home -->

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