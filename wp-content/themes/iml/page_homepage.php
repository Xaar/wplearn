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
					<div class="promo">
						<?php echo TCHPCSCarousel(); ?>
					</div>

				</div> <!-- promo-band -->

			</div> <!-- promo-band-wrapper -->

			



<?php get_footer(); ?>