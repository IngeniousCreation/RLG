<?php
/**
 * Custom Sidebar Filters
 * Price Range, Availability, and Categories
 * 
 * @package Basel Child
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Render custom sidebar filters
 */
function rlg_render_custom_sidebar_filters() {
	if ( ! is_product_category() && ! is_shop() ) {
		return;
	}
	
	?>
	<div class="rlg-custom-sidebar-filters">

		<!-- Price Range Filter -->
		<div class="rlg-filter-widget rlg-price-filter">
			<h3 class="rlg-filter-title">Price Range</h3>
			<div class="rlg-filter-content">
				<?php rlg_render_custom_price_filter(); ?>
			</div>
		</div>

		<!-- Availability Filter -->
		<div class="rlg-filter-widget rlg-availability-filter">
			<h3 class="rlg-filter-title">Availability</h3>
			<div class="rlg-filter-content">
				<ul class="rlg-availability-list">
					<?php
					$current_url = esc_url( add_query_arg( array() ) );
					$stock_status = isset( $_GET['stock_status'] ) ? sanitize_text_field( $_GET['stock_status'] ) : '';

					// In Stock
					$in_stock_url = add_query_arg( 'stock_status', 'instock', $current_url );
					$in_stock_active = ( $stock_status === 'instock' ) ? ' class="active"' : '';

					// Out of Stock
					$out_of_stock_url = add_query_arg( 'stock_status', 'outofstock', $current_url );
					$out_of_stock_active = ( $stock_status === 'outofstock' ) ? ' class="active"' : '';

					// All (remove filter)
					$all_url = remove_query_arg( 'stock_status', $current_url );
					$all_active = ( empty( $stock_status ) ) ? ' class="active"' : '';
					?>
					<li<?php echo $all_active; ?>>
						<a href="<?php echo esc_url( $all_url ); ?>" class="rlg-ajax-filter" data-filter-type="stock_status" data-filter-value="">
							<span class="rlg-checkbox"></span>
							All Products
						</a>
					</li>
					<li<?php echo $in_stock_active; ?>>
						<a href="<?php echo esc_url( $in_stock_url ); ?>" class="rlg-ajax-filter" data-filter-type="stock_status" data-filter-value="instock">
							<span class="rlg-checkbox"></span>
							In Stock
						</a>
					</li>
					<li<?php echo $out_of_stock_active; ?>>
						<a href="<?php echo esc_url( $out_of_stock_url ); ?>" class="rlg-ajax-filter" data-filter-type="stock_status" data-filter-value="outofstock">
							<span class="rlg-checkbox"></span>
							Out of Stock
						</a>
					</li>
				</ul>
			</div>
		</div>

		<!-- Categories Filter -->
		<div class="rlg-filter-widget rlg-categories-filter">
			<h3 class="rlg-filter-title">Categories</h3>
			<div class="rlg-filter-content">
				<?php rlg_render_category_tree(); ?>
			</div>
		</div>

	</div>
	<?php
}

/**
 * Render category tree from mega menu structure
 */
function rlg_render_category_tree() {
	// Get mega menu config
	if ( ! function_exists( 'basel_child_get_mega_menu_config' ) ) {
		return;
	}
	
	$menu_items = basel_child_get_mega_menu_config();
	$base_url = home_url();
	
	echo '<div class="rlg-category-tree">';
	
	foreach ( $menu_items as $item ) {
		// Only show Men, Women, Kids, and Movies Jackets
		if ( ! in_array( $item['id'], array( 'men', 'women', 'kids', 'movies' ) ) ) {
			continue;
		}
		
		$label = $item['label'];
		$url = $base_url . $item['url'];
		
		echo '<div class="rlg-category-group">';
		echo '<h4 class="rlg-category-parent"><a href="' . esc_url( $url ) . '" class="rlg-ajax-filter-category">' . esc_html( $label ) . '</a></h4>';

		// Show subcategories for Men and Women
		if ( $item['has_mega'] && ! empty( $item['columns'] ) ) {
			echo '<ul class="rlg-category-children">';

			// Collect all items from all columns
			$all_items = array();
			foreach ( $item['columns'] as $column ) {
				if ( ! empty( $column['items'] ) ) {
					$all_items = array_merge( $all_items, $column['items'] );
				}
			}

			// Shuffle and show random 8 items
			shuffle( $all_items );
			$random_items = array_slice( $all_items, 0, 8 );

			foreach ( $random_items as $sub_item ) {
				$sub_url = $base_url . $sub_item['url'];
				echo '<li><a href="' . esc_url( $sub_url ) . '" class="rlg-ajax-filter-category">' . esc_html( $sub_item['label'] ) . '</a></li>';
			}

			echo '</ul>';
		}

		echo '</div>';
	}
	
	echo '</div>';
}

