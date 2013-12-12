<?php
/*
Template Name: About
*/
?>

<?php get_header(); ?>

<div id="content" class="hero-content row clear-nav" role="main">
  	<div class="page-title row">

		<h1>About Inventive Medical</h1>
		
	</div>

	<div class="about-leftcol-wrapper">
		<h2 class="heading-leftcol">The IML Story</h2>
    <div class="about_image"><?php the_post_thumbnail('sixteen-nine-large'); ?></div>
		<h1 class="about-section-title">Inventive Medical Ltd</h1>
<p>Inventive Medical Limited (IML) is a wholly owned trading subsidiary of the University College London Hospitals Charity (UCLHC).  IML was established in 2008 to support and market the HeartWorks simulation system which is a medical education initiative financed by UCLHC.</p>

<p>IML's HeartWorks Simulator Systems provide a comprehensive teaching and review tool for all clinicians, from medical students to all cardiac specialists, who share the need for a greater understanding of cardiac anatomy and echocardiography.</p>

<p>The HeartWorks simulator systems are market leaders in the provision of focussed cardiac echocardiography training for clinicians in medical schools, teaching hospitals, universities and medical device companies around the world.  IML's mission is to enhance education in echocardiography through the use of highly sophisticated medical simulators and on-line training material.</p>

<p>At the core of the system is a computer generated, animated 3D model of the normal human heart which has unrivalled qualities of accuracy and interactivity.  Progression from this point has led to high fidelity ultrasound simulation, both virtual and manikin based, for transthoracic and transoesophageal echocardiography.</p>

  <h2 class="heading-leftcol">The IML Team</h2>
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
