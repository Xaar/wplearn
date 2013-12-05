<?php
/*
Template Name: About_Heartworks
*/
?>

<?php get_header(); ?>

<div id="content" class="hero-content row clear-nav" role="main">
  	<div class="page-title row">

		<h1>About Heartworks</h1>
		
	</div>

	<div class="about-leftcol-wrapper">
		<h2 class="heading-leftcol">The Heartworks Story</h2>
    <div class="about_image"><?php the_post_thumbnail('sixteen-nine-large'); ?></div>
     <p> The three clinicians who have led this project direct a highly successful course in peri-operative transoesophageal echocardiography at the Heart Hospital in London, UK. They developed the idea of creating a virtual heart in response to the surprising absence of a sufficiently accurate model of the heart with which to teach cardiac anatomy to their students. The idea of using the anatomical 3D data set to generate a simulated ultrasound image was a natural progression from this point.</p>

      <p>A chance conversation with friends led to contact with Glassworks, an award-winning computer graphics company with a strong track record in taking on unusual projects. Painstaking research and collation of large amounts of reference material, combined with input from a wide range of clinicians and cardiac morphologists at the leading edge in their fields has complemented the expertise of Glassworks to produce an anatomical model of unsurpassed accuracy and realism.</p>

<p>Custom designed ultrasound simulation software has resulted in freely interactive TOE image simulation with true to life control of the probe depth and flexion and rotation of the imaging plane. Collaboration with Asylum, a leading Models and Effects company, resulted in the development of a haptic interface which allows a manikin TOE simulator to drive the HeartWorks software. Like the virtual heart and PC based ultrasound simulation modules, the manikin simulator is now commercially available. 
  </p>
  <h2 class="heading-leftcol">The Heartworks Team</h2>
 <?php if (have_posts()) : while (have_posts()) : the_post();

the_content();

endwhile; endif; ?>
  <div class="clearfix"></div>
</div>

  <div class="sidebar-wrapper">
<?php

  get_sidebar('upcoming-events');
  get_sidebar('news');


?>
</div> <!-- sidebar wrapper -->
</div>

<?php get_footer(); ?>

