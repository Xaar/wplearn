<?php

if ( function_exists ( dynamic_sidebar(5) ) ) : ?>
<!-- ... regular html ... -->
<!-- ... regular html ... -->


<?php dynamic_sidebar (5); ?>

<?php endif; ?>

<div class='news-rightcol-wrapper col'>
  <div id="recent-news">
    <h2 class="heading-sidebar">RECENT NEWS</h2>
<?php
$wp_query = new WP_Query( array ( 'post_type' => 'news-events', 'posts_per_page' => 5, 'meta_key' => 'article_type', 'meta_compare' => '==', 'meta_value' => 'News', 'orderby' => 'menu_order', 'order' => 'ASC'));
$i=0;
while ( $wp_query->have_posts() ) : $wp_query->the_post();
        $i++;
?>
    <div class="news-listing-text">
      <h3><?=the_title();?></h3>
      <div><?=get_post_meta($post->ID, 'location', true); ?></div>
      <a href="<?php the_permalink(); ?>">View news details &raquo;</a>
    </div>
<?php
endwhile;
?>
  </div>

  <div class='clearfix'></div>

  <?php get_sidebar('facebook');?>

</div>
