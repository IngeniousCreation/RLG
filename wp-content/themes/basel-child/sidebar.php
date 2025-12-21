<?php
/**
 * The sidebar containing the secondary widget area
 *
 * Displays on posts and pages.
 *
 * If no active widgets are in this sidebar, hide it completely.
 */

$sidebar_class = basel_get_sidebar_class();

$sidebar_name = basel_get_sidebar_name();

if( strstr( $sidebar_class, 'col-sm-0' ) ) return;

if ( basel_get_opt( 'shop_hide_sidebar_tablet' ) || basel_get_opt( 'shop_hide_sidebar_mobile' ) || basel_get_opt( 'shop_hide_sidebar_desktop' ) || basel_get_opt( 'hide_main_sidebar_mobile' ) ) {

	basel_enqueue_inline_style( 'opt-off-canvas-sidebar' );
}

// Show sidebar if widgets are active OR if it's shop/category page (for custom filters)
$show_sidebar = is_active_sidebar( $sidebar_name ) || ( ( is_product_category() || is_shop() ) && $sidebar_name === 'sidebar-shop' );

if ( $show_sidebar ) : ?>
	<aside class="sidebar-container <?php echo esc_attr( $sidebar_class ); ?> area-<?php echo esc_attr( $sidebar_name ); ?>">
		<div class="basel-close-sidebar-btn"><span><?php esc_html_e( 'Close', 'basel' ); ?></span></div>
		<div class="sidebar-inner basel-scroll">
			<div class="widget-area basel-sidebar-content">
				<?php do_action( 'basel_before_sidebar_area' ); ?>
				<?php dynamic_sidebar( $sidebar_name ); ?>
				<?php do_action( 'basel_after_sidebar_area' ); ?>
			</div><!-- .widget-area -->
		</div><!-- .sidebar-inner -->
	</aside><!-- .sidebar-container -->
<?php endif; ?>





