<?php
/**
 * ============================================================================
 * CUSTOM DESKTOP SEARCH BAR
 * ============================================================================
 * - Replaces Basel's default search
 * - Uses wpdb->prepare() for security
 * - Shows max 3 results
 * - Shows "View All (count)" link
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Custom Desktop Search Handler
 */
class RLG_Custom_Desktop_Search {

	public function __construct() {
		// Register AJAX handler
		add_action( 'wp_ajax_rlg_desktop_search', array( $this, 'ajax_search' ) );
		add_action( 'wp_ajax_nopriv_rlg_desktop_search', array( $this, 'ajax_search' ) );
	}

	/**
	 * AJAX search handler
	 */
	public function ajax_search() {
		global $wpdb;

		// Get and sanitize search query
		$search_query = isset( $_REQUEST['query'] ) ? sanitize_text_field( $_REQUEST['query'] ) : '';

		if ( empty( $search_query ) || strlen( $search_query ) < 2 ) {
			wp_send_json( array(
				'success' => false,
				'message' => 'Please enter at least 2 characters',
				'results' => array(),
				'total' => 0
			) );
		}

		// Search products (max 3)
		$products = $this->search_products( $search_query, 3 );
		
		// Get total count
		$total_count = $this->get_total_count( $search_query );

		// Format results
		$results = array();
		
		if ( ! empty( $products ) ) {
			foreach ( $products as $product_data ) {
				$product = wc_get_product( $product_data->ID );
				
				if ( ! $product ) {
					continue;
				}

				$results[] = array(
					'id' => $product->get_id(),
					'title' => $product->get_name(),
					'url' => $product->get_permalink(),
					'image' => $product->get_image( 'thumbnail' ),
					'price' => $product->get_price_html(),
					'sku' => $product->get_sku()
				);
			}
		}

		wp_send_json( array(
			'success' => true,
			'results' => $results,
			'total' => $total_count,
			'show_view_all' => $total_count > 3,
			'search_url' => home_url( '/?s=' . urlencode( $search_query ) . '&post_type=product' )
		) );
	}

	/**
	 * Search products using wpdb->prepare
	 */
	private function search_products( $search_term, $limit = 3 ) {
		global $wpdb;

		// Prepare search term for LIKE query
		$search_like = '%' . $wpdb->esc_like( $search_term ) . '%';

		// Get 'exclude-from-catalog' term ID
		$exclude_term = get_term_by( 'slug', 'exclude-from-catalog', 'product_visibility' );
		$exclude_term_id = $exclude_term ? $exclude_term->term_id : 0;

		// Search query with wpdb->prepare
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
			$search_like,
			$search_like,
			$search_like,
			$exclude_term_id,
			$search_like,
			$search_like,
			$limit
		);

		return $wpdb->get_results( $sql );
	}

	/**
	 * Get total product count
	 */
	private function get_total_count( $search_term ) {
		global $wpdb;

		$search_like = '%' . $wpdb->esc_like( $search_term ) . '%';
		
		$exclude_term = get_term_by( 'slug', 'exclude-from-catalog', 'product_visibility' );
		$exclude_term_id = $exclude_term ? $exclude_term->term_id : 0;

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
			$search_like,
			$search_like,
			$search_like,
			$exclude_term_id
		);

		return (int) $wpdb->get_var( $sql );
	}
}

// Initialize
new RLG_Custom_Desktop_Search();

