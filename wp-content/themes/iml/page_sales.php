<?php
/*
Template Name: Sales
*/
?>

<?php
define("THISPAGE", "sales-support");
?>

<?php get_header(); ?>

<div id="content" class="hero-content row" role="main">
..
<h1><?php the_title(); ?></h1>
<h1><?=$post->post_content?></h1>
<h1><?php the_content(); ?></h1>
..
</div><!-- hero-content -->



<div id="products" class="product-carousel row">
[simplemaps]
[recent-posts]
<?=do_shortcode('[simplemap]');?>
<?=do_shortcode('[simplemaps]');?>
<?=do_shortcode('[freeworldcontinentmap]');?>
<div class="clearfix"></div>
</div>



<div class="promo-band-wrapper">

<div class="promo-band row">
<!-- ENDORSEMENTS GALLERY ADD HERE -->

</div> <!-- promo-band -->

</div> <!-- promo-band-wrapper -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

