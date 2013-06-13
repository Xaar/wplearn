<?php

if ( function_exists ( dynamic_sidebar(2) ) ) : ?>
<!-- ... regular html ... -->
<!-- ... regular html ... -->


<?php dynamic_sidebar (2); ?>

<?php endif; ?>

<div class='events-rightcol-wrapper alignright'>
  <div id="upcoming-events">
    <h2>UPCOMING EVENTS</h2>
<?php
$wp_query = new WP_Query( array ( 'post_type' => 'news-events', 'posts_per_page' => 5, 'meta_key' => 'event_end_date', 'meta_compare' => '>=', 'meta_value' => date('Ymd'), 'orderby' => 'meta_value', 'order' => 'ASC'));
$i=0;
while ( $wp_query->have_posts() ) : $wp_query->the_post();
        $i++;
?>
    <div class="event-listing-text-right">
      <h3><?=the_title();?></h3>
      <div><?=date_range();?></div>
      <div><?=get_post_meta($post->ID, 'location', true); ?></div>
      <a href="<?php the_permalink(); ?>">View event details &raquo;</a>
    </div>

    <div class='clearfix'></div>

<?php
endwhile;
?>

  </div>

  <div class='clearfix'></div>

  <div class="fb-root"></div>
  <script>
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
  </script>

  
  <div class="fb-like-box" data-href="https://www.facebook.com/EchoSimulator" data-width="292" data-show-faces="true" data-stream="false" data-show-border="true" data-header="false"></div>

</div>
