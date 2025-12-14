<div class="slider">

<div class="slider-children active">
    <div class="sl-left">
        <span><h2 class="slider-heading">RLG</h2>
        <p class="slider-p">Kid's Leather Jackets Collection</p></span>
        <button class="btn-primary"><a href="<?php get_bloginfo('url'); ?>/kids">Shop now</a></button>
    </div>    
    
      <div class="sl-right" style="background-image:url('https://staging.stabene.net/realnew/wp-content/uploads/2021/11/kids-jacket.png'); /*background-size:cover;     box-shadow: 7px 0px 7px 0px #cacaca inset;*/">
       
    </div> 
</div>


<div class="slider-children">
    <div class="sl-left">
        <span><h2 class="slider-heading">RLG</h2>
        <p class="slider-p">Men's Leather Jackets Collection</p></span>
        <button class="btn-primary"><a href="<?php get_bloginfo('url'); ?>/men">Shop now</a></button>
    </div>    
    
      <div class="sl-right" style="background-image:url('https://staging.stabene.net/realnew/wp-content/uploads/2021/11/man-leather-2.png'); /*background-size:cover;     box-shadow: 7px 0px 7px 0px #cacaca inset; */">
      
    </div> 
</div>



<div class="slider-children">
    <div class="sl-left">
        <span><h2 class="slider-heading">RLG</h2>
        <p class="slider-p">Women's Leather Jackets Collection</p></span>
        <button class="btn-primary"><a href="<?php get_bloginfo('url'); ?>/women">Shop now</a></button>
    </div>    
    
      <div class="sl-right" style="background-image:url('https://staging.stabene.net/realnew/wp-content/uploads/2021/11/woman-leather-jacket.png');">
      
    </div> 
</div>



<!--<div class="slider-children ">
    <div class="sl-left">
        <span><h2 class="slider-heading">R L G</h2>
        <p class="slider-p">All Collection</p></span>
        <button class="btn-primary"><a href="">Shop now</a></button>
    </div>    
    
      <div class="sl-right" style="background-image:url('https://staging.stabene.net/unofficial/wp-content/uploads/2021/10/accessories-99911.png');">
      
    </div> 
</div>-->






<div class="car-button">
    <ul>
        <!--<li></li>-->
        <!--<li></li>-->
        <!--<li></li>-->
    </ul>
    
    </div>

</div>


<section style="    text-align: center;
    margin-top: 1em;">
    <a href="https://www.amazon.co.uk/b?tag=realleatherga-21&linkCode=ur1&node=14075837031" target="blank">
        <img src="https://images-eu.ssl-images-amazon.com/images/G/02/handmade/2020/associates/personalgifting/XCM_CUTTLE_1234696_1240679_UK_3193375_728x90_en_GB.jpg">
        
    </a>
    
</section>



<section id="home-top-cat" class="">
    <?php
    
        $orderby = 'name';
        $order = 'desc';
        $hide_empty = false ;
        $cat_args = array(
            'orderby'    => $orderby,
            'order'      => $order,
            'hide_empty' => $hide_empty,
        );
 
$product_categories = get_terms( 'product_cat', $cat_args );
 
if( !empty($product_categories)){
    
        echo '<ul>';
    foreach ($product_categories as $key => $category) {
        if(($category->term_id === 16) || ($category->term_id === 20) || ($category->term_id === 19) || ($category->term_id === 22) ){
        echo '<li>';
        echo '<a href="'.get_term_link($category).'" >';
        echo $category->name;
        echo '</a>';
        echo '</li>';
        }
        
        
    }
    echo '</ul>';
}
    
    
    ?>
    
</section>







