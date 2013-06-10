<?php
/*
Template Name: News-Events
*/
?>

<?php
define("THISPAGE", "news-events");

get_header(); 
//$pagex = (get_query_var('page')) ? "?page=".get_query_var('page') : "?page=1";
$pagex = (get_query_var('page')) ? get_query_var('page') : 1;
?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
var page = <?=$pagex;?>;
jQuery(document).ready(function($){
  filter = $('#select-filter option:selected').val();
  $('#news-event-ajax').load("<?php echo get_site_url(); ?>/ajax/"+page+'?filter='+filter);

  $('#select-filter').on("change", function(){
    filter = $('#select-filter option:selected').val();
    $('#news-event-ajax').load("<?php echo get_site_url(); ?>/ajax/"+page+'?filter='+filter);
  });
});
</script>
<div class="page-wrapper site-content">
	<div class="page-title row">
		<h1>Latest News and Events</h1>
		<h2>View latest news from Inventive Medical and get information about upcoming events our team will be attending.</h2>
	</div>
	<div id='filter'>
		<select id='select-filter'>
			<option value='all'>Filter: news and events</option>
			<option value='event'>Filter: news</option>
			<option value='news'>Filter: events</option>
		</select>
	</div>
	<div id='news-event-ajax' class="news-leftcol-wrapper alignleft">

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
