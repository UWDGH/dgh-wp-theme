<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package uw_wp_theme
 */

?>

		<footer id="colophon" class="site-footer">
			<a href="<?php echo home_url('/'); ?>" class="footer-wordmark" title="<?php echo get_bloginfo('name'); ?> Home"><?php echo get_bloginfo('name'); ?></a>

			<a hidden class="be-boundless hide" aria-hidden="true" href="https://www.washington.edu/boundless/">Be boundless</a>

			<div class="h4" id="social_preface">Connect with us:</div>
			<nav aria-labelledby="social_preface">
				<ul class="footer-social">
					<li><a class="facebook" href="https://www.facebook.com/UWDGH">Facebook</a></li>
					<li hidden class="hide" aria-hidden="true"><a class="twitter" href="https://twitter.com/uwdgh">Twitter</a></li>
					<li><a class="instagram" href="https://www.instagram.com/globalhealthuw/">Instagram</a></li>
					<li><a class="youtube" href="https://www.youtube.com/channel/UC7hLYa-wDea1W_-V1C8Yz0Q">YouTube</a></li>
					<li><a class="linkedin" href="https://www.linkedin.com/company/globalhealthuw/">LinkedIn</a></li>
					<li hidden class="hide" aria-hidden="true"><a class="pinterest" href="https://www.pinterest.com/uofwa/">Pinterest</a></li>
					<li><a class="smugmug" href="https://uw-globalhealth.smugmug.com/">SmugMug</a></li>
				</ul>
			</nav>

			<nav aria-label="footer">
				<?php uw_wp_theme_footer_menu(); ?>
				<ul id="footer-login" class="footer-links small">
					<li><?php do_action( 'uw_wp_child_theme_login_link' ); ?></li>
				</ul>
			</nav>

			<div class="site-info">
				<p>&copy; <?php echo date( 'Y' ); ?> University of Washington  |  Seattle, WA</p>
			</div><!-- .site-info -->
		</footer><!-- #colophon -->
	</div><!-- #page-inner -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
