<?php
/**
 * Static Homepage Configuration
 * Manages product IDs and category mappings for the static homepage
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get best selling product IDs
 * Update these IDs based on your actual best-selling products
 */
function rlg_get_best_selling_ids() {
    // You can update this array with actual product IDs
    // Or fetch from database based on sales
    $ids = get_option('rlg_best_selling_ids');
    
    if (!$ids) {
        // Default fallback - get top 12 products by sales
        global $wpdb;
        
        $query = $wpdb->prepare(
            "SELECT p.ID
             FROM {$wpdb->posts} p
             LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = 'total_sales'
             WHERE p.post_type = 'product'
             AND p.post_status = 'publish'
             ORDER BY CAST(pm.meta_value AS UNSIGNED) DESC
             LIMIT %d",
            12
        );
        
        $products = $wpdb->get_col($query);
        $ids = implode(',', $products);

        // Cache for 24 hours
        update_option('rlg_best_selling_ids', $ids);
    }

    return $ids;
}

/**
 * Get category configurations for homepage
 */
function rlg_get_homepage_categories() {
    // Helper function to safely get term ID
    $get_term_id = function($slug) {
        $term = get_term_by('slug', $slug, 'product_cat');
        return $term ? $term->term_id : 0;
    };

    return array(
        array(
            'id' => 'mens-jackets',
            'name' => "Men's Leather Jackets",
            'term_id' => $get_term_id('mens-leather-jackets'),
            'items' => 8
        ),
        array(
            'id' => 'womens-jackets',
            'name' => "Women's Leather Jackets",
            'term_id' => $get_term_id('womens-leather-jackets'),
            'items' => 8
        ),
        array(
            'id' => 'celebrity-jackets',
            'name' => 'Celebrity Jackets',
            'term_id' => $get_term_id('celebrity-jackets'),
            'items' => 8
        ),
        array(
            'id' => 'biker-jackets',
            'name' => 'Biker Jackets',
            'term_id' => $get_term_id('biker-jackets'),
            'items' => 8
        ),
    );
}

/**
 * Update best selling IDs (run this via cron or manually)
 */
function rlg_update_best_selling_ids() {
    global $wpdb;
    
    $query = $wpdb->prepare(
        "SELECT p.ID
         FROM {$wpdb->posts} p
         LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = 'total_sales'
         WHERE p.post_type = 'product'
         AND p.post_status = 'publish'
         ORDER BY CAST(pm.meta_value AS UNSIGNED) DESC
         LIMIT %d",
        12
    );
    
    $products = $wpdb->get_col($query);
    $ids = implode(',', $products);
    
    update_option('rlg_best_selling_ids', $ids);
    
    return $ids;
}

/**
 * Schedule daily update of best selling products
 */
function rlg_schedule_best_selling_update() {
    if (!wp_next_scheduled('rlg_update_best_selling')) {
        wp_schedule_event(time(), 'daily', 'rlg_update_best_selling');
    }
}
add_action('wp', 'rlg_schedule_best_selling_update');

/**
 * Hook for scheduled update
 */
add_action('rlg_update_best_selling', 'rlg_update_best_selling_ids');

/**
 * Clear homepage transients when products are updated
 */
function rlg_clear_homepage_cache($post_id) {
    if (get_post_type($post_id) === 'product') {
        delete_option('rlg_best_selling_ids');
        
        // Also clear Basel shortcode transients
        if (function_exists('basel_clear_all_caches')) {
            basel_clear_all_caches();
        }
    }
}
add_action('save_post_product', 'rlg_clear_homepage_cache');

/**
 * Admin menu to manually refresh homepage data
 */
function rlg_add_homepage_refresh_button() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    global $wp_admin_bar;
    
    $wp_admin_bar->add_node(array(
        'id'    => 'rlg-refresh-homepage',
        'title' => '🏠 Refresh Homepage Data',
        'href'  => wp_nonce_url(admin_url('admin-post.php?action=rlg_refresh_homepage'), 'rlg_refresh_homepage'),
        'meta'  => array(
            'title' => 'Refresh best selling products and clear homepage caches',
        ),
    ));
}
add_action('admin_bar_menu', 'rlg_add_homepage_refresh_button', 100);

/**
 * Handle homepage refresh
 */
function rlg_handle_homepage_refresh() {
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized');
    }
    
    check_admin_referer('rlg_refresh_homepage');
    
    $ids = rlg_update_best_selling_ids();
    
    if (function_exists('basel_clear_all_caches')) {
        basel_clear_all_caches();
    }
    
    wp_redirect(add_query_arg('rlg_homepage_refreshed', '1', wp_get_referer()));
    exit;
}
add_action('admin_post_rlg_refresh_homepage', 'rlg_handle_homepage_refresh');