/**
 * Render 100% custom price filter (no WooCommerce widget)
 */
function rlg_render_custom_price_filter() {
	global $wpdb;

	// Get current category
	$current_category = get_queried_object();
	$category_id = isset($current_category->term_id) ? $current_category->term_id : 0;

	// Get min and max prices from database
	if ($category_id) {
		$sql = $wpdb->prepare("
			SELECT
				MIN(CAST(pm.meta_value AS DECIMAL(10,2))) as min_price,
				MAX(CAST(pm.meta_value AS DECIMAL(10,2))) as max_price
			FROM {$wpdb->posts} p
			INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
			INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
			INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
			WHERE p.post_type = 'product'
				AND p.post_status = 'publish'
				AND tt.taxonomy = 'product_cat'
				AND tt.term_id = %d
				AND pm.meta_key = '_price'
				AND pm.meta_value != ''
		", $category_id);

		$prices = $wpdb->get_row($sql);
		$min_price = floor($prices->min_price ?? 0);
		$max_price = ceil($prices->max_price ?? 1000);
	} else {
		// Shop page - get all products
		$sql = "
			SELECT
				MIN(CAST(meta_value AS DECIMAL(10,2))) as min_price,
				MAX(CAST(meta_value AS DECIMAL(10,2))) as max_price
			FROM {$wpdb->postmeta}
			WHERE meta_key = '_price'
				AND meta_value != ''
		";

		$prices = $wpdb->get_row($sql);
		$min_price = floor($prices->min_price ?? 0);
		$max_price = ceil($prices->max_price ?? 1000);
	}

	// Get current filter values
	$current_min = isset($_GET['min_price']) ? intval($_GET['min_price']) : $min_price;
	$current_max = isset($_GET['max_price']) ? intval($_GET['max_price']) : $max_price;

	// Get current URL
	$current_url = home_url($_SERVER['REQUEST_URI']);
	$current_url = remove_query_arg(array('min_price', 'max_price'), $current_url);

	?>
	<div class="slider-wrapper">
		<div class="track-container">
			<div class="track-background"></div>
			<div class="track-fill" id="trackFill"></div>
			<div class="range-inputs">
				<input type="range" id="minInput" class="rlg-price-slider-min"
					min="<?php echo esc_attr($min_price); ?>"
					max="<?php echo esc_attr($max_price); ?>"
					value="<?php echo esc_attr($current_min); ?>"
					step="1"
					data-min="<?php echo esc_attr($min_price); ?>"
					data-max="<?php echo esc_attr($max_price); ?>">
				<input type="range" id="maxInput" class="rlg-price-slider-max"
					min="<?php echo esc_attr($min_price); ?>"
					max="<?php echo esc_attr($max_price); ?>"
					value="<?php echo esc_attr($current_max); ?>"
					step="1"
					data-min="<?php echo esc_attr($min_price); ?>"
					data-max="<?php echo esc_attr($max_price); ?>">
			</div>
		</div>

		<div class="price-display">
			<div class="price-item">
				<span class="price-label">Min Price</span>
				<div class="price-value">£<span id="minValue" class="rlg-min-value"><?php echo esc_html($current_min); ?></span></div>
			</div>
			<div class="price-separator">—</div>
			<div class="price-item">
				<span class="price-label">Max Price</span>
				<div class="price-value">£<span id="maxValue" class="rlg-max-value"><?php echo esc_html($current_max); ?></span></div>
			</div>
		</div>

		<!-- Hidden inputs for form submission -->
		<input type="hidden" class="rlg-price-input-min" value="<?php echo esc_attr($current_min); ?>">
		<input type="hidden" class="rlg-price-input-max" value="<?php echo esc_attr($current_max); ?>">

		<button type="button" class="rlg-price-filter-button" style="display: none !important;" data-url="<?php echo esc_url($current_url); ?>">
			Apply Filter
		</button>
	</div>
	<?php
}

