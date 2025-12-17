<?php

/**
 * Fix for deprecated dynamic property warnings in Basel Theme (PHP 8.2+)
 */
if (!function_exists('rlg_suppress_deprecated_warnings')) {
    function rlg_suppress_deprecated_warnings() {
        error_reporting(E_ALL & ~E_DEPRECATED);
    }
    add_action('init', 'rlg_suppress_deprecated_warnings', 1);
}

/**
 * Fix for Google Listings plugin fatal error
 * Prevents null $post from being passed to bulk_edit_hook
 */
if (!function_exists('rlg_fix_google_listings_null_post')) {
    function rlg_fix_google_listings_null_post() {
        // Remove Google Listings bulk edit hook to prevent fatal error
        if (class_exists('Automattic\WooCommerce\GoogleListingsAndAds\Admin\BulkEdit\BulkEditInitializer')) {
            remove_action('save_post', array(
                'Automattic\WooCommerce\GoogleListingsAndAds\Admin\BulkEdit\BulkEditInitializer',
                'bulk_edit_hook'
            ), 10);
        }
    }
    add_action('init', 'rlg_fix_google_listings_null_post', 999);
}

/**
 * Include static homepage configuration
 */
require_once get_stylesheet_directory() . '/inc/static-homepage-config.php';

add_action( 'wp_enqueue_scripts', 'basel_child_enqueue_styles', 1000 );

function basel_child_enqueue_styles() {
	$version = basel_get_theme_info( 'Version' );

	if( basel_get_opt( 'minified_css' ) ) {
		wp_enqueue_style( 'basel-style', get_template_directory_uri() . '/style.min.css', array('bootstrap'), $version );
	} else {
		wp_enqueue_style( 'basel-style', get_template_directory_uri() . '/style.css', array('bootstrap'), $version );
	}

    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array('bootstrap'), $version );

    // Enqueue custom mega menu styles
    wp_enqueue_style( 'rlg-mega-menu', get_stylesheet_directory_uri() . '/assets/css/mega-menu.css', array(), $version );

    // Enqueue custom mega menu script
    wp_enqueue_script( 'rlg-mega-menu', get_stylesheet_directory_uri() . '/assets/js/mega-menu.js', array(), $version, true );
}

/**
 * ============================================================================
 * CUSTOM MEGA MENU INTEGRATION
 * ============================================================================
 */

// Include mega menu configuration and template
require_once get_stylesheet_directory() . '/includes/mega-menu-config.php';
require_once get_stylesheet_directory() . '/includes/mega-menu-template.php';

/**
 * ============================================================================
 * CUSTOM PRODUCT TABS
 * ============================================================================
 */

// Remove parent theme tab functions to avoid conflicts
remove_filter('woocommerce_product_tabs', 'customize_woocommerce_product_tabs');
remove_filter('woocommerce_product_tabs', 'add_custom_product_tabs');

/**
 * Customize WooCommerce product tabs
 */
function rlg_customize_product_tabs($tabs) {
    global $product;

    // 1. DESCRIPTION TAB - Only show if product has description
    if (isset($tabs['description'])) {
        $description = $product->get_description();
        if (empty($description)) {
            unset($tabs['description']);
        } else {
            $tabs['description']['priority'] = 10;
            $tabs['description']['callback'] = 'rlg_custom_description_tab_content';
        }
    }

    // Remove additional information tab (we renamed it to description)
    if (isset($tabs['additional_information'])) {
        unset($tabs['additional_information']);
    }

    // 2. ORDER PROCESS TAB
    $tabs['order_process'] = array(
        'title'    => __('Order Process', 'basel-child'),
        'priority' => 20,
        'callback' => 'rlg_order_process_tab_content',
    );

    // 3. SHIPPING & RETURNS TAB
    $tabs['shipping_returns'] = array(
        'title'    => __('Shipping & Returns', 'basel-child'),
        'priority' => 30,
        'callback' => 'rlg_shipping_returns_tab_content',
    );

    // Remove reviews tab if needed (optional)
    // unset($tabs['reviews']);

    return $tabs;
}
add_filter('woocommerce_product_tabs', 'rlg_customize_product_tabs', 98);

/**
 * Order Process tab content
 */
function rlg_order_process_tab_content() {
    echo '<h2>Our Order Process</h2>';
    echo '<img src="https://realleathergarments.co.uk/wp-content/uploads/2024/11/ORDER-QUE-01.png" alt="Order Process" style="max-width: 100%; height: auto;">';
}

/**
 * Shipping & Returns tab content
 */
