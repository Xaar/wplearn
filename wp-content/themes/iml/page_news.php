<?php
/*
Template Name: News
*/
?>

<?php
define("THISPAGE", "news");
?>

<?php get_header(); ?>

<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
<?php
$pods = new Pod('hw_events');
$pods->findRecords('name ASC', 25);
echo $pods->showTemplate('tplEvents');
?>				</div><!-- #content -->
	</div><!-- #primary -->


<?php get_footer(); ?>
