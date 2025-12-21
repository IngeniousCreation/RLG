/**
 * Custom AJAX Filters
 * Handles AJAX filtering for custom sidebar filters
 * 
 * @package Basel Child
 * @since 1.0.0
 */

(function($) {
    'use strict';

    var RLGAjaxFilters = {

        init: function() {
            var self = this;

            console.log('RLG AJAX Filters: Initializing');

            // Check if PJAX is available
            if (typeof $.fn.pjax === 'undefined') {
                console.log('RLG AJAX Filters: PJAX not available, filters will use page reload');
                return;
            }

            // Check if Basel AJAX shop is enabled
            if (!$('body').hasClass('basel-ajax-shop-on')) {
                console.log('RLG AJAX Filters: Basel AJAX shop is disabled');
                return;
            }

            console.log('RLG AJAX Filters: AJAX mode enabled');

            // Unbind Basel's handlers immediately and continuously
            this.disableBaselPriceFilter();

            // Initialize price sliders on page load
            this.initPriceSliders();

            this.bindCustomPriceFilter();
            this.bindAvailabilityFilter();
            this.bindCategoryFilter();

            // Rebind after PJAX to prevent Basel from taking over
            $(document).on('pjax:complete', function() {
                console.log('RLG AJAX Filters: PJAX complete - rebinding filters');
                self.disableBaselPriceFilter();
                self.initPriceSliders(); // Reinitialize sliders after AJAX
                // DON'T hide loading here - it's handled in renderCallback
            });

            // Also hide on PJAX end (backup) - only if render callback failed
            $(document).on('pjax:end', function() {
                console.log('RLG AJAX Filters: PJAX end');
                // DON'T hide loading here - it's handled in renderCallback
            });

            // Hide on PJAX error
            $(document).on('pjax:error', function() {
                console.log('RLG AJAX Filters: PJAX error - hiding loading');
                self.hideLoading();
            });
        },

        /**
         * Disable Basel's price filter handler
         */
        disableBaselPriceFilter: function() {
            // Unbind all Basel handlers
            $(document).off('click', '.widget_price_filter form .button');
            $('.widget_price_filter form .button').off('click');

            console.log('RLG AJAX Filters: Basel price filter handlers disabled');
        },

        /**
         * Bind custom price filter (100% custom - no WooCommerce widget)
         */
        bindCustomPriceFilter: function() {
            var self = this;

            // Initialize price sliders
            this.initPriceSliders();

            // Bind filter button click
            $(document).on('click', '.rlg-price-filter-button', function(e) {
                e.preventDefault();
                e.stopPropagation();

                console.log('RLG AJAX Filters: ===== BUTTON CLICKED =====');

                var $button = $(this);
                var baseUrl = $button.data('url');
                var minPrice = $('.rlg-price-input-min').val();
                var maxPrice = $('.rlg-price-input-max').val();

                console.log('RLG AJAX Filters: Custom price filter clicked');
                console.log('RLG AJAX Filters: Min Price: ' + minPrice);
                console.log('RLG AJAX Filters: Max Price: ' + maxPrice);

                // Show loading IMMEDIATELY
                console.log('RLG AJAX Filters: About to show loading...');
                self.showLoading();
                console.log('RLG AJAX Filters: showLoading() called');

                // Build URL with price parameters
                var url = baseUrl;
                var separator = url.indexOf('?') !== -1 ? '&' : '?';
                url += separator + 'min_price=' + minPrice + '&max_price=' + maxPrice;

                console.log('RLG AJAX Filters: Filter URL: ' + url);

                // Small delay to ensure overlay renders
                setTimeout(function() {
                    console.log('RLG AJAX Filters: Starting PJAX...');
                    // Trigger PJAX
                    self.loadProducts(url);
                }, 100);

                return false;
            });

            console.log('RLG AJAX Filters: Custom price filter bound');
        },

        /**
         * Initialize price range sliders
         */
        initPriceSliders: function() {
            var self = this; // Store reference to RLGAjaxFilters

            console.log('RLG AJAX Filters: Initializing price sliders...');

            var $minSlider = $('#minInput');
            var $maxSlider = $('#maxInput');
            var $minInput = $('.rlg-price-input-min');
            var $maxInput = $('.rlg-price-input-max');
            var $trackFill = $('#trackFill');
            var $minValue = $('#minValue');
            var $maxValue = $('#maxValue');

            console.log('RLG AJAX Filters: Min slider:', $minSlider.length);
            console.log('RLG AJAX Filters: Max slider:', $maxSlider.length);
            console.log('RLG AJAX Filters: Track fill:', $trackFill.length);
            console.log('RLG AJAX Filters: Min value span:', $minValue.length);
            console.log('RLG AJAX Filters: Max value span:', $maxValue.length);

            if (!$minSlider.length || !$maxSlider.length) {
                console.log('RLG AJAX Filters: No sliders found, exiting');
                return;
            }

            var minRange = parseInt($minSlider.attr('data-min'));
            var maxRange = parseInt($minSlider.attr('data-max'));

            console.log('RLG AJAX Filters: Price range:', minRange, '-', maxRange);
            console.log('RLG AJAX Filters: Initial min value:', $minSlider.val());
            console.log('RLG AJAX Filters: Initial max value:', $maxSlider.val());

            // Update track fill and price display
            function updateTrack() {
                var minVal = parseInt($minSlider.val());
                var maxVal = parseInt($maxSlider.val());

                // Prevent overlap
                if (minVal > maxVal - 1) {
                    minVal = maxVal - 1;
                    $minSlider.val(minVal);
                }

                // Update hidden inputs
                $minInput.val(minVal);
                $maxInput.val(maxVal);

                // Update price display text
                $minValue.text(minVal);
                $maxValue.text(maxVal);

                // Update track fill
                var minPercent = ((minVal - minRange) / (maxRange - minRange)) * 100;
                var maxPercent = ((maxVal - minRange) / (maxRange - minRange)) * 100;

                $trackFill.css({
                    'left': minPercent + '%',
                    'right': (100 - maxPercent) + '%'
                });
            }

            // Slider events - update display while dragging
            $minSlider.on('input', function() {
                if (parseInt($(this).val()) > parseInt($maxSlider.val()) - 1) {
                    $(this).val(parseInt($maxSlider.val()) - 1);
                }
                updateTrack();
            });

            $maxSlider.on('input', function() {
                if (parseInt($(this).val()) < parseInt($minSlider.val()) + 1) {
                    $(this).val(parseInt($minSlider.val()) + 1);
                }
                updateTrack();
            });

            // Apply filter when slider is released
            $minSlider.on('change', function() {
                console.log('RLG AJAX Filters: Min slider released, applying filter');
                self.applyPriceFilter();
            });

            $maxSlider.on('change', function() {
                console.log('RLG AJAX Filters: Max slider released, applying filter');
                self.applyPriceFilter();
            });

            // Initial update
            updateTrack();

            console.log('RLG AJAX Filters: Price sliders initialized successfully');

            console.log('RLG AJAX Filters: Price sliders initialized');
        },

        /**
         * Apply price filter
         */
        applyPriceFilter: function() {
            // Prevent duplicate calls
            if (this.isLoading) {
                console.log('RLG AJAX Filters: Already loading, ignoring duplicate call');
                return;
            }

            var minPrice = $('.rlg-price-input-min').val();
            var maxPrice = $('.rlg-price-input-max').val();
            var $button = $('.rlg-price-filter-button');
            var baseUrl = $button.data('url');

            console.log('RLG AJAX Filters: Applying price filter:', minPrice, '-', maxPrice);

            // Build URL with price parameters
            var url = baseUrl;
            var separator = url.indexOf('?') !== -1 ? '&' : '?';
            url += separator + 'min_price=' + minPrice + '&max_price=' + maxPrice;

            console.log('RLG AJAX Filters: Filter URL:', url);

            // Load products
            this.loadProducts(url);
        },
        
        /**
         * Bind availability filter clicks
         */
        bindAvailabilityFilter: function() {
            var self = this;
            
            $(document).on('click', '.rlg-availability-list a.rlg-ajax-filter', function(e) {
                e.preventDefault();
                
                var $link = $(this);
                var url = $link.attr('href');
                
                console.log('RLG AJAX Filters: Availability filter clicked - ' + url);
                
                // Update active state
                $link.closest('ul').find('li').removeClass('active');
                $link.closest('li').addClass('active');
                
                // Trigger PJAX
                self.loadProducts(url);
                
                return false;
            });
        },
        
        /**
         * Bind category filter clicks
         */
        bindCategoryFilter: function() {
            var self = this;
            
            $(document).on('click', '.rlg-categories-list a.rlg-ajax-filter-category, .rlg-category-tree a.rlg-ajax-filter-category', function(e) {
                e.preventDefault();
                
                var $link = $(this);
                var url = $link.attr('href');
                
                console.log('RLG AJAX Filters: Category filter clicked - ' + url);
                
                // Trigger PJAX
                self.loadProducts(url);
                
                return false;
            });
        },
        
        /**
         * Load products via PJAX
         */
        loadProducts: function(url) {
            // Prevent duplicate calls
            if (this.isLoading) {
                console.log('RLG AJAX Filters: Already loading, aborting duplicate request');
                return;
            }

            console.log('RLG AJAX Filters: Loading products via PJAX - ' + url);

            var self = this;

            // Set loading flag
            this.isLoading = true;

            // Show loading overlay immediately BEFORE pjax starts
            this.showLoading();

            console.log('RLG AJAX Filters: Overlay should be visible now');

            // Start PJAX immediately (no delay needed)
            $.pjax({
                container: '.site-content',
                fragment: '.site-content',
                timeout: 30000,
                url: url,
                scrollTo: false,
                push: true,
                replace: false,
                renderCallback: function(context, html, afterRender) {
                    console.log('RLG AJAX Filters: Render callback - processing HTML');

                    // Extract only the .site-content from the response
                    var $html = $('<div>').html(html);
                    var $siteContent = $html.find('.site-content');

                    if ($siteContent.length) {
                        console.log('RLG AJAX Filters: Found .site-content in response');
                        var cleanHtml = $siteContent.html();

                        // Remove duplicated styles (Basel theme function)
                        if (typeof baselThemeModule !== 'undefined' && typeof baselThemeModule.removeDuplicatedStylesFromHTML === 'function') {
                            baselThemeModule.removeDuplicatedStylesFromHTML(cleanHtml, function(finalHtml) {
                                context.html(finalHtml);
                                afterRender();

                                // Reinitialize shop page
                                if (typeof baselThemeModule.shopPageInit === 'function') {
                                    baselThemeModule.shopPageInit();
                                }

                                // Trigger images loaded
                                $(document).trigger('basel-images-loaded');

                                // Hide loading overlay and reset flag
                                self.hideLoading();
                                self.isLoading = false;
                            });
                        } else {
                            context.html(cleanHtml);
                            afterRender();

                            // Hide loading overlay and reset flag
                            self.hideLoading();
                            self.isLoading = false;
                        }
                    } else {
                        console.log('RLG AJAX Filters: .site-content not found, using full HTML');
                        context.html(html);
                        afterRender();

                        // Hide loading overlay and reset flag
                        self.hideLoading();
                        self.isLoading = false;
                    }
                }
            }).fail(function() {
                // Reset loading flag on error
                self.isLoading = false;
            });
        },

        /**
         * Show loading overlay - Modern design (only on product area)
         */
        showLoading: function() {
            console.log('RLG AJAX Filters: ========== SHOWING LOADING OVERLAY ==========');

            // Remove any existing overlay first
            $('.rlg-filter-loading-overlay').remove();

            // Find the product content area
            var $productArea = $('.site-content.shop-content-area');

            if ($productArea.length === 0) {
                console.log('RLG AJAX Filters: Product area not found, using body');
                $productArea = $('body');
            }

            // Make sure the product area has position relative
            if ($productArea.css('position') === 'static') {
                $productArea.css('position', 'relative');
            }

            // Modern loading overlay with dots animation
            var loadingHTML = '<div class="rlg-filter-loading-overlay" style="' +
                'display: flex !important; ' +
                'position: absolute !important; ' +
                'top: 0 !important; ' +
                'left: 0 !important; ' +
                'right: 0 !important; ' +
                'bottom: 0 !important; ' +
                'width: 100% !important; ' +
                'min-height: 500px !important; ' +
                'background: rgba(255, 255, 255, 0.75) !important; ' +
                'backdrop-filter: blur(4px) !important; ' +
                'z-index: 999 !important; ' +
                'justify-content: center !important; ' +
                'align-items: flex-start !important; ' +
                'padding-top: 100px !important; ' +
                'opacity: 1 !important; ' +
                'visibility: visible !important; ' +
                'pointer-events: all !important;' +
                '">' +
                    '<div class="rlg-loading-content" style="' +
                        'text-align: center;' +
                    '">' +
                        // Three dots loader
                        '<div class="rlg-dots-loader" style="' +
                            'display: flex; ' +
                            'gap: 12px; ' +
                            'justify-content: center; ' +
                            'margin-bottom: 24px;' +
                        '">' +
                            '<div class="rlg-dot" style="' +
                                'width: 16px; ' +
                                'height: 16px; ' +
                                'background: #715242; ' +
                                'border-radius: 50%; ' +
                                'animation: rlg-bounce 1.4s infinite ease-in-out both; ' +
                                'animation-delay: -0.32s;' +
                            '"></div>' +
                            '<div class="rlg-dot" style="' +
                                'width: 16px; ' +
                                'height: 16px; ' +
                                'background: #715242; ' +
                                'border-radius: 50%; ' +
                                'animation: rlg-bounce 1.4s infinite ease-in-out both; ' +
                                'animation-delay: -0.16s;' +
                            '"></div>' +
                            '<div class="rlg-dot" style="' +
                                'width: 16px; ' +
                                'height: 16px; ' +
                                'background: #715242; ' +
                                'border-radius: 50%; ' +
                                'animation: rlg-bounce 1.4s infinite ease-in-out both;' +
                            '"></div>' +
                        '</div>' +
                        '<p class="rlg-loading-text" style="' +
                            'margin: 0; ' +
                            'font-size: 16px; ' +
                            'font-weight: 500; ' +
                            'color: #333; ' +
                            'letter-spacing: 0.5px;' +
                        '">Loading Products</p>' +
                    '</div>' +
                '</div>';

            $productArea.append(loadingHTML);

            console.log('RLG AJAX Filters: Overlay appended to product area');
            console.log('RLG AJAX Filters: Overlay count:', $('.rlg-filter-loading-overlay').length);
        },

        /**
         * Hide loading overlay
         */
        hideLoading: function() {
            console.log('RLG AJAX Filters: ========== HIDING LOADING OVERLAY ==========');
            var $overlay = $('.rlg-filter-loading-overlay');

            // Fade out
            $overlay.css('opacity', '0');

            // Remove after animation
            setTimeout(function() {
                $overlay.remove();
                console.log('RLG AJAX Filters: Overlay removed');
            }, 300);
        }
    };

    // Override Basel's price filter handler before it loads
    if (typeof baselThemeModule !== 'undefined') {
        // Store original woocommercePriceSlider function
        var originalWoocommercePriceSlider = baselThemeModule.woocommercePriceSlider;

        // Override it to prevent Basel from binding click handler
        baselThemeModule.woocommercePriceSlider = function() {
            // Call original function to initialize slider
            if (typeof originalWoocommercePriceSlider === 'function') {
                originalWoocommercePriceSlider.call(baselThemeModule);
            }

            // Immediately unbind Basel's click handler
            $(document).off('click', '.widget_price_filter form .button');

            console.log('RLG AJAX Filters: Overridden Basel woocommercePriceSlider');
        };
    }

    // Initialize on document ready
    $(document).ready(function() {
        RLGAjaxFilters.init();

        // Continuously monitor and disable Basel's handler
        setInterval(function() {
            $(document).off('click', '.widget_price_filter form .button');
        }, 100);
    });

    // Reinitialize after PJAX complete
    $(document).on('pjax:complete', function() {
        console.log('RLG AJAX Filters: Reinitializing after PJAX');
        // Hide loading overlay
        RLGAjaxFilters.hideLoading();
        // No need to reinit, event handlers are on document
    });

    // Prevent page reload on PJAX timeout
    $(document).on('pjax:timeout', function(event) {
        console.log('RLG AJAX Filters: PJAX timeout - preventing page reload');
        event.preventDefault(); // Prevent default page reload
        // Hide loading overlay
        RLGAjaxFilters.hideLoading();
    });

    // Handle PJAX errors
    $(document).on('pjax:error', function(xhr, textStatus, error, options) {
        console.log('RLG AJAX Filters: PJAX error - ' + textStatus);
        console.log('RLG AJAX Filters: Error details - ' + error);

        // Hide loading overlay
        RLGAjaxFilters.hideLoading();

        // Don't reload page on error
        return false;
    });

})(jQuery);