function rlg_shipping_returns_tab_content() {
    ?>
    <div class="rlg-shipping-returns-content">
        <h3>Return & Refund Policy</h3>

        <div class="rlg-policy-section">
            <h4>Eligibility & Timeframe</h4>
            <ul>
                <li><strong>Refunds:</strong> Available within 72 hours of receiving your order</li>
                <li><strong>Returns:</strong> 5 days from receipt to decide if the item is right for you</li>
                <li><strong>Custom Items:</strong> Non-refundable if wrong size is selected - please ensure accurate measurements</li>
            </ul>
        </div>

        <div class="rlg-policy-section">
            <h4>Return Conditions</h4>
            <ul>
                <li>Item must be unused, unworn, and in original packaging</li>
                <li>Original tags must be attached</li>
                <li>Valid receipt or proof of purchase required</li>
                <li>Return note must be enclosed with the item</li>
            </ul>
        </div>

        <div class="rlg-policy-section">
            <h4>Return Process</h4>
            <ol>
                <li>Contact us at <a href="mailto:contact@realleathergarments.co.uk">contact@realleathergarments.co.uk</a> within the timeframe</li>
                <li>Provide your order number and reason for return</li>
                <li>Ship the item back using trackable shipping (customer's responsibility)</li>
                <li>We'll inspect and notify you of approval/rejection</li>
            </ol>
        </div>

        <div class="rlg-policy-section">
            <h4>Refund Processing</h4>
            <ul>
                <li><strong>UK Orders:</strong> Processed within 5 working days of receipt</li>
                <li><strong>International Orders:</strong> Processed within 12 working days of receipt</li>
                <li>Refund applied to original payment method within 20 days</li>
                <li>Original delivery charge deducted unless item is faulty</li>
            </ul>
        </div>

        <div class="rlg-policy-section">
            <h4>Important Notes</h4>
            <ul>
                <li>Customer responsible for return shipping costs</li>
                <li>We recommend using trackable shipping or insurance</li>
                <li>Items damaged during return shipping may receive partial/no refund</li>
                <li>Returns after 72-hour window not eligible for refund</li>
                <li>Faulty or damaged items: Full refund/exchange provided</li>
            </ul>
        </div>

        <div class="rlg-policy-section">
            <h4>Cancellations</h4>
            <p>You may cancel an order before dispatch by emailing <a href="mailto:contact@realleathergarments.co.uk">contact@realleathergarments.co.uk</a> with your order number, account name, and address.</p>
        </div>

        <div class="rlg-policy-contact">
            <p><strong>Questions?</strong> Contact our support team at <a href="mailto:contact@realleathergarments.co.uk">contact@realleathergarments.co.uk</a></p>
        </div>
    </div>
    <?php
}

/**
 * Enqueue product tabs CSS with high priority
 */
function rlg_enqueue_product_tabs_css() {
    if (is_product()) {
        wp_enqueue_style(
            'rlg-product-tabs',
            get_stylesheet_directory_uri() . '/assets/css/product-tabs.css',
            array('basel-style'),
            filemtime(get_stylesheet_directory() . '/assets/css/product-tabs.css')
        );
    }
}
add_action('wp_enqueue_scripts', 'rlg_enqueue_product_tabs_css', 999);

/**
 * Enqueue custom footer CSS
 */
function rlg_enqueue_footer_css() {
    wp_enqueue_style(
        'rlg-footer',
        get_stylesheet_directory_uri() . '/assets/css/footer.css',
        array('basel-style'),
        filemtime(get_stylesheet_directory() . '/assets/css/footer.css')
    );
}
add_action('wp_enqueue_scripts', 'rlg_enqueue_footer_css', 999);

/**
 * Custom description tab content - shows long description with filtered content
 */
function rlg_custom_description_tab_content() {
    global $product;

    // Get the long description
    $description = $product->get_description();

    // Remove the unwanted message
    $description = str_replace("Thank you for reading this post, don't forget to subscribe!", '', $description);
    $description = str_replace('Thank you for reading this post, don&#8217;t forget to subscribe!', '', $description);

    // Output the description (WooCommerce adds the wrapper automatically)
    echo wp_kses_post($description);
}

/**
 * ============================================================================
 * CUSTOM JACKET FORM
 * ============================================================================
 */

// Include custom jacket form handler (email API)
require_once get_stylesheet_directory() . '/includes/custom-jacket-form-handler.php';

// Include custom jacket form display (button and modal)
require_once get_stylesheet_directory() . '/includes/custom-jacket-form-display.php';

/**
 * Override Basel's main navigation function with custom mega menu
 * This replaces the default basel_header_block_main_nav() function
 */
function basel_header_block_main_nav() {
	?>
	<div class="main-nav site-navigation basel-navigation menu-<?php echo esc_attr( basel_get_opt('menu_align') ); ?>" role="navigation">
		<?php
		// Render the mega menu (desktop)
		basel_child_render_mega_menu();

		// Render mobile overlay
		basel_child_render_mobile_menu_overlay();
		?>
	</div><!--END MAIN-NAV-->
	<?php
}

/**
 * Override Basel's mobile icon function to use our custom hamburger
 * This function is called by Basel's header generator in the right-column
 */
function basel_header_block_mobile_icon() {
	// Render our custom hamburger button
	basel_child_render_mobile_menu_toggle();
}

/**
 * ============================================================================
 * AJAX PRODUCT SEARCH FOR MOBILE MENU
 * ============================================================================
 */

/**
 * AJAX handler for mobile product search
 */
function basel_child_ajax_product_search() {
	// Check if search query exists
	if ( ! isset( $_GET['s'] ) || empty( $_GET['s'] ) ) {
		wp_send_json_error( array( 'message' => 'No search query provided' ) );
	}

	$search_query = sanitize_text_field( $_GET['s'] );

	// Query products
	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => 5,
		's'              => $search_query,
		'post_status'    => 'publish',
	);

	$products = new WP_Query( $args );

	if ( ! $products->have_posts() ) {
		wp_send_json_success( array(
			'html' => '<div style="padding: 15px; color: #888; text-align: center;">No products found</div>'
		) );
	}

	$html = '';

	while ( $products->have_posts() ) {
		$products->the_post();
		global $product;

		$product_id    = get_the_ID();
		$product_title = get_the_title();
		$product_url   = get_permalink();
		$product_image = get_the_post_thumbnail_url( $product_id, 'thumbnail' );
		$product_price = $product->get_price_html();

		$html .= '<a href="' . esc_url( $product_url ) . '" class="rlg-search-result-item">';

		if ( $product_image ) {
			$html .= '<img src="' . esc_url( $product_image ) . '" alt="' . esc_attr( $product_title ) . '" class="rlg-search-result-image">';
		}

		$html .= '<div class="rlg-search-result-info">';
		$html .= '<div class="rlg-search-result-title">' . esc_html( $product_title ) . '</div>';

		if ( $product_price ) {
			$html .= '<div class="rlg-search-result-price">' . $product_price . '</div>';
		}

		$html .= '</div>';
		$html .= '</a>';
	}

	wp_reset_postdata();

	wp_send_json_success( array( 'html' => $html ) );
}

