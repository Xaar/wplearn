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
<script>
var page = <?=$pagex;?>;
jQuery(document).ready(function($){
  $('#news-event-ajax').load("<?php echo get_site_url(); ?>/ajax/"+page+'/?filter=news');
});
</script>
<div class="page-wrapper site-content clear-nav">
	
	<div class="page-title row">

		<h1>Latest Events</h1>
		
	</div>

	<div id='news-event-ajax' class="news-leftcol-wrapper">

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
