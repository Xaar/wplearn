<?php
/*
Template Name: News-Events
*/
?>

<?php
define("THISPAGE", "news-events");

get_header(); 
?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
<div class="page-wrapper site-content">
	<div class="page-title row">
		<h2><?php if(get_post_meta($post->ID, "article_type", true)=='Event') { 
			echo "Event";
		}else{ 
			echo "News Story";
		}?></h2>
		<h1><?php the_title(); ?></h1>
	</div>
	<div id='news-event' class="news-leftcol-wrapper alignleft">

		<div class="hero-news-content row">
			<div><?php the_post_thumbnail('sixteen-nine-large'); ?></div>
                        <div class="hero-news-listing-text">
                                <p><?php if(get_post_meta($post->ID, "article_type", true)=='Event') echo date_range();?></p>
                                <p><?php echo get_post_meta($post->ID, ('article'), true); ?></p>
				<div class="social-buttons">

				</div>
                        </div>
                </div>
		<div class="page-title row">
			<h2>More News Stories</h2>
		</div>
<?php
// 5 Latest posts
$wp_query = new WP_Query( array ( 'post_type' => 'news-events', 'posts_per_page' => 5, 'orderby' => 'menu_order', 'order' => 'ASC'));
while ( $wp_query->have_posts() ) : $wp_query->the_post();
?>              <div class="content-listing">
<?php
        if ( has_post_thumbnail()) : ?><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" ><?php { the_post_thumbnail('thumbnail') ; } ?></a>
<?php
        endif;?>
                        <div class="news-listing-text">
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

	<div class='news-rightcol-wrapper alignright'>
		<div id="upcoming-events">
			<h2>UPCOMING EVENTS</h2>
<?php
$wp_query = new WP_Query( array ( 'post_type' => 'news-events', 'posts_per_page' => 5, 'meta_key' => 'event_end_date', 'meta_compare' => '>=', 'meta_value' => date('Ymd'), 'orderby' => 'meta_value', 'order' => 'ASC'));
$i=0;
while ( $wp_query->have_posts() ) : $wp_query->the_post();
        $i++;
?>
                        <div class="news-listing-text">
                                <h3><?=the_title();?></h3>
                                <div><?=date_range();?></div>
                                <div><?=get_post_meta($post->ID, 'location', true); ?></div>
                                <a href="<?php the_permalink(); ?>">View event details &raquo;</a>
                        </div>
<?php
endwhile;



?>
		</div>
		<div class="fb-like-box" data-href="https://www.facebook.com/EchoSimulator" data-width="292" data-show-faces="true" data-stream="false" data-show-border="true" data-header="false"></div>
	</div>

<?php

//wp_reset_postdata();
?>
</div> <!-- page-wrapper -->

<?php get_footer(); ?>
