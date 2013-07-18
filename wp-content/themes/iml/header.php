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

    <div class="header_inner_wrapper">
      <div class="mobile-nav-icon">
        <a class="mobile-nav-toggle"><img src="<?php bloginfo('template_directory'); ?>/images/mobile-nav-icon.png" title="Inventive Medical Ltd." alt="" width="27px" height="19px" /></a>
      </div>
      <div class="logo">
        <h1 class="site-title" ><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php bloginfo('template_directory'); ?>/images/inventive-medical-logo.png" title="Inventive Medical Ltd." alt="" width="155px" height="28px" /></a></h1>
        <h1 class="mobile-site-title" style='display:none'><a href="#" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php bloginfo('template_directory'); ?>/images/inventive-medical-logo-mobile.png" title="Inventive Medical Ltd." alt="" width="61px" height="28px" /></a></h1>
      </div>

      <nav id="site-navigation" class="navigation-main" role="navigation">
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
  <script>
    $(document).ready(function () {
      if($(window).width()<=950) {
        $('.site-title').css('display', 'none');
        $('.mobile-site-title').css('display', 'inline');
        $('.mobile-nav-icon').css('display', 'inline');
        $('.header_inner_wrapper').addClass('mobile_header_inner_wrapper');
        $('.mobile_header_inner_wrapper').removeClass('header_inner_wrapper');

        $('.menu-mainnav-container').addClass('mobile-menu-mainnav-container');
        $('.mobile-menu-mainnav-container').removeClass('menu-mainnav-container');

        $('.site-header').addClass('mobile-site-header');
        $('.mobile-site-header').removeClass('site-header');

         $('.clear-nav').addClass('clear-nav-mobile');
        $('.clear-nav-mobile').removeClass('clear-nav');

        $('.hw-menu-collapsed').css('margin-top', '40px');

        $(".mobile-menu-mainnav-container").slideUp(1);
      }
    });

    $('.mobile-nav-toggle').on('click', (function(){
      $(".mobile-menu-mainnav-container").slideToggle(600);
    }));

    function mobile_view () {
      $('.mobile-nav-icon').css('display', 'inline');
      $('.header_inner_wrapper').addClass('mobile_header_inner_wrapper');
      $('.mobile_header_inner_wrapper').removeClass('header_inner_wrapper');
      $('.menu-mainnav-container').addClass('mobile-menu-mainnav-container');
      $('.mobile-menu-mainnav-container').removeClass('menu-mainnav-container');
      $('.site-header').addClass('mobile-site-header');
      $('.mobile-site-header').removeClass('site-header');
      $('.site-title').css('display', 'none');
      $('.mobile-site-title').css('display', 'inline');
      $('.clear-nav').addClass('clear-nav-mobile');
      $('.clear-nav-mobile').removeClass('clear-nav');
      $('.hw-menu-collapsed').css('margin-top', '40px');
    }

    function desktop_view () {
      $('.mobile-nav-icon').css('display', 'none');
      $('.mobile_header_inner_wrapper').addClass('header_inner_wrapper');
      $('.header_inner_wrapper').removeClass('mobile_header_inner_wrapper');
      $('.site-title').css('display', 'inline');
      $('.mobile-site-title').css('display', 'none');
      $('.mobile-menu-mainnav-container').slideDown(10);
      $('.mobile-menu-mainnav-container').addClass('menu-mainnav-container');
      $('.menu-mainnav-container').removeClass('mobile-menu-mainnav-container');
      $('.mobile-site-header').addClass('site-header');
      $('.site-header').removeClass('mobile-site-header');
      $('.clear-nav-mobile').addClass('clear-nav');
      $('.clear-nav').removeClass('clear-nav-mobile');
      $('.hw-menu-collapsed').css('margin-top', '96px');
    }

    function resizedw(){
      var width = $(this).width();
      if(width<=950){
        mobile_view();
      }else{
        desktop_view();
      }
      $('#map').empty();
      smp_contm(true);
    }

    var doit;

    $(window).resize(function () {
      clearTimeout(doit);
      doit = setTimeout(resizedw, 100);
      var width = $(this).width();
      
    });
</script>