// Register AJAX handlers for both logged-in and non-logged-in users
add_action( 'wp_ajax_basel_child_product_search', 'basel_child_ajax_product_search' );
add_action( 'wp_ajax_nopriv_basel_child_product_search', 'basel_child_ajax_product_search' );

// Remove the default short description action
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);

// Add the short description action at a different priority (e.g., 41)
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 39);


function add_inline_styles() {
    // Check if it's a single product page
    if (is_product()) {
        global $product;

        // Ensure $product is a valid WooCommerce product object
        if (!is_object($product) || !is_a($product, 'WC_Product')) {
            $product = wc_get_product(get_the_ID());
        }

        // Check if the product is external
        if ($product && is_object($product) && $product->is_type('external')) {
            // Add your inline styles
            echo '<style>
                /* Your custom styles go here */
                .scfw-size-chart-main {
                    position: initial !important;
                }
            </style>';
        }
    }
}

add_action('wp_head', 'add_inline_styles');



add_filter( 'woocommerce_product_review_comment_meta', 'hide_review_date', 10, 2 );
function hide_review_date( $comment_meta, $comment ) {
    // Find and replace the comment date HTML with an empty string
    $comment_meta = preg_replace('/<time[^>]+class="woocommerce-review__published-date"[^>]*>.*?<\/time>/i', '', $comment_meta);
    return $comment_meta;
}



/* Remove the default WooCommerce 3 JSON/LD structured data */
function remove_output_structured_data() {
  remove_action( 'wp_footer', array( WC()->structured_data, 'output_structured_data' ), 10 ); // This removes structured data from all frontend pages
  remove_action( 'woocommerce_email_order_details', array( WC()->structured_data, 'output_email_structured_data' ), 30 ); // This removes structured data from all Emails sent by WooCommerce
}
//add_action( 'init', 'remove_output_structured_data' );
















// Email automation code - DISABLED (was running on every page load causing slowness)
// This code should be moved to a proper WooCommerce hook like 'woocommerce_order_status_processing'
// Uncomment and fix if needed:

/*
function send_order_size_confirmation_email( $order_id ) {
    $order = wc_get_order( $order_id );

    if ( ! $order ) {
        return;
    }

    $items = $order->get_items();

    foreach ( $items as $item ) {
        $product_id = $item->get_variation_id();
        $variation_data = wc_get_product_variation_attributes( $product_id );
        $selected_size = isset( $variation_data['attribute_pa_size'] ) ? $variation_data['attribute_pa_size'] : '';
        $customer_email = $order->get_billing_email();

        switch ($selected_size) {
            case 'XXS':
            case 'XS':
            case 'S':
            case 'M':
                $email_template = "Dear Customer,

I hope this email finds you well. We are thrilled to inform you that your order for the Bloodshot Vin Diesel Black Jacket in size {$selected_size} has been successfully received and is currently being processed.

Please review the size details below:
Chest measurement: 34 inches
Sleeve length: 25 inches
Waist measurement: 30 inches

If there are any changes or specific instructions regarding the size, feel free to let us know.

Best regards,
Real Leather Garments";
                break;

            default:
                $email_template = "Dear Customer,

We are unable to process your order at this time. Please contact customer support for assistance.

Best regards,
Real Leather Garments";
                break;
        }

        $subject = 'Order Confirmation - Size ' . $selected_size;
        $headers = 'From: yourstore@example.com' . "\r\n" .
            'Reply-To: yourstore@example.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail('admin@realleathergarments.co.uk', $subject, $email_template, $headers);
    }
}
// Uncomment this line to enable the email automation on order processing:
// add_action( 'woocommerce_order_status_processing', 'send_order_size_confirmation_email' );
*/











add_action('woocommerce_product_query', 'custom_modify_product_query');

function custom_modify_product_query($query) {
    if (is_admin() || !is_tax('product_cat')) {
        return;
    }

    // Set the orderby parameter to 'rating' to order products by rating
    $query->set('orderby', 'rating');
    $query->set('order', 'DESC'); // Order products in descending order (highest rating first)
}










function custom_category_sidebar_shortcode() {
    if ( is_product_category() ) { 
        $current_category = get_queried_object();
        $category_slug = $current_category->slug; // Get the slug of the current category

        ob_start(); // Start output buffering

        // Check if it's exactly the Men's category or any child category of Men
        if ( $category_slug == 'men' || term_is_ancestor_of( get_term_by( 'slug', 'men', 'product_cat' ), $current_category, 'product_cat' ) ) {
            echo do_shortcode('[elementor-template id="35525"]'); // Men Template ID
        } 
        // Check if it's exactly the Women's category or any child category of Women
        elseif ( $category_slug == 'women' || term_is_ancestor_of( get_term_by( 'slug', 'women', 'product_cat' ), $current_category, 'product_cat' ) ) {
            echo do_shortcode('[elementor-template id="35532"]'); // Women Template ID
        } 
        // Fallback for other categories (if needed)
        else {
            echo '<p>No specific template found for this category.</p>';
        }

        return ob_get_clean(); // Return the buffered content
    }
}
add_shortcode( 'custom_category_sidebar', 'custom_category_sidebar_shortcode' );



function enqueue_font_awesome() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
}
//add_action('wp_enqueue_scripts', 'enqueue_font_awesome');


