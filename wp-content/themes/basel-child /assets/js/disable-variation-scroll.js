/**
 * Disable Auto Scroll on Variation Select
 * Prevents page from scrolling to top when selecting product variations
 */

(function($) {
    'use strict';

    // Override Basel settings immediately
    if (typeof basel_settings !== 'undefined') {
        basel_settings.swatches_scroll_top_mobile = 0;
        basel_settings.swatches_scroll_top_desktop = 0;
    }

    $(document).ready(function() {
        // Override settings again after DOM ready
        if (typeof basel_settings !== 'undefined') {
            basel_settings.swatches_scroll_top_mobile = 0;
            basel_settings.swatches_scroll_top_desktop = 0;
        }

        // Stop any scroll animations on variation events
        $(document).on('show_variation', '.variations_form', function(e) {
            $('html, body').stop(true, true);
        });

        $(document).on('hide_variation', '.variations_form', function(e) {
            $('html, body').stop(true, true);
        });

        // Prevent scroll on variation form change
        $('.variations_form').on('woocommerce_variation_select_change', function() {
            $('html, body').stop(true, true);
        });

        // Intercept any animate scrollTop calls
        var originalAnimate = $.fn.animate;
        $.fn.animate = function(properties, duration, easing, complete) {
            // If trying to animate scrollTop, prevent it
            if (properties && typeof properties.scrollTop !== 'undefined') {
                // Check if this is from variation selection
                var stack = new Error().stack;
                if (stack && (stack.indexOf('swatchesVariations') > -1 || stack.indexOf('show_variation') > -1)) {
                    // Don't animate, just return
                    return this;
                }
            }
            // Otherwise, call original animate
            return originalAnimate.call(this, properties, duration, easing, complete);
        };
    });

})(jQuery);

