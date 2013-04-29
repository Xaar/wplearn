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
		<div class="footer-wrapper row">
			<div class="logo col">
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php bloginfo('template_directory'); ?>/images/inventive-medical-logo-dark.png" title="Inventive Medical Ltd." alt="" width="155px" height="28px" /></a></h1>
					
				</div>
			
			<ul class="footer-links col">
				<li>Terms and Conditions</li>
				<li>Disclaimer</li>
				<li>Privacy</li>
				<li>UCLH</li>
				<li>Footer link</li>
			</ul>			
			
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #pagewrapper -->

<?php wp_footer(); ?>

</body>
</html>