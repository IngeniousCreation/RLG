<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ingenious
 */

?>


<div id="subscription-form">
    <?php echo do_shortcode('[email-subscribers-form id="2"]'); ?>
</div>

	<footer id="footer" class="site-footer">
	    
            <div id="footer-sidebar1">
                    <?php
                        if(is_active_sidebar('footer-sidebar-1')){
                        dynamic_sidebar('footer-sidebar-1');
                        }
                    ?>
            </div>
            <div id="footer-sidebar-2">
                    <?php
                        if(is_active_sidebar('footer-sidebar-2')){
                        dynamic_sidebar('footer-sidebar-2');
                        }
                    ?>
            </div>
            <div id="footer-sidebar3">
                    <?php
                        if(is_active_sidebar('footer-sidebar-3')){
                        dynamic_sidebar('footer-sidebar-3');
                        }
                    ?>
            </div>
            <div id="footer-sidebar4">
                    <?php
                        if(is_active_sidebar('footer-sidebar-4')){
                        dynamic_sidebar('footer-sidebar-4');
                        }
                    ?>
            </div>
            
             <div id="footer-sidebar5">
                    <?php
                        if(is_active_sidebar('footer-sidebar-5')){
                        dynamic_sidebar('footer-sidebar-5');
                        }
                    ?>
            </div>
            
            
		<!--<div class="site-info">
			<a href="<?php //echo esc_url( __( 'https://wordpress.org/', 'ingenious' ) ); ?>">
				<?php
				/* translators: %s: CMS name, i.e. WordPress. */
				//printf( esc_html__( 'Proudly powered by %s', 'ingenious' ), 'WordPress' );
				?>
			</a>
			<span class="sep"> | </span>
				<?php
				/* translators: 1: Theme name, 2: Theme author. */
				//printf( esc_html__( 'Theme: %1$s by %2$s.', 'ingenious' ), 'ingenious', '<a href="http://underscores.me/">Underscores.me</a>' );
				?>
		</div>
		-->
		<!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
