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
		// member function
		sticky_header_menu_resize() {
			var maxheight = window.innerHeight - 75;	// 75 = 45(navbar height) + 30(buffer)
			if ( parseInt($(window).width()) < 768 ) {
				$(" div[id^='uw-wp-child-theme-sticky-header'] .navbar .container-fluid ").css({"max-height": maxheight+"px", "overflow-y": "scroll", "overflow-x": "clip"});
			} else {
				$(" div[id^='uw-wp-child-theme-sticky-header'] .navbar .container-fluid ").css({"max-height": "", "overflow-y": "", "overflow-x": ""});
			}
		},

	};

	// The document ready event executes when the HTML-Document is loaded
	// and the DOM is ready.
	$(document).ready(function( $ ) {
		
    $(window).on("scroll resize click", function(){
      uw_wp_child_theme_options.sticky_header_menu_resize();
    });
		
		uw_wp_child_theme_options.do_card_title_attribute();
		
	});

  // The window load event executes after the document ready event,
  // when the complete page is fully loaded.
  $(window).on('load', function () {
		// code
  });
	
})(jQuery, this, this.document);