<?php
/*
Template Name: About
*/
?>

<?php
define("THISPAGE", "about");
?>

<?php get_header(); ?>

<div id="content" class="hero-content row" role="main">
  	<div class="page-title row">

		<h1>About Inventive Medical</h1>
		
	</div>

	<div class="about-leftcol-wrapper">
		<h2 class="heading-leftcol">The IML Story</h2>
		<h1 class="about-section-title">We design and develop advanced echocardiography imaging products for the medical industry</h1>
		<p>Lorem ipsum dolor sit amet, in qui nominavi maluisset, et voluptatum definitionem mea. Ad integre persequeris sit, in eum feugait propriae accusamus, ad eos posse nostrud nostrum. Nisl recusabo erroribus has id. Sed justo malorum apeirian te, iudico volutpat at pro. Illum adipiscing at nec, vide repudiandae ea nam. Eam et vitae nonumes convenire. Ea vis aperiam scripserit.
  </p>
  <p>
Eum no officiis voluptatum, ne pro perpetua appellantur. Porro aliquam philosophia at eum, no numquam mediocrem splendide usu. Et alterum pertinacia eam, duo ut dicat quando efficiendi, vel consul convenire consequuntur at. Ut commodo adipisci usu.
  </p>
  <p>
Ex quidam fierent dissentiunt usu. Cu his debitis temporibus scribentur, vim elit graeci suscipiantur ne. Mel an harum menandri, fabulas sensibus eleifend et sit. Quem eloquentiam at his, te vim elit option antiopam, ei brute contentiones vis.
  </p>
  <p>
Quaeque feugait atomorum eam ad, mucius sententiae quo ad. Veniam tantas vis ad, ei eos ipsum tibique assentior. In usu fierent oporteat. Cibo invenire indoctum cum ei.
  </p>
  <p>
Pri tantas invidunt et, ei sed aliquid deserunt dissentias. Duo nostrud detracto assueverit id, admodum postulant consequuntur vis no. Alii apeirian te ius, ut tation gloriatur nec. An per zril possit putant, ne per nihil adipisci. No consul vivendo intellegebat vix, ea recteque argumentum sit.
  </p>
  <p>
Sit cu porro putant, usu ei sonet partem, odio malis ius ne. An option ceteros tacimates sit, nec sonet vitae laboramus ut. His an modo facer intellegebat. Lorem utroque dolorem vim ne, cu vim suas aeque, te ullum volutpat mel. Ad eum eius indoctum comprehensam, contentiones intellegebat ea est, mei no vide omnium fastidii. Posse nostrud his ut, suas aperiam dissentiet ius in, est cetero scripta id.
  </p>
  <p>
Eius gubergren ei sit. Ea his adipisci volutpat percipitur, ne oblique recusabo assueverit pri. Cu eam solum platonem percipitur, duo partem nemore ancillae et. Eu quo molestiae complectitur. Ad assum delenit consequuntur mei.
  </p>
  <h2 class="heading-leftcol">The IML Team</h2>
 <?php if (have_posts()) : while (have_posts()) : the_post();

the_content();

endwhile; endif; ?>
	
</div>

	<div class="sidebar-wrapper">
<?php

  get_sidebar('upcoming-events');
  get_sidebar('news');


?>
</div> <!-- sidebar wrapper -->



  <div><!-- #content -->



<?php get_footer(); ?>
