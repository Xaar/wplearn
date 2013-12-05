<?php
/*
Template Name: IML Home
*/
?>

<?php get_header(); ?>



		<div id="content" class="hero-content row clear-nav" role="main">
			

			<?php if (have_posts()) : while (have_posts()) : the_post();

the_content();

endwhile; endif; ?>

		</div><!-- hero-content -->
	



				


<?php get_footer(); ?>
