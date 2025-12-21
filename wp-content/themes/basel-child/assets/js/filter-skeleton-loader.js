/**
 * Filter Loading Indicator
 * Simple PJAX-only loading indicator
 *
 * @package Basel Child
 * @since 1.0.9
 */

(function($) {
    'use strict';

    var RLGLoadingIndicator = {

        init: function() {
            var self = this;

            // Create loading overlay
            this.createOverlay();

            // Bind PJAX events
            if (typeof $.fn.pjax !== 'undefined') {
                this.bindPjaxEvents();
            }
        },

        createOverlay: function() {
            // Remove existing overlay if any
            $('.rlg-filter-loading-overlay').remove();

            var loadingHTML =
                '<div class="rlg-filter-loading-overlay">' +
                    '<div class="rlg-loading-content">' +
                        '<div class="rlg-spinner"></div>' +
                        '<p class="rlg-loading-text">Loading Products...</p>' +
                    '</div>' +
                '</div>';

            $('body').append(loadingHTML);
            console.log('RLG Loading: Overlay created');
        },

        show: function() {
            console.log('RLG Loading: Showing overlay');

            // Make sure overlay exists
            if (!$('.rlg-filter-loading-overlay').length) {
                this.createOverlay();
            }

            $('.rlg-filter-loading-overlay').addClass('active');
            console.log('RLG Loading: Active class added');
        },

        hide: function() {
            console.log('RLG Loading: Hiding overlay');
            $('.rlg-filter-loading-overlay').removeClass('active');
        },

        bindPjaxEvents: function() {
            var self = this;

            console.log('RLG Loading: Binding PJAX events');

            // Show on PJAX send
            $(document).on('pjax:send', function() {
                console.log('RLG Loading: PJAX send event');
                self.show();
            });

            // Hide on PJAX complete
            $(document).on('pjax:complete', function() {
                console.log('RLG Loading: PJAX complete event');
                setTimeout(function() {
                    self.hide();
                }, 300);
            });

            // Hide on PJAX error
            $(document).on('pjax:error', function() {
                console.log('RLG Loading: PJAX error event');
                self.hide();
            });
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        // DISABLED - Using custom-ajax-filters.js loading overlay instead
        // RLGLoadingIndicator.init();
        console.log('RLG Loading: Disabled - using custom-ajax-filters.js overlay');
    });

})(jQuery);

