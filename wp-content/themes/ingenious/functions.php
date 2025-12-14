<?php
/**
 * ingenious functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ingenious
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'ingenious_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function ingenious_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on ingenious, use a find and replace
		 * to change 'ingenious' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'ingenious', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'ingenious' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'ingenious_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
		
		add_theme_support('woocommerce');
	}
endif;
add_action( 'after_setup_theme', 'ingenious_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function ingenious_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'ingenious_content_width', 640 );
}
add_action( 'after_setup_theme', 'ingenious_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function ingenious_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'ingenious' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'ingenious' ),
			//'before_widget' => '<section id="%1$s" class="widget %2$s">',
			//'after_widget'  => '</section>',
			//'before_title'  => '<h2 class="widget-title">',
			//'after_title'   => '</h2>',
		)
	);
	
	
         register_sidebar( array(
            'name' => 'Footer Sidebar 1',
            'id' => 'footer-sidebar-1',
            'description' => 'Appears in the footer area',
            'before_widget'  => '',
            'after_widget'   => "",
            //'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            //'after_widget' => '</aside>',
            //'before_title' => '<h3 class="widget-title">',
            //'after_title' => '</h3>',
            ) );

        register_sidebar( array(
            'name' => 'Footer Sidebar 2',
            'id' => 'footer-sidebar-2',
            'description' => 'Appears in the footer area',
             'before_widget'  => '',
            'after_widget'   => "",
            //'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            //'after_widget' => '</aside>',
            //'before_title' => '<h3 class="widget-title">',
            //'after_title' => '</h3>',
            ) );
            
        register_sidebar( array(
            'name' => 'Footer Sidebar 3',
            'id' => 'footer-sidebar-3',
            'description' => 'Appears in the footer area',
             'before_widget'  => '',
            'after_widget'   => "",
            //'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            //'after_widget' => '</aside>',
            //'before_title' => '<h3 class="widget-title">',
            //'after_title' => '</h3>',
            ) );
            
             register_sidebar( array(
            'name' => 'Footer Sidebar 4',
            'id' => 'footer-sidebar-4',
            'description' => 'Appears in the footer area',
             'before_widget'  => '',
            'after_widget'   => "",
            //'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            //'after_widget' => '</aside>',
            //'before_title' => '<h3 class="widget-title">',
            //'after_title' => '</h3>',
            ) );
            
            
            register_sidebar( array(
            'name' => 'Footer Sidebar 5',
            'id' => 'footer-sidebar-5',
            'description' => 'Appears in the footer area',
             'before_widget'  => '',
            'after_widget'   => "",
            //'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            //'after_widget' => '</aside>',
            //'before_title' => '<h3 class="widget-title">',
            //'after_title' => '</h3>',
            ) );


}



add_action( 'widgets_init', 'ingenious_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
 

function ingenious_scripts($hook) {
	//wp_enqueue_style( 'ingenious-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'ingenious-style', 'rtl', 'replace' );
	
	wp_enqueue_style ('custom-css' , get_template_directory_uri() . '/assets/css/custom.css' , array() , rand() . '11');
	wp_enqueue_style ('common-css' , get_template_directory_uri() . '/assets/css/common.css' , array() , rand() . '11');
	//wp_enqueue_style ('jquery.skeleton' , get_template_directory_uri() . '/assets/css/jquery.skeleton.css' , array() , rand() . '11');
	wp_enqueue_style ('normalize-css' , get_template_directory_uri() . '/assets/css/normalize.css');
	
	wp_enqueue_style ('max992-css' , get_template_directory_uri() . '/assets/css/max-width992.css' , array() , rand() , 'only screen and (max-width: 992px)' );
	
	wp_enqueue_style ('min992-css' , get_template_directory_uri() . '/assets/css/min-width992.css' , array() , rand() , 'only screen and (min-width: 992px)' );

	//wp_enqueue_script( 'ingenious-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
    
    //wp_enqueue_script( 'jquery.scheletrone' , get_template_directory_uri() . '/assets/js/jquery.scheletrone.js' , array() , rand() . '11' , true);	
	wp_enqueue_script( 'custom-js' , get_template_directory_uri() . '/assets/js/custom-js.js' , array() , rand() . '11' , true);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
	
	echo $hook;
}
add_action( 'wp_enqueue_scripts', 'ingenious_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}


// Disables the block editor from managing widgets in the Gutenberg plugin.
//add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );

// Disables the block editor from managing widgets.
//add_filter( 'use_widgets_block_editor', '__return_false' );


function disable_page_header( $return ) {
   return false;
}
//add_filter( 'the_title', 'disable_page_header');

function enable_page_header( $return ) {
   return $return;
}



//add_filter( 'woocommerce_get_catalog_ordering_args', 'bbloomer_first_sort_by_stock_amount', 9999 );
 
function bbloomer_first_sort_by_stock_amount( $args ) {
   $product = wc_get_product(get_the_ID());
   //echo '<pre>';
   //print_r($product);
   //echo $product->get_name();
   //echo '</pre>';
   
   $args['orderby'] = 'meta_value';
   $args['meta_key'] = '_stock_status';
   return $args;
}

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 11 );


add_filter('woocommerce_default_catalog_orderby_options', 'custom_add_to_cart_sorting_option');

function custom_add_to_cart_sorting_option($options) {
    $options['custom_add_to_cart'] = __('Show Add to Cart First', 'woocommerce');
    return $options;
}


//add_action( 'woocommerce_single_product_summary', 'wdm_add_custom_fields', 10 );
add_filter( 'the_title', 'enable_page_header');
function wdm_add_custom_fields()
{
    
    
    /* if your have added information using a metabox */
    echo '<h1>' . get_the_title() . '</h1>';
    $product = wc_get_product(get_the_ID());
   
   // echo ($product->get_sale_price() != null) ? '<span class="original-price">' . $product->get_price() . get_woocommerce_currency_symbol() . '</span><span class="sale-price">' . $product->get_sale_price() . get_woocommerce_currency_symbol() . '</span>' : 
