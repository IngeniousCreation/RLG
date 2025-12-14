	<main id="primary" class="site-main <?php echo (is_product_category() || is_page('shop') ) ? 'shop-loop-page' : 'info-content-page'; ?>">
        
        
        <?php if(is_front_page()){ 
            include 'slider.php';    
        }
        else {
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
        }
		?>

	</main><!-- #main -->