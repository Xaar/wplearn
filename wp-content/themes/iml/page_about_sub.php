<?php
/*
Template Name: About_Sub_Page
*/
?>

<?php get_header(); ?>

<div id="content" class="hero-content row clear-nav" role="main">
	<div class="page-title row">
		<h1>About Inventive Medical</h1>
	</div>

		<h2 class="heading-leftcol"><?php the_title(); ?></h2>
    
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="about-sub">
			<?php the_content(); ?>
		</div>
<?php endwhile; endif; ?>

		<div class="clearfix"></div>

</div><!-- content -->

<?php get_footer(); ?>

