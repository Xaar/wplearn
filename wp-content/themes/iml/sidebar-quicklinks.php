<div class="quick-links-wrapper">
  <div class="quick-links row">
    <h4>Quick Links</h4>
    <hr />
  </div>

  <div class="quick-links row">
    <div class="quick-link col">
      <img src="<?php bloginfo('template_directory'); ?>/images/link-pathology.png">
      <h2><?php  $wp_query = new WP_Query( array ( 'post_type' => 'products', 'meta_key' => 'product_type', 'meta_compare' => '==', 'meta_value' => 'Pathologies'));
        while ( $wp_query->have_posts() ) : $wp_query->the_post();
        ?>         <a href="<?=the_permalink();?>">Heartworks Pathology Modules</a></h2>
      <p>Heartworks has recently created the first release of our Pathological Models. Focusing primarily on ventricular function, they are intended to address differing pathology found in day to day and emergency clinical environment cases. </p>
      <div class="cta-grey">
        <a href="<?=the_permalink();?>">Learn more &raquo;</a>
      </div>
        <?php
        endwhile;
        ?> 
      </div>
    <div class="quick-link col">
      <img src="<?php bloginfo('template_directory'); ?>/images/link-gateway.png">
      <h2><?php  $wp_query = new WP_Query( array ( 'post_type' => 'products', 'meta_key' => 'product_type', 'meta_compare' => '==', 'meta_value' => 'Gateway'));
        while ( $wp_query->have_posts() ) : $wp_query->the_post();
        ?>         <a href="<?=the_permalink();?>">Heartworks Gateway</a></h2>
      <p>Gateway is our 12-month, or multi-year, fixed price subscription service, designed to ensure that Heartworks customers receive the latest software developments and enhancements that keep your Heartworks system current.</p>
      <div class="cta-grey">
        <a href="<?=the_permalink();?>">Learn more &raquo;</a>
         </div>
        <?php
        endwhile;
        ?> 
    </div>
    <div class="quick-link col">
      <img src="<?php bloginfo('template_directory'); ?>/images/link-sales.png">
      <h2><a href="">Sales &amp; Support</a></h2>
      <p>Find a Heartworks distributor in your region or contact a member of the IML team directly.</p>
      <div class="cta-grey">
      <a href="<?php bloginfo('url'); ?>/sales-support">Learn more &raquo;</a>
    </div>
  </div>
  </div> <!-- .quick-links row -->
</div><!--  .quick-links-wrapper -->

<div class="clearfix"></div>
