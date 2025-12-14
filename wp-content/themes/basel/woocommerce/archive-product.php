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
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

if( basel_is_woo_ajax() === 'fragments' ) {
	basel_woocommerce_main_loop( true );
	die();
}

if ( ! basel_is_woo_ajax() ) {
	get_header( 'shop' ); 
} else {
	basel_page_top_part();
}

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

if ( basel_get_opt( 'cat_desc_position' ) == 'before' ) {
	/**
	 * Hook: woocommerce_archive_description.
	 *
	 * @hooked woocommerce_taxonomy_archive_description - 10
	 * @hooked woocommerce_product_archive_description - 10
	 */
	do_action( 'woocommerce_archive_description' ); 
}
?>

<div class="shop-loop-head">
	<?php
		basel_current_breadcrumbs( 'shop' );

		/**
		 * Hook: woocommerce_before_shop_loop.
		 *
		 * @hooked wc_print_notices - 10
		 * @hooked woocommerce_result_count - 20
		 * @hooked woocommerce_catalog_ordering - 30
		 */
		do_action( 'woocommerce_before_shop_loop' );
	?>
</div>

<?php do_action( 'basel_shop_filters_area' ); ?>

<div class="basel-active-filters">
	<?php 

		do_action( 'basel_before_active_filters_widgets' );

		the_widget( 'WC_Widget_Layered_Nav_Filters', array(
			'title' => ''
		), array() );

		do_action( 'basel_after_active_filters_widgets' );

	?>
</div>

<div class="basel-shop-loader"></div>

<?php

//WC 3.4
$have_posts = 'have_posts';
if ( function_exists( 'WC' ) && WC()->version >= '3.4' ) {
	$have_posts = 'woocommerce_product_loop';
}

//print_r($_GET);
//wp_die();
if ( $have_posts() ) {

	woocommerce_product_loop_start();
	$current_category = get_queried_object();
    $current_category_id = $current_category->term_id;
    
    $min = (isset($_GET['min_price'])) ? $_GET['min_price'] : 0;
    $max = (isset($_GET['max_price'])) ? $_GET['max_price'] : 200000000000;
	
$args = array(
    'post_type'      => 'product',
    'posts_per_page' => 300,
    'orderby'        => array(
        'meta_value_num' => 'DESC', // Order by the presence of add to cart
        'title'          => 'ASC',  // Then order by product title
    ),
    'meta_query'     => array(
        'relation' => 'OR',
        array(
            'key'     => '_product_url',
            'compare' => 'EXISTS',  // Check if external product
        ),
        array(
            'key'     => '_product_url',
            'compare' => 'NOT EXISTS',  // Check if internal product
        ),
    ),
    
    'tax_query'      => array(
        array(
            'taxonomy' => 'product_cat', // Use the correct taxonomy name for categories
            'field'    => 'term_id',
            'terms'    => $current_category_id,
            'operator' => 'IN',
        ),
    ),
    
   
    'meta_query'     => array(
        
        array(
            'key'     => '_price',
            'value'   => array($min , $max  ),
            'type'    => 'NUMERIC',
            'compare' => 'BETWEEN',
        ),
    ),
    
    
    
);


$args2 = array(
    'post_type'      => 'product',
    'posts_per_page' => 300,
    'orderby'        => array(
        'meta_value_num' => 'DESC', // Order by the presence of add to cart
        'title'          => 'ASC',  // Then order by product title
    ),
    'meta_query'     => array(
        'relation' => 'OR',
        array(
            'key'     => '_product_url',
            'compare' => 'EXISTS',  // Check if external product
        ),
        array(
            'key'     => '_product_url',
            'compare' => 'NOT EXISTS',  // Check if internal product
        ),
    ),
    
    'tax_query'      => array(
        array(
            'taxonomy' => 'product_cat', // Use the correct taxonomy name for categories
            'field'    => 'term_id',
            'terms'    => $current_category_id,
            'operator' => 'IN',
        ),
    ),
    
   

);


if(isset($_GET['min_price'])){
    $products = new WP_Query( $args );
}else{
    $products = new WP_Query( $args2 );
}
// echo '<pre>';
// print_r($products);
// echo '</pre>';

// if ( $products_query->have_posts() ) {
//     while ( $products_query->have_posts() ) {
//         $products_query->the_post();

//         // Display or manipulate product data
//         the_title();  // Display product title
        
//         the_id(); // Display product content
//         echo '<br>';
//         // ... other product data
//     }

//     wp_reset_postdata(); // Reset the post data to the main query
// } else {
//     // No products found
//     echo 'No products found.';
// }






	if ( wc_get_loop_prop( 'total' ) ) {
	    
	    if(isset($_GET['orderby']) || isset($_GET['s'])){
	        	while ( have_posts() ) {
        		    
        		    
        		    
        			the_post();
                
        			/**
        			 * Hook: woocommerce_shop_loop.
        			 *
        			 * @hooked WC_Structured_Data::generate_product_data() - 10
        			 */
        			do_action( 'woocommerce_shop_loop' );
        
        		wc_get_template_part( 'content', 'product' );
        		}
        	
	    }else{
        		while ( $products->have_posts() ) {
        		    
        		    
        		    
        			$products->the_post();
                
        			/**
        			 * Hook: woocommerce_shop_loop.
        			 *
        			 * @hooked WC_Structured_Data::generate_product_data() - 10
        			 */
        			do_action( 'woocommerce_shop_loop' );
        
        		wc_get_template_part( 'content', 'product' );
        		}
        	}
	
	}

	woocommerce_product_loop_end();

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

if ( basel_get_opt( 'cat_desc_position' ) == 'after' ) {
	/**
	 * Hook: woocommerce_archive_description.
	 *
	 * @hooked woocommerce_taxonomy_archive_description - 10
	 * @hooked woocommerce_product_archive_description - 10
	 */
	do_action( 'woocommerce_archive_description' );
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

if ( ! basel_is_woo_ajax() ) {
	get_footer( 'shop' ); 
} else {
	basel_page_bottom_part();
}
