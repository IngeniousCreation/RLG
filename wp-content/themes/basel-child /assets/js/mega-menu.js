/**
 * Custom Mega Menu JavaScript
 * Handles mobile menu interactions and accessibility
 * 
 * @package Basel Child
 * @since 1.0.0
 */

(function() {
	'use strict';
	
	// Wait for DOM to be ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initMegaMenu);
	} else {
		initMegaMenu();
	}
	
	function initMegaMenu() {
		const megaMenu = document.querySelector('.rlg-mega-menu');
		const overlay = document.querySelector('.rlg-mobile-menu-overlay');
		const menuItems = document.querySelectorAll('.rlg-menu-item.has-mega-menu');

		if (!megaMenu || !overlay) {
			return;
		}

		// Mobile menu toggle - use event delegation to handle dynamically created buttons
		document.addEventListener('click', function(e) {
			// Check if clicked element is the toggle button or inside it
			const toggleButton = e.target.closest('.rlg-mobile-menu-toggle');

			if (toggleButton) {
				e.preventDefault();
				e.stopPropagation();

				const isExpanded = toggleButton.getAttribute('aria-expanded') === 'true';

				// Update all toggle buttons (both normal and sticky header)
				const allToggles = document.querySelectorAll('.rlg-mobile-menu-toggle');
				allToggles.forEach(function(toggle) {
					toggle.setAttribute('aria-expanded', !isExpanded);
				});

				megaMenu.classList.toggle('active');
				overlay.classList.toggle('active');
				document.body.style.overflow = isExpanded ? '' : 'hidden';

				return;
			}
		});
		
		// Close menu when clicking overlay
		overlay.addEventListener('click', function() {
			closeMobileMenu();
		});

		// Close mobile menu on scroll
		let lastScrollTop = 0;
		window.addEventListener('scroll', function() {
			const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

			// Only close if menu is open and user has scrolled
			if (megaMenu.classList.contains('active') && Math.abs(scrollTop - lastScrollTop) > 10) {
				closeMobileMenu();
			}

			lastScrollTop = scrollTop;
		});

		// Function to close mobile menu
		function closeMobileMenu() {
			// Update all toggle buttons (query fresh each time to catch cloned buttons)
			const allToggles = document.querySelectorAll('.rlg-mobile-menu-toggle');
			allToggles.forEach(function(toggle) {
				toggle.setAttribute('aria-expanded', 'false');
			});

			megaMenu.classList.remove('active');
			overlay.classList.remove('active');
			document.body.style.overflow = '';

			// Close all open submenus
			menuItems.forEach(function(item) {
				item.classList.remove('active');
			});
		}
		
		// Mobile submenu toggle
		if (window.innerWidth <= 991) {
			menuItems.forEach(function(item) {
				const link = item.querySelector('.rlg-menu-link');
				
				link.addEventListener('click', function(e) {
					// Only prevent default on mobile
					if (window.innerWidth <= 991) {
						e.preventDefault();
						
						// Close other open items
						menuItems.forEach(function(otherItem) {
							if (otherItem !== item) {
								otherItem.classList.remove('active');
							}
						});
						
						// Toggle current item
						item.classList.toggle('active');
					}
				});
			});
		}
		
		// Handle window resize
		let resizeTimer;
		window.addEventListener('resize', function() {
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(function() {
				if (window.innerWidth > 991) {
					// Reset mobile menu state on desktop
					closeMobileMenu();
				}
			}, 250);
		});

		// Keyboard navigation
		document.addEventListener('keydown', function(e) {
			// Close menu on Escape key
			if (e.key === 'Escape') {
				const isMenuOpen = megaMenu.classList.contains('active');

				if (isMenuOpen) {
					closeMobileMenu();

					// Focus the first toggle button
					const firstToggle = document.querySelector('.rlg-mobile-menu-toggle');
					if (firstToggle) {
						firstToggle.focus();
					}
				}
			}
		});
		
		// Trap focus in mobile menu when open
		megaMenu.addEventListener('keydown', function(e) {
			if (e.key === 'Tab' && megaMenu.classList.contains('active')) {
				const focusableElements = megaMenu.querySelectorAll(
					'a[href], button:not([disabled])'
				);
				const firstElement = focusableElements[0];
				const lastElement = focusableElements[focusableElements.length - 1];
				
				if (e.shiftKey && document.activeElement === firstElement) {
					e.preventDefault();
					lastElement.focus();
				} else if (!e.shiftKey && document.activeElement === lastElement) {
					e.preventDefault();
					firstElement.focus();
				}
			}
		});
		
		// Accessibility: Announce menu state changes
		const announcer = document.createElement('div');
		announcer.setAttribute('role', 'status');
		announcer.setAttribute('aria-live', 'polite');
		announcer.setAttribute('aria-atomic', 'true');
		announcer.className = 'sr-only';
		announcer.style.cssText = 'position:absolute;left:-10000px;width:1px;height:1px;overflow:hidden;';
		document.body.appendChild(announcer);

		mobileToggle.addEventListener('click', function() {
			const isExpanded = this.getAttribute('aria-expanded') === 'true';
			announcer.textContent = isExpanded ? 'Menu opened' : 'Menu closed';
		});
	}

	/**
	 * Mobile AJAX Search
	 */
	function initMobileSearch() {
		const searchInput = document.querySelector('.rlg-mobile-search-input');
		const searchResults = document.querySelector('.rlg-mobile-search-results');

		if (!searchInput || !searchResults) {
			return;
		}

		let searchTimeout;

		searchInput.addEventListener('input', function() {
			const query = this.value.trim();

			// Clear previous timeout
			clearTimeout(searchTimeout);

			// Hide results if query is too short
			if (query.length < 3) {
				searchResults.classList.remove('active');
				searchResults.innerHTML = '';
				return;
			}

			// Debounce search
			searchTimeout = setTimeout(function() {
				performSearch(query, searchResults);
			}, 300);
		});

		// Close results when clicking outside
		document.addEventListener('click', function(e) {
			if (!e.target.closest('.rlg-mobile-search')) {
				searchResults.classList.remove('active');
			}
		});
	}

	/**
	 * Perform AJAX search
	 */
	function performSearch(query, resultsContainer) {
		// Show loading state
		resultsContainer.innerHTML = '<div style="padding: 15px; color: #888; text-align: center;">Searching...</div>';
		resultsContainer.classList.add('active');

		// Get AJAX URL from WordPress
		const ajaxUrl = '/wp-admin/admin-ajax.php';

		// Make AJAX request
		const xhr = new XMLHttpRequest();
		xhr.open('GET', ajaxUrl + '?action=basel_child_product_search&s=' + encodeURIComponent(query), true);

		xhr.onload = function() {
			if (xhr.status === 200) {
				try {
					const response = JSON.parse(xhr.responseText);
					if (response.success && response.data.html) {
						resultsContainer.innerHTML = response.data.html;
					} else {
						resultsContainer.innerHTML = '<div style="padding: 15px; color: #888; text-align: center;">No products found</div>';
					}
				} catch (e) {
					resultsContainer.innerHTML = '<div style="padding: 15px; color: #888; text-align: center;">Error loading results</div>';
				}
			} else {
				resultsContainer.innerHTML = '<div style="padding: 15px; color: #888; text-align: center;">Error loading results</div>';
			}
		};

		xhr.onerror = function() {
			resultsContainer.innerHTML = '<div style="padding: 15px; color: #888; text-align: center;">Error loading results</div>';
		};

		xhr.send();
	}

	// Initialize mobile search
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initMobileSearch);
	} else {
		initMobileSearch();
	}
})();
