	<main id="primary" class="site-main shop-loop-page main-category">
        	
        	<header class="entry-header">
	        	<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
        	</header><!-- .entry-header -->
        		<?php
if ( function_exists('yoast_breadcrumb') ) {
  yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
}
?>
      <div id="inner-main-cat-1">
      <?php
       		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
        
		?>
	<?php
//get_sidebar();
?>
</div>
	</main><!-- #main -->
	
	
	
