<?php
/*
Template Name: News-Events
*/
?>

<?php
define("THISPAGE", "news-events");

get_header(); 
?>
<div class="page-wrapper site-content">
	<div class="page-title row">
		<h2><?php
			$oid = $post->ID; 
			$type = strtolower(get_post_meta($oid, "article_type", true));
			if($type=='event') { 
				echo "Event";
			}else{ 
				echo "News Story";
			}?></h2>
		<h1><?php the_title(); ?></h1>
	</div>
	<div id='news-event' class="<?=$type;?>-leftcol-wrapper alignleft">

		<div class="hero-<?=$type;?>-content row">
			<div><?php the_post_thumbnail('sixteen-nine-large'); ?></div>
                        <div class="hero-<?=$type;?>-listing-text">
                                <p><?php if($type=='event') echo date_range();?></p>
                                <p><?php echo get_post_meta($oid, ('article'), true); ?></p>
				<div class="social-buttons">

				</div>
                        </div>
                </div>

		<div class="page-title row">
			<h2>More <?=($type=='news') ? "News Stories" : "Events";?></h2>
		</div>
<?php
// 5 Latest posts
$wp_query = new WP_Query( array ( 'post_type' => 'news-events', 'posts_per_page' => 6, 'meta_key' => 'article_type', 'meta_compare' => '==', 'meta_value' => $type, 'orderby' => 'menu_order', 'order' => 'ASC'));
while ( $wp_query->have_posts() ) : $wp_query->the_post();
  if($post->ID==$oid) {
    continue;
   }
?>              <div class="content-listing">
<?php
        if ( has_post_thumbnail()) : ?><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" ><?php { the_post_thumbnail('thumbnail') ; } ?></a>
<?php
        endif;?>
                        <div class="<?=$type;?>-listing-text">
                                <h3><?php the_title(); ?></h3>
                                <p><?php if(get_post_meta($post->ID, "article_type", true)=='Event') echo date_range();?></p>
                                <p><?php echo get_post_meta($post->ID, ('excerpt'), true); ?></p>
                                <a href="<?php the_permalink(); ?>">Read full story &raquo;</a>
                        </div>
                </div>
<?php
endwhile;
?>
	</div> <!-- .news-leftcol -->

<?php
if($type=='news') {
  get_sidebar('upcoming-events');
  get_sidebar('past-events');
}else{
  get_sidebar('news');
}

//wp_reset_postdata();
?>
</div> <!-- page-wrapper -->

<?php get_footer(); ?>
