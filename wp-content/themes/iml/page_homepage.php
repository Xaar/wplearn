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
						<h4>products</h4>
						<?php echo get_new_royalslider(3); ?>
					</div>
					<div class="promo simulator-home col">
						<h4>news</h4>
						<?php echo get_new_royalslider(3); ?>
					</div>
					<div class="promo news-home col">
						<h4>events</h4>
						<?php echo get_new_royalslider(7); ?>
					</div>
				</div> <!-- promo-band -->

			</div> <!-- promo-band-wrapper -->


					<div id="featured-product-home" class="featured-product row">
						<?php
						// The Query
						$the_query_home2 = new WP_Query( 'post_type=featured-products&posts_per_page=1' );
						// The Loop
						while ( $the_query_home2->have_posts() ) : $the_query_home2->the_post();
						?>
						
								 <div class="heroTxtContent featured-prod-txt col">

								 	<?php $featuredprod = get_field('product_page_link'); ?>

								 <h3>Featured Product</h3>	
								 <h2><?php the_title(); ?></h2>
								 <?php the_content(); ?>
								 <div class="cta-green">
								 	<a href= "<?php echo get_permalink( $featuredprod->ID ); ?>">Learn more &raquo; </a> 
								 </div>
								<div class="cta-green">
								 	<a href= "<?php echo get_permalink( $featuredprod->ID ); ?>">Learn more &raquo; </a> 
								 </div>
								</div>
							
								 <?php if ( has_post_thumbnail()) : ?>
						 <div class="featured-prod-img">
						   <a href="<?php echo get_permalink( $featuredprod->ID ); ?>" title="<?php the_title_attribute(); ?>" >
						   <?php the_post_thumbnail(); ?>
						   </a></div>
						 <?php endif; ?>
						<?php
						endwhile;

						// Reset Post Data
						wp_reset_postdata();

						?>

					</div> <!-- #featured-product-home -->

<?php get_sidebar('quicklinks'); ?>

<?php get_footer(); ?>
