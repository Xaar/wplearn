<?php
/*
Template Name: Heartworks
*/
?>

<?php
define("THISPAGE", "heartworks");
?>

<?php get_header(); ?>

<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			 <?php the_post(); ?>	
			<?php the_content(); ?>

				</div><!-- #content -->
	</div><!-- #primary -->


<?php get_footer(); ?>