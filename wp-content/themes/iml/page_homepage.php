<?php
/*
Template Name: IML Home
*/
?>

<?php
define("THISPAGE", "home");
?>

<?php get_header(); ?>

<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php the_post(); ?>	
			<?php the_content(); ?>

				</div><!-- #content -->
	</div><!-- #primary -->


<?php get_footer(); ?>