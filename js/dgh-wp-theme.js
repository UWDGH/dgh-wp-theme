/**
 * jQuery should be accessed through $ by passing the jQuery object into an anonymous function.
 * @see {@link https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/#common-libraries}
 */
(function ($, window, document, undefined) {
	// At the top of the file, set "wp" to its existing value (if present)
	window.wp = window.wp || {};

	// object uw_wp_child_theme_options
	const uw_wp_child_theme_options = {

		// member function
		do_card_title_attribute() {
			// add card title attribute
			var data = {
				_ajax_nonce: UW_WP_Child_Theme.nonce, // nonce
				action: 'enable_card_title_attribute_ajax_callback', // This is the PHP function to call - note it must be hooked to AJAX
			};
			jQuery.post(UW_WP_Child_Theme.admin_ajax_url, data, function(response) {
				if ( response == true ) {
					$( ".card" ).each(function () {
						$(this).attr( 'title', $(this).find( ".card-title" ).text() );
					});
				}
			});

		},

	};

	// The document ready event executes when the HTML-Document is loaded
	// and the DOM is ready.
	$(document).ready(function( $ ) {
		
		uw_wp_child_theme_options.do_card_title_attribute();
		
	});

  // The window load event executes after the document ready event,
  // when the complete page is fully loaded.
  $(window).on('load', function () {
		// code
  });
	
})(jQuery, this, this.document);