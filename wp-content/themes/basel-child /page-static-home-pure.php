<?php
/**
 * Template Name: Static Homepage (Pure HTML)
 * Description: Fully static homepage with no database queries
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="main-page-wrapper">
    <!-- Banner Section - Full Width -->
    <section class="rlg-banner-section">
        <a href="https://staging.realleathergarments.co.uk/mens-brown-leather-military-vest/" class="rlg-banner-link">
            <picture>
                <source media="(max-width: 768px)" srcset="https://staging.realleathergarments.co.uk/wp-content/uploads/2025/12/REAL-LEATHER-BANNER-MOBILE-SIZE-1-1.jpg">
                <img src="https://staging.realleathergarments.co.uk/wp-content/uploads/2025/12/up2-1.jpg" alt="Real Leather Garments Banner" width="1600" height="400" loading="eager">
            </picture>
        </a>
    </section>

    <div class="container">
        <div class="row">
            <div class="site-content col-sm-12" role="main">

<?php
// Include all product sections from generated file
$sections_file = get_stylesheet_directory() . '/sections_generated.html';
if (file_exists($sections_file)) {
    include $sections_file;
}
?>

                <!-- Content Section -->
                <section class="rlg-content-section">
                    <h3 class="rlg-content-heading">Iconic Real Leather Jackets For Men and Women</h3>

                    <div class="rlg-content-text">
                        <p>Access all the latest trends with our real leather jackets that have a classic and timeless appeal and come in different styles. From leather to sheepskin fur, our jackets are made with premium quality fabric and are available at the best affordable prices.</p>

                        <p>Our <a href="https://staging.realleathergarments.co.uk/bomber-jackets/"><strong>Bomber Leather Jackets</strong></a> are made with the highest quality genuine leather and weather-resistant material. We take pride in presenting a collection that also sets the bar high when it comes to design and patterns.</p>

                        <p>Also in terms of quality and craftsmanship of our <a href="https://staging.realleathergarments.co.uk/women-leather-jackets/"><strong>Real Leather Jacket Women</strong></a> we use the finest quality leather that ensures the best fits, durability, and unique trendy designs.</p>

                        <p><a id="load-more-content" class="wpex-toggle-element-trigger" href="#show-more-text">Read More</a></p>

                        <div id="show-more-text" class="wpex-toggle-element" style="display: none;">
                            <h2 class="content-headings-one">A Guide to Classic Mens Fashion</h2>
                            <p><a href="https://staging.realleathergarments.co.uk/mens-gilets-waistcoats/"><strong>Mens leather gilet</strong></a> serves as a great choice to transform your entire personality. It has its charm that never goes out of trend. The sleeveless design is a unique feature of this attire that allows you to keep comfortable while staying on-trend.</p>

                            <p>You can layer it under a heavy coat or put it on over a casual t-shirt, It has a distinctive style that compliments your personality well.</p>

                            <p>A blend of vintage and modern styles, our <a href="https://staging.realleathergarments.co.uk/gascoigne-mens-sheepskin-leather-gilet/"><strong>mens sheepskin gilets</strong></a> are designed to give you maximum warmth with a chic appearance. It features a vibe of boldness and adventure that makes it a trendsetting piece in the world of fashion.</p>

                            <p>Sheepskin coats are among the best choices for winter as well as for the late fall season. The popularity of <a href="https://staging.realleathergarments.co.uk/sheepskin-coats-for-men/"><strong>mens sheepskin coats</strong></a> continues to rise in the world of men's fashion. Our men's coats fabricated from lambskin or sheepskin are a great investment for your winter wardrobe. The furry collars and front button closures ensure enough warmth against the cold and contribute to your standout presence.</p>

                            <p>When you think about the classic design and durability, one thing that comes to mind is <a href="https://staging.realleathergarments.co.uk/shearling-jackets/"><strong>men's shearling jackets</strong></a>. We offer a wide range of shearling jackets that goes for both casual and formal look.</p>

                            <p>To upgrade your winter wardrobe, explore our wide collection of <a href="https://staging.realleathergarments.co.uk/movies-leather-jackets/"><strong>movie jackets</strong></a> that feature an extensive variety of high-quality shearling men's Real Leather Jackets, a perfect combination of warmth, style, and durability.</p>

                            <p>Such as fans' favorite <a href="https://staging.realleathergarments.co.uk/brooklyn-nine-nine-peralta-leather-jacket/"><strong>Brooklyn 99 jacket</strong></a>, which had ruled the hearts of people also available to glam up your personality.</p>

                            <h2 class="content-headings-two">Diversity in Women's Fashion</h2>
                            <p>Choosing a style that makes you confident about your appearance and serves as a symbol of rebellion and empowerment is only possible with a bomber <a href="https://staging.realleathergarments.co.uk/leather-biker-jackets-for-women/"><strong>womens moto jacket</strong></a>. Real Leather versions are ideal for fall and winter and keep you warm and stylish with their aesthetic appeal i.e. zippers, studs, and multiple pockets.</p>

                            <p>A stylish and luxurious outerwear piece, <a href="https://staging.realleathergarments.co.uk/leather-sheepskin-coat-for-womens/"><strong>womens sheepskin coat</strong></a> embodies a perfect blend of warmth comfort, and timeless grace. If you want to look stylish while enjoying winter cold nights and chill mornings, a sheepskin coat is a must-have for your winter wardrobe.</p>

                            <p>Experience the warmth and comfort of <strong>real leather jacket men</strong> and <strong>real leather jacket women</strong>, that can be carried out anywhere with style and elegance.</p>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
</div>

<style>
/* Inline critical CSS for above-the-fold content */
.rlg-banner-section {
    margin: 0 0 40px;
    line-height: 0;
    position: relative;
    z-index: 1;
    clear: both;
    width: 100vw;
    margin-left: calc(-50vw + 50%);
}

