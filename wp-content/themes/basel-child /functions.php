<?php

add_action( 'wp_enqueue_scripts', 'basel_child_enqueue_styles', 1000 );

function basel_child_enqueue_styles() {
	$version = basel_get_theme_info( 'Version' );
	
	if( basel_get_opt( 'minified_css' ) ) {
		wp_enqueue_style( 'basel-style', get_template_directory_uri() . '/style.min.css', array('bootstrap'), $version );
	} else {
		wp_enqueue_style( 'basel-style', get_template_directory_uri() . '/style.css', array('bootstrap'), $version );
	}
	
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array('bootstrap'), $version );
}

// Remove the default short description action
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);

// Add the short description action at a different priority (e.g., 41)
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 39);


function add_inline_styles() {
    // Check if it's a single product page
    if (is_product()) {
        global $product;

        // Check if the product is external
        if ($product && $product->is_type('external')) {
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
add_action( 'init', 'remove_output_structured_data' );
















// emai automation code


// Get WooCommerce orders
$orders = wc_get_orders( array( 'status' => 'processing' ) );

// Loop through orders
foreach ( $orders as $order ) {
    // Get order items
    $items = $order->get_items();

    // Loop through order items
    foreach ( $items as $item ) {
        // Get product variation ID
        $product_id = $item->get_variation_id();

        // Get product variation data
        $variation_data = wc_get_product_variation_attributes( $product_id );

        // Get selected size variation
        $selected_size = isset( $variation_data['attribute_pa_size'] ) ? $variation_data['attribute_pa_size'] : '';

        // Get customer email
        $customer_email = $order->get_billing_email();

        // Select appropriate email template based on size
        switch ($selected_size) {
            case 'XXS':
                $email_template = "Dear Customer,

I hope this email finds you well. We are thrilled to inform you that your order for the Bloodshot Vin Diesel Black Jacket in size XXS has been successfully received and is currently being processed. We appreciate your choice in selecting our product, and we are committed to ensuring your satisfaction.

Please review the size details below:
Chest measurement: 34 inches
Sleeve length: 25 inches
Waist measurement: 30 inches

If there are any changes or specific instructions regarding the size, feel free to let us know, and we will do our best to accommodate your preferences.

We understand the importance of a well-fitted jacket, and we want to make sure you have a positive experience with your purchase.

Best regards,
Real Leather Garments";
                break;

            case 'XS':
                $email_template = "Dear Customer,

I hope this email finds you well. We are thrilled to inform you that your order for the Bloodshot Vin Diesel Black Jacket in size XS has been successfully received and is currently being processed. We appreciate your choice in selecting our product, and we are committed to ensuring your satisfaction.

Please review the size details below:
Chest measurement: 34 inches
Sleeve length: 25 inches
Waist measurement: 30 inches

If there are any changes or specific instructions regarding the size, feel free to let us know, and we will do our best to accommodate your preferences.

We understand the importance of a well-fitted jacket, and we want to make sure you have a positive experience with your purchase.

Best regards,
Real Leather Garments";
                break;

            case 'S':
                $email_template = "Dear Customer,

I hope this email finds you well. We are thrilled to inform you that your order for the Bloodshot Vin Diesel Black Jacket in size S has been successfully received and is currently being processed. We appreciate your choice in selecting our product, and we are committed to ensuring your satisfaction.

Please review the size details below:
Chest measurement: 34 inches
Sleeve length: 25 inches
Waist measurement: 30 inches

If there are any changes or specific instructions regarding the size, feel free to let us know, and we will do our best to accommodate your preferences.

We understand the importance of a well-fitted jacket, and we want to make sure you have a positive experience with your purchase.

Best regards,
Real Leather Garments";
                break;

            case 'M':
                $email_template = "Dear Customer,

I hope this email finds you well. We are thrilled to inform you that your order for the Bloodshot Vin Diesel Black Jacket in size M has been successfully received and is currently being processed. We appreciate your choice in selecting our product, and we are committed to ensuring your satisfaction.

Please review the size details below:
Chest measurement: 34 inches
Sleeve length: 25 inches
Waist measurement: 30 inches

If there are any changes or specific instructions regarding the size, feel free to let us know, and we will do our best to accommodate your preferences.

We understand the importance of a well-fitted jacket, and we want to make sure you have a positive experience with your purchase.

Best regards,
Real Leather Garments";
                break;

            // Add more cases for other sizes as needed

            default:
                // Default template if size not found
                $email_template = "Dear Customer,

We are unable to process your order at this time. Please contact customer support for assistance.

Best regards,
Real Leather Garments";
                break;
        }

        // Send email
        $subject = 'Order Confirmation - Size ' . $selected_size;
        $headers = 'From: yourstore@example.com' . "\r\n" .
            'Reply-To: yourstore@example.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        // Send email to the specified email address
        mail('admin@realleathergarments.co.uk', $subject, $email_template, $headers);
    }
}











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