'<span class="sale-price"><label></label>' . get_woocommerce_currency_symbol() . ' ' . $product->get_price() . '</span>'; 
    /* if you have used ACF to add custom fields */
    //echo 'XYZ';
}
//add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
//add_action( 'woocommerce_after_single_variation', 'action_woocommerce_product_thumbnails'); 
function action_woocommerce_product_thumbnails() {
  
         global $product;    
  // echo wc_get_stock_html( $product );
  $product_id = 151;
$product = wc_get_product($product_id);
$variations = $product->get_available_variations();


foreach($variations as $ab ) {
    //echo $ab['attributes']['attribute_pa_size'] . $ab['attributes']['attribute_pa_color'] . '<br>' . $ab['max_qty'] .  '<br>' ;
}



//echo '<pre>';
//print_r($variations);
//echo '</pre>';
            
   
       
    
    
}



// remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
// remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
// remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
// remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
// remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

// remove_action( 'woocommerce_before_subcategory', 'woocommerce_template_loop_category_link_open', 10 );
// remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );
// remove_action( 'woocommerce_after_subcategory', 'woocommerce_template_loop_category_link_close', 10 );

// remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
// remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
// remove_action( 'woocommerce_before_shop_loop', 10);

//add_action( 'woocommerce_after_shop_loop_item');, 'bbloomer_show_stock_shop', 1 );
  
function bbloomer_show_stock_shop() {
   global $product;
   echo wc_get_stock_html( $product );
}



//remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
//remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
//remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
//remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );




add_filter('woocommerce_before_shop_loop_item', 'abb_shop_loop');
function abb_shop_loop($qqq) {
    //echo'wahaj123';
    return $qqq;
}

add_filter('show_content_on_pages' , 'customise_content_of_page');
function customise_content_of_page($a) {
    //$a = the_content();
    if(is_product_category() && !is_product_category(294)) {
    
        //include 'functions-part/sort-products.php';
        return the_content();
    } else {
        return the_content();
    }
    //return $a;
}





//add_action( 'woocommerce_shop_loop_item_title', 'custom_loop_details_sec', 10 );

function custom_loop_details_sec() {
 
    $data = wc_get_product(get_the_ID());
    //echo '<pre>';
    //print_r ($data->product_url);
    //echo '</pre>';
    $data =  $data->product_url;
    $data = explode('.' , $data);
    echo $data[1];
   //echo 'wajj' . $data->get_name();
    
}



function get_price() {
    //add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
}


//add_action( 'woocommerce_shop_loop_item_title', 'custom_loop_details', 10 );
function custom_loop_details() {
    $data = '<div>';
    $data .= get_the_title();
    
    //$data .= '<span class="price"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">';
    //$data .= '$';
   // $data .= '</span>';
    $data = wc_get_product(get_the_ID())->get_price() . '111';
  //  $data .= '</bdi></span> – <span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">';
   // $data .= '$';
   // $data .= '</span>';
  
  //  $data .= '</bdi></span></span>';
    $data .= '</div>';
    
   echo $data;
    
}















