<?php

define("THISPAGE", "products");

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
    items: { visible: 4, width: 180, height: 200 },
    scroll: { items: 1, fx: "scroll", duration: "auto" },
    auto: false,
    prev: { button: ".previous", key: "left" },
    next: { button: ".next", key: "right" }
  });
});
$(function() {
    // setup ul.tabs to work as tabs for each div directly under div.panes
    $("ul.tabs").tabs("div.panes > div");
});
</script>

<?php 
$postid=$post->ID;
get_sidebar('navigation');
?>


  <div class="hero-product-content row">
    <div class="hero-product-listing-text col">
      <h3><?=(get_post_meta($postid, ('product_type'), true)=='Simulator') ? 'Heartworks Simulator' : get_post_meta($post->ID, ('product_type'), true); ?></h3>
      <h2><?=get_post_meta($postid, ('subtitle'), true); ?></h2>
      <p><?=get_post_meta($postid, ('product_description'), true); ?>
      </p>
      <div class="cta-green">
                  <a href= "<?php echo get_permalink( $featuredprod[0]->ID ); ?>">New test &raquo; </a> 
                 </div>
                <div class="cta-green">
                  <a href= "<?php echo get_permalink( $featuredprod[0]->ID ); ?>">Learn more &raquo; </a> 
                 </div>
    </div><!-- hero-product-listing-text -->
    <div class="hero-product-img">
     <img class='right hero-product-img' src="<?=wp_get_attachment_url(get_post_meta($postid, ('product_image'), true));?>"/>
   </div>
  </div><!-- hero-product-content row -->


  <script type="text/javascript" src="<?=get_template_directory_uri();?>/js/easyModal.js"></script>


<?php // Loop to place hidden videos
$custom_fields = get_post_custom($postid);
$x =  $custom_fields['video'];
$videos = maybe_unserialize($x[0]);
echo insert_videos($videos);
?>

  <div class="hw-endorsements-wrapper">
    <div class="hw-endorsements row">
      <div class = "product-quote col">
        <h3><?=get_post_meta($postid, ('quote'), true); ?></h3>
        <p class="quote-credit"><?=get_post_meta($postid, ('quote_credit'), true); ?></p>
      </div>


      <div class='movie-wrapper'>
<?=insert_thumbs($videos);?>

      </div><!-- move-wrapper -->
    </div> <!-- hw-endorsements row -->
  </div> <!-- hw-endorsements-wrapper -->

  <div class="products-tabs-wrapper">
    <div class="product-tabs">
      <ul class="tabs">
        <li><a href="#">Product Overview</a></li>
<?php
// Check if patholgies, and loop through each module if so
if(get_post_meta($postid, ('product_type'), true)=='Pathologies') {
  $pathologies = get_post_meta($postid, ('pathologies'), true);
  foreach($pathologies as $module) {
?>
        <li><a href="#"><?=get_the_title($module);?></a></li>
<?php
  }
}		
?>
        <li><a href="#">FAQ</a></li>
        <li><a href="#">Enquiries</a></li>
      </ul>

      <div class="cta-green-tabs right">
        <a href= "">How to Buy &raquo; </a> 
      </div>
    </div><!-- product-tabs -->

    <div class="panes">
      <div class="pane">
        <div class="tabs-text col">
          <h2><?=get_post_meta($postid, ('product_type'), true); ?></h2>
          <p> <?=get_post_meta($postid, ('product_overview'), true); ?></p>
          <div class="cta-green-inline">
            <a href="<?=get_permalink(get_post_meta($postid, ('ask_a_question'), true)); ?>">Ask a Question</a>
          </div>
        </div>
        <div class="tabs-gallery">

<?php
$custom_fields = get_post_custom($postid);
$x =  $custom_fields['lightbox'];
$gallery = maybe_unserialize($x[0]);
$thumb = wp_get_attachment_image_src( $gallery[0], 'medium' );
$theme = get_template_directory_uri();
foreach($gallery as $id) {
$x = wp_get_attachment_image_src( $id, 'large' );
$url[] = $x[0];
}

foreach($url as $src) {
  $i++;
  $visible = ($i=='1') ? " " : "style='display:none' ";

  echo "        <a href='$src' rel='lightbox[test]' $visible><img src='$thumb[0]'/></a>";

  if ($i=='1') echo " <a href='$src' id='slide-icon' class='slideshow-icon' rel='lightbox[test]'><img src='$theme/images/slideshow-icon.png'/></a>"; 

}

?>        </div><!-- tabs-gallery -->
      </div><!-- pane -->
<?php
// Check if patholgies, and loop through each module if so
if(get_post_meta($postid, ('product_type'), true)=='Pathologies') {
        $pathologies = get_post_meta($postid, ('pathologies'), true);
        foreach($pathologies as $module) {?>
      <div class="pane">
        <h2><?=get_the_title($module);?></h2>
        <p><?=get_post_meta($postid, ('content'), true); ?></p>				
      </div>

<?php   }
}
?>
      <!-- FAQ's -->
      <div class="pane">
        <div class="faq-text col">
        <h2>Frequently Asked Questions</h2>
<?php
$questions = get_post_meta($postid, ('frequently_asked_questions'), true);
foreach($questions as $var => $val) {
  echo "        <h3 class=\'faq-question\'>". get_the_title($val) . "</h3> <p>" . get_post_meta($val, 'answer', true) . "</p><br>";
}?>
      </div>
    </div>
      
      <!-- Enquiries -->
      <div class="pane">
        <div class="tabs-contact col">
           <h2>Submit an Enquiry</h2>
<?=do_shortcode('[si-contact-form form=\'1\']');?>
      </div>
    </div>

    </div> <!-- close panes -->
  </div><!-- products-tabs-wrapper -->
</div> <!-- Close the wrapper -->

<?php get_sidebar('products'); ?>

<?php get_sidebar('quicklinks'); ?>

<?php get_footer(); ?>
