<?php

if ( function_exists ( dynamic_sidebar(5) ) ) : ?>
<!-- ... regular html ... -->
<!-- ... regular html ... -->


<?php dynamic_sidebar (5); ?>

<?php endif; ?>

<div class='news-rightcol-wrapper col'>
  <div id="recent-news">
    <h2 class="heading-sidebar">RECENT NEWS</h2>
    <div class="sidebar-contents">
<?php
$wp_query = new WP_Query( array ( 'post_type' => 'news', 'posts_per_page' => 5, 'orderby' => 'menu_order', 'order' => 'ASC'));
$i=0;
while ( $wp_query->have_posts() ) : $wp_query->the_post();
        $i++;
?>
    <div class="news-listing-text-right">
      <a href="<?php the_permalink(); ?>"><h3><?=the_title();?></h3></a>
      <a href="<?php the_permalink(); ?>">View news details &raquo;</a>
    </div>
<?php
endwhile;
?>
  </div>
    </div> <!-- sidebar contents -->
  <div class='clearfix'></div>

  <?php get_sidebar('facebook');?>

</div>
