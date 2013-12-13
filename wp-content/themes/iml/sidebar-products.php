<script type="text/javascript">
/* CarouFredSel: a circular, responsive jQuery carousel.
Configuration created by the "Configuration Robot"
at caroufredsel.dev7studios.com
*/
$(window).load(function() {
  $(".carousel-ul").carouFredSel({
    circular: false, infinite: false, width: "100%", height: 200,
    items: { visible: "variable", width: 180, height: 200 },
    scroll: { items: 1, fx: "scroll", duration: "auto" },
    auto: false,
    prev: { button: ".previous", key: "left" },
    next: { button: ".next", key: "right" },
    swipe: { onTouch: true , onMouse: true}
  });
});
</script>
<div id="products" class="product-carousel row">
  <div class="previous"></div>
  <div class="next"></div>
  <ul class="carousel-ul">
<?php
// The Query
//$the_query_prod_carousel = new WP_Query( 'post_type=products&posts_per_page=10' );
$the_query_prod_carousel = new WP_Query( array ( 'post_type' => 'products', 'meta_key' => 'product_type', 'meta_compare' => '!=', 'meta_value' => 'Industry', 'posts_per_page' => '10'));
// The Loop
while ( $the_query_prod_carousel->have_posts() ) : $the_query_prod_carousel->the_post();
        $image_id = get_post_meta($post->ID, ('product_thumbnail'), true);
        $url = wp_get_attachment_url($image_id);
?>
    <li><a href="<?php the_permalink();?>"/><img src="<?php echo $url ?>" width="180" height="200" /><?php the_title(); ?></a></li>
<?php
endwhile;

// Reset Post Data
wp_reset_postdata();
?>
  </ul>
  <div class="clearfix"></div>
  <div class="previous"></div>
  <div class="next"></div>
  <div class="prod-carousel-title"><h4>Explore Heartworks Products</h4></div>
</div>

<div class="clearfix"></div>
