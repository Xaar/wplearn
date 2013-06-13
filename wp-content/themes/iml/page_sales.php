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
    font-family: verdana;
    padding-top: 50px;
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
  #loginForm label {
    float: left;
    width: 100px;
    text-align: right;
    clear: left;
    margin-right: 15px;
  }
  #loginForm fieldset {
    border: none;
  }
  #loginForm fieldset > div {
    padding-top: 3px;
    padding-bottom: 3px;
  }
  #loginForm #login {
     margin-left: 115px;
  }
  #testmodal {
    background: none repeat scroll 0 0 #FFFFFF;
    border-radius: 5px 5px 5px 5px;
    box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
    width: 400px;
  }
  #testmodal .header {
    background: #DDD;
    border-bottom: 1px solid #CCCCCC;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    padding: 18px 18px 14px;
  }
  #testmodal .header h3 {
    margin: 0;
    padding: 0;
    text-shadow: 0 1px 0 rgba(255,255,255,0.5);
  }
  #testmodal form {
    padding: 10px 0;
  }
  #testmodal .txt {
    padding: 10px 20px;
  }
  #testmodal .txt input {
    width: 220px;
    height: 16px;
    padding: 3px;
    border: 1px solid;
    border-color: #8C8C8C #CCCCCC #CCCCCC #8C8C8C;
  }
  #testmodal .txt label {
    float: left;
    width: 130px;
    line-height: 24px;
  }
  #testmodal .btn {
    padding: 10px 20px 10px 150px;
  }
  #testmodal .btn a {
    float: left;
    padding: 0 20px;
    line-height: 26px;
    border: 1px solid;
    border-color: #00729F;
    border-radius: 3px;
    background: -moz-linear-gradient(center top , #049CDB, #0064CD);
    text-align: center;
    font-family: sans-serif;
    font-size: 14px;
    text-shadow: 0 1px 0 rgba(0,0,0,0.5);
    color: #FFF;
    margin: 0 10px 0 0;
  }
  #testmodal .btn a.cancel {
    border-color: #AAAAAA #AAAAAA #888888;
    background: -moz-linear-gradient(center top , #EEEEEE, #CCCCCC);
    box-shadow: none;
    color: #333;
    text-shadow: 0 1px 0 rgba(255,255,255,0.5);
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

<a href="#" class="open-testmodal">test modal</a>


<div id="testmodal">
	<div class="header">
		<h3>Create a new account</h3>
	</div>
	<form action="">
		<div class="txt">
			<label for="username">Username:</label>
			<input type="text" name="" id="username">
		</div>
		<div class="txt">
			<label for="email">Email address:</label>
			<input type="text" name="" id="email">
		</div>
		<div class="txt">
			<label for="password">Password:</label>
			<input type="password" name="" id="password">
		</div>
		<div class="btn clearfix">
			<a class="close" href="#">Sign Up</a>
			<a class="close cancel" href="#">Cancel</a>
		</div>
	</form>
</div>


</div><!-- hero-content -->

<?php get_footer(); ?>
