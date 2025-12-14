<?php

function get_ajax_posts_new() {
    
    if (!empty($_POST)) {
        $thisLinkHover_n = $_POST['thisLinkHover_n'];
    }
    
    
    
    
      global $product;
    
        $args = array(
            'post_type'             => 'product',
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => 1,
            'posts_per_page'        => '8',
            'tax_query'             => array(
                
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $thisLinkHover_n
                )
            )
        );
$getProducts = new WP_Query($args);

    
    
    


echo '<div class="popular_pr">';
while($getProducts->have_posts()){
    $getProducts->the_post();
    $title = get_the_title();
    $url = get_the_permalink();
    $thumb = get_the_post_thumbnail(get_the_id(), array(200,180));
    $price = get_post_meta( get_the_ID(), '_price', true );
    $price = wc_price( $price );
    
   
   //wc_get_template_part( 'content', 'product' );
    
    $r_Pro = "<div class='inner_popular_pr'><a href='{$url}'>";
    $r_Pro .= $thumb;
    $r_Pro .= "<h4>{$title}</h4>";
   
    $r_Pro .= "<div class='price_pop'>{$price}</div>";
    $r_Pro .= "</a></div>";
    
    
    echo $r_Pro;
    
    
}
echo'</div>';



    
    
}




add_action('wp_ajax_get_ajax_posts', 'get_ajax_posts_new');
add_action('wp_ajax_nopriv_get_ajax_posts', 'get_ajax_posts_new');


include'query_products.php';



//add_action('wp_footer' , 'enq_ajax_query' , 100);
function enq_ajax_query(){
    $aj_uri = admin_url('admin-ajax.php');
    echo "<script>
    (function($) {
         $(document).ready(function(){
        
        $('.pop-cat-nav li').click(function(){
            var cat_text = (this.innerText);
            var cat_text = cat_text.toLowerCase();
            //alert(cat_text);
            
            $.ajax({
                type: 'POST',
                url:  '$aj_uri',
                data:{thisLinkHover_n : cat_text , action : 'get_ajax_posts_new'},
                success: function(data){
                    console.log(data);
                    //$('.popular_pr').html(result);
                }
                
                
                
                
            });
            
            
        });
        
         });
   
})(jQuery);
    </script>
    ";
    
}


?>