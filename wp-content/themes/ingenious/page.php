<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ingenious
 */

get_header();
?>

<?php if( is_category() || is_product_category() || is_page('shop') ){ 

    include 'template-parts/sidebar-page.php'; 

} else {
    include 'template-parts/full-width-page.php'; 
}

?>


<?php
get_footer();
?>