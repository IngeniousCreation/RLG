<?php
/**
 * Custom Product Archive Template - 100% Custom Implementation
 *
 * NO WooCommerce default functions - Complete custom control
 *
 * @package Basel Child
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

// Handle AJAX fragments request
if( basel_is_woo_ajax() === 'fragments' ) {
	basel_woocommerce_main_loop( true );
	die();
}

// Load header
if ( ! basel_is_woo_ajax() ) {
	get_header( 'shop' );
} else {
	basel_page_top_part();
}

// Start content wrapper
$content_class = basel_get_content_class();
$content_class .= ' content-with-products';
?>

<div class="site-content shop-content-area <?php echo esc_attr($content_class); ?>" role="main">

<?php
// ============================================================================
// CUSTOM DATABASE QUERY LOGIC
// ============================================================================

global $wpdb;

// Get current category
$current_category = get_queried_object();
$category_id = $current_category->term_id ?? 0;

// Check if this is a search query
$search_query = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

// Get filter parameters
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 999999999;
$stock_status = isset($_GET['stock_status']) ? sanitize_text_field($_GET['stock_status']) : '';
$orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'menu_order';

// Pagination
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$posts_per_page = 20; // Products per page - initial load
$offset = ($paged - 1) * $posts_per_page;

// Include custom query functions
require_once get_stylesheet_directory() . '/includes/custom-product-queries.php';

// Get products using custom query
$query_result = rlg_get_products_custom_query(array(
	'category_id' => $category_id,
	'search' => $search_query,
	'min_price' => $min_price,
	'max_price' => $max_price,
	'stock_status' => $stock_status,
	'orderby' => $orderby,
	'posts_per_page' => $posts_per_page,
	'offset' => $offset,
	'paged' => $paged
));

$products = $query_result['products'];
$total_products = $query_result['total'];
$max_num_pages = ceil($total_products / $posts_per_page);

if ( ! empty( $products ) ) :
?>

	<?php
	// Display heading - Search or Category
	if ( ! empty( $search_query ) ) :
		// Search results heading
	?>
		<div class="rlg-category-heading">
			<h1>Search Results for: "<?php echo esc_html( $search_query ); ?>"</h1>
			<p class="woocommerce-result-count">Found <?php echo esc_html( $total_products ); ?> product<?php echo $total_products != 1 ? 's' : ''; ?></p>
		</div>
	<?php
	elseif ( is_product_category() ) :
		// Category heading
	?>
		<div class="rlg-category-heading">
			<?php
			// Display breadcrumbs before H1
			if ( function_exists('woocommerce_breadcrumb') ) {
				woocommerce_breadcrumb(array(
					'delimiter'   => ' › ',
					'wrap_before' => '<nav class="rlg-breadcrumbs" aria-label="Breadcrumb"><div class="breadcrumb-inner">',
					'wrap_after'  => '</div></nav>',
					'before'      => '',
					'after'       => '',
					'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
				));
			}
			?>
			<h1><?php echo esc_html( $current_category->name ); ?></h1>
		</div>
	<?php endif; ?>

	<?php
	// Display category description with Read More functionality
	if ( is_product_category() && ! empty( $current_category->description ) ) :
		$description = $current_category->description;
		$description_length = strlen( strip_tags( $description ) );
		$preview_length = 500; // Character limit for preview

		if ( $description_length > $preview_length ) :
			// Long description - add Read More
			$preview_text = substr( strip_tags( $description ), 0, $preview_length );
			$preview_text = substr( $preview_text, 0, strrpos( $preview_text, ' ' ) ); // Cut at last space
	?>
		<div class="rlg-category-description">
			<div class="rlg-description-preview">
				<?php echo wpautop( do_shortcode( $preview_text . '...' ) ); ?>
			</div>
			<div class="rlg-description-full" style="display: none;">
				<?php echo wpautop( do_shortcode( $description ) ); ?>
			</div>
			<button class="rlg-read-more-btn">Read More</button>
		</div>
	<?php else : ?>
		<div class="rlg-category-description">
			<?php echo wpautop( do_shortcode( $description ) ); ?>
		</div>
	<?php endif; ?>
	<?php endif; ?>

	<!-- Mobile Filter Button -->
	<div class="rlg-mobile-filter-trigger">
		<button class="rlg-filter-btn" id="rlgMobileFilterBtn">
			<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M2 4h16M6 10h8M9 16h2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
			</svg>
			<span>Filters</span>
		</button>
	</div>

	<!-- Custom Product Grid -->
	<div class="rlg-products-grid">
		<div class="rlg-products-row">

	<?php
	$product_count = 0;
	foreach ( $products as $product_data ) :

		// Get product object
		$product = wc_get_product( $product_data->ID );

		if ( ! $product ) continue;

		$product_count++;

		// Get product data
		$product_id = $product->get_id();
		$product_title = $product->get_name();
		$product_url = $product->get_permalink();
		$product_image_id = $product->get_image_id();
		$product_image_url = wp_get_attachment_image_url( $product_image_id, 'woocommerce_thumbnail' );
		$product_price = $product->get_price_html();
		$is_on_sale = $product->is_on_sale();
		$stock_status = $product->get_stock_status();

		// Calculate discount percentage
		$discount_percentage = '';
		if ( $is_on_sale ) {
			$regular_price = 0;
			$sale_price = 0;

			// Handle variable products
			if ( $product->is_type( 'variable' ) ) {
				$variation_prices = $product->get_variation_prices();
				if ( ! empty( $variation_prices['regular_price'] ) && ! empty( $variation_prices['sale_price'] ) ) {
					$regular_price = (float) max( $variation_prices['regular_price'] );
					$sale_price = (float) min( $variation_prices['sale_price'] );
				}
			} else {
				// Simple product
				$regular_price = (float) $product->get_regular_price();
				$sale_price = (float) $product->get_sale_price();
			}

			if ( $regular_price > 0 && $sale_price > 0 && $regular_price > $sale_price ) {
				$discount = ( ( $regular_price - $sale_price ) / $regular_price ) * 100;
				$discount_percentage = '-' . round( $discount ) . '%';
			}
		}
		?>

		<div class="rlg-product-item">
			<div class="rlg-product-inner">

				<!-- Product Image -->
				<div class="rlg-product-image">
					<a href="<?php echo esc_url($product_url); ?>">
						<?php if ( $product_image_url ) : ?>
							<img src="<?php echo esc_url($product_image_url); ?>" alt="<?php echo esc_attr($product_title); ?>" />
						<?php else : ?>
							<img src="<?php echo wc_placeholder_img_src(); ?>" alt="<?php echo esc_attr($product_title); ?>" />
						<?php endif; ?>
					</a>

					<?php if ( $is_on_sale && $discount_percentage ) : ?>
						<span class="rlg-sale-badge"><?php echo esc_html( $discount_percentage ); ?></span>
					<?php endif; ?>

					<?php if ( $stock_status === 'outofstock' ) : ?>
						<span class="rlg-stock-badge out-of-stock">Out of Stock</span>
					<?php endif; ?>
				</div>

				<!-- Product Info -->
				<div class="rlg-product-info">
					<h3 class="rlg-product-title">
						<a href="<?php echo esc_url($product_url); ?>"><?php echo esc_html($product_title); ?></a>
					</h3>
					<div class="rlg-product-price">
						<?php echo $product_price; ?>
					</div>
				</div>

			</div>
		</div>

	<?php endforeach; ?>

		</div><!-- .rlg-products-row -->
	</div><!-- .rlg-products-grid -->

	<?php
	// Load More Button
	if ( $max_num_pages > 1 ) :
		$next_page = $paged + 1;
		$current_url = home_url($_SERVER['REQUEST_URI']);
		$current_url = remove_query_arg('paged', $current_url);
		$next_url = add_query_arg('paged', $next_page, $current_url);

		// Calculate showing range
		$showing_from = 1;
		$showing_to = min($paged * $posts_per_page, $total_products);
	?>

	<div class="rlg-load-more-wrapper">
		<div class="rlg-showing-info">
			Showing <span class="rlg-showing-from"><?php echo $showing_from; ?></span> - <span class="rlg-showing-to"><?php echo $showing_to; ?></span> of <span class="rlg-showing-total"><?php echo $total_products; ?></span> total
		</div>
		<button class="rlg-load-more-btn"
			data-page="<?php echo esc_attr($next_page); ?>"
			data-max-pages="<?php echo esc_attr($max_num_pages); ?>"
			data-category="<?php echo esc_attr($category_id); ?>"
			data-search="<?php echo esc_attr($search_query); ?>"
			data-min-price="<?php echo esc_attr($min_price); ?>"
			data-max-price="<?php echo esc_attr($max_price); ?>"
			data-stock-status="<?php echo esc_attr($stock_status); ?>"
			data-orderby="<?php echo esc_attr($orderby); ?>"
			data-per-page="<?php echo esc_attr($posts_per_page); ?>"
			data-total="<?php echo esc_attr($total_products); ?>">
			SHOW MORE
		</button>
		<div class="rlg-load-more-loading" style="display: none;">
			<span class="rlg-loading-spinner"></span>
			<span class="rlg-loading-text">Loading products...</span>
		</div>
	</div>

	<?php endif; ?>

<?php else : ?>

	<!-- No products found -->
	<p class="woocommerce-info">No products were found matching your selection.</p>

<?php endif; ?>

</div><!-- .site-content -->

<?php
// Sidebar
get_sidebar( 'shop' );

// Load footer
if ( ! basel_is_woo_ajax() ) {
	get_footer( 'shop' );
} else {
	basel_page_bottom_part();
}

