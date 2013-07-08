<?php
/*
Template Name: Heartworks
*/
define("THISPAGE", "heartworks");

get_header();
?>
<script type="text/javascript">
/* CarouFredSel: a circular, responsive jQuery carousel.
Configuration created by the "Configuration Robot"
at caroufredsel.dev7studios.com
*/
$(window).load(function() {
  $(".carousel-ul").carouFredSel({
    circular: true, infinite: true, width: "100%", height: 200, 
    items: { visible: "variable", width: 180, height: 200 }, 
    scroll: { items: 1, fx: "scroll", duration: "auto" },
    auto: false,
    prev: { button: ".previous", key: "left" },
    next: { button: ".next", key: "right" }
  });
});
</script>

<?php get_sidebar('navigation');?>

<div id="content" class="hero-content row" role="main">
<?php the_post(); ?>	
<?php the_content(); ?>
<?php echo get_new_royalslider(2); ?>
</div><!-- hero-content -->

<?php get_sidebar('products'); ?>

<div class="gateway-promo-wrapper row">
  <div class="gateway-promo">
    <h1>Discover <b>HeartWorks GateWay</b> - the most cost-effective way to purchase a HeartWorks Simulator</h1>
    <p>Get peace of mind with a 12 month or multi-year, fixed price software subscription and support service.</p>
  </div>

  <div class="gateway-promo-bullets">
    <ul class="bullets-left">
      <li>Pathology modules included.</li>
      <li>Version updates and anatomical updates included.</li>
      <li>Software support, maintenance and bug fix releases included.</li>
    </ul>
    <ul class="bullets-right">
      <li>Ensure your system remains up-to-date and supported.</li>
      <li>One annual purchase versus multiple purchases through the year.</li>
      <li>Significant cost savings versus separate purchases.</li>
    </ul>
    <div class="clearfix"></div>
  </div><!-- gateway-promo-bullets -->
	
  <div class="cta-gateway"><a href="">Find out more about GateWay</a></div>
</div><!-- gateway-promo-wrapper-->

<script type="text/javascript" src="<?=get_template_directory_uri();?>/js/easyModal.js"></script>

<?=insert_videos(array('909', '898'));?>

<div class="hw-endorsements-wrapper">
  <div class="hw-endorsements row">
    <h4>SEE ENDORSEMENTS OF HEARTWORKS PRODUCTS FROM LEADING PROFESSIONALS</h4>
    <!-- ENDORSEMENTS GALLERY ADD HERE -->
<?=insert_thumbs(array('909', '898'));?>
  </div> <!-- promo-band -->
</div> <!-- promo-band-wrapper -->

<?php get_sidebar('quicklinks'); ?> 

<?php get_footer(); ?>
