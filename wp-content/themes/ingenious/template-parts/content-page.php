<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ingenious
 */

?>

<?php 
if ( is_product_category() ){
    global $wp_query;

    // get the query object
    $cat = $wp_query->get_queried_object();
    
    
    echo '<pre>';
    //print_r($cat);
    echo '</pre>';

    // get the thumbnail id using the queried category term_id
    $thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true ); 

    // get the image URL
    $image = wp_get_attachment_url( $thumbnail_id ); 

    // print the IMG HTML
    //echo "<img src='{$image}' alt='' width='762' height='365' />";
    
    $enter_header = '<style> .entry-header {background-image:url("';
    $enter_header .= get_the_post_thumbnail_url();
    $enter_header .= '");text-align: center;color: white;min-height: 11em; background-repeat: no-repeat; background-color: #d8d8d8; background-size:cover;}';
    $enter_header .= '</style>';
    echo $enter_header;
    
}
else {
    
    $enter_header = '<style> .entry-header {background-image:url("';
    $enter_header .= get_the_post_thumbnail_url();
    $enter_header .= '");text-align: center;color: white;min-height: 11em; background-repeat: no-repeat; background-color: #d8d8d8; background-size:cover;}';
    $enter_header .= '</style>';
    echo $enter_header;
  
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php echo (is_product_category()) ? 'loop-shop-products-all' : ''; ?>>
	<?php if (!(is_product_category() || is_page('shop') )) { ?>
	
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->
	
		<?php
if ( function_exists('yoast_breadcrumb') ) {
  yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
}
?>
<?php } ?>

	<?php //ingenious_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		do_action('show_content_on_pages');
		//the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ingenious' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Edit <span class="screen-reader-text">%s</span>', 'ingenious' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
