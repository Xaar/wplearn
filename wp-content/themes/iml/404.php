<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package IML
 */

get_header(); ?>

  <div id="content" class="hero-content row clear-nav" role="main">
    <div class="page-title row">
      <h1>Sorry, something went wrong</h1>
      <p>The requested page could not be found, please try again or <a href="<?php bloginfo('url'); ?>">click here to return the the homepage</a></p>
    </div>
  </div><!-- #content -->
  <?php get_sidebar('quicklinks'); ?>
<?php get_footer(); ?>
