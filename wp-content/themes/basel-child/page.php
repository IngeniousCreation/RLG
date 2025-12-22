<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that other
 * 'pages' on your WordPress site will use a different template.
 */

get_header(); ?>

<style>
/* Hide default Basel page title */
.page-title.title-shop,
.page-title-default {
    display: none !important;
}
</style>

<?php 
// Get content width and sidebar position
$content_class = basel_get_content_class();
$page_layout = basel_get_page_layout();
?>

<div class="site-content <?php echo esc_attr( $content_class ); ?>" role="main">

	<?php /* The loop */ ?>
	<?php while ( have_posts() ) : the_post(); ?>

		<!-- Custom Page Header with Breadcrumbs -->
		<div class="rlg-page-header">
			<?php
			// Display breadcrumbs before H1
			if ( function_exists('woocommerce_breadcrumb') ) {
				woocommerce_breadcrumb(array(
					'delimiter'   => ' › ',
					'wrap_before' => '<nav class="rlg-breadcrumbs-page" aria-label="Breadcrumb">',
					'wrap_after'  => '</nav>',
					'before'      => '',
					'after'       => '',
					'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
				));
			}
			?>
			<h1 class="rlg-page-title"><?php the_title(); ?></h1>
		</div>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="entry-content">
				<?php the_content(); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'basel' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
			</div><!-- .entry-content -->
		</article><!-- #post -->

		<?php comments_template(); ?>
	<?php endwhile; ?>

</div><!-- .site-content -->

<?php if( $page_layout == 'sidebar-left' || $page_layout == 'sidebar-right' ): ?>
	<div class="sidebar-container col-sm-12 col-md-3">
		<?php get_sidebar(); ?>
	</div>
<?php endif; ?>

<?php get_footer(); ?>

