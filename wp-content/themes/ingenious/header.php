<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ingenious
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9987590822220213"
     crossorigin="anonymous"></script>
	<meta name="google-site-verification" content="vzxhbx0c1y3dgtXkVeH4hUTqkjlq-NJUviu_HJUeQDQ" />
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<?php wp_head(); 
	
	//die('some thing went wrong');
	?>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9987590822220213"
     crossorigin="anonymous"></script>
    <meta name="p:domain_verify" content="2d5d8769691b07cceca01f91ee8e9719"/> 
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'ingenious' ); ?></a>

	<header id="masthead" class="site-header">
	    <div class="top-header">
			<small class="top-notice"></small>
		</div>
		<div class="site-branding text-center">
			<?php
			the_custom_logo();
			if ( is_front_page() && is_home() ) :
				?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php
			else :
				?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
			endif;
			$ingenious_description = get_bloginfo( 'description', 'display' );
			if ( $ingenious_description || is_customize_preview() ) :
				?>
			<?php endif; ?>
		</div>
		<form class="search-form" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">		
			<input type="text" id="advanced-search-input" name="s" placeholder="Search...">
		</form>
		<!-- .site-branding -->

<!-- 		<nav id="site-navigation" class="main-navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'ingenious' ); ?></button>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'menu-1',
					'menu_id'        => 'primary-menu',
				)
			);
			?>
		</nav> -->
		<!-- #site-navigation -->
		
		<!-- Top Icons-->
		<div class="icons icons-top">
<!-- 			<?php echo do_shortcode("[advanced-search]");?> -->
			<div class="xoo-wsc-cart-trigger"><i class="ico cart-icon"></i></div>
			<!--<a href="cart"><i class="ico cart-icon"></i></a>-->
			<a class="toggle-menu">
			<span class="ico dash-icon"></span>
			<span class="ico dash-icon"></span>
			<span class="ico dash-icon"></span>
			</a>
		</div>
	</header>
	<div class="header-bottom">
			<!-- .site-branding -->

				<nav id="site-navigation" class="main-navigation">
					<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'ingenious' ); ?></button>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'menu-1',
							'menu_id'        => 'primary-menu',
						)
					);
					?>
				</nav>
				<!-- #site-navigation -->
		</div>
	
	<!-- #masthead -->