/**
 * ========================================
 * LAZY LOAD FONTS ON USER INTERACTION
 * ========================================
 * Defers font loading until user interacts with the page
 * (scroll, mousemove, click, touchstart, keydown)
 */

// Global variable to store font URLs
global $basel_lazy_font_urls;
$basel_lazy_font_urls = array();

/**
 * Capture font URLs before dequeuing
 */
function basel_child_capture_font_urls() {
    global $wp_styles, $basel_lazy_font_urls;

    // Initialize array
    $basel_lazy_font_urls = array();

    // Capture Google Fonts URL
    if ( isset( $wp_styles->registered['xts-google-fonts'] ) ) {
        $basel_lazy_font_urls['google_fonts'] = $wp_styles->registered['xts-google-fonts']->src;
    }

    // Capture Typekit URL
    if ( isset( $wp_styles->registered['basel-typekit'] ) ) {
        $basel_lazy_font_urls['typekit'] = $wp_styles->registered['basel-typekit']->src;
    }

    // Capture Font Awesome URLs
    if ( isset( $wp_styles->registered['vc_font_awesome_5'] ) ) {
        $basel_lazy_font_urls['font_awesome'] = $wp_styles->registered['vc_font_awesome_5']->src;
    }

    if ( isset( $wp_styles->registered['vc_font_awesome_5_shims'] ) ) {
        $basel_lazy_font_urls['font_awesome_shims'] = $wp_styles->registered['vc_font_awesome_5_shims']->src;
    }

    // Capture CDN Font Awesome if exists
    if ( isset( $wp_styles->registered['font-awesome'] ) ) {
        $basel_lazy_font_urls['font_awesome_cdn'] = $wp_styles->registered['font-awesome']->src;
    }
}
add_action( 'wp_enqueue_scripts', 'basel_child_capture_font_urls', 50000 );

/**
 * Dequeue all font-related styles to prevent initial load
 */
function basel_child_dequeue_fonts() {
    // Dequeue Google Fonts
    wp_dequeue_style( 'xts-google-fonts' );
    wp_deregister_style( 'xts-google-fonts' );

    // Dequeue Typekit
    wp_dequeue_style( 'basel-typekit' );
    wp_deregister_style( 'basel-typekit' );

    // Dequeue Font Awesome (all variants)
    wp_dequeue_style( 'vc_font_awesome_5' );
    wp_deregister_style( 'vc_font_awesome_5' );
    wp_dequeue_style( 'vc_font_awesome_5_shims' );
    wp_deregister_style( 'vc_font_awesome_5_shims' );
    wp_dequeue_style( 'yith-wcwl-font-awesome' );
    wp_deregister_style( 'yith-wcwl-font-awesome' );
    wp_dequeue_style( 'font-awesome' );
    wp_deregister_style( 'font-awesome' );
    wp_dequeue_style( 'fontawesome' );
    wp_deregister_style( 'fontawesome' );

    // Dequeue plugin font CSS files - try multiple hooks
    wp_dequeue_style( 'xoo-wsc-fonts' ); // Side Cart fonts
    wp_deregister_style( 'xoo-wsc-fonts' );

    // Dequeue Google Site Kit CSS (contains Google Sans font)
    wp_dequeue_style( 'googlesitekit-admin-css' );
    wp_deregister_style( 'googlesitekit-admin-css' );

    // Dequeue Google Merchant Center / Shopping scripts
    wp_dequeue_script( 'google-shopping-merchant-api' );
    wp_deregister_script( 'google-shopping-merchant-api' );
    wp_dequeue_script( 'google-merchant-center' );
    wp_deregister_script( 'google-merchant-center' );
}
add_action( 'wp_enqueue_scripts', 'basel_child_dequeue_fonts', 99999 );

/**
 * Remove Google Merchant Center iframe
 */
function basel_child_remove_google_merchant_iframe() {
    ?>
    <script>
    // Remove Google Merchant Center iframe on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Remove any Google Shopping/Merchant iframes
        var removeGoogleIframes = function() {
            var iframes = document.querySelectorAll('iframe[src*="google.com/shopping"], iframe[src*="merchantverse"]');
            iframes.forEach(function(iframe) {
                console.log('🚫 Removing Google Merchant iframe:', iframe.src);
                iframe.remove();
            });
        };

        // Remove immediately
        removeGoogleIframes();

        // Also check after a delay (in case they load later)
        setTimeout(removeGoogleIframes, 1000);
        setTimeout(removeGoogleIframes, 3000);

        // Watch for new iframes being added
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.tagName === 'IFRAME' && node.src && (node.src.includes('google.com/shopping') || node.src.includes('merchantverse'))) {
                        console.log('🚫 Blocking Google Merchant iframe:', node.src);
                        node.remove();
                    }
                });
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
    </script>
    <?php
}
add_action( 'wp_footer', 'basel_child_remove_google_merchant_iframe', 1 );

/**
 * Remove Side Cart fonts with even higher priority
 */
function basel_child_remove_sidecart_fonts() {
    wp_dequeue_style( 'xoo-wsc-fonts' );
    wp_deregister_style( 'xoo-wsc-fonts' );
}
add_action( 'wp_enqueue_scripts', 'basel_child_remove_sidecart_fonts', 999999 );
add_action( 'wp_print_styles', 'basel_child_remove_sidecart_fonts', 999999 );

/**
 * Remove font preload links from header
 */
function basel_child_remove_font_preloads() {
    remove_action( 'wp_head', 'basel_font_icon_preload' );
}
add_action( 'init', 'basel_child_remove_font_preloads', 1 );

/**
 * Remove @font-face declarations from dynamic CSS
 * We'll load them via JavaScript on user interaction
 */
