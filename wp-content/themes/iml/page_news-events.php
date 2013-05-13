<?php
/*
Template Name: News-Events
*/
?>

<?php
define("THISPAGE", "news-events");
?>

<?php get_header(); ?>



<div class="page-wrapper site-content">

	<div class="page-title row">
<h1>Latest News and Events</h1>
<h2>View latest news from Inventive Medical and get information about upcoming events our team will be attending.</h2>

</div>

<div class="news-leftcol-wrapper">

<div class="hero-news-content row">

	<?php
						// The Query
						$the_query_news_hero = new WP_Query( 'category_name=news&posts_per_page=1' );
						// The Loop
						while ( $the_query_news_hero->have_posts() ) : $the_query_news_hero->the_post();
						?>
									 <?php if ( has_post_thumbnail()) : ?>

						   <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
						   	<?php { the_post_thumbnail( 'sixteen-nine-large' ); } ?>
						   </a>
						 <?php endif; ?>
								 <div class="hero-news-listing-text">
								 <h3>Featured News</h3>	
								 <h2><?php the_title(); ?></h2>
								 <?php the_excerpt(); ?>
								 <a href="<?php the_permalink(); ?>">Read full story &raquo;</a>
								</div>
								
						<?php
						endwhile;

						// Reset Post Data
						wp_reset_postdata();

						?>

			

</div><!-- hero-news-content -->

						<?php
						// The Query
						$the_query_more_news = new WP_Query( 'category_name=news&posts_per_page=6' );
						// The Loop
						while ( $the_query_more_news->have_posts() ) : $the_query_more_news->the_post();
						?>
						
								 <div class="content-listing">
								 	<?php if ( has_post_thumbnail()) : ?>
						 
						   <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
						   	<?php { the_post_thumbnail( 'thumbnail' ); } ?>
						  
						   </a>
						 <?php endif; ?>
						 <div class="news-listing-text">
								 <h2><?php the_title(); ?></h2>
								 <?php the_excerpt(); ?>
								 <a href="<?php the_permalink(); ?>">Read full story &raquo;</a>
								</div>
							</div>
								 
						<?php
						endwhile;

						// Reset Post Data
						wp_reset_postdata();

						?>



</div> <!-- .news-leftcol -->

</div> <!-- page-wrapper -->


<?php get_footer(); ?>
