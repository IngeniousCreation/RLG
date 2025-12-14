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
if (is_user_logged_in()) {
    
    
    function __request_posts($page_num , $per_page_result , $type=''){
        
        
            $category_id = get_queried_object_id();
    
            // Replace with your actual site URL and consumer key/secret
            $api_url = 'https://realleathergarments.co.uk/wp-json/wc/v3/products';
            $consumer_key = 'ck_8d84c660a2ceb11bbe18afec07bf85a6f697be82';
            $consumer_secret = 'cs_23d0da0c37885402ee84aa2aef8779c9158fca71';
            
            // Set parameters for the API request
            $page = $page_num;
            $per_page = $per_page_result;
            $category_slug = $category_id; // Replace with the actual category slug
            $type_r = (!empty($type)) ? '&type=' . $type : '';  
            // Build the API request URL with category filter


            $request_url = "$api_url?page=$page&per_page=$per_page&category=$category_slug{$type_r}";



            // Build the API request URL
            //$request_url = "$api_url?page=$page&per_page=$per_page";
            
            $headers = array(
                'Authorization: Basic ' . base64_encode("$consumer_key:$consumer_secret"),
            );
            
            
            
            // Initialize cURL session
            $ch = curl_init($request_url);
            
            // Set cURL options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_HEADER, true);
            
            // Execute cURL session
            $response = curl_exec($ch);
            
            // Check for cURL errors
            if (curl_errno($ch)) {
                echo 'cURL error: ' . curl_error($ch);
            } else {
                // Parse the JSON response
                list($headers, $body) = explode("\r\n\r\n", $response, 2);
                    preg_match('/x-wp-total: (\d+)/', $headers, $total_matches);
                    preg_match('/x-wp-totalpages: (\d+)/', $headers, $total_pages_matches);
            
                    $total_count = isset($total_matches[1]) ? intval($total_matches[1]) : 0;
                    $total_pages = isset($total_pages_matches[1]) ? intval($total_pages_matches[1]) : 0;
               
                
                $products = json_decode( $body, true);
                // Print products using print_r
                // echo '<h2>Products:</h2>';
                echo '<pre>';
                echo $total_count;
                print_r($total_pages);
                echo '</pre>';
            }
            
            // Close cURL session
            curl_close($ch);
    }
    
    
    
    
    echo __request_posts(1, 100, 'simple');
    
    






}
?>


<?php

$html = '<ul class="products products-custom-column columns-4">';

foreach($products as $product){
    $p_id = $product['id'];
    $p_typeListSimple = $product['type'];
    $p_type = $product['type'];
    $p_name = $product['name'];
    $p_link = $product['permalink'];
    $p_price  = $product['price'];
    $p_price  = (!empty($p_price)) ? "<ins><span class='woocommerce-Price-amount amount'><bdi><span class='woocommerce-Price-currencySymbol'>£</span>&nbsp;{$p_price} </bdi></span></ins>" : "";
    $p_regular  = $product['regular_price'];
    $p_regular = (!empty($p_regular)) ? "<del aria-hidden='true'><span class='woocommerce-Price-amount amount'><bdi><span class='woocommerce-Price-currencySymbol'>£</span>&nbsp;{$p_regular}</bdi></span></del>" : "";
    $btn  = $product['button_text'];
    $btn = (empty($btn) && $p_type !== 'external') ? 'Select Options' : 'View on Amazon';
    $thumb  = $product['images'][0]['src'];
    $thumb_2  = $product['images'][1]['src'];
    $btn_link = (!empty($product['external_url'])) ? $product['external_url'] : $p_link;
    $typeList = ($p_type == 'external') ? 'external' : 'purchasable';
    $p_stock = $product['stock_status'];
    $sale = $product['on_sale'];
    $saleList = ($sale == true) ? 'sale' : '';
    $saleHTML = ($sale == true) ? "<span class='onsale'>Sale!</span>" : '';
    $dataAddToCart = ($p_type !== 'external') ? "add_to_cart_button ajax_add_to_cart" : '';
    
    $html .= "
        <li class='product type-product post-{$p_id} status-publish {$p_stock} has-post-thumbnail {$saleList} shipping-taxable {$typeList} product-type-{$p_typeListSimple}'>
  <a target='__blank' href='{$p_link}' class='woocommerce-LoopProduct-link woocommerce-loop-product__link'>
    {$saleHTML}
    <img width='600' height='600' src='{$thumb}' class='attachment-woocommerce_thumbnail size-woocommerce_thumbnail offOnHover' alt='Soleil: Women’s Collarless Leather Jacket' decoding='async'>
    <img width='400' height='157' src='{$thumb_2}' class='attachment-400x400 size-400x400' alt='Deep Womens Red Leathers Jacket' decoding='async' loading='lazy'>
  </a>
  <div id='shop_loop_buttons' class='wahaj-11'>
    <a target='__blank' href='{$p_link}' class='woocommerce-LoopProduct-link woocommerce-loop-product__link'>
      <h2 class='woocommerce-loop-product__title'>{$p_name}</h2>
      <span class='price'>
        {$p_regular}
        {$p_price}
      </span>
      <div class='wc-ppcp-paylater-msg-shop-container' id='wc-ppcp-paylater-msg-2532' style='display: none'></div>
    </a>
    <a target='__blank' href='{$btn_link}' data-quantity='1' class='button wp-element-button product_type_{$p_typeListSimple} add_to_cart_button' data-product_id='{$p_id}' data-product_sku=' aria-label='Add “{$p_name}” to your cart' rel='nofollow'>{$btn}</a>
  </div>
  <!-- CLOSE DIV -->
</li>
    
    
    ";
    
    
    
   





    
    
   
    
}

$html .= '</ul>';



function navigation_custom(){
    
}

$html .= "<nav class='woocommerce-pagination'>
    <ul class='page-numbers'>
    <li><span aria-current='page' class='page-numbers current'>1</span></li>
    <li><a class='page-numbers' href='https://realleathergarments.co.uk/men-leather-jackets/page/2/?orderby=date'>2</a></li>
    <li><a class='page-numbers' href='https://realleathergarments.co.uk/men-leather-jackets/page/3/?orderby=date'>3</a></li>
    <li><a class='page-numbers' href='https://realleathergarments.co.uk/men-leather-jackets/page/4/?orderby=date'>4</a></li>
    <li><a class='next page-numbers' href='https://realleathergarments.co.uk/men-leather-jackets/page/2/?orderby=date'>→</a></li>
</ul>
</nav>";




add_filter('show_custom_loop' , '__show_custom_loop');
function __show_custom_loop($html){
    //echo $html;
   
}
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
	 
	
	 
	 
	do_action( 'woocommerce_before_shop_loop', $html );
	
    echo $html;	
//apply_fiters('show_custom_loop' , $html ); 

	woocommerce_product_loop_start();

	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Hook: woocommerce_shop_loop.
			 */
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
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