function basel_child_remove_icon_fonts_from_css( $css ) {
    // Remove all @font-face declarations
    $css = preg_replace( '/@font-face\s*\{[^}]+\}/s', '', $css );
    return $css;
}
add_filter( 'basel_get_all_theme_settings_css', 'basel_child_remove_icon_fonts_from_css', 999 );

/**
 * Add critical CSS to prevent FOUT (Flash of Unstyled Text)
 */
function basel_child_font_loading_css() {
    ?>
    <style id="basel-font-loading-css">
        /* Use system fonts until custom fonts load */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        /* Add font-display: swap for better performance when fonts do load */
        @supports (font-display: swap) {
            * {
                font-display: swap;
            }
        }

        /* Prevent layout shift */
        body.fonts-loading {
            visibility: visible;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'basel_child_font_loading_css', 1 );

/**
 * Enqueue lazy load fonts script
 */
function basel_child_enqueue_lazy_load_fonts_script() {
    global $basel_lazy_font_urls;

    // Enqueue the external JS file
    wp_enqueue_script(
        'basel-lazy-load-fonts',
        get_stylesheet_directory_uri() . '/assets/js/lazy-load-fonts.js',
        array(),
        '1.0.2',
        true // Load in footer
    );

    // Prepare font URLs array
    $font_urls = ! empty( $basel_lazy_font_urls ) ? $basel_lazy_font_urls : array();

    // Add theme URL and version for icon fonts
    $font_urls['theme_url'] = BASEL_THEME_DIR;
    $font_urls['version'] = basel_get_theme_info( 'Version' );

    // Pass font URLs to the script
    wp_localize_script(
        'basel-lazy-load-fonts',
        'baselLazyFontUrls',
        $font_urls
    );
}
add_action( 'wp_enqueue_scripts', 'basel_child_enqueue_lazy_load_fonts_script', 999999 );

/**
 * ============================================================================
 * PERFORMANCE OPTIMIZATION: Reduce HTTP Request Timeout
 * ============================================================================
 */

/**
 * Reduce HTTP request timeout to prevent slow external API calls from blocking page load
 */
function basel_child_reduce_http_timeout( $timeout ) {
    return 5; // 5 seconds instead of default 30 seconds
}
add_filter( 'http_request_timeout', 'basel_child_reduce_http_timeout' );

/**
 * Disable external HTTP requests in admin for faster admin panel
 * Only allow critical domains
 */
function basel_child_limit_admin_http_requests( $pre, $args, $url ) {
    // Allow these critical domains
    $allowed_domains = array(
        'api.wordpress.org',
        'downloads.wordpress.org',
        'localhost',
    );

    // Check if URL is from allowed domain
    foreach ( $allowed_domains as $domain ) {
        if ( strpos( $url, $domain ) !== false ) {
            return $pre; // Allow the request
        }
    }

    // Block all other external requests in admin
    if ( is_admin() ) {
        return new WP_Error( 'http_request_blocked', 'External HTTP request blocked for performance' );
    }

    return $pre;
}
add_filter( 'pre_http_request', 'basel_child_limit_admin_http_requests', 10, 3 );

/**
 * Disable plugin/theme update checks on every page load
 */
function basel_child_disable_update_checks() {
    remove_action( 'load-update-core.php', 'wp_update_plugins' );
    remove_action( 'load-plugins.php', 'wp_update_plugins' );
    remove_action( 'load-update.php', 'wp_update_plugins' );
    remove_action( 'wp_update_plugins', 'wp_update_plugins' );
    remove_action( 'load-themes.php', 'wp_update_themes' );
}
add_action( 'admin_init', 'basel_child_disable_update_checks' );

/**
 * Block slow external requests on frontend
 */
function basel_child_block_slow_frontend_requests( $pre, $args, $url ) {
    // Skip if in admin
    if ( is_admin() ) {
        return $pre;
    }

    // Block known slow external services on frontend
    $blocked_domains = array(
        'google-analytics.com',
        'googletagmanager.com',
        'facebook.com',
        'facebook.net',
        'connect.facebook.net',
        'graph.facebook.com',
        'doubleclick.net',
        'google.com/recaptcha',
        'gstatic.com',
        'fonts.googleapis.com',
        'fonts.gstatic.com',
    );

    foreach ( $blocked_domains as $domain ) {
        if ( strpos( $url, $domain ) !== false ) {
            // Return empty response instead of making the request
            return array(
                'headers'  => array(),
                'body'     => '',
                'response' => array(
                    'code'    => 200,
                    'message' => 'OK',
                ),
                'cookies'  => array(),
                'filename' => null,
            );
        }
    }

    return $pre;
}
add_filter( 'pre_http_request', 'basel_child_block_slow_frontend_requests', 5, 3 );





function wa_load_more_toggle_script() {
    ?>
    <script>
    // Runs as soon as this script is parsed (no need for onload/DOMContentLoaded)
    document.addEventListener('click', function(event) {
        // Support clicks on the element OR anything inside it
        var trigger = event.target.closest ? event.target.closest('#load-more-content') : null;
        if (!trigger) return; // Click was not on the load-more element

        event.preventDefault();

        var secElem = document.getElementById('show-more-text');
        if (secElem) {
            secElem.classList.toggle('show-content');
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'wa_load_more_toggle_script', 100);


/**
 * ============================================================================
 * CUSTOM SIZE CHART FUNCTIONALITY - CENTRALIZED MANAGEMENT
 * ============================================================================
 */

// Disable Basel theme's built-in size guide to avoid conflicts
remove_action('woocommerce_single_product_summary', 'basel_sguide_display', 38);

// Add admin menu for centralized size chart management
function rlg_add_size_chart_admin_menu() {
    add_submenu_page(
        'edit.php?post_type=product',
        'Size Charts',
        'Size Charts',
        'manage_woocommerce',
        'rlg-size-charts',
        'rlg_size_chart_admin_page'
    );
}
add_action('admin_menu', 'rlg_add_size_chart_admin_menu');

// Centralized admin page for managing size charts
function rlg_size_chart_admin_page() {
    // Handle adding new size chart
    if (isset($_POST['rlg_add_size_chart']) && check_admin_referer('rlg_size_charts_action', 'rlg_size_charts_nonce')) {
        $image_id = isset($_POST['size_chart_image']) ? sanitize_text_field($_POST['size_chart_image']) : '';
        $selected_categories = isset($_POST['size_chart_categories']) ? array_map('intval', $_POST['size_chart_categories']) : array();

        if (!empty($image_id) && !empty($selected_categories)) {
            $assigned_count = 0;
            $already_assigned = array();

            foreach ($selected_categories as $term_id) {
                // Check if category already has a size chart
                $existing_image = get_term_meta($term_id, 'rlg_size_chart_image', true);

                if ($existing_image && $existing_image != $image_id) {
                    // Category already has a different size chart
                    $term = get_term($term_id);
                    $already_assigned[] = $term->name;
                } else {
                    // Assign the size chart
                    update_term_meta($term_id, 'rlg_size_chart_image', $image_id);
                    $assigned_count++;
                }
            }

            if ($assigned_count > 0) {
                echo '<div class="notice notice-success is-dismissible"><p>Size chart assigned to ' . $assigned_count . ' categor' . ($assigned_count > 1 ? 'ies' : 'y') . ' successfully!</p></div>';
            }

            if (!empty($already_assigned)) {
                echo '<div class="notice notice-warning is-dismissible"><p><strong>Warning:</strong> The following categories already have a size chart and were skipped: ' . implode(', ', $already_assigned) . '. Please remove their existing size chart first if you want to reassign.</p></div>';
            }

            if ($assigned_count == 0 && empty($already_assigned)) {
                echo '<div class="notice notice-error is-dismissible"><p>Please select an image and at least one category.</p></div>';
            }
        } else {
            echo '<div class="notice notice-error is-dismissible"><p>Please select an image and at least one category.</p></div>';
        }
    }

    // Handle deleting size chart
    if (isset($_POST['rlg_delete_size_chart']) && check_admin_referer('rlg_size_charts_action', 'rlg_size_charts_nonce')) {
        $delete_categories = isset($_POST['delete_categories']) ? array_map('intval', $_POST['delete_categories']) : array();

        if (!empty($delete_categories)) {
            foreach ($delete_categories as $term_id) {
                delete_term_meta($term_id, 'rlg_size_chart_image');
            }
            echo '<div class="notice notice-success is-dismissible"><p>Size chart removed from ' . count($delete_categories) . ' categor' . (count($delete_categories) > 1 ? 'ies' : 'y') . '!</p></div>';
        }
    }

    // Get all product categories
    $all_categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => false,
        'orderby' => 'name',
        'order' => 'ASC'
    ));

    // Get categories with size charts
    $categories_with_charts = array();
    foreach ($all_categories as $category) {
        $image_id = get_term_meta($category->term_id, 'rlg_size_chart_image', true);
        if ($image_id) {
            $categories_with_charts[] = array(
                'category' => $category,
                'image_id' => $image_id,
                'image_url' => wp_get_attachment_url($image_id)
            );
        }
    }

    ?>
    <div class="wrap">
        <h1>Manage Size Charts</h1>
        <p>Upload a size chart image and assign it to multiple categories at once.</p>

        <!-- Add New Size Chart Form -->
        <div class="rlg-size-chart-add-form" style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; margin-bottom: 20px;">
            <h2>Add New Size Chart</h2>
            <form method="post" action="">
                <?php wp_nonce_field('rlg_size_charts_action', 'rlg_size_charts_nonce'); ?>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label>Size Chart Image</label>
                        </th>
                        <td>
                            <div id="rlg-size-chart-image-wrapper">
                                <img id="rlg-size-chart-image-preview" src="" style="max-width: 300px; max-height: 200px; display: none; border: 1px solid #ddd; padding: 5px; margin-bottom: 10px;" />
                            </div>
                            <input type="hidden" id="rlg-size-chart-image-id" name="size_chart_image" value="" />
                            <button type="button" class="button button-secondary" id="rlg-upload-size-chart-btn">
                                <span class="dashicons dashicons-upload" style="vertical-align: middle;"></span> Upload Image
                            </button>
                            <button type="button" class="button" id="rlg-remove-size-chart-btn" style="display: none;">
                                <span class="dashicons dashicons-no" style="vertical-align: middle;"></span> Remove
                            </button>
                            <p class="description">Upload the size chart image that will be displayed to customers.</p>
                            <p class="description" style="margin-top: 10px;">
                                <strong>Or enter Attachment ID directly:</strong>
                                <input type="number" id="rlg-manual-attachment-id" placeholder="e.g., 46804" style="width: 100px; margin-left: 5px;" />
                                <button type="button" class="button button-small" id="rlg-load-attachment-btn" style="margin-left: 5px;">Load Image</button>
                                <br><small>Tip: The size chart image ID is <strong>46804</strong></small>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label>Assign to Categories</label>
                        </th>
                        <td>
                            <select name="size_chart_categories[]" id="rlg-size-chart-categories" multiple style="width: 100%; height: 200px;">
                                <?php foreach ($all_categories as $category) :
                                    $has_size_chart = get_term_meta($category->term_id, 'rlg_size_chart_image', true);
                                    $is_disabled = !empty($has_size_chart);
                                ?>
                                    <option value="<?php echo esc_attr($category->term_id); ?>" <?php echo $is_disabled ? 'disabled style="color: #999; background: #f5f5f5;"' : ''; ?>>
                                        <?php
                                        echo esc_html($category->name);
                                        if ($category->parent) {
                                            $parent = get_term($category->parent, 'product_cat');
                                            if ($parent && !is_wp_error($parent)) {
                                                echo ' (Parent: ' . esc_html($parent->name) . ')';
                                            }
                                        }
                                        echo ' - ' . $category->count . ' product' . ($category->count != 1 ? 's' : '');
                                        if ($is_disabled) {
                                            echo ' [Already Assigned]';
                                        }
                                        ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description">Hold Ctrl (Windows) or Cmd (Mac) to select multiple categories. The same size chart will be assigned to all selected categories.</p>
                            <p class="description"><strong>Note:</strong> Categories marked as <em>[Already Assigned]</em> are disabled because they already have a size chart. Remove their existing size chart first to reassign.</p>
                        </td>
                    </tr>
                </table>

                <p class="submit">
                    <input type="submit" name="rlg_add_size_chart" class="button button-primary" value="Assign Size Chart to Selected Categories" />
                </p>
            </form>
        </div>

        <!-- Current Size Charts -->
        <h2>Current Size Charts (<?php echo count($categories_with_charts); ?>)</h2>

        <?php if (!empty($categories_with_charts)) : ?>
            <form method="post" action="">
                <?php wp_nonce_field('rlg_size_charts_action', 'rlg_size_charts_nonce'); ?>

                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th style="width: 40px;">
                                <input type="checkbox" id="rlg-select-all" />
                            </th>
                            <th style="width: 30%;">Category</th>
                            <th style="width: 50%;">Size Chart Image</th>
                            <th style="width: 20%;">Products</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories_with_charts as $item) :
                            $category = $item['category'];
                            $image_url = $item['image_url'];
                        ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="delete_categories[]" value="<?php echo esc_attr($category->term_id); ?>" class="rlg-category-checkbox" />
                            </td>
                            <td>
                                <strong><?php echo esc_html($category->name); ?></strong>
                                <?php if ($category->parent) :
                                    $parent = get_term($category->parent, 'product_cat');
                                    if ($parent && !is_wp_error($parent)) :
                                ?>
                                    <br><small style="color: #666;">Parent: <?php echo esc_html($parent->name); ?></small>
                                <?php
                                    endif;
                                endif;
                                ?>
                            </td>
                            <td>
                                <img src="<?php echo esc_url($image_url); ?>" style="max-width: 200px; max-height: 100px; border: 1px solid #ddd; padding: 5px;" />
                            </td>
                            <td>
                                <?php echo $category->count; ?> product<?php echo $category->count != 1 ? 's' : ''; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <p class="submit">
                    <input type="submit" name="rlg_delete_size_chart" class="button button-secondary" value="Remove Size Chart from Selected Categories" onclick="return confirm('Are you sure you want to remove size charts from selected categories?');" />
                </p>
            </form>
        <?php else : ?>
            <p>No size charts assigned yet. Use the form above to add your first size chart.</p>
        <?php endif; ?>
    </div>

    <style>
        .rlg-size-chart-add-form {
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
        }
        .rlg-size-chart-add-form h2 {
            margin-top: 0;
        }
        #rlg-size-chart-categories {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
        }
        .wp-list-table img {
            display: block;
        }
    </style>

    <script type="text/javascript">
    jQuery(document).ready(function($) {
        var file_frame;

        // Upload button click
        $('#rlg-upload-size-chart-btn').on('click', function(e) {
            e.preventDefault();

            if (file_frame) {
                file_frame.open();
                return;
            }

            file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Select Size Chart Image',
                button: {
                    text: 'Use this image',
                },
                multiple: false
            });

            file_frame.on('select', function() {
                var attachment = file_frame.state().get('selection').first().toJSON();
                $('#rlg-size-chart-image-id').val(attachment.id);
                $('#rlg-size-chart-image-preview').attr('src', attachment.url).show();
                $('#rlg-remove-size-chart-btn').show();
            });

            file_frame.open();
        });

        // Remove button click
        $('#rlg-remove-size-chart-btn').on('click', function(e) {
            e.preventDefault();
            $('#rlg-size-chart-image-id').val('');
            $('#rlg-size-chart-image-preview').attr('src', '').hide();
            $(this).hide();
        });

        // Load attachment by ID
        $('#rlg-load-attachment-btn').on('click', function(e) {
            e.preventDefault();
            var attachmentId = $('#rlg-manual-attachment-id').val();

            if (!attachmentId) {
                alert('Please enter an attachment ID');
                return;
            }

            // Use WordPress AJAX to get attachment details
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'get_attachment_url',
                    attachment_id: attachmentId
                },
                success: function(response) {
                    if (response.success) {
                        $('#rlg-size-chart-image-id').val(attachmentId);
                        $('#rlg-size-chart-image-preview').attr('src', response.data.url).show();
                        $('#rlg-remove-size-chart-btn').show();
                        $('#rlg-manual-attachment-id').val('');
                    } else {
                        alert('Error: ' + response.data.message);
                    }
                },
                error: function() {
                    alert('Failed to load attachment. Please try again.');
                }
            });
        });

        // Select all checkbox
        $('#rlg-select-all').on('change', function() {
            $('.rlg-category-checkbox').prop('checked', $(this).prop('checked'));
        });

        // Individual checkbox
        $('.rlg-category-checkbox').on('change', function() {
            if (!$(this).prop('checked')) {
                $('#rlg-select-all').prop('checked', false);
            }
        });
    });
    </script>
    <?php
}

