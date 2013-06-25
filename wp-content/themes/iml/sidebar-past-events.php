<?php

if ( function_exists ( dynamic_sidebar(6) ) ) : ?>
<!-- ... regular html ... -->
<!-- ... regular html ... -->


<?php dynamic_sidebar (6); ?>

<?php endif; ?>

<div class='events-rightcol-wrapper col'>
  <div id="upcoming-events">
    <h2 class="heading-sidebar">PAST EVENTS</h2>
<?php
$wp_query = new WP_Query( array ( 'post_type' => 'news-events', 'posts_per_page' => 6, 'meta_key' => 'event_end_date', 'meta_compare' => '<', 'meta_value' => date('Ymd'), 'orderby' => 'meta_value', 'order' => 'DESC'));
$i=0;
while ( $wp_query->have_posts() ) : 
        $wp_query->the_post();
        $i++;
	if(get_post_meta($post->ID, 'article_type', true)=='News') continue
?>
    <div class="event-listing-text-right">
      <h3><?=the_title();?></h3>
      <div><?=date_range();?></div>
      <div><?=get_post_meta($post->ID, 'location', true); ?></div>
      <a href="<?php the_permalink(); ?>">View event details &raquo;</a>
    </div>
<?php
endwhile;
?>
  </div>

  <div class='clearfix'></div>

  <?php get_sidebar('facebook');?>

</div>
