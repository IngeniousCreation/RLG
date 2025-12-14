<?php
//wp_die('aaaaaa');
global $product;
$category = get_queried_object();
//echo $category->term_id;

$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			//'product_cat' => 'uncategorized'
			
			'tax_query' => array( 
    array(
      'taxonomy' => 'product_cat',
      'field' => 'id',
      'terms' => $category->term_id
    ))
			);
		$loop = new WP_Query( $args );
		
		//echo'<pre>';
		//print_r($loop);
		//echo '</pre>';
		
		
		if ( $loop->have_posts() ) {
		     echo '<ul class="products columns-3">';
		    
			while ( $loop->have_posts() ) : $loop->the_post();
				if(get_class(wc_get_product( get_the_ID()  )) == 'WC_Product_External') {
				wc_get_template_part( 'content', 'product' );
				} else if(get_class(wc_get_product( get_the_ID()  )) !== 'WC_Product_External') {
				wc_get_template_part( 'content', 'product' );
				}
			endwhile;
		} else {
			echo __( 'No products found' );
		}
		wp_reset_postdata();
		
		 echo '</ul>';
		
		


?>