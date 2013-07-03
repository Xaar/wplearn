<?php
/*
Template Name: Sales
*/
?>

<?php
define("THISPAGE", "sales");
?>

<?php get_header(); ?>


<script type="text/javascript">
$(function() {
    // setup ul.tabs to work as tabs for each div directly under div.panes
    $("ul.tabs").tabs("div.panes > div");
});
</script>

<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>-->

<div id="content" class="hero-content row" role="main">

  <div class="page-title row">
    <h1>Sales and Support</h1>
  </div>

  <div class="sales-leftcol-wrapper">
    <div class="products-tabs-wrapper">
      <div class="product-tabs">
        <ul class="tabs">
          <li><a href="#">How to Buy</a></li>
          <li><a href="#">Sales Enquiries</a></li>
          <li><a href="#">Support Enquiries</a></li>
        </ul>
      </div><!-- product-tabs -->

      <div class="panes">
        <!-- MAP -->
        <div class="pane">
          <div class="tabs-text col">
            <h2>Find a distributor</h2>
<?=do_shortcode('[simplemap]');?>
            <div class="cta-green-inline">
              <a href="<?=get_permalink(get_post_meta($postid, ('ask_a_question'), true)); ?>">Ask a Question</a>
            </div>
          </div>
        </div><!-- pane -->

        <!-- FAQ's -->
        <div class="pane">
          <div class="faq-text col">
          <h2>Frequently Asked Questions</h2>
<?=do_shortcode('[si-contact-form form=\'1\']');?>

          </div>
        </div><!-- pane -->
      
        <!-- Enquiries -->
        <div class="pane">
          <div class="tabs-contact col">
             <h2>Submit an Enquiry</h2>
<?=do_shortcode('[si-contact-form form=\'1\']');?>
          </div>
        </div><!-- pane -->
      </div> <!-- close panes -->
    </div><!-- products-tabs-wrapper -->
  </div> <!-- leftcol -->

  <div class="sidebar-wrapper">
<?php
  get_sidebar('upcoming-events');
  get_sidebar('news');
?>
  </div> <!-- sidebar wrapper -->

</div><!-- hero-content -->

<?php get_footer(); ?>