.rlg-banner-link {
    display: block;
    transition: opacity 0.3s;
}

.rlg-banner-link:hover {
    opacity: 0.95;
}

.rlg-banner-section img {
    width: 100%;
    height: auto;
    display: block;
}

/* Fix header overlap issue */
.main-page-wrapper {
    position: relative;
    z-index: 1;
    padding-top: 20px;
}

.site-content {
    position: relative;
}

.rlg-products-section {
    margin: 50px 0;
}

.rlg-section-title {
    text-align: center;
    font-size: 40px;
    margin: 0 0 30px;
    font-weight: 600;
}

/* Owl Carousel Styling */
.basel-products-custom.owl-carousel {
    position: relative;
}

.basel-products-custom .owl-nav {
    position: absolute;
    top: 50%;
    width: 100%;
    transform: translateY(-50%);
    pointer-events: none;
}

.basel-products-custom .owl-nav button {
    position: absolute;
    background: rgba(0,0,0,0.5) !important;
    color: #fff !important;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    font-size: 24px;
    line-height: 40px;
    text-align: center;
    pointer-events: all;
    transition: background 0.3s;
}

.basel-products-custom .owl-nav button:hover {
    background: rgba(0,0,0,0.8) !important;
}

.basel-products-custom .owl-nav .owl-prev {
    left: -20px;
}

.basel-products-custom .owl-nav .owl-next {
    right: -20px;
}

.basel-products-custom .product-item {
    padding: 10px;
}

.basel-products-custom .product-wrapper {
    border: none !important;
    transition: box-shadow 0.3s;
    height: 500px;
    display: flex;
    flex-direction: column;
}

.basel-products-custom .product-wrapper .product-element-top,
.basel-products-custom .product-wrapper .product-element-bottom,
.basel-products-custom .product-wrapper .product-information,
.basel-products-custom .product-wrapper .product-image-link {
    border: none !important;
}

.basel-products-custom .product-wrapper:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.basel-products-custom .product-image {
    position: relative;
    overflow: hidden;
    height: 350px;
    flex-shrink: 0;
}

.basel-products-custom .product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s;
}

.basel-products-custom .product-image:hover img {
    transform: scale(1.05);
}

.basel-products-custom .product-info {
    padding: 15px;
    text-align: center;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.basel-products-custom .product-title {
    font-size: 16px;
    margin: 0 0 10px;
    font-weight: 500;
}

.basel-products-custom .product-title a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s;
}

.basel-products-custom .product-title a:hover {
    color: #000;
}

.basel-products-custom .product-price {
    font-size: 18px;
    font-weight: 600;
    color: #000;
}

.rlg-content-section {
    margin: 60px 0;
    padding: 40px 20px;
}

.rlg-content-heading {
    text-align: center;
    font-size: 40px;
    margin: 0 0 30px;
    font-weight: 600;
}

.rlg-content-text {
    max-width: 900px;
    margin: 0 auto;
}

.rlg-content-text p {
    text-align: center;
    margin-bottom: 20px;
    line-height: 1.8;
}

.rlg-content-text h2 {
    text-align: center;
    font-size: 32px;
    margin: 40px 0 20px;
    font-weight: 600;
}

.wpex-toggle-element {
    margin-top: 20px;
}

.wpex-toggle-element-trigger {
    display: inline-block;
    padding: 10px 20px;
    background: #000;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    transition: background 0.3s;
}

.wpex-toggle-element-trigger:hover {
    background: #333;
    color: #fff;
}

@media (max-width: 768px) {
    .rlg-section-title,
    .rlg-content-heading {
        font-size: 28px;
        margin: 0 0 20px;
    }

    .rlg-products-section {
        margin: 30px 0;
    }

    .rlg-content-section {
        margin: 40px 0;
        padding: 20px 10px;
    }

    .rlg-content-text h2 {
        font-size: 24px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Initialize Owl Carousel for all product sections
    $('.basel-products-custom.owl-carousel').each(function() {
        var $carousel = $(this);

        $carousel.owlCarousel({
            items: 4,
            loop: false,
            margin: 30,
            nav: true,
            dots: false,
            autoplay: false,
            navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            responsive: {
                0: {
                    items: 1,
                    margin: 10
                },
                480: {
                    items: 2,
                    margin: 15
                },
                768: {
                    items: 3,
                    margin: 20
                },
                992: {
                    items: 4,
                    margin: 30
                }
            }
        });
    });

    // Read More toggle functionality
    var trigger = document.getElementById('load-more-content');
    var content = document.getElementById('show-more-text');

    if (trigger && content) {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            if (content.style.display === 'none') {
                content.style.display = 'block';
                trigger.textContent = 'Show Less';
            } else {
                content.style.display = 'none';
                trigger.textContent = 'Read More';
            }
        });
    }
});
</script>

<?php
get_footer();
?>

