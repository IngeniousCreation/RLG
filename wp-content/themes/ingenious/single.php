<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package ingenious
 */

get_header();
?>
<?php 
    $image = get_the_post_thumbnail_url(); 
?>






	<main id="primary" class="site-main <?php echo (is_product()) ? 'single_product_page' : ''?>">
	    
	    <div id="single_page">
	        <div class="delivery-notice">Notice: Normal Delivery within 10 - 15 days</div>
	   <?php if(!is_product()) : ?>
	    <header class="entry-header" <?php echo 'style="background-image:url('. $image .')"';?> >
	        	<h1 class="entry-title"><?php the_title(); ?></h1>        	</header>
	        
	    <?php endif; ?>     
	        <?php	if ( function_exists('yoast_breadcrumb') ) {
                 yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
            }
?>

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', get_post_type() );

			the_post_navigation(
				array(
					'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'ingenious' ) . '</span> <span class="nav-title">%title</span>',
					'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'ingenious' ) . '</span> <span class="nav-title">%title</span>',
				)
			);

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>
</div>
	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
