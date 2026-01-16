<?php
/**
 * Custom Mega Menu Configuration
 * Hardcoded menu structure for performance optimization
 * 
 * @package Basel Child
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Get mega menu configuration
 * 
 * @return array Menu structure
 */
function basel_child_get_mega_menu_config() {
	return array(
		array(
			'id'       => 'men',
			'label'    => 'Men',
			'url'      => '/men',
			'has_mega' => true,
			'columns'  => array(
				array(
					'title' => 'Jackets',
					'items' => array(
						array( 'label' => 'Leather Jackets', 'url' => '/men-leather-jackets/' ),
						array( 'label' => 'Bomber Jackets', 'url' => '/bomber-jackets/' ),
						array( 'label' => 'Suede Jackets', 'url' => '/suede-jackets/' ),
						array( 'label' => 'Shearling Jackets', 'url' => '/shearling-jackets/' ),
						array( 'label' => 'Sheepskin Jackets', 'url' => '/mens-sheepskin-flying-jackets/' ),
						array( 'label' => 'Hooded Jackets', 'url' => '/mens-leather-jackets-with-hood/' ),
						array( 'label' => 'Biker Jackets', 'url' => '/biker-jackets/' ),
						array( 'label' => 'Blouson Jackets', 'url' => '/mens-leather-blouson-jackets/' ),
						array( 'label' => 'Aviator Jackets', 'url' => '/mens-leather-aviator-jackets/' ),
						array( 'label' => 'Varsity Jackets', 'url' => '/mens-varsity-jackets/' ),
						array( 'label' => 'Perforated Jackets', 'url' => '/perforated-leather-jacket/' ),
						array( 'label' => 'Slim Fit Jackets', 'url' => '/slim-fit-leather-jackets/' ),
						array( 'label' => 'Distressed Jackets', 'url' => '/mens-distressed-leather-jacket/' ),
						array( 'label' => 'Leather Blazers', 'url' => '/leather-blazers-for-men/' ),
						array( 'label' => 'Vintage Jackets', 'url' => '/vintage-leather-jacket-mens/' ),
						array( 'label' => 'Faux Leather Jackets', 'url' => '/mens-faux-leather-jackets/' ),
						array( 'label' => 'Leather Shirt Jackets', 'url' => '/mens-leather-shirt-jackets/' ),
						array( 'label' => 'Puffer Jackets', 'url' => '/mens-puffer-jackets/' ),
						array( 'label' => 'Fringe Leather Jackets', 'url' => '/mens-fringe-leather-jacket/' ),
						array( 'label' => 'Winter jackets', 'url' => '/winter-jackets-for-men/' ),
					),
				),
				array(
					'title' => 'Coats & More',
					'items' => array(
						array( 'label' => 'Leather Coats', 'url' => '/leather-coats-for-men/' ),
						array( 'label' => 'Sheepskin Coats', 'url' => '/sheepskin-coats-for-men/' ),
						array( 'label' => 'Shearling Coats', 'url' => '/shearling-coats-for-men/' ),
						array( 'label' => 'Waistcoats', 'url' => '/mens-gilets-waistcoats/' ),
						array( 'label' => 'Double Breasted Coats', 'url' => '/double-breasted-coats-for-men/' ),
						array( 'label' => 'Trench Coats', 'url' => '/trench-coats-for-men/' ),
					),
				),
				array(
					'title' => 'By Color',
					'items' => array(
						array( 'label' => 'Black Leather Jackets', 'url' => '/mens-black-leather-jacket/' ),
						array( 'label' => 'Blue Leather Jackets', 'url' => '/blue-leather-jackets/' ),
						array( 'label' => 'Brown Leather Jackets', 'url' => '/mens-brown-leather-jacket/' ),
						array( 'label' => 'Red Leather Jackets', 'url' => '/red-leather-jacket/' ),
						array( 'label' => 'Green Leather Jackets', 'url' => '/green-leather-jacket-for-men/' ),
						array( 'label' => 'White Leather Jackets', 'url' => '/white-leather-jackets-for-men/' ),
					),
				),
				array(
					'title' => 'Accessories',
					'items' => array(
						array( 'label' => 'Accessories', 'url' => '/accessories/' ),
						array( 'label' => 'Leather Bags', 'url' => '/leather-bags/' ),
						array( 'label' => 'Leather Belts', 'url' => '/mens-belts/' ),
						array( 'label' => 'Leather Cap', 'url' => '/mens-leather-cap/' ),
						array( 'label' => 'Leather Gloves', 'url' => '/mens-leather-gloves/' ),
						array( 'label' => 'Leather Shoes', 'url' => '/footwear/' ),
						array( 'label' => 'Leather Wallets', 'url' => '/mens-leather-wallet/' ),
						array( 'label' => 'Leather Golf Bags', 'url' => '/leather-golf-bags/' ),
					),
				),
			),
		),
		array(
			'id'       => 'women',
			'label'    => 'Women',
			'url'      => '/women/',
			'has_mega' => true,
			'columns'  => array(
				array(
					'title' => 'Jackets',
					'items' => array(
						array( 'label' => 'Leather Jackets', 'url' => '/women-leather-jackets/' ),
						array( 'label' => 'Biker Jackets', 'url' => '/leather-biker-jackets-for-women/' ),
						array( 'label' => 'Slim Fit Jackets', 'url' => '/slim-fit-leather-jackets-for-women/' ),
						array( 'label' => 'Suede Jackets', 'url' => '/suede-leather-jackets-for-women/' ),
						array( 'label' => 'Distressed Jackets', 'url' => '/womens-distressed-leather-jacket/' ),
						array( 'label' => 'Hooded Jackets', 'url' => '/womens-leather-jackets-with-hood/' ),
						array( 'label' => 'Shearling Jackets', 'url' => '/womens-shearling-jackets/' ),
						array( 'label' => 'Cropped Jackets', 'url' => '/womens-cropped-leather-jackets/' ),
						array( 'label' => 'Aviator Jackets', 'url' => '/womens-leather-aviator-jackets/' ),
						array( 'label' => 'Sheepskin Jackets', 'url' => '/womens-sheepskin-flying-jacket/' ),
						array( 'label' => 'Leather Blazers', 'url' => '/leather-blazers-for-women/' ),
						array( 'label' => 'Faux Leather Jackets', 'url' => '/womens-faux-leather-jackets/' ),
						array( 'label' => 'Vintage Jackets', 'url' => '/vintage-leather-jacket-womens/' ),
						array( 'label' => 'Bomber Jackets', 'url' => '/womens-bomber-leather-jackets/' ),
						array( 'label' => 'Collarless Jackets', 'url' => '/womens-collarless-leather-jackets/' ),
						array( 'label' => 'Puffer Jackets', 'url' => '/womens-puffer-jackets/' ),
						array( 'label' => 'Oversized Jackets', 'url' => '/womens-oversized-leather-jacket/' ),
						array( 'label' => 'Winter Jackets', 'url' => '/winter-jackets-for-women/' ),
					),
				),
				array(
					'title' => 'Coats & More',
					'items' => array(
						array( 'label' => 'Leather Coats', 'url' => '/leather-coats-for-women/' ),
						array( 'label' => 'Double Breasted Coats', 'url' => '/double-breasted-coats-for-women/' ),
						array( 'label' => 'Waistcoats', 'url' => '/gilets-waistcoats-for-women/' ),
						array( 'label' => 'Shearling Coats', 'url' => '/shearling-coats-for-women/' ),
						array( 'label' => 'Sheepskin Coats', 'url' => '/sheepskin-coats-for-women/' ),
						array( 'label' => 'Trench Coats', 'url' => '/trench-coats-for-women/' ),
					),
				),
				array(
					'title' => 'By Color',
					'items' => array(
						array( 'label' => 'Black Leather Jackets', 'url' => '/womens-black-leather-jackets/' ),
						array( 'label' => 'Blue Leather Jackets', 'url' => '/womens-blue-leather-jackets/' ),
						array( 'label' => 'Brown Leather Jackets', 'url' => '/womens-brown-leather-jackets/' ),
						array( 'label' => 'Red Leather Jackets', 'url' => '/red-leather-jacket/' ),
						array( 'label' => 'Green Leather Jackets', 'url' => '/green-leather-jacket-for-women/' ),
						array( 'label' => 'White Leather Jackets', 'url' => '/white-leather-jackets-for-women/' ),
					),
				),
				array(
					'title' => 'Accessories',
					'items' => array(
						array( 'label' => 'Acccessories', 'url' => '/accessories-women/' ),
						array( 'label' => 'Leather Bags', 'url' => '/womens-leather-bags/' ),
						array( 'label' => 'Leather Belts', 'url' => '/womens-leather-belts/' ),
						array( 'label' => 'Leather Gloves', 'url' => '/womens-leather-gloves/' ),
						array( 'label' => 'Leather Shoes', 'url' => '/footwear-for-women/' ),
						array( 'label' => 'Saffiano Bags', 'url' => '/saffiano-leather-bags/' ),
						array( 'label' => 'Leather Leggings', 'url' => '/real-leather-leggings/' ),
						array( 'label' => 'Leather Tote Bags', 'url' => '/womens-leather-tote-bags/' ),
					),
				),
			),
		),
		array(
			'id'       => 'kids',
			'label'    => 'Kids',
			'url'      => '/kids/',
			'has_mega' => false,
		),
		array(
			'id'       => 'movies',
			'label'    => 'Movies Jackets',
			'url'      => '/movies-leather-jackets/',
			'has_mega' => false,
		),
		array(
			'id'       => 'custom',
			'label'    => 'Custom Leather Jackets',
			'url'      => '/custom-leather-jackets/',
			'has_mega' => false,
		),
		array(
			'id'       => 'contact',
			'label'    => 'Contact Us',
			'url'      => '/contact-us/',
			'has_mega' => false,
		),
	);
}

