<?php
//wp_die('aaaaaa');
global $product;










$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1
			);
		$loop = new WP_Query( $args );
		
		echo '<pre>';
		//print_r($loop);
		echo '</pre>';
		
		if ( $loop->have_posts() ) {
		     echo '<ul class="products columns-3">';
		     //print_r($loop->the_post());
			while ( $loop->have_posts() ) : $loop->the_post();
			echo '<pre>';
			//print_r(wc_get_product(768)->name);
		//echo get_class(wc_get_product(768));
			echo '</pre>';
			    //if(get_the_id() == 768) {
			        //echo get_product_url(768);
		    	//echo get_the_permalink();
				if(get_class(wc_get_product( get_the_ID()  )) == 'WC_Product_External') {
 				
				wc_get_template_part( 'content', 'product' );
				}
			endwhile;
		} else {
			echo __( 'No products found' );
		}
		wp_reset_postdata();
		
		 echo '</ul>';
		
		
		
		
		
		
		
		
  /*  $args = array(
          "post_type" => array('product'),
          "post_status" => "publish",
          "posts_per_page" => 1,
        );
    $count = 0;
    $the_query = new WP_Query( $args );
    $site_name = get_bloginfo('url');
    $post_name = $the_query->posts[$count]->post_name;
    $p_url =  get_the_permalink();
    echo '<ul class="products columns-3">';
    while($the_query->have_posts()) {
        $the_query->the_post();
        //echo get_the_id();
         
        //echo '<pre>';
        //print_r($the_query->the_post());
        //echo '</pre>';
        if(get_the_id() == 808){
        echo '<li>';
            echo wc_get_template_part( 'content', 'product' );
        //echo '<br /><a href="'.get_permalink().'">' . woocommerce_get_product_thumbnail().' '.get_the_title().'</a>';
        //echo "<p>{$site_name}/{$post_name}</p>";
        //echo "<p>{$p_url}</p>";
        echo "</li>";
        }
        
        $count++;
    }
    
    echo '</ul>';







echo '<pre>';
//print_r($the_query->posts[0]->ID);
print_r($the_query->posts);
echo '</pre>';
//$product = wc_get_product(get_the_ID());
print_r($product);
*/


?>