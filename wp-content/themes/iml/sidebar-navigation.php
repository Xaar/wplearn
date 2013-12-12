<div class="hw-menu-collapsed row open-navmodal">
  <div class="hw-menu-btn-arrows">
    <img src="<?php bloginfo('template_directory'); ?>/images/hw-menu-arrows.png" />
  </div>
  <div class="hw-menu-btn">
    <a href="">Menu</a>
  </div>
  <div class="hw-menu-title" id="hw-title-container">
<?php
$title = (get_the_title()=='Heartworks') ? "Heartworks Echocardiography Training Products" : get_the_title();?>

    <h1 id="hw-menu-title-txt"><?=$title;?></h1> <!-- Needs to display the current product when on a single product page -->
  </div>
  <div class="hw-menu-logo"><img src="<?php bloginfo('template_directory'); ?>/images/hw-menu-logo.png" /></div>
  <div class="clearfix"></div>
</div>

<div id="navmodal" class="hw-menu-expanded-wrapper row">
  <div class="hw-menu-expanded-title">
    <h1>Learn More about Heartworks</h1>
  </div> <!-- Expanded menu title -->
  <div class="hw-menu-expanded-logo"></div>
  <div class="clearfix"></div>
  <div class="hw-menu-content-wrapper row">
    <div class="hw-menu-lists">

     
      <div class="hw-menu-list">
        <div id="simulator-icon" class="hw-menu-category-icon"></div>
        <h2>Simulator Systems</h2>
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

$wp_query = new WP_Query( array ( 'post_type' => 'products', 'meta_key' => 'product_type', 'meta_compare' => '==', 'meta_value' => 'Gateway', 'orderby' => 'menu_order', 'order' => 'ASC'));
while ( $wp_query->have_posts() ) : $wp_query->the_post();
?>          <li><a href="<?=the_permalink();?>" class="hw-menu-link"><?=the_title();?><div class="hw-menu-arrow"></div></a></li>
<?php
endwhile;
?> 
     </ul>
      </div>
      <div class="hw-menu-list">
        <div id="professional-icon" class="hw-menu-category-icon"></div>
        <h2>Industry Partnership</h2>
        <ul>
          <?php
          $wp_query = new WP_Query( array ( 'post_type' => 'products', 'meta_key' => 'product_type', 'meta_compare' => '==', 'meta_value' => 'Industry', 'orderby' => 'menu_order', 'order' => 'ASC'));
          while ( $wp_query->have_posts() ) : $wp_query->the_post();
          ?>          <li><a href="<?=the_permalink();?>" class="hw-menu-link"><?=the_title();?><div class="hw-menu-arrow"></div></a></li>
          <?php
          endwhile;
          ?>          
        </ul>
      </div>
       <div class="hw-menu-list">
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
    </div> <!-- Expanded menu lists -->
  </div> <!-- Expanded menu content -->
</div> <!-- Expanded menu wrapper -->
<script type="text/javascript">

$(document).ready(function () {
    menuTitlePosition();
/*    slideshowIconPosition();*/
    $(window).resize(function() {
        menuTitlePosition();
    });
});

function menuTitlePosition() {
  var menuTitle = $("#hw-menu-title-txt");
  var menuTitleHeight = $("#hw-menu-title-txt").height();

  if (menuTitleHeight > 30 && menuTitleHeight < 45 ) {
    menuTitle.css({
      "top":"9%"
    });
  }
   else if (menuTitleHeight >= 45 ) {
    menuTitle.css({
      "top":"0"
    });
  }
  else {
    menuTitle.css({
      "top":"20%"
    });
  }
}
</script>

