<?php

if ( function_exists ( dynamic_sidebar(6) ) ) : ?>
<!-- ... regular html ... -->
<!-- ... regular html ... -->


<?php dynamic_sidebar (6); ?>

<?php endif; ?>

<div class='events-rightcol-wrapper'>
    <h2 class="heading-sidebar">PAST EVENTS</h2>
    <div class="sidebar-contents">
<?php
$wp_query = new WP_Query( array ( 'post_type' => 'events', 'posts_per_page' => 6, 'meta_key' => 'event_end_date', 'meta_compare' => '<', 'meta_value' => date('Ymd'), 'orderby' => 'meta_value', 'order' => 'DESC'));
$i=0;
while ( $wp_query->have_posts() ) : 
        $wp_query->the_post();
        $i++;
?>
    <div class="event-listing-text-right">
      <a href="<?php the_permalink(); ?>"><h3><?=the_title();?></h3></a>
      <p><?=date_range();?><br />
      <?=get_post_meta($post->ID, 'location', true); ?></p>
      <a href="<?php the_permalink(); ?>">View event details &raquo;</a>
    </div>
<?php
endwhile;
?>
  </div> <!-- sidebar contents -->



</div>
