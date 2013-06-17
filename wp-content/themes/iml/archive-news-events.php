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
<script>
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

<?php get_sidebar( 'upcoming-events' ); ?>
<?php get_sidebar( 'past-events' ); ?>
<?php get_sidebar( 'facebook' ); ?>

<?php

//wp_reset_postdata();
?>
</div> <!-- page-wrapper -->

<?php get_footer(); ?>