// AJAX handler to get attachment URL by ID
add_action('wp_ajax_get_attachment_url', 'rlg_get_attachment_url_ajax');
function rlg_get_attachment_url_ajax() {
    $attachment_id = isset($_POST['attachment_id']) ? intval($_POST['attachment_id']) : 0;

    if (!$attachment_id) {
        wp_send_json_error(array('message' => 'Invalid attachment ID'));
    }

    $url = wp_get_attachment_url($attachment_id);

    if (!$url) {
        wp_send_json_error(array('message' => 'Attachment not found'));
    }

    wp_send_json_success(array('url' => $url));
}

// Display size chart button on product page after price
function rlg_display_size_chart_button() {
    global $product;

    if (!$product) {
        return;
    }

    // Get product categories
    $terms = wp_get_post_terms($product->get_id(), 'product_cat');

    if (empty($terms) || is_wp_error($terms)) {
        return;
    }

    // Check each category for a size chart image
    $size_chart_image_id = null;
    foreach ($terms as $term) {
        $image_id = get_term_meta($term->term_id, 'rlg_size_chart_image', true);
        if ($image_id) {
            $size_chart_image_id = $image_id;
            break; // Use the first category with a size chart
        }
    }

    if (!$size_chart_image_id) {
        return;
    }

    $size_chart_image_url = wp_get_attachment_url($size_chart_image_id);

    if (!$size_chart_image_url) {
        return;
    }

    ?>
    <div class="rlg-size-chart-button-wrapper">
        <button type="button" class="rlg-size-chart-button" data-image="<?php echo esc_url($size_chart_image_url); ?>">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M2 2h12v12H2V2zm1 1v10h10V3H3zm1 1h8v1H4V4zm0 2h8v1H4V6zm0 2h8v1H4V8zm0 2h5v1H4v-1z"/>
            </svg>
            <?php _e('Size Chart', 'basel-child'); ?>
        </button>
    </div>

    <!-- Size Chart Modal -->
    <div id="rlg-size-chart-modal" class="rlg-size-chart-modal" style="display: none;">
        <div class="rlg-size-chart-modal-overlay"></div>
        <div class="rlg-size-chart-modal-content">
            <button type="button" class="rlg-size-chart-modal-close">&times;</button>
            <img src="<?php echo esc_url($size_chart_image_url); ?>" alt="<?php _e('Size Chart', 'basel-child'); ?>" />
        </div>
    </div>
    <?php
}
// Add after price (priority 12 is after the default price at priority 10)
add_action('woocommerce_single_product_summary', 'rlg_display_size_chart_button', 12);