add_action( 'woocommerce_before_shop_loop_item_title', 'add_on_hover_shop_loop_image' ) ; 

function add_on_hover_shop_loop_image() {
    if(wc_get_product()->get_gallery_image_ids()) {


    $image_id = wc_get_product()->get_gallery_image_ids()[0] ; 

    if ( $image_id ) {

        echo wp_get_attachment_image( $image_id , array(400,400) ) ;

    } else {  //assuming not all products have galleries set

       // echo wc_get_product()->get_gallery_image_ids()[0]; 

    }

}

echo '<div id="shop_loop_buttons" class="wahaj-11">';
}

add_action( 'woocommerce_after_shop_loop_item', 'after_loop_price' ) ; 
function after_loop_price() {
    echo '</div> <!-- CLOSE DIV -->';
    
    
}



//add_filter('comments_open', '__return_false', 20, 2);


add_filter('pings_open', '__return_false', 20, 2);




// add_filter( 'woocommerce_get_image_size_gallery_thumbnail', function( $size ) {
// return array(
// 'width' => 300,
// 'height' => 450,
// 'crop' => 0,
// );
// } );

//if(is_home()) {
//remove_filter('the_content' , 'remove_filter_cus');
function remove_filter_cus() {
    return;
}

//}
//add_filter( 'the_content', 'wpse247535_display_news_slider');
function wpse247535_display_news_slider( $content ) {
    if ( is_home() ) {
        $contentt = explode('<p>' , $content);
       //$content .= "more plugin content";
    }
   //echo '<pre>';
   
   echo $content;
  
   //echo '</pre>';
   
   //return $content();
    //the_excerpt();
}

/* ICONS */
add_action('wp_head' , 'faviconfunc' , 5);
function faviconfunc() {
    
    $main_url = get_template_directory_uri() . '/assets/favicons';
    $favilinks =  "<link rel=\"apple-touch-icon\" sizes=\"57x57\" href=\"{$main_url}/apple-icon-57x57.png\">\n";
    $favilinks .= "<link rel=\"apple-touch-icon\" sizes=\"60x60\" href=\"{$main_url}/apple-icon-60x60.png\">\n";
    $favilinks .= "<link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"{$main_url}/apple-icon-72x72.png\">\n";
    $favilinks .= "<link rel=\"apple-touch-icon\" sizes=\"76x76\" href=\"{$main_url}/apple-icon-76x76.png\">\n";
    $favilinks .= "<link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"{$main_url}/apple-icon-114x114.png\">\n";
    $favilinks .= "<link rel=\"apple-touch-icon\" sizes=\"120x120\" href=\"{$main_url}/apple-icon-120x120.png\">\n";
    $favilinks .= "<link rel=\"apple-touch-icon\" sizes=\"144x144\" href=\"{$main_url}/apple-icon-144x144.png\">\n";
    $favilinks .= "<link rel=\"apple-touch-icon\" sizes=\"152x152\" href=\"{$main_url}/apple-icon-152x152.png\">\n";
    $favilinks .= "<link rel=\"apple-touch-icon\" sizes=\"180x180\" href=\"{$main_url}/apple-icon-180x180.png\">\n";
    $favilinks .= "<link rel=\"icon\" type=\"image/png\" sizes=\"192x192\"  href=\"{$main_url}/android-icon-192x192.png\">\n";
    $favilinks .= "<link rel=\"icon\" type=\"image/png\" sizes=\"32x32\" href=\"{$main_url}/favicon-32x32.png\">\n";
    $favilinks .= "<link rel=\"icon\" type=\"image/png\" sizes=\"96x96\" href=\"{$main_url}/favicon-96x96.png\">\n";
    $favilinks .= "<link rel=\"icon\" type=\"image/png\" sizes=\"16x16\" href=\"{$main_url}/favicon-16x16.png\">\n";
    $favilinks .= "<link rel=\"manifest\" href=\"{$main_url}/manifest.json\">\n";
    $favilinks .= "<meta name=\"msapplication-TileColor\" content=\"#ffffff\">\n";
    $favilinks .= "<meta name=\"msapplication-TileImage\" content=\"/ms-icon-144x144.png\">\n";
    $favilinks .= "<meta name=\"theme-color\" content=\"#ffffff\">";
    echo  $favilinks ;
}

