/**
 * Mobile Filter Sidebar
 * 
 * @package Basel Child
 * @since 1.0.0
 */

(function($) {
    'use strict';

    var RLGMobileFilters = {
        $sidebar: null,
        $overlay: null,
        $body: null,

        init: function() {
            this.$sidebar = $('.sidebar-container');
            this.$body = $('body');

            console.log('RLG Mobile Filters: Sidebar found:', this.$sidebar.length);
            console.log('RLG Mobile Filters: Sidebar classes:', this.$sidebar.attr('class'));

            // Create overlay if it doesn't exist
            if ($('.rlg-mobile-sidebar-overlay').length === 0) {
                this.$overlay = $('<div class="rlg-mobile-sidebar-overlay"></div>');
                this.$body.append(this.$overlay);
            } else {
                this.$overlay = $('.rlg-mobile-sidebar-overlay');
            }

            this.bindEvents();
            console.log('RLG Mobile Filters: Initialized');
        },

        bindEvents: function() {
            var self = this;

            // Open filter sidebar
            $(document).on('click', '#rlgMobileFilterBtn', function(e) {
                e.preventDefault();
                self.openSidebar();
            });

            // Close via close button
            $(document).on('click', '.basel-close-sidebar-btn', function(e) {
                e.preventDefault();
                self.closeSidebar();
            });

            // Close via overlay click
            this.$overlay.on('click', function() {
                self.closeSidebar();
            });

            // Close on ESC key
            $(document).on('keydown', function(e) {
                if (e.keyCode === 27 && self.$sidebar.hasClass('mobile-active')) {
                    self.closeSidebar();
                }
            });
        },

        openSidebar: function() {
            var self = this;
            console.log('RLG Mobile Filters: Opening sidebar...');
            console.log('RLG Mobile Filters: Sidebar element:', this.$sidebar);
            console.log('RLG Mobile Filters: Sidebar display:', this.$sidebar.css('display'));
            console.log('RLG Mobile Filters: Sidebar visibility:', this.$sidebar.css('visibility'));

            // Use native DOM to set cssText with !important (jQuery can't do this)
            this.$sidebar.each(function() {
                this.style.cssText = 'display: block !important; visibility: visible !important; opacity: 1 !important;';
            });

            // Add active class
            this.$sidebar.addClass('mobile-active');
            this.$overlay.addClass('active');
            this.$body.css('overflow', 'hidden');

            console.log('RLG Mobile Filters: Sidebar opened');
            console.log('RLG Mobile Filters: Sidebar left position:', this.$sidebar.css('left'));
            console.log('RLG Mobile Filters: Sidebar display after:', this.$sidebar.css('display'));
            console.log('RLG Mobile Filters: Sidebar style attr:', this.$sidebar.attr('style'));
        },

        closeSidebar: function() {
            this.$sidebar.removeClass('mobile-active');
            this.$overlay.removeClass('active');
            this.$body.css('overflow', '');
            console.log('RLG Mobile Filters: Sidebar closed');
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        RLGMobileFilters.init();
    });

    // Read More functionality for category description
    $(document).on('click', '.rlg-read-more-btn', function(e) {
        e.preventDefault();
        var $btn = $(this);
        var $preview = $btn.siblings('.rlg-description-preview');
        var $full = $btn.siblings('.rlg-description-full');

        if ($full.is(':visible')) {
            // Show preview, hide full
            $preview.slideDown();
            $full.slideUp();
            $btn.text('Read More');
            $btn.removeClass('active');
        } else {
            // Show full, hide preview
            $preview.slideUp();
            $full.slideDown();
            $btn.text('Read Less');
            $btn.addClass('active');
        }
    });

})(jQuery);