// Enqueue size chart styles and scripts
function rlg_enqueue_size_chart_assets() {
    if (!is_product()) {
        return;
    }

    // Inline CSS for size chart
    wp_add_inline_style('child-style', '
        .rlg-size-chart-button-wrapper {
            margin: 15px 0;
        }

        .rlg-size-chart-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: #333;
            transition: all 0.3s ease;
        }

        .rlg-size-chart-button:hover {
            background: #f5f5f5;
            border-color: #999;
        }

        .rlg-size-chart-button svg {
            width: 16px;
            height: 16px;
        }

        .rlg-size-chart-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 999999;
        }

        .rlg-size-chart-modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
        }

        .rlg-size-chart-modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 90vw;
            max-height: 90vh;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            overflow: auto;
        }

        .rlg-size-chart-modal-content img {
            display: block;
            width: auto;
            height: auto;
            max-width: 100%;
            max-height: calc(90vh - 80px);
            margin: 0 auto;
            object-fit: contain;
        }

        .rlg-size-chart-modal-close {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 30px;
            height: 30px;
            background: #fff;
            border: none;
            border-radius: 10px;
            font-size: 24px;
            line-height: 1;
            cursor: pointer;
            color: #333;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            z-index: 10;
            display: inline-flex;
            justify-content: center;
            align-items: center;
        }

        .rlg-size-chart-modal-close:hover {
            background: #f5f5f5;
        }

        @media (max-width: 768px) {
            .rlg-size-chart-button {
                position: relative;
                z-index: 999999;
            }

            .rlg-size-chart-modal-content {
                max-width: 95vw;
                max-height: 95vh;
                padding: 15px;
            }

            .rlg-size-chart-modal-content img {
                max-height: calc(95vh - 60px);
            }

            .rlg-size-chart-modal-close {
                width: 28px;
                height: 28px;
                font-size: 20px;
                border-radius: 8px;
            }
        }
    ');

    // Inline JavaScript for size chart modal
    wp_add_inline_script('jquery', '
        jQuery(document).ready(function($) {
            // Open modal
            $(document).on("click", ".rlg-size-chart-button", function(e) {
                e.preventDefault();
                $("#rlg-size-chart-modal").fadeIn(300);
                $("body").css("overflow", "hidden");
            });

            // Close modal
            $(document).on("click", ".rlg-size-chart-modal-close, .rlg-size-chart-modal-overlay", function(e) {
                e.preventDefault();
                $("#rlg-size-chart-modal").fadeOut(300);
                $("body").css("overflow", "");
            });

            // Close on ESC key
            $(document).on("keydown", function(e) {
                if (e.key === "Escape" && $("#rlg-size-chart-modal").is(":visible")) {
                    $("#rlg-size-chart-modal").fadeOut(300);
                    $("body").css("overflow", "");
                }
            });
        });
    ');
}
add_action('wp_enqueue_scripts', 'rlg_enqueue_size_chart_assets', 1001);


