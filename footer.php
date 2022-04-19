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
			<a href="https://globalhealth.washington.edu" class="footer-wordmark" title="University of Washington - Department of Global Health">University of Washington - Department of Global Health</a>

			<a hidden class="hide" href="https://www.washington.edu/boundless/"><h3 class="be-boundless">Be boundless</h3></a>

			<h4>Connect with us:</h4>
			<nav aria-label="social networking">
				<ul class="footer-social">
					<li><a class="facebook" href="https://www.facebook.com/UWDGH">Facebook</a></li>
					<li><a class="twitter" href="https://twitter.com/uwdgh">Twitter</a></li>
					<li><a class="instagram" href="https://www.instagram.com/globalhealthuw/">Instagram</a></li>
					<li><a class="youtube" href="https://www.youtube.com/channel/UC7hLYa-wDea1W_-V1C8Yz0Q">YouTube</a></li>
					<li><a class="linkedin" href="https://www.linkedin.com/company/globalhealthuw/">LinkedIn</a></li>
					<li hidden class="hide"><a class="pinterest" href="https://www.pinterest.com/uofwa/">Pinterest</a></li>
					<li><a class="smugmug" href="https://uw-globalhealth.smugmug.com/">SmugMug</a></li>
				</ul>
			</nav>

			<nav aria-label="footer navigation global health">
				<ul class="footer-links">
					<li><a href="https://globalhealth.washington.edu/contact">Contact</a></li>
					<li><a href="https://globalhealth.washington.edu/support-us">Donate</a></li>
					<li><a href="https://globalhealth.washington.edu/about-us/jobs">Jobs</a></li>
					<li><a href="https://globalhealth.washington.edu/events">Events</a></li>
					<li><a href="https://globalhealth.washington.edu/news">News</a></li>
					<li><a href="https://globalhealth.washington.edu/intranet">Intranet</a></li>
					<li><?php UW_GlobalHealth::login_link(); ?></li>
				</ul>
			</nav>

			<nav aria-label="footer navigation uw">
				<ul class="footer-links">
					<li><a href="https://www.uw.edu/accessibility">Accessibility</a></li>
					<li hidden class="hide"><a href="https://uw.edu/contact">Contact Us</a></li>
					<li hidden class="hide"><a href="https://www.washington.edu/jobs">Jobs</a></li>
					<li><a href="https://www.washington.edu/safety">Campus Safety</a></li>
					<li><a href="https://my.uw.edu/">My UW</a></li>
					<li hidden class="hide"><a href="https://www.washington.edu/rules/wac">Rules Docket</a></li>
					<li><a href="https://www.washington.edu/online/privacy/">Privacy</a></li>
					<li><a href="https://www.washington.edu/online/terms/">Terms</a></li>
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
