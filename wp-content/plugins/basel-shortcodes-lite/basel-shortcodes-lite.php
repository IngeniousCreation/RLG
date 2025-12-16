<?php
/**
 * Plugin Name: Basel Shortcodes Lite
 * Description: Ultra-fast custom DB queries with prepared statements - NO WooCommerce overhead. Replaces XTEMOS Post Types plugin for better performance.
 * Version: 2.0.0
 * Author: Wahaj Masood
 * Author URI: https://realleathergarments.co.uk
 * Text Domain: basel-shortcodes-lite
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get products using direct database query with prepared statements
 * SUPER FAST - bypasses WooCommerce completely
 */
function basel_get_products_direct($atts) {
    global $wpdb;

    $limit = intval($atts['items_per_page']);
    $include = $atts['include'];
    $taxonomies = $atts['taxonomies'];

    // Cache key
    $cache_key = 'basel_prod_' . md5(serialize($atts));
    $cached = wp_cache_get($cache_key, 'basel_products');

    if (false !== $cached) {
        return $cached;
    }

    $products = array();

    // Query 1: Get products by specific IDs (for Best Selling section)
    if (!empty($include)) {
        $ids = array_map('intval', array_map('trim', explode(',', $include)));
        $placeholders = implode(',', array_fill(0, count($ids), '%d'));

        $query = $wpdb->prepare(
            "SELECT p.ID, p.post_title, p.guid
             FROM {$wpdb->posts} p
             WHERE p.ID IN ($placeholders)
             AND p.post_status = 'publish'
             AND p.post_type = 'product'
             ORDER BY FIELD(p.ID, $placeholders)
             LIMIT %d",
            array_merge($ids, $ids, array($limit))
        );

        $products = $wpdb->get_results($query);
    }
    // Query 2: Get products by category (for category sections)
    elseif (!empty($taxonomies)) {
        $term_ids = array_map('intval', array_map('trim', explode(',', $taxonomies)));
        $placeholders = implode(',', array_fill(0, count($term_ids), '%d'));

        $query = $wpdb->prepare(
            "SELECT DISTINCT p.ID, p.post_title, p.guid
             FROM {$wpdb->posts} p
             INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
             INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
             WHERE tt.term_id IN ($placeholders)
             AND tt.taxonomy = 'product_cat'
             AND p.post_status = 'publish'
             AND p.post_type = 'product'
             ORDER BY p.post_date DESC
             LIMIT %d",
            array_merge($term_ids, array($limit))
        );

        $products = $wpdb->get_results($query);
    }

    // Get product meta (price, image) in ONE batch query
    if (!empty($products)) {
        $product_ids = wp_list_pluck($products, 'ID');
        $placeholders = implode(',', array_fill(0, count($product_ids), '%d'));

        $meta_query = $wpdb->prepare(
            "SELECT post_id, meta_key, meta_value
             FROM {$wpdb->postmeta}
             WHERE post_id IN ($placeholders)
             AND meta_key IN ('_price', '_thumbnail_id', '_stock_status')
             ORDER BY post_id",
            $product_ids
        );

        $meta_results = $wpdb->get_results($meta_query);

        // Organize meta by product ID
        $meta_by_product = array();
        foreach ($meta_results as $meta) {
            $meta_by_product[$meta->post_id][$meta->meta_key] = $meta->meta_value;
        }

        // Attach meta to products
        foreach ($products as &$product) {
            $product->meta = isset($meta_by_product[$product->ID]) ? $meta_by_product[$product->ID] : array();
        }
    }

    wp_cache_set($cache_key, $products, 'basel_products', 3600);

    return $products;
}

/**
 * Get product image URL directly from database
 */
function basel_get_product_image_url($thumbnail_id, $size = 'medium') {
    if (empty($thumbnail_id)) {
        return wc_placeholder_img_src($size);
    }

    global $wpdb;

    $cache_key = 'img_' . $thumbnail_id . '_' . $size;
    $cached = wp_cache_get($cache_key, 'basel_images');

    if (false !== $cached) {
        return $cached;
    }

    $query = $wpdb->prepare(
        "SELECT meta_value FROM {$wpdb->postmeta}
         WHERE post_id = %d AND meta_key = '_wp_attached_file'",
        $thumbnail_id
    );

    $file = $wpdb->get_var($query);

    if ($file) {
        $upload_dir = wp_upload_dir();
        $url = $upload_dir['baseurl'] . '/' . $file;
        wp_cache_set($cache_key, $url, 'basel_images', 3600);
        return $url;
    }

    return wc_placeholder_img_src($size);
}

/**
 * Register basel_products shortcode with custom rendering
 */
