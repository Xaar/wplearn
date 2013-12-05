<?php
/*
Template Name: Events
*/
?>

<?php
define("THISPAGE", "events");

get_header(); 
$pagex = (get_query_var('page')) ? get_query_var('page') : 1;
?>
<div class="page-wrapper site-content clear-nav">
	
	<div class="page-title row">

		<h1>Latest Events</h1>
		
	</div>

	<div id='news-event-ajax' class="news-leftcol-wrapper">

<?php
$ppp = 6;
$wp_query = new WP_Query( array ( 'post_type' => 'events', 'posts_per_page' => "$ppp", 'orderby' => 'menu_order', 'order' => 'ASC', 'paged' => "$page" ));
$i=$pagex*$ppp-$ppp;
while ( $wp_query->have_posts() ) : $wp_query->the_post();
        $i++;
        $featured = ($i==1 ? true : false);
?>
 <?=($featured ? '<h2 class="heading-leftcol">Featured Story</h2>' : '');?>
<div class="<?=($featured ? "hero-event-content row" : 'content-listing');?>">
<?php
        if ( has_post_thumbnail()) : ?><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" ><?php { the_post_thumbnail($featured ? 'sixteen-nine-large' : 'thumbnail') ; } ?></a>
<?php
        endif;?>
                        <div class="<?=($featured ? "hero-event-listing-text" : "event-listing-text");?>">

                               <?=($featured ? '<h1 class="news-title">'. get_the_title().' </h1>' : '<h2>'. get_the_title().' </h2>');?>
                               <?=" <p class='date-range'>". date_range() .', '. get_post_meta($post->ID, ('location'), true). "</p>";?>
                                <p><?php echo get_post_meta($post->ID, ($featured ? 'summary' : 'excerpt'), true); ?></p>
                                <?php if($type=='news') echo  " <a href='".get_permalink($post->ID)."'>Read full story &raquo;</a>";?>
				<?=" <a href='".get_permalink($post->ID)."'>View event details &raquo;</a>";?>
                        </div>
                </div>
<?php
endwhile;

if($wp_query->max_num_pages>1){?>
    <div class="pagination">
    <?php
      if ($pagex > 1) { ?>
        <a href="<?php echo '?page=' . ($pagex -1); //prev link ?>"><</a>
                        <?php }
    for($i=1;$i<=$wp_query->max_num_pages;$i++){?>
        <a href="<?php echo '?page=' . $i; ?>" <?php echo ($pagex==$i)? 'class="pagination-selected"':'';?>><?php echo $i;?></a>
        <?php
    }
    if($pagex < $wp_query->max_num_pages){?>
        <a href="<?php echo '?page=' . ($pagex + 1); //next link ?>">></a>
    <?php } ?>
    </div>
<?php }elseif($pagex>0 && $wp_query->max_num_pages==0){?>
        <a href="<?php echo '?page=1'; //prev link ?>">Return to first page</a>
<?php
}
?>



	</div> <!-- .news-leftcol -->


<div class="sidebar-wrapper">
<?php get_sidebar( 'news' ); ?>
<?php get_sidebar( 'facebook' ); ?>
</div>

<?php

//wp_reset_postdata();
?>
</div> <!-- page-wrapper -->
<?php get_footer(); ?>
