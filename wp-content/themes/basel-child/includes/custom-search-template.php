<?php
/**
 * ============================================================================
 * CUSTOM SEARCH BAR TEMPLATE
 * ============================================================================
 * Renders the custom search bar HTML
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Override Basel's search block with custom search
 */
function rlg_custom_header_block_search() {
	$header_search = basel_get_opt( 'header_search' );
	
	// If search is disabled in theme settings, don't show anything
	if ( $header_search == 'disable' ) {
		return;
	}

	?>
	<div class="rlg-custom-search-wrapper search-button">
		<!-- Search Icon (Mobile) -->
		<a class="mobile-search rlg-search-icon" href="#" rel="nofollow" aria-label="<?php esc_html_e( 'Search', 'basel' ); ?>">
			<svg class="rlg-search-icon-svg" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M21 21L16.65 16.65M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		</a>

		<!-- Desktop Search Bar -->
		<div class="rlg-desktop-search-bar">
			<form class="rlg-search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<div class="rlg-search-input-wrapper">
					<input
						type="text"
						class="rlg-search-input"
						name="s"
						placeholder="Search products..."
						autocomplete="off"
						value="<?php echo get_search_query(); ?>"
					/>
					<input type="hidden" name="post_type" value="product" />
					<button type="submit" class="rlg-search-submit" aria-label="Search">
						<svg class="rlg-search-icon-svg" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M21 21L16.65 16.65M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>
				</div>
			</form>

			<!-- Search Results Dropdown -->
			<div class="rlg-search-results" style="display: none;">
				<div class="rlg-search-results-inner">
					<!-- Results will be inserted here via AJAX -->
				</div>
			</div>
		</div>

		<!-- Mobile Search Overlay (Full Screen) -->
		<div class="basel-search-wrapper rlg-mobile-search-overlay">
			<div class="basel-search-inner">
				<span class="basel-close-search"><?php esc_html_e('close', 'basel'); ?></span>
				<form class="rlg-mobile-search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<div class="search-extended">
						<div>
							<input 
								type="text" 
								class="search-field" 
								name="s" 
								placeholder="Search products..." 
								autocomplete="off"
							/>
							<input type="hidden" name="post_type" value="product" />
							<button type="submit" class="button">
								<?php esc_html_e( 'Search', 'basel' ); ?>
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Override Basel's search function using output buffering
 * This is called from functions.php
 */

