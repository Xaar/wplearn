<?php
/*
Template Name: IML Home
*/
?>

<?php get_header(); ?>
	<!-- Chang URLs to wherever Video.js files will be hosted -->
	<link href="<?php bloginfo('template_directory'); ?>/js/video-js/video-js.css" rel="stylesheet" type="text/css">

	<!-- Unless using the CDN hosted version, update the URL to the Flash SWF -->
	<script>
		videojs.options.flash.swf = "<?php bloginfo('template_directory'); ?>/js/video-js/video-js.swf";
	</script>


	<div id="content" class="hero-content row clear-nav home-heart" role="main">
	
		<div id="home-mission" class="col">
			<h1>Inventive Medical Limited</h1>
			<p>IML is the acknowledged leader and preferred partner to<br />teaching hospitals, medical institutions and companies<br />across the globe engaged in the training and advancement <br />of focused cardiac echocardiography.</p>
		</div>		
		<div id="home-video-container">

			<video id="heart-animation" class="video-js" data-setup='{ "controls": false, "autoplay": true, "preload": "auto", "width": 480, "height": 480, "loop": true }' poster="<?php bloginfo('template_directory'); ?>/video/heartworks-heart-white.png" data-setup="{}">
				<source src="<?php bloginfo('template_directory'); ?>/video/Home-Heart-Loop.mp4" type='video/mp4' />
   <!--  <source src="http://video-js.zencoder.com/oceans-clip.webm" type='video/webm' />
    <source src="http://video-js.zencoder.com/oceans-clip.ogv" type='video/ogg' /> -->
			</video>
		</div>
	 	<div class="clearfix"></div>
	</div><!-- hero-content -->
	
<?php get_footer(); ?>
