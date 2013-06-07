<?php
/*
Template Name: Sales
*/
?>

<?php
define("THISPAGE", "sales");
?>

<?php get_header(); ?>

        <style type="text/css">
			.bg_img img{
				width:100%;
				position:fixed;
				top:0px;
				left:0px;
				z-index:-1;
			}
		</style>


<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

		<div class="bg_img"><img src="images/1.jpg" alt="background" /></div>
		<div class="oe_wrapper">
			<div id="oe_overlay" class="oe_overlay"></div>
			<ul id="oe_menu" class="oe_menu">
				<li><a href="">Collections</a>
					<div>
						<ul>
							<li class="oe_heading">Summer 2011</li>
							<li><a href="#">Milano</a></li>
							<li><a href="#">Paris</a></li>
							<li><a href="#">Special Events</a></li>
							<li><a href="#">Runway Show</a></li>
							<li><a href="#">Overview</a></li>
						</ul>
						<ul>
							<li class="oe_heading">Winter 2010</li>
							<li><a href="#">Milano</a></li>
							<li><a href="#">New York</a></li>
							<li><a href="#">Behind the scenes</a></li>
							<li><a href="#">Interview</a></li>
							<li><a href="#">Photos</a></li>
							<li><a href="#">Download</a></li>
						</ul>
					</div>
				</li>
			</ul>	
		</div>
        <!-- The JavaScript -->
        <script type="text/javascript">
            $(function() {
				var $oe_menu		= $('#oe_menu');
				var $oe_menu_items	= $oe_menu.children('li');
				var $oe_overlay		= $('#oe_overlay');

                $oe_menu_items.bind('mouseenter',function(){
					var $this = $(this);
					$this.addClass('slided selected');
					$this.children('div').css('z-index','9999').stop(true,true).slideDown(200,function(){
						$oe_menu_items.not('.slided').children('div').hide();
						$this.removeClass('slided');
					});
				}).bind('mouseleave',function(){
					var $this = $(this);
					$this.removeClass('selected').children('div').css('z-index','1');
				});

				$oe_menu.bind('mouseenter',function(){
					var $this = $(this);
					$oe_overlay.stop(true,true).fadeTo(200, 0.6);
					$this.addClass('hovered');
				}).bind('mouseleave',function(){
					var $this = $(this);
					$this.removeClass('hovered');
					$oe_overlay.stop(true,true).fadeTo(200, 0);
					$oe_menu_items.children('div').hide();
				})
            });
        </script>

                </div><!-- #content -->
</div><!-- #primary -->


<?php get_footer(); ?>
