<?php
/*
Template Name: IML Home
*/
?>

<?php
define("THISPAGE", "home");
?>

<?php get_header(); ?>


		<div id="content" class="hero-content" role="main">
			
			<?php the_post(); ?>	
			<?php the_content(); ?>

		</div><!-- hero-content -->

			<div class="promo-band-wrapper">

				<div class="promo-band row">
					<div class="promo col">
						<?php echo get_new_royalslider(3); ?>
					</div>
					<div class="promo col">
						<?php echo get_new_royalslider(3); ?>
					</div>
					<div class="promo col">
						<?php echo get_new_royalslider(3); ?>
					</div>

				</div> <!-- promo-band -->

			</div> <!-- promo-band-wrapper -->

			



<?php get_footer(); ?>