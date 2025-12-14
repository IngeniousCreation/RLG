<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>
<header class="woocommerce-products-header">
	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
	<?php endif; ?>

	<?php
	/**
	 * Hook: woocommerce_archive_description.
	 *
	 * @hooked woocommerce_taxonomy_archive_description - 10
	 * @hooked woocommerce_product_archive_description - 10
	 */
	do_action( 'woocommerce_archive_description' );
	?>
</header>




 <?php
//if (is_user_logged_in()) {
    
    
    function __count_totals(){
     global $wpdb;

$category_id = get_queried_object_id();;  // Replace with your actual category ID
$custom_field = '_product_url';  // Replace with the actual custom field key

$sql_count = $wpdb->prepare(
    "SELECT COUNT(DISTINCT p.ID) AS counts , p.ID, p.post_title, m.meta_value
    FROM {$wpdb->posts} AS p
    LEFT JOIN {$wpdb->postmeta} AS m ON (p.ID = m.post_id AND m.meta_key = %s)
    LEFT JOIN {$wpdb->term_relationships} AS tr ON (p.ID = tr.object_id)
    LEFT JOIN {$wpdb->term_taxonomy} AS tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
    WHERE tt.term_id = %d
    AND p.post_type = 'product'
    AND p.post_status = 'publish'
    ORDER BY m.meta_value IS NOT NULL, m.meta_value ASC",
    $custom_field,
    $category_id
);

$resultsTotal = $wpdb->get_results($sql_count);
$GLOBALS['resultsTotal'] = $resultsTotal[0]->counts;


$limit = 30;
if($_GET['page'] == 2){
    $offset = ', ' . $limit;
}elseif($_GET['page'] > 2){
    $offset = ', ' . $limit * $_GET['page'];
}else{
    $offset = '';
}


//$offset = $limit ;
$sql = $wpdb->prepare(
    "SELECT DISTINCT p.ID, p.post_title, m.meta_value
    FROM {$wpdb->posts} AS p
    LEFT JOIN {$wpdb->postmeta} AS m ON (p.ID = m.post_id AND m.meta_key = %s)
    LEFT JOIN {$wpdb->term_relationships} AS tr ON (p.ID = tr.object_id)
    LEFT JOIN {$wpdb->term_taxonomy} AS tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
    WHERE tt.term_id = %d
    AND p.post_type = 'product'
    AND p.post_status = 'publish'
    ORDER BY m.meta_value IS NOT NULL, m.meta_value ASC LIMIT $limit {$offset}",
    $custom_field,
    $category_id
);

$results = $wpdb->get_results($sql);



if ($results) {
    
    echo '<ul class="products products-custom-column columns-4">';
    foreach ($results as $result) {
        $p_id = $result->ID;
        $single_price = get_post_meta($result->ID, '_price', true);
        // Get regular price
        $regular_price = get_post_meta($result->ID, '_regular_price', true);
        
        // Get sale price
        $sale_price = get_post_meta($result->ID, '_sale_price', true);
        
        $p_price  = (!empty($sale_price)) ? "<ins><span class='woocommerce-Price-amount amount'><bdi><span class='woocommerce-Price-currencySymbol'>£</span>&nbsp;{$sale_price} </bdi></span></ins>" : "";
        
        
        $p_regular = (!empty($regular_price) && !empty($p_price)) ? "<del aria-hidden='true'><span class='woocommerce-Price-amount amount'><bdi><span class='woocommerce-Price-currencySymbol'>£</span>&nbsp;{$regular_price}</bdi></span></del>" : "";
        $p_regular = (empty($p_regular) && !empty($regular_price) && empty($sale_price)) ? "<ins aria-hidden='true'><span class='woocommerce-Price-amount amount'><bdi><span class='woocommerce-Price-currencySymbol'>£</span>&nbsp;{$regular_price}</bdi></span></ins>" : $p_regular;
        
        $p_price_single = (empty($p_price) && empty($p_regular)) ? "<ins><span class='woocommerce-Price-amount amount'><bdi><span class='woocommerce-Price-currencySymbol'>£</span>&nbsp;{$single_price} </bdi></span></ins>" : "";
        
        $product = wc_get_product($result->ID);
        
        
    //     if ($product->is_type('variable')) {
    //     // Get variations
    //     $variations = $product->get_available_variations();

    //     // Output or manipulate the variations as needed
    //     echo '<pre>';
    //     print_r(count($variations) . 'ww');
    //     echo '</pre>';
    // }
    
    
        $thumbnail = get_the_post_thumbnail_url($result->ID);
    
        $stock_status = get_post_meta($result->ID, '_stock_status', true);
        
        $p_url = get_the_permalink( $result->ID);
        $p_title = $result->post_title;
        $p_name = $result->post_title;
        $price = $product->get_price();
        $typeList = (empty($result->meta_value)) ? 'purchasable' : 'external';
        $p_typeListSimple = (empty($result->meta_value)) ? 'simple' : 'external';
        $btn = (!empty($result->meta_value)) ? 'View On Amazon' : 'Select Option';
        $btn_link = (!empty($result->meta_value)) ? $result->meta_value : $p_url;
        
        $saleHTML = (!empty($p_price)) ? "<span class='onsale'>Sale!</span>" : "";
        $saleClass = (!empty($p_price)) ? "sale" : "";
        
        
        echo "<li class='product type-product post-{$result->ID} status-publish {$stock_status} has-post-thumbnail shipping-taxable {$typeList} {$saleClass} product-type-{$p_typeListSimple}'>";
        echo "<a target='__blank' href='{$p_url}' class='woocommerce-LoopProduct-link woocommerce-loop-product__link'>
    {$saleHTML}
    <img width='600' height='600' src='{$thumbnail}' class='attachment-woocommerce_thumbnail size-woocommerce_thumbnail offOnHover' alt='{$p_title}' decoding='async'>
    <img width='400' height='157' src='{$thumbnail}' class='attachment-400x400 size-400x400' alt='{$p_title}' decoding='async' loading='lazy'>
  </a>
  <div id='shop_loop_buttons' class='wahaj-11'>
    <a target='__blank' href='{$p_url}' class='woocommerce-LoopProduct-link woocommerce-loop-product__link'>
      <h2 class='woocommerce-loop-product__title'>{$p_title}</h2>
      <span class='price'>
        {$p_regular}
        {$p_price}
        {$p_price_single}
      </span>
      <div class='wc-ppcp-paylater-msg-shop-container' id='wc-ppcp-paylater-msg-2532' style='display: none'></div>
    </a>
    <a target='__blank' href='{$btn_link}' data-quantity='1' class='button wp-element-button product_type_{$p_typeListSimple} add_to_cart_button' data-product_id='{$p_id}' data-product_sku=' aria-label='Add “{$p_name}” to your cart' rel='nofollow'>{$btn}</a>
  </div>";
        
        
        
    
        echo "</li>";
    }
} else {
    echo 'No products found';
}


echo '</ul>';

        
        
    }
    

