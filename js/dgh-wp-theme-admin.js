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

		console.log( DGH_WP_Theme_Admin.faculty_home_page_id );
		// disable the "Faculty Home" option in the template list
		if ( DGH_WP_Theme_Admin.faculty_home_page_id ) {
			$(' input[value="templates/template-faculty.php"] ')
				.prop( 'disabled', true )
				.parent( 'p' ).css( 'color', 'grey' );
		}
		
	});

  // The window load event executes after the document ready event,
  // when the complete page is fully loaded.
  $(window).on('load', function () {
		// code
  });
	
})(jQuery, this, this.document);