/**
 * ============================================================================
 * CUSTOM DESKTOP SEARCH BAR JAVASCRIPT
 * ============================================================================
 * Handles AJAX search with wpdb->prepare queries
 * Shows max 3 results + "View All (count)" link
 */

(function($) {
	'use strict';

	console.log('🔍 RLG Custom Desktop Search: Loaded');

	var searchTimeout;
	var currentRequest;

	/**
	 * Initialize search functionality
	 */
	function initCustomSearch() {
		var $searchInput = $('.rlg-search-input');
		var $searchResults = $('.rlg-search-results');
		var $searchForm = $('.rlg-search-form');

		if (!$searchInput.length) {
			return;
		}

		// Handle input changes
		$searchInput.on('input', function() {
			var query = $(this).val().trim();

			// Clear previous timeout
			clearTimeout(searchTimeout);

			// Hide results if query is too short
			if (query.length < 2) {
				$searchResults.hide();
				return;
			}

			// Debounce search
			searchTimeout = setTimeout(function() {
				performSearch(query);
			}, 300);
		});

		// Handle form submission
		$searchForm.on('submit', function(e) {
			var query = $searchInput.val().trim();
			
			if (query.length < 2) {
				e.preventDefault();
				return false;
			}
		});

		// Close results when clicking outside
		$(document).on('click', function(e) {
			if (!$(e.target).closest('.rlg-desktop-search-bar').length) {
				$searchResults.hide();
			}
		});

		// Show results when input is focused and has value
		$searchInput.on('focus', function() {
			var query = $(this).val().trim();
			if (query.length >= 2 && $searchResults.find('.rlg-search-result-item').length > 0) {
				$searchResults.show();
			}
		});
	}

	/**
	 * Perform AJAX search
	 */
	function performSearch(query) {
		var $searchResults = $('.rlg-search-results');
		var $resultsInner = $('.rlg-search-results-inner');

		// Abort previous request
		if (currentRequest) {
			currentRequest.abort();
		}

		// Show loading state
		$resultsInner.html('<div class="rlg-search-loading">Searching...</div>');
		$searchResults.show();

		console.log('🔍 Searching for:', query);

		// Make AJAX request
		currentRequest = $.ajax({
			url: ajaxurl || '/wp-admin/admin-ajax.php',
			type: 'GET',
			data: {
				action: 'rlg_desktop_search',
				query: query
			},
			success: function(response) {
				console.log('🔍 Search response:', response);

				if (response.success && response.results.length > 0) {
					renderResults(response);
				} else {
					$resultsInner.html('<div class="rlg-no-results">No products found</div>');
				}
			},
			error: function(xhr, status, error) {
				if (status !== 'abort') {
					console.error('🔍 Search error:', error);
					$resultsInner.html('<div class="rlg-no-results">Search error. Please try again.</div>');
				}
			},
			complete: function() {
				currentRequest = null;
			}
		});
	}

	/**
	 * Render search results
	 */
	function renderResults(data) {
		var $resultsInner = $('.rlg-search-results-inner');
		var html = '';

		// Render product results
		data.results.forEach(function(product) {
			html += '<a href="' + product.url + '" class="rlg-search-result-item">';
			html += '	<div class="rlg-result-image">' + product.image + '</div>';
			html += '	<div class="rlg-result-content">';
			html += '		<h4 class="rlg-result-title">' + product.title + '</h4>';
			
			if (product.sku) {
				html += '	<div class="rlg-result-sku">SKU: ' + product.sku + '</div>';
			}
			
			html += '		<div class="rlg-result-price">' + product.price + '</div>';
			html += '	</div>';
			html += '</a>';
		});

		// Add "View All" link if there are more results
		if (data.show_view_all) {
			html += '<a href="' + data.search_url + '" class="rlg-view-all-link">';
			html += '	View All (' + data.total + ')';
			html += '</a>';
		}

		$resultsInner.html(html);
	}

	/**
	 * Initialize on document ready
	 */
	$(document).ready(function() {
		initCustomSearch();
		console.log('🔍 RLG Custom Desktop Search: Initialized');
	});

})(jQuery);