/* ICONS ENDS */


/* META TAGS */
add_action('wp_head', 'google_tag_manager_head', 0);
function  google_tag_manager_head() {
    
    /* ADSENSE */
    echo '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9987590822220213"
     crossorigin="anonymous"></script>';
    
    
    
    
    echo '<meta name="google-site-verification" content="FSzNBw-IuNdqyg_UovzqkcDHbombigymhtE2fli9bEg" />';
    
    echo "<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MGHNF56');</script>
<!-- End Google Tag Manager -->";


echo "<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src='https://www.googletagmanager.com/gtag/js?id=UA-140454394-4'></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-140454394-4');
</script>";
    
    
}

add_action('wp_body_open' , 'google_tag_manager_body', -1);
function google_tag_manager_body() {
    echo '<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MGHNF56"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->';
}

/* META TAGS END */



add_filter( 'body_class' , 'add_body_classes');
function add_body_classes($classes) {
    
    if(is_product_category() || is_page('shop')){
        //$classes =  array_merge( $classes, array( 'shop-cat-page' ) );
        $classes[] =  'shop-cat-page';
        //return $classes;
    } elseif(is_product()){
        //$classes =  array_merge( $classes, array( 'single-product-page' ) );
        $classes[] =  'single-product-page';
        
    } elseif(is_cart()){
        //$classes =  array_merge( $classes, array( 'cart-page' ) );
        $classes[] =  'cart-page';
        //return $classes;
    } elseif(is_checkout()){
        //$classes =  array_merge( $classes, array( 'checkout-page' ) );
        $classes[] =  'checkout-page';
        //return $classes;
    }
    return $classes;
}






remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );




//add_action( 'woocommerce_before_shop_loop', 'get_category_content', 11 );
function get_category_content(){
    return woocommerce_taxonomy_archive_description();
}


add_action('woocommerce_before_main_content' , 'wrapper_after_single_header' , 30);
function wrapper_after_single_header(){
    if(is_product()){
        echo '<div class="inner_single_page_content">';
    }
    
}

add_action( 'woocommerce_after_main_content', 'wrapper_closing_single_tag', 9 );
function wrapper_closing_single_tag(){
    if(is_product()){
    echo '<!-- after--shop--loop--closing --> </div> <!-- after--shop--loop--closing -->';
    }
}



add_action('woocommerce_before_shop_loop' , 'wrapper_after_header' , 9);
function wrapper_after_header(){
    echo '<div class="inner_shop_page_content">';
    echo '<a class="promo-banner" target="_blank" rel="nofollow" href="https://www.amazon.co.uk/dp/B01MYQWQYL?th=1&linkCode=ll1&tag=realleatherga-21&linkId=7bce4e6c7a4cef19d7cf9492c98c7d89&language=en_GB&ref_=as_li_ss_tl"><img src="https://realleathergarments.co.uk/wp-content/uploads/2022/04/young-banner-3.png" alt="youngsoul leather jackets"></a>';
}


// FAQ Setting - Change Priority number to change the display

add_action( 'woocommerce_after_shop_loop', 'wrapper_closing_tag', 10 );
function wrapper_closing_tag(){
    return woocommerce_taxonomy_archive_description();
    echo '<!-- after--shop--loop--closing --> </div> <!-- after--shop--loop--closing -->';
    
}


include 'functions-part/get_products.php';




add_action('wp_footer' , 'show_session', 100);
    function show_session() {

       
        
        if($_SERVER["REMOTE_ADDR"]==='116.90.97.168'){

        echo'<pre>';
        //echo'wahajj ';
        //print_r(wc()->session->customer);
        
        //print_r(wc()->session->get( 'store_api_draft_order', 0 ));
        
        print_r(wc()->session);
        
        
        print_r(wc()->session->get( 'store_api_draft_order', 0 ));
        
        //print_r($order);
        
        
       
        echo'</pre>';
        
     

    }



}


// NUMBER OF PRODUCTS TO SHOW PER PAGE.
function custom_woocommerce_products_per_page() {
    return 52;
}
add_filter('loop_shop_per_page', 'custom_woocommerce_products_per_page');

// PAGINATION PAGES, NO INDEX
function custom_pagination_meta_tags() {
    if ( is_paged() ) {
        echo '<meta name="robots" content="noindex, nofollow" />';
    }
}

add_action( 'wp_head', 'custom_pagination_meta_tags' );







