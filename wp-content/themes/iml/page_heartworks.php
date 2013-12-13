<?php
/*
Template Name: Heartworks
*/
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
    next: { button: ".next", key: "right" },
    swipe: { onTouch: true , onMouse: true}
  });
});
</script>

<?php get_sidebar('navigation');?>

<div id="content" class="hero-content row" role="main">
  <ul class="heartworks-products">
  <?php
// The Query
//$the_query_prod_carousel = new WP_Query( 'post_type=products&posts_per_page=10' );
$the_query_prod_carousel = new WP_Query( array ( 'post_type' => 'products', 'meta_key' => 'product_type', 'meta_compare' => '!=', 'meta_value' => 'Industry', 'posts_per_page' => '10'));
// The Loop
while ( $the_query_prod_carousel->have_posts() ) : $the_query_prod_carousel->the_post();
        $image_id = get_post_meta($post->ID, ('product_thumbnail'), true);
        $url = wp_get_attachment_url($image_id);
?>
    <li><a href="<?php the_permalink();?>"/><img src="<?php echo $url ?>" /><?php the_title(); ?></a></li>
<?php
endwhile;

// Reset Post Data
wp_reset_postdata();
?>
</ul>
</div><!-- hero-content -->

<?php get_sidebar('gateway'); ?>

<script type="text/javascript" src="<?=get_template_directory_uri();?>/js/easyModal.js"></script>

<?=insert_videos(array('961', '961', '961', '961'));?>

<div class="hw-endorsements-wrapper">
  <div class="hw-endorsements row">
    <h4>SEE ENDORSEMENTS OF HEARTWORKS PRODUCTS FROM LEADING PROFESSIONALS</h4>
    <!-- ENDORSEMENTS GALLERY ADD HERE -->
    <div class="vid-thumb-wrapper">
<?=insert_thumbs_endorsements(array('961', '961', '961', '961'));?>
    </div> <!-- div thumb wrapper -->
  </div> <!-- promo-band -->
</div> <!-- promo-band-wrapper -->


<?php get_footer(); ?>
