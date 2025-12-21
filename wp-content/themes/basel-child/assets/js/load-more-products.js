/**
 * Load More Products - AJAX Functionality
 * 
 * @package Basel Child
 * @since 1.0.0
 */

(function($) {
    'use strict';

    var RLGLoadMore = {
        isLoading: false,

        init: function() {
            this.bindLoadMoreButton();
            this.updateProgressBar();
            console.log('RLG Load More: Initialized');
        },

        updateProgressBar: function() {
            var $showingInfo = $('.rlg-showing-info');
            if ($showingInfo.length) {
                var showingTo = parseInt($('.rlg-showing-to').text());
                var total = parseInt($('.rlg-showing-total').text());
                var progressPercent = (showingTo / total) * 100;
                $showingInfo.css('--progress-width', progressPercent + '%');
            }
        },

        bindLoadMoreButton: function() {
            var self = this;

            $(document).on('click', '.rlg-load-more-btn', function(e) {
                e.preventDefault();

                if (self.isLoading) {
                    console.log('RLG Load More: Already loading...');
                    return;
                }

                var $button = $(this);
                var page = parseInt($button.data('page'));
                var maxPages = parseInt($button.data('max-pages'));
                var category = $button.data('category');
                var minPrice = $button.data('min-price');
                var maxPrice = $button.data('max-price');
                var stockStatus = $button.data('stock-status');
                var orderby = $button.data('orderby');
                var perPage = parseInt($button.data('per-page'));
                var total = parseInt($button.data('total'));

                console.log('RLG Load More: Loading page ' + page + ' of ' + maxPages);

                self.loadMoreProducts({
                    page: page,
                    category: category,
                    min_price: minPrice,
                    max_price: maxPrice,
                    stock_status: stockStatus,
                    orderby: orderby
                }, $button, maxPages, perPage, total);
            });
        },

        loadMoreProducts: function(data, $button, maxPages, perPage, total) {
            var self = this;

            self.isLoading = true;

            // Hide button, show loading
            $button.hide();
            $('.rlg-load-more-loading').show();

            $.ajax({
                url: rlgLoadMore.ajaxurl,
                type: 'POST',
                data: {
                    action: 'rlg_load_more_products',
                    nonce: rlgLoadMore.nonce,
                    page: data.page,
                    category: data.category,
                    min_price: data.min_price,
                    max_price: data.max_price,
                    stock_status: data.stock_status,
                    orderby: data.orderby
                },
                success: function(response) {
                    console.log('RLG Load More: Success', response);

                    if (response.success && response.data.html) {
                        // Append new products to grid
                        $('.rlg-products-row').append(response.data.html);

                        // Update button data
                        var nextPage = response.data.current_page + 1;
                        $button.data('page', nextPage);

                        // Update showing info
                        var showingTo = Math.min(response.data.current_page * perPage, total);
                        $('.rlg-showing-to').text(showingTo);

                        // Update progress bar
                        self.updateProgressBar();

                        // Hide loading
                        $('.rlg-load-more-loading').hide();

                        // Check if there are more pages
                        if (response.data.has_more) {
                            $button.show();
                        } else {
                            // No more products
                            $button.remove();
                            $('.rlg-load-more-loading').html('<p class="rlg-no-more-products">No more products to load</p>').show();
                        }
                    } else {
                        console.error('RLG Load More: Invalid response', response);
                        $('.rlg-load-more-loading').hide();
                        $button.show();
                    }

                    self.isLoading = false;
                },
                error: function(xhr, status, error) {
                    console.error('RLG Load More: AJAX Error', error);
                    $('.rlg-load-more-loading').hide();
                    $button.show();
                    self.isLoading = false;
                }
            });
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        RLGLoadMore.init();
    });

})(jQuery);

