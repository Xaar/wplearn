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
		
	</div>

	<div id='filter'>
		<select id='select-filter'>
			<option value='all'>Show: News and Events</option>
			<option value='event'>Show: News only</option>
			<option value='news'>Show: Events only</option>
		</select>
	</div>
	<div id='news-event-ajax' class="news-leftcol-wrapper">

	</div> <!-- .news-leftcol -->


<div class="sidebar-wrapper">
<?php get_sidebar( 'upcoming-events' ); ?>
<?php get_sidebar( 'past-events' ); ?>
<?php get_sidebar( 'facebook' ); ?>
</div>

<?php

//wp_reset_postdata();
?>
</div> <!-- page-wrapper -->

<?php get_footer(); ?>