function basel_shortcode_products_lite($atts) {
    $atts = shortcode_atts(array(
        'items_per_page' => 12,


        'columns' => 4,
        'layout' => 'grid',
        'include' => '',
        'taxonomies' => '',
        'slides_per_view' => 4,
        'autoplay' => 'no',
    ), $atts);

    // Get products using direct DB query
    $products = basel_get_products_direct($atts);

    if (empty($products)) {
        return '';
    }

    // Start output
    ob_start();

    $wrapper_class = 'basel-products-custom';
    $wrapper_class .= ' layout-' . esc_attr($atts['layout']);
    $wrapper_class .= ' columns-' . esc_attr($atts['columns']);

    if ($atts['layout'] === 'carousel') {
        $carousel_id = 'carousel-' . rand(100, 999);
        echo '<div id="' . esc_attr($carousel_id) . '" class="' . esc_attr($wrapper_class) . ' owl-carousel">';
    } else {
        echo '<div class="' . esc_attr($wrapper_class) . '">';
    }

    // Render each product with custom HTML (NO WooCommerce templates)
    foreach ($products as $product) {
        $product_url = get_permalink($product->ID);
        $price = isset($product->meta['_price']) ? $product->meta['_price'] : '';
        $thumbnail_id = isset($product->meta['_thumbnail_id']) ? $product->meta['_thumbnail_id'] : '';
        $stock_status = isset($product->meta['_stock_status']) ? $product->meta['_stock_status'] : 'instock';
        $image_url = basel_get_product_image_url($thumbnail_id);

        ?>
        <div class="product-item" data-id="<?php echo esc_attr($product->ID); ?>">
            <div class="product-wrapper">
                <div class="product-image">
                    <a href="<?php echo esc_url($product_url); ?>">
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->post_title); ?>" loading="lazy">
                    </a>
                </div>
                <div class="product-info">
                    <h3 class="product-title">
                        <a href="<?php echo esc_url($product_url); ?>">
                            <?php echo esc_html($product->post_title); ?>
                        </a>
                    </h3>
                    <?php if ($price): ?>
                    <span class="product-price">
                        <span class="woocommerce-Price-currencySymbol">£</span><?php echo esc_html(number_format((float)$price, 2)); ?>
                    </span>
                    <?php endif; ?>
                    <?php if ($stock_status === 'outofstock'): ?>
                    <span class="out-of-stock">Out of Stock</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }

    echo '</div>';

    // Add carousel script if needed
    if ($atts['layout'] === 'carousel') {
        ?>
        <script>
        jQuery(document).ready(function($) {
            if (typeof $.fn.owlCarousel !== 'undefined') {
                $('#<?php echo esc_js($carousel_id); ?>').owlCarousel({
                    items: <?php echo intval($atts['slides_per_view']); ?>,
                    autoplay: <?php echo $atts['autoplay'] === 'yes' ? 'true' : 'false'; ?>,
                    loop: true,
                    margin: 30,
                    nav: true,
                    dots: false,
                    navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
                    responsive: {
                        0: { items: 1 },
                        480: { items: 2 },
                        768: { items: 3 },
                        992: { items: <?php echo intval($atts['slides_per_view']); ?> }
                    }
                });
            }
        });
        </script>
        <?php
    }

    return ob_get_clean();
}

add_shortcode('basel_products', 'basel_shortcode_products_lite');

/**
 * Clear cache when products are updated
 */
function basel_clear_product_cache($post_id) {
    if (get_post_type($post_id) === 'product') {
        wp_cache_flush();
    }
}
add_action('save_post_product', 'basel_clear_product_cache');

/**
 * Add custom CSS for products
 */
function basel_shortcodes_custom_css() {
    ?>
    <style>
    .basel-products-custom {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        margin: 20px 0;
    }
    .basel-products-custom.layout-grid {
        display: grid;
    }
    .basel-products-custom.layout-grid.columns-4 {
        grid-template-columns: repeat(4, 1fr);
    }
    .basel-products-custom.layout-grid.columns-3 {
        grid-template-columns: repeat(3, 1fr);
    }
    .basel-products-custom .product-item {
        background: #fff;
        border: 1px solid #e5e5e5;
        transition: all 0.3s ease;
    }
    .basel-products-custom .product-item:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .basel-products-custom .product-wrapper {
        padding: 15px;
    }
    .basel-products-custom .product-image {
        margin-bottom: 15px;
        overflow: hidden;
    }
    .basel-products-custom .product-image img {
        width: 100%;
        height: auto;
        transition: transform 0.3s ease;
    }
    .basel-products-custom .product-item:hover .product-image img {
        transform: scale(1.05);
    }
    .basel-products-custom .product-title {
        font-size: 16px;
        margin: 0 0 10px;
        line-height: 1.4;
    }
    .basel-products-custom .product-title a {
        color: #333;
        text-decoration: none;
    }
    .basel-products-custom .product-title a:hover {
        color: #000;
    }
    .basel-products-custom .product-price {
        font-size: 18px;
        font-weight: bold;
        color: #333;
    }
    .basel-products-custom .out-of-stock {
        color: #e74c3c;
        font-size: 14px;
        display: block;
        margin-top: 5px;
    }
    @media (max-width: 991px) {
        .basel-products-custom.layout-grid.columns-4,
        .basel-products-custom.layout-grid.columns-3 {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 576px) {
        .basel-products-custom.layout-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>
    <?php
}
add_action('wp_head', 'basel_shortcodes_custom_css', 100);

