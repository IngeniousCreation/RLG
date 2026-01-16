<?php
/**
 * ============================================================================
 * IMPROVED HEADER SEARCH WITH WPDB QUERIES
 * ============================================================================
 * - Shows max 3 results
 * - Uses wpdb->prepare for security and performance
 * - Shows "View All (18)" link with total count
 * - Searches product title, content, and SKU
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Override Basel's AJAX search with improved version
 */
class RLG_Improved_Search {

	public function __construct() {
		// Remove ALL Basel search handlers and add ours with priority 1
		add_action( 'wp_ajax_basel_ajax_search', array( $this, 'improved_ajax_search' ), 1 );
		add_action( 'wp_ajax_nopriv_basel_ajax_search', array( $this, 'improved_ajax_search' ), 1 );

		// Remove Basel's handlers after they're added
		add_action( 'wp_loaded', array( $this, 'remove_basel_search' ), 999 );
	}

	/**
	 * Remove Basel's default search handlers
	 */
	public function remove_basel_search() {
		// Remove all actions on basel_ajax_search except ours
		remove_all_actions( 'wp_ajax_basel_ajax_search', 10 );
		remove_all_actions( 'wp_ajax_nopriv_basel_ajax_search', 10 );

		// Re-add our handler to make sure it's there
		add_action( 'wp_ajax_basel_ajax_search', array( $this, 'improved_ajax_search' ), 1 );
		add_action( 'wp_ajax_nopriv_basel_ajax_search', array( $this, 'improved_ajax_search' ), 1 );
	}

	/**
	 * Improved AJAX search handler
	 */
	public function improved_ajax_search() {
		global $wpdb;

		// Log that our handler is being called
		error_log( '🔍 RLG Improved Search: Handler called with query: ' . ( isset( $_REQUEST['query'] ) ? $_REQUEST['query'] : 'empty' ) );

		// Get and sanitize search query
		$search_query = isset( $_REQUEST['query'] ) ? sanitize_text_field( $_REQUEST['query'] ) : '';

		if ( empty( $search_query ) || strlen( $search_query ) < 2 ) {
			echo json_encode( array(
				'suggestions' => array(
					array(
						'value'     => esc_html__( 'Please enter at least 2 characters', 'basel-child' ),
						'no_found'  => true,
						'permalink' => ''
					)
				)
			) );
			die();
		}

		// Get total count of matching products
		$total_count = $this->get_total_product_count( $search_query );

		// Get max 3 products
		$products = $this->search_products( $search_query, 3 );

		$suggestions = array();

		if ( ! empty( $products ) ) {
			$factory = new WC_Product_Factory();

			foreach ( $products as $product_data ) {
				$product = $factory->get_product( $product_data->ID );

				if ( ! $product ) {
					continue;
				}

				$suggestions[] = array(
					'value'     => html_entity_decode( $product_data->post_title ),
					'permalink' => get_permalink( $product_data->ID ),
					'price'     => $product->get_price_html(),
					'thumbnail' => $product->get_image(),
					'sku'       => $product->get_sku() ? esc_html__( 'SKU:', 'basel' ) . ' ' . $product->get_sku() : '',
				);
			}

			// Add "View All" link if there are more than 3 results
			if ( $total_count > 3 ) {
				$suggestions[] = array(
					'value'      => sprintf( esc_html__( 'View All (%d)', 'basel-child' ), $total_count ),
					'permalink'  => home_url( '/?s=' . urlencode( $search_query ) . '&post_type=product' ),
					'view_all'   => true,
					'total'      => $total_count
				);
			}

		} else {
			$suggestions[] = array(
				'value'     => esc_html__( 'No products found', 'basel' ),
				'no_found'  => true,
				'permalink' => ''
			);
		}

		echo json_encode( array(
			'suggestions' => $suggestions
		) );

		die();
	}

	/**
	 * Search products using wpdb->prepare
	 * 
	 * @param string $search_query Search term
	 * @param int    $limit        Number of results to return
	 * @return array               Array of product objects
	 */
	private function search_products( $search_query, $limit = 3 ) {
		global $wpdb;

		$search_term = '%' . $wpdb->esc_like( $search_query ) . '%';

		// Get product visibility term IDs to exclude hidden products
		$product_visibility_term_ids = wc_get_product_visibility_term_ids();
		$exclude_term_id = $product_visibility_term_ids['exclude-from-search'];

		// Prepare SQL query
		$sql = $wpdb->prepare(
			"SELECT DISTINCT p.ID, p.post_title, p.post_date
			FROM {$wpdb->posts} p
			LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = '_sku'
			LEFT JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
			LEFT JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id 
				AND tt.taxonomy = 'product_visibility'
			WHERE p.post_type = 'product'
				AND p.post_status = 'publish'
				AND (
					p.post_title LIKE %s
					OR p.post_content LIKE %s
					OR pm.meta_value LIKE %s
				)
				AND (tt.term_id IS NULL OR tt.term_id != %d)
			ORDER BY 
				CASE 
					WHEN p.post_title LIKE %s THEN 1
					WHEN pm.meta_value LIKE %s THEN 2
					ELSE 3
				END,
				p.post_date DESC
			LIMIT %d",
			$search_term,
			$search_term,
			$search_term,
			$exclude_term_id,
			$search_term,
			$search_term,
			$limit
		);

		return $wpdb->get_results( $sql );
	}

	/**
	 * Get total count of matching products
	 * 
	 * @param string $search_query Search term
	 * @return int                 Total count
	 */
	private function get_total_product_count( $search_query ) {
		global $wpdb;

		$search_term = '%' . $wpdb->esc_like( $search_query ) . '%';

		// Get product visibility term IDs
		$product_visibility_term_ids = wc_get_product_visibility_term_ids();
		$exclude_term_id = $product_visibility_term_ids['exclude-from-search'];

		$sql = $wpdb->prepare(
			"SELECT COUNT(DISTINCT p.ID)
			FROM {$wpdb->posts} p
			LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = '_sku'
			LEFT JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
			LEFT JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id 
				AND tt.taxonomy = 'product_visibility'
			WHERE p.post_type = 'product'
				AND p.post_status = 'publish'
				AND (
					p.post_title LIKE %s
					OR p.post_content LIKE %s
					OR pm.meta_value LIKE %s
				)
				AND (tt.term_id IS NULL OR tt.term_id != %d)",
			$search_term,
			$search_term,
			$search_term,
			$exclude_term_id
		);

		return (int) $wpdb->get_var( $sql );
	}
}

// Initialize the improved search
new RLG_Improved_Search();

