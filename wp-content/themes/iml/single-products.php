<?php
/*
Template Name: News-Events
*/
?>

<?php
define("THISPAGE", "products");

get_header();
get_sidebar();
?>
<div class="page-wrapper site-content">
	<div class="page-title row">
		<h1><?php the_title(); ?></h1>
	</div>

        <div id='product' class="product-leftcol-wrapper alignleft">

                <div class="hero-product-content row">
                        <div><?php the_post_thumbnail('sixteen-nine-large'); ?></div>
                        <div class="hero-product-listing-text">
                                <p>subtitle = <?=get_post_meta($post->ID, ('subtitle'), true); ?></p>
                                <p>product_type = <?=get_post_meta($post->ID, ('product_type'), true); ?></p>
                                <p>product_thumbnail = <?=get_post_meta($post->ID, ('product_thumbnail'), true); ?></p>
                                <p>quote = <?=get_post_meta($post->ID, ('quote'), true); ?></p>
                                <p>how_to_buy = <a href="<?=get_permalink(get_post_meta($post->ID, ('how_to_buy'), true)); ?>">link</a></p>
                                <p>description (content) = <?=get_post_meta($post->ID, ('content'), true); ?></p>
                                <p>overview_image = <?=get_post_meta($post->ID, ('overview_image'), true); ?></p>
                                <p>frequently_asked_questions = <?=get_post_meta($post->ID, ('frequently_asked_questions'), true); ?></p>
                                <p>ask_a_question = <a href="<?=get_permalink(get_post_meta($post->ID, ('ask_a_question'), true)); ?>">link</a></p>
                                <p>pathologies = <?=get_post_meta($post->ID, ('pathologies'), true); ?></p>
                        </div>
                </div>




</div> <!-- page-wrapper -->

<?php get_footer(); ?>
