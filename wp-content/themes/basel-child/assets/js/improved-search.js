/**
 * ============================================================================
 * IMPROVED HEADER SEARCH JAVASCRIPT
 * ============================================================================
 * Handles custom rendering for "View All" link
 * NOTE: This script works with Basel's existing autocomplete
 */

(function($) {
	'use strict';

	console.log('🔍 RLG Improved Search: Script loaded');

	// The PHP handler will return the results
	// Basel's autocomplete will render them automatically
	// We just need to handle the "View All" click

	/**
	 * Handle "View All" link clicks
	 */
	$(document).on('click', '.autocomplete-suggestions .view-all-suggestion', function(e) {
		console.log('🔍 View All clicked');

		// Find the search form and submit it
		var $form = $('form.basel-ajax-search').first();

		if ($form.length) {
			$form.submit();
		}
	});

	/**
	 * Log when search is triggered (for debugging)
	 */
	$(document).on('focus', 'form.basel-ajax-search input[type="text"]', function() {
		console.log('🔍 Search input focused - AJAX should trigger on typing');
	});

	$(document).on('input', 'form.basel-ajax-search input[type="text"]', function() {
		console.log('🔍 Search input changed: ' + $(this).val());
	});

})(jQuery);

