/**
 * jQuery should be accessed through $ by passing the jQuery object into an anonymous function.
 * @see {@link https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/#common-libraries}
 */
(function ($, window, document, undefined) {
	// At the top of the file, set "wp" to its existing value (if present)
	window.wp = window.wp || {};

	// The document ready event executes when the HTML-Document is loaded
	// and the DOM is ready.
	$(document).ready(function( $ ) {

		/**
		 * Stopgap measure
		 * test for arbiratry xss injections in the querystring, remove querystring and redirect
		 */
		const dirty = ['<','%3C','<script>','%3Cscript%3E','%3E%3C','><','%3Cimg','<img'];
		dirty.forEach((dirt) => {
			if ( location.search.indexOf(dirt) !== -1 )  {
				el = document.createElement('div');
				el.setAttribute('style', 'background-color: #f8d7da;');
				el.innerHTML = 'Lets not do this!';
				document.body.prepend( el );
				window.location = window.location.href.replace(location.search, '');
			}
		});

		//a11y: indicate the current presented list page
		$( '.faculty-pagination a.btn.primary' )
			.attr( 'aria-current', true );
		//a11y: add list role to the cards grid
		$( '#faculty-cards' )
			.attr( 'role', 'list' )
			.attr( 'aria-describedby', 'faculty-cards-description');
		//a11y: add listitem role to each su card container
		$( '.su-post-faculty-card' )
			// .attr( 'tabindex', '0' )
			.attr( 'role', 'listitem' );
		
	});

	// The window load event executes after the document ready event,
	// when the complete page is fully loaded.
	$(window).on('load', function () {
		// nothing to do
	});
	
})(jQuery, this, this.document);