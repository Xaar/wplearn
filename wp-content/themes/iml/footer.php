<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package IML
 */
?>

	

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-footer-white">
		<div class="footer-wrapper row">
			<div class="logo col">
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php bloginfo('template_directory'); ?>/images/inventive-medical-logo-dark.png" title="Inventive Medical Ltd." alt="" width="155px" height="28px" /></a></h1>
					

				</div>
			
			<ul class="footer-links">
				<li><a href="<?php bloginfo('url'); ?>/sales-support">Contact us</a></li>
				<li><a href="<?php bloginfo('url'); ?>/privacy-policy">Privacy Policy</a></li>
				<li><a href="http://www.uclhcharity.org.uk/" target="_blank">UCLH Charity</a></li>
			</ul>			
			<div id="wireworks-credit">
				<a href="http://www.wireworksdigital.co.uk">Site by: <img src="<?php bloginfo('template_directory'); ?>/images/wireworks.jpg" title="Wireworks" /></a>
				</div>
		</div><!-- .site-info -->


	<div id="footer-address">
					<p><a href="Tel: +44 (0) 203 447 9360">Tel: +44 (0) 203 447 9360</a>
					Inventive Medical Ltd, 5th Floor East, 250 Euston Road, London, NW1 2PG, Registered in the UK No: 6468381</p>

				</div>
</div><!-- #footerwrapper -->

</div><!-- site footer white -->

</footer><!-- #colophon -->
<?php wp_footer(); ?>

</body>
</html>