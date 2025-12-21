<?php
/**
 * Custom Product Database Queries
 * 
 * Direct SQL queries using $wpdb->prepare() for security and performance
 *
 * @package Basel Child
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get products using custom database query
 * 
 * @param array $args Query arguments
 * @return array Array with 'products' and 'total' count
 */
function rlg_get_products_custom_query( $args = array() ) {
	global $wpdb;
	
	// Default arguments
	$defaults = array(
		'category_id' => 0,
		'min_price' => 0,
		'max_price' => 999999999,
		'stock_status' => '',
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'posts_per_page' => 50,
		'offset' => 0,
		'paged' => 1
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	// Extract arguments
	extract( $args );
	
	// Build WHERE clauses
	$where_clauses = array();
	$join_clauses = array();
	
	// Base WHERE clause - only published products
	$where_clauses[] = "p.post_type = 'product'";
	$where_clauses[] = "p.post_status = 'publish'";
	
	// Category filter
	if ( $category_id > 0 ) {
		$join_clauses[] = "INNER JOIN {$wpdb->term_relationships} AS tr ON p.ID = tr.object_id";
		$join_clauses[] = "INNER JOIN {$wpdb->term_taxonomy} AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";
		$where_clauses[] = $wpdb->prepare( "tt.term_id = %d", $category_id );
		$where_clauses[] = "tt.taxonomy = 'product_cat'";
	}
	
	// Price filter
	if ( $min_price > 0 || $max_price < 999999999 ) {
		$join_clauses[] = "INNER JOIN {$wpdb->postmeta} AS pm_price ON p.ID = pm_price.post_id AND pm_price.meta_key = '_price'";
		$where_clauses[] = $wpdb->prepare(
			"CAST(pm_price.meta_value AS DECIMAL(10,2)) BETWEEN %f AND %f",
			$min_price,
			$max_price
		);
	}
	
	// Stock status filter
	if ( ! empty( $stock_status ) ) {
		$join_clauses[] = "INNER JOIN {$wpdb->postmeta} AS pm_stock ON p.ID = pm_stock.post_id AND pm_stock.meta_key = '_stock_status'";
		$where_clauses[] = $wpdb->prepare( "pm_stock.meta_value = %s", $stock_status );
	}
	
	// Build ORDER BY clause
	$order_clause = rlg_get_order_by_clause( $orderby, $order );
	
	// Join for ordering (if needed)
	if ( strpos( $order_clause, 'pm_order' ) !== false ) {
		$join_clauses[] = "LEFT JOIN {$wpdb->postmeta} AS pm_order ON p.ID = pm_order.post_id AND pm_order.meta_key = '_menu_order'";
	}
	
	if ( strpos( $order_clause, 'pm_price_order' ) !== false ) {
		$join_clauses[] = "LEFT JOIN {$wpdb->postmeta} AS pm_price_order ON p.ID = pm_price_order.post_id AND pm_price_order.meta_key = '_price'";
	}
	
	// Build final query parts
	$join_sql = implode( ' ', array_unique( $join_clauses ) );
	$where_sql = 'WHERE ' . implode( ' AND ', $where_clauses );
	
	// Count total products (for pagination)
	$count_sql = "
		SELECT COUNT(DISTINCT p.ID)
		FROM {$wpdb->posts} AS p
		{$join_sql}
		{$where_sql}
	";
	
	$total = $wpdb->get_var( $count_sql );
	
	// Get products with limit and offset
	$products_sql = $wpdb->prepare(
		"
		SELECT DISTINCT p.ID, p.post_title, p.post_name, p.post_date
		FROM {$wpdb->posts} AS p
		{$join_sql}
		{$where_sql}
		{$order_clause}
		LIMIT %d OFFSET %d
		",
		$posts_per_page,
		$offset
	);
	
	$products = $wpdb->get_results( $products_sql );
	
	return array(
		'products' => $products,
		'total' => intval( $total )
	);
}

/**
 * Get ORDER BY clause based on orderby parameter
 *
 * @param string $orderby Order by field
 * @param string $order ASC or DESC
 * @return string SQL ORDER BY clause
 */
function rlg_get_order_by_clause( $orderby, $order = 'ASC' ) {
	$order = strtoupper( $order ) === 'DESC' ? 'DESC' : 'ASC';

	switch ( $orderby ) {
		case 'price':
			return "ORDER BY CAST(pm_price_order.meta_value AS DECIMAL(10,2)) {$order}";

		case 'price-desc':
			return "ORDER BY CAST(pm_price_order.meta_value AS DECIMAL(10,2)) DESC";

		case 'date':
			return "ORDER BY p.post_date DESC";

		case 'title':
			return "ORDER BY p.post_title {$order}";

		case 'menu_order':
		default:
			return "ORDER BY CAST(pm_order.meta_value AS UNSIGNED) ASC, p.post_title ASC";
	}
}

/**
 * Get product count for a category with filters
 *
 * @param int $category_id Category term ID
 * @param array $filters Filter parameters
 * @return int Product count
 */
function rlg_get_product_count( $category_id, $filters = array() ) {
	global $wpdb;

	$where_clauses = array();
	$join_clauses = array();

	$where_clauses[] = "p.post_type = 'product'";
	$where_clauses[] = "p.post_status = 'publish'";

	// Category
	if ( $category_id > 0 ) {
		$join_clauses[] = "INNER JOIN {$wpdb->term_relationships} AS tr ON p.ID = tr.object_id";
		$join_clauses[] = "INNER JOIN {$wpdb->term_taxonomy} AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";
		$where_clauses[] = $wpdb->prepare( "tt.term_id = %d", $category_id );
		$where_clauses[] = "tt.taxonomy = 'product_cat'";
	}

	// Price filter
	if ( isset( $filters['min_price'] ) || isset( $filters['max_price'] ) ) {
		$min = isset( $filters['min_price'] ) ? floatval( $filters['min_price'] ) : 0;
		$max = isset( $filters['max_price'] ) ? floatval( $filters['max_price'] ) : 999999999;

		$join_clauses[] = "INNER JOIN {$wpdb->postmeta} AS pm_price ON p.ID = pm_price.post_id AND pm_price.meta_key = '_price'";
		$where_clauses[] = $wpdb->prepare(
			"CAST(pm_price.meta_value AS DECIMAL(10,2)) BETWEEN %f AND %f",
			$min,
			$max
		);
	}

	// Stock status
	if ( ! empty( $filters['stock_status'] ) ) {
		$join_clauses[] = "INNER JOIN {$wpdb->postmeta} AS pm_stock ON p.ID = pm_stock.post_id AND pm_stock.meta_key = '_stock_status'";
		$where_clauses[] = $wpdb->prepare( "pm_stock.meta_value = %s", $filters['stock_status'] );
	}

	$join_sql = implode( ' ', array_unique( $join_clauses ) );
	$where_sql = 'WHERE ' . implode( ' AND ', $where_clauses );

	$sql = "
		SELECT COUNT(DISTINCT p.ID)
		FROM {$wpdb->posts} AS p
		{$join_sql}
		{$where_sql}
	";

	return intval( $wpdb->get_var( $sql ) );
}

/**
 * Get price range for products in a category
 *
 * @param int $category_id Category term ID
 * @return array Array with 'min' and 'max' prices
 */
function rlg_get_price_range( $category_id ) {
	global $wpdb;

	$sql = $wpdb->prepare(
		"
		SELECT
			MIN(CAST(pm.meta_value AS DECIMAL(10,2))) as min_price,
			MAX(CAST(pm.meta_value AS DECIMAL(10,2))) as max_price
		FROM {$wpdb->posts} AS p
		INNER JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id AND pm.meta_key = '_price'
		INNER JOIN {$wpdb->term_relationships} AS tr ON p.ID = tr.object_id
		INNER JOIN {$wpdb->term_taxonomy} AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
		WHERE p.post_type = 'product'
		AND p.post_status = 'publish'
		AND tt.term_id = %d
		AND tt.taxonomy = 'product_cat'
		",
		$category_id
	);

	$result = $wpdb->get_row( $sql );

	return array(
		'min' => floatval( $result->min_price ?? 0 ),
		'max' => floatval( $result->max_price ?? 0 )
	);
}