<section id="about" class="max-w m-inline" style="--percentile : 90%; --valuepx: 40px auto; ">
    
    <h3 class="t-center main-headings d-none" ><i>Discover More Unique Products</i> </h3>
    <div class="aboutcontent">
        
        <div class="stayconnect">
            <h4>CONNECT WITH US</h4>
           <ul>
               <li><a href="https://web.facebook.com/Real-Leather-Garments-103018171736846?_rdc=1&_rdr" class="ico fb-icon"></a></li>
               <li><a href="https://www.instagram.com/realleathergarments/" class="ico insta-icon"></a></li>
               <li><a href="https://twitter.com/realleatheruk" class="ico twitter-icon"></a></li>
               <!--<li><a href="mailto:info@unofficialclothingstore.pk" class="ico email-icon"></a></li>
               -->
               <!--<li><a href=""><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/whatsapp-icon.svg"></a></li>-->
               <!--<li><a href=""><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/call-icon.svg"></a></li>
               <li><a href=""><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/email-icon.svg"></a></li>-->
           </ul> 
       </div>
       <div><h3 class="main-headings"><i>Discover More Unique Products</i> </h3>
      <p>Our company brings an exceptional range of high quality, genuine leather products that will keep you and your loved ones warm, while helping you stay on top of the fashion scene. From timeless and elegant jackets and clothing for men and women to some smart products for feisty kids, Real Leather garments has it all.</p>
    </div>
    </div>
    
</section>






<section id="popular-collection" class="">
    
    <h3 class="t-center main-headings"><i>Popular Collection</i> </h3>
    
    <div class="grid-popular">
        
        <a href="<?php get_bloginfo('url'); ?>/men" class="grid-0">Men</a>
        <a href="<?php get_bloginfo('url'); ?>/women" class="grid-1">Women</a>
        <a href="<?php get_bloginfo('url'); ?>/kids" class="grid-2">Kid's</a>
        
    <?php
    
   /* $i = 0;
        foreach ($product_categories as $key => $category) {
        
        
         $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true ); 

    // get the image URL
    $image = wp_get_attachment_url( $thumbnail_id ); 
    
    
    if( ($category->term_id == 291) || ($category->term_id == 293) || ($category->term_id == 294) ) {
     echo '<div class="grid-' . $i . '" style=" background-image:url(' . $image . '); background-repeat: no-repeat;     filter: saturate(0.5); background-position: top;" >';
    //echo "<img src='{$image}' alt='' width='762' height='365' />";
       
        echo '<a href="'.get_term_link($category).'" >';
        echo $category->name;
        echo '</a>';
        echo '</div>';
        
        $i++;
        
    }
        
    }
    
    */
    ?>
    </div>
    
    </section>
    
    
    
<section id="home_products">
    
    <h3 class="t-center main-headings"><i>Best Selling</i> </h3>
   
        <ul class="pop-cat-nav">
            <li>Kids</li>
            <li>Men</li>
            <li>Women</li>
        </ul>
    
    
    <div class="woocommerce">
    <?php 
    
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
                    'terms' => 'kids'
                )
            )
        );
$getProducts = new WP_Query($args);



 echo'<pre>';
    //print_r($getProducts);
    echo '</pre>';
    
    
    
    
echo '<pre>';
//print_r($getProducts);
echo'</pre>';

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
   // $r_Pro .=  woocommerce_template_loop_add_to_cart();
    $r_Pro .= "</a></div>";
    
    
    echo $r_Pro;
    
    
}
echo'</div>';




//echo '<pre>';
//print_r(new WP_Query($args));
//echo '</pre>';    
            /*$showposts = new WP_Query(['post_type' => 'product' , 'posts_per_page' => 1 ,
            
           'tax_query' => [
                'taxonomy' => 'product_cat',
                 'field'         => 'slug',
            'terms'         => 'kids', // Possibly 'exclude-from-search' too
            'operator'      => 'NOT IN'
                ]
            
            ]);
            echo'<pre>';
            //print_r(new WP_Query(array('post_type'=> 'product')));
            
            print_r( $showposts);
            echo '</pre>';
            */
    ?>
    
    </div>
    
</section>