//}



?>



    






    
    
    
    
    
    
    
    
<?php
if ( woocommerce_product_loop() ) {

	/**
	 * Hook: woocommerce_before_shop_loop.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 * @hooked woocommerce_result_count - 20
	 * @hooked woocommerce_catalog_ordering - 30
	 */
	 
	
	 
	 
	do_action( 'woocommerce_before_shop_loop');

    $current_url = strtok($_SERVER["REQUEST_URI"], '?');

        

	echo __count_totals();
    	$pagination_count = $GLOBALS['resultsTotal'] / 30;
	    $__chkfirstpage = (!isset($_GET['page'])) ? 'current-page' : '';

        echo "<nav class='woocommerce-pagination wc-pagination-custom'>
            <ul class='page-numbers'>
            <li><a class='page-numbers {$__chkfirstpage}' href='{$current_url}'>1</a></li>";
            
        
        for ($i = 1; $i < $pagination_count; $i++) {
            
            $i_inc = $i + 1;
            $page_2 = (isset($_GET['page']) && $_GET['page'] == $i_inc ) ? 'current-page' : '';
            echo "<li><a class='page-numbers {$page_2}' href='{$current_url}?page={$i_inc}'>{$i_inc}</a></li>
            ";
        }
        
        
        echo "</ul>
        </nav>";

	
	
   // echo $html;	
//apply_fiters('show_custom_loop' , $html ); 

// 	woocommerce_product_loop_start();

/*
 	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
 			the_post();

			
			do_action( 'woocommerce_shop_loop' );

 			wc_get_template_part( 'content', 'product' );
		}
 	}
 */

// 	woocommerce_product_loop_end();

	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	do_action( 'woocommerce_after_shop_loop' );
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );
