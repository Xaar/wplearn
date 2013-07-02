<?php
/*
Template Name: AJAX
*/
?>

<?php
define("THISPAGE", "ajax");
?>

<?php
$page = (get_query_var('page')) ? get_query_var('page') : 1;
$filter = ucfirst($_GET['filter']);
//$page = ($filter!='All') ? 1 : $page;
$pagex = $page;
$ppp = 6;
$wp_query = new WP_Query( array ( 'post_type' => 'news-events', 'posts_per_page' => "$ppp", 'meta_key' => 'article_type', 'meta_compare' => '!=', 'meta_value' => "$filter", 'orderby' => 'menu_order', 'order' => 'ASC', 'paged' => "$page" ));
$i=$page*$ppp-$ppp;
while ( $wp_query->have_posts() ) : $wp_query->the_post();
        $i++;
	$type = strtolower(get_post_meta($post->ID, "article_type", true));
        $featured = ($i==1 ? true : false);
?>
 <?=($featured ? '<h2 class="heading-leftcol">Featured Story</h2>' : '');?>              
<div class="<?=($featured ? "hero-$type-content row" : 'content-listing');?>">
<?php
        if ( has_post_thumbnail()) : ?><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" ><?php { the_post_thumbnail($featured ? 'sixteen-nine-large' : 'thumbnail') ; } ?></a>
<?php
        endif;?>
                        <div class="<?=($featured ? "hero-news-listing-text" : "$type-listing-text");?>">
                                
                               <?=($featured ? '<h1 class="news-title">'. get_the_title().' </h1>' : '<h2>'. get_the_title().' </h2>');?>   
                               <?php if($type=='event') echo  " <p class='date-range'>". date_range() .', '. get_post_meta($post->ID, ('location'), true). "</p>";?>
                                <p><?php echo get_post_meta($post->ID, ($featured ? 'summary' : 'excerpt'), true); ?></p>
                                <?php if($type=='news') echo  " <a href='<?php the_permalink(); ?>''>Read full story &raquo;</a>";?>
                                 <?php if($type=='event') echo  " <a href='<?php the_permalink(); ?>''>View event details &raquo;</a>";?>
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
