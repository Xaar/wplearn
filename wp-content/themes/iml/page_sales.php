<?php
/*
Template Name: Sales
*/
?>

<?php
define("THISPAGE", "sales");
?>

<?php get_header(); ?>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>
<!--[if IE 7]>
  <style type="text/css">
    #vtab > ul > li.selected{
      border-right: 1px solid #fff !important;
    }
    #vtab > ul > li {
      border-right: 1px solid #ddd !important;
    }
    #vtab > div { 
      z-index: -1 !important;
      left:1px;
    }
  </style>
<![endif]-->
<style type="text/css">
  body {
    background: #fff;
  }
  #vtab {
    margin: auto;
    width: 1000px;
    height: 100%;
  }
  #vtab > ul > li {
    width: 170px;
    height: 30px;
    font-size: 14px;
    background-color: #fff !important;
    list-style-type: none;
    display: block;
    text-align: left;
    margin: auto;
    padding: 10px 0px 5px 10px;
    border: 1px solid #fff;
    position: relative;
    border-right: none;
    opacity: .3;
    -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=30)";
    filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=30);
  }
  #vtab > ul > li.home {
    background: url('home.png') no-repeat center center;
  }
  #vtab > ul > li.login {
    background: url('login.png') no-repeat center center;
  }
  #vtab > ul > li.support {
    background: url('support.png') no-repeat center center;
  }
  #vtab > ul > li.selected {
    opacity: 1;
    -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
    filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=100);
    border: 1px solid #ddd;
    border-right: none;
    z-index: 10;
    font-weight: bold;
    color: #ffffff;
    background-color: #7a7a7a !important;
    position: relative;
  }
  #vtab > ul {
    float: left;
    width: 180px;
    text-align: left;
    display: block;
    margin: auto 0;
    padding: 0;
    position: relative;
    top: 0px;
  }
  #vtab > div {
    background-color: #fafafa;
    margin-left: 180px;
    border: 1px solid #ddd;
    min-height: 500px;
    padding: 12px;
    position: relative;
    z-index: 9;
    -moz-border-radius: 20px;
  }
  #vtab > div > h4 {
    color: #800;
    font-size: 1.2em;
    padding-top: 5px;
    margin-top: 0;
    clear: none;
  }
</style>

<script type="text/javascript">
  $(function() {
    var $items = $('#vtab>ul>li');
    $items.click(function() {
      $items.removeClass('selected');
      $(this).addClass('selected');

      var index = $items.index($(this));
      $('#vtab>div').hide().eq(index).show();
    }).eq(0).click();
  });
</script>

<div id="content" class="hero-content row" role="main">

  <div id="vtab">
    <ul>
      <li class="home">How to Buy</li>
      <li class="sales">Sales Enquires</li>
      <li class="support">Support Enquiries</li>
    </ul>
    <div>
      <h4>Regional Map</h4>
      <?=do_shortcode('[simplemap]');?>
    </div>
    <div>
      <h4>Contact Us</h4>
      <?=do_shortcode('[si-contact-form form=\'1\']');?>
    </div>
    <div>
      <h4>Contact Us</h4>
      <?=do_shortcode('[si-contact-form form=\'1\']');?>
    </div>
  </div>

</div><!-- hero-content -->

<?php get_footer(); ?>
