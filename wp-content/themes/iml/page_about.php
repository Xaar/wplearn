<?php
/*
Template Name: About
*/
?>

<?php get_header(); ?>

<div id="content" class="hero-content row clear-nav" role="main">
  	<div class="page-title row">

		<h1>About Inventive Medical</h1>
		
	</div>

	<div class="about-leftcol-wrapper">
		<h2 class="heading-leftcol">The IML Story</h2>
		<?php if ( has_post_thumbnail() ) { ?>
    <div class="about_image"><?php the_post_thumbnail('sixteen-nine-large'); ?></div>
<?php } ?>
		<h1 class="about-section-title">Inventive Medical Ltd</h1>

 <?php if (have_posts()) : while (have_posts()) : the_post();

the_content();

endwhile; endif; ?>
	<div class="clearfix"></div>
</div>

	<div class="sidebar-wrapper">
<?php

  get_sidebar('upcoming-events');
  get_sidebar('news');


?>
</div> <!-- sidebar wrapper -->
</div>

<?php get_footer(); ?>
