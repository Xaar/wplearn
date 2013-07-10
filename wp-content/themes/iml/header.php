<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package IML
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="initial-scale=1; maximum-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400italic,300,300italic,600,600italic,700,700italic' rel='stylesheet' type='text/css'>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />


<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> class="gradient">

<?php do_action( 'before' ); ?>
  <header id="masthead" class="site-header" role="banner">

    <div id="header_inner_wrapper">
      <div class="logo">
        <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php bloginfo('template_directory'); ?>/images/inventive-medical-logo.png" title="Inventive Medical Ltd." alt="" width="155px" height="28px" /></a></h1>
      </div>

      <nav id="site-navigation" class="navigation-main" role="navigation">
        <h1 class="menu-toggle"><?php _e( 'Menu', 'IML' ); ?></h1>
        <div class="screen-reader-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'IML' ); ?>"><?php _e( 'Skip to content', 'IML' ); ?></a></div>
        <?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
      </nav><!-- #site-navigation -->

      <form name="navsearchform" action="/">
        <input type="text" name="s" value="Search this site" />
        <input type="image" name="navsearchbtn" src="<?php bloginfo('template_directory'); ?>/images/search-btn.png" />
      </form>
    </div> <!-- header_inner_wrapper -->
    <hr />
  </header><!-- #masthead -->
