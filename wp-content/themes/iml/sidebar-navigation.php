<?php

if ( function_exists ( dynamic_sidebar(0) ) ) : ?>
<!-- ... regular html ... -->
<!-- ... regular html ... -->


<?php dynamic_sidebar (0); ?>

<?php endif; ?>

<div class="hw-menu-collapsed row open-testmodal">
  <div class="hw-menu-btn-arrows">
    <img src="<?php bloginfo('template_directory'); ?>/images/hw-menu-arrows.png" />
  </div>
  <div class="hw-menu-btn">
    <a href="">Click for Menu</a>
  </div>
  <div class="hw-menu-title">
<?php
$title = (get_the_title()=='Heartworks') ? "Heartworks Echocardiography Simultators and E-Learning Products" : get_the_title();?>

    <h1><?=$title;?></h1> <!-- Needs to display the current product when on a single product page -->
  </div>
  <div class="hw-menu-logo"></div>
  <div class="clearfix"></div>
</div>

<div id="testmodal" class="hw-menu-expanded-wrapper row">
  <div class="hw-menu-expanded-title">
    <h1>Learn More about Heartworks</h1>
  </div> <!-- Expanded menu title -->
  <div class="hw-menu-expanded-logo"></div>
  <div class="clearfix"></div>
  <div class="hw-menu-content-wrapper row">
    <div class="hw-menu-lists">
      <div class="hw-menu-list col">
        <div id="about-icon" class="hw-menu-category-icon"></div>
        <h2>About Heartworks</h2>
        <ul>
        <!-- Leave as static for now - client to confirm -->
          <li><a class="hw-menu-link">About us<div class="hw-menu-arrow"></div></a></li>
          <li><a class="hw-menu-link">The team<div class="hw-menu-arrow"></div></a></li>
          <li><a class="hw-menu-link">Another link<div class="hw-menu-arrow"></div></a></li>
        </ul>
      </div>
      <div class="hw-menu-list col">
        <div id="elearn-icon" class="hw-menu-category-icon"></div>
        <h2>Education</h2>
        <ul>
        <!-- write wp query for products > elearning -->
<?php
$wp_query = new WP_Query( array ( 'post_type' => 'products', 'meta_key' => 'product_type', 'meta_compare' => '==', 'meta_value' => 'eLearn', 'orderby' => 'menu_order', 'order' => 'ASC'));
while ( $wp_query->have_posts() ) : $wp_query->the_post();
?>          <li><a href="<?=the_permalink();?>" class="hw-menu-link"><?=the_title();?><div class="hw-menu-arrow"></div></a></li>
<?php
endwhile;
?>          <li><a class="hw-menu-link">Media store<div class="hw-menu-arrow"></div></a></li>
        </ul>
      </div>
      <div class="hw-menu-list col">
        <div id="simulator-icon" class="hw-menu-category-icon"></div>
        <h2>Echo Simulators</h2>
        <ul>
        <!-- write wp query for products > simulators -->
<?php
$wp_query = new WP_Query( array ( 'post_type' => 'products', 'meta_key' => 'product_type', 'meta_compare' => '==', 'meta_value' => 'Simulator', 'orderby' => 'menu_order', 'order' => 'ASC'));
while ( $wp_query->have_posts() ) : $wp_query->the_post();
?>          <li><a href="<?=the_permalink();?>" class="hw-menu-link"><?=the_title();?><div class="hw-menu-arrow"></div></a></li>
<?php
endwhile;

$wp_query = new WP_Query( array ( 'post_type' => 'products', 'meta_key' => 'product_type', 'meta_compare' => '==', 'meta_value' => 'Pathologies', 'orderby' => 'menu_order', 'order' => 'ASC'));
while ( $wp_query->have_posts() ) : $wp_query->the_post();
?>          <li><a href="<?=the_permalink();?>" class="hw-menu-link"><?=the_title();?><div class="hw-menu-arrow"></div></a></li>
<?php
endwhile;
?>        </ul>
      </div>
      <div class="hw-menu-list col">
        <div id="professional-icon" class="hw-menu-category-icon"></div>
        <h2>Professional Products</h2>
        <ul>
          <li><a class="hw-menu-link">Watchman Device<div class="hw-menu-arrow"></div></a></li>
          <li><a class="hw-menu-link">Media store<div class="hw-menu-arrow"></div></a></li>
        </ul>
      </div>
    </div> <!-- Expanded menu lists -->
  </div> <!-- Expanded menu content -->
</div> <!-- Expanded menu wrapper -->

