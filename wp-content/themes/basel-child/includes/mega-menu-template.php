<?php
/**
 * Custom Mega Menu Template
 * Renders the hardcoded mega menu HTML
 * 
 * @package Basel Child
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Render the mega menu
 */
function basel_child_render_mega_menu() {
	$menu_items = basel_child_get_mega_menu_config();
	$base_url   = home_url();

	?>
	<nav class="rlg-mega-menu" role="navigation" aria-label="Primary Navigation">
		<!-- Mobile Search Box -->
		<div class="rlg-mobile-search">
			<form class="rlg-mobile-search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="text"
					   class="rlg-mobile-search-input"
					   placeholder="Search for products..."
					   name="s"
					   autocomplete="off"
					   aria-label="Search">
				<input type="hidden" name="post_type" value="product">
				<button type="submit" class="rlg-mobile-search-button" aria-label="Search">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<circle cx="11" cy="11" r="8"></circle>
						<path d="m21 21-4.35-4.35"></path>
					</svg>
				</button>
				<div class="rlg-mobile-search-results"></div>
			</form>
		</div>

		<ul class="rlg-menu-list">
			<?php foreach ( $menu_items as $item ) : ?>
				<li class="rlg-menu-item <?php echo $item['has_mega'] ? 'has-mega-menu' : ''; ?>" data-menu-id="<?php echo esc_attr( $item['id'] ); ?>">
					<a href="<?php echo esc_url( $base_url . $item['url'] ); ?>" class="rlg-menu-link">
						<?php echo esc_html( $item['label'] ); ?>
						<?php if ( $item['has_mega'] ) : ?>
							<span class="rlg-menu-arrow" aria-hidden="true">›</span>
						<?php endif; ?>
					</a>
					
					<?php if ( $item['has_mega'] && ! empty( $item['columns'] ) ) : ?>
						<div class="rlg-mega-panel">
							<div class="rlg-mega-wrapper">
								<div class="rlg-mega-columns">
									<?php foreach ( $item['columns'] as $column ) : ?>
										<div class="rlg-mega-column">
											<?php if ( ! empty( $column['title'] ) ) : ?>
												<h3 class="rlg-column-title"><?php echo esc_html( $column['title'] ); ?></h3>
											<?php endif; ?>
											
											<?php if ( ! empty( $column['items'] ) ) : ?>
												<ul class="rlg-column-items">
													<?php foreach ( $column['items'] as $sub_item ) : ?>
														<li class="rlg-column-item">
															<a href="<?php echo esc_url( $base_url . $sub_item['url'] ); ?>" class="rlg-column-link">
																<?php echo esc_html( $sub_item['label'] ); ?>
															</a>
														</li>
													<?php endforeach; ?>
												</ul>
											<?php endif; ?>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>
	<?php
}

/**
 * Render mobile menu toggle button
 */
function basel_child_render_mobile_menu_toggle() {
	?>
	<button class="rlg-mobile-menu-toggle" aria-label="Toggle Menu" aria-expanded="false">
		<span class="rlg-toggle-icon">
			<span class="rlg-toggle-bar"></span>
			<span class="rlg-toggle-bar"></span>
			<span class="rlg-toggle-bar"></span>
		</span>
	</button>
	<?php
}

/**
 * Render mobile menu overlay
 */
function basel_child_render_mobile_menu_overlay() {
	?>
	<div class="rlg-mobile-menu-overlay" aria-hidden="true"></div>
	<?php
}

