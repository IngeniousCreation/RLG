<?php
/**
 * Template Name: Static Homepage
 * Description: Optimized static homepage with minimal overhead
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="main-page-wrapper">
    <div class="container">
        <div class="row">
            <div class="site-content col-sm-12" role="main">
                
                <!-- Banner Section -->
                <section class="rlg-banner-section">
                    <a href="https://staging.realleathergarments.co.uk/mens-brown-leather-military-vest/" class="rlg-banner-link">
                        <picture>
                            <source media="(max-width: 768px)" srcset="https://staging.realleathergarments.co.uk/wp-content/uploads/2025/12/REAL-LEATHER-BANNER-MOBILE-SIZE-1-1.jpg">
                            <img src="https://staging.realleathergarments.co.uk/wp-content/uploads/2025/12/up2-1.jpg" alt="Real Leather Garments Banner" width="1600" height="400" loading="eager">
                        </picture>
                    </a>
                </section>

                <!-- Best Selling Products -->
                <section class="rlg-products-section">
                    <h2 class="rlg-section-title">Best Selling Products</h2>
                    <?php
                    // Use Basel Shortcodes Lite plugin for fast product display
                    $best_selling_ids = rlg_get_best_selling_ids();
                    if ($best_selling_ids) {
                        echo do_shortcode('[basel_products items_per_page="12" layout="carousel" slides_per_view="4" autoplay="no" include="' . $best_selling_ids . '"]');
                    }
                    ?>
                </section>

                <!-- Men's Leather Gilets & Waistcoat -->
                <section class="rlg-products-section">
                    <h2 class="rlg-section-title">Men's Leather Gilets &amp; Waistcoat</h2>
                    <?php
                    $term = get_term_by('slug', 'mens-gilets-waistcoats', 'product_cat');
                    if ($term) {
                        echo do_shortcode('[basel_products items_per_page="8" layout="carousel" slides_per_view="4" autoplay="no" taxonomies="' . $term->term_id . '"]');
                    }
                    ?>
                </section>

                <!-- Leather Biker Jackets for Men's -->
                <section class="rlg-products-section">
                    <h2 class="rlg-section-title">Leather Biker Jackets for Men's</h2>
                    <?php
                    $term = get_term_by('slug', 'biker-jackets', 'product_cat');
                    if ($term) {
                        echo do_shortcode('[basel_products items_per_page="8" layout="carousel" slides_per_view="4" autoplay="no" taxonomies="' . $term->term_id . '"]');
                    }
                    ?>
                </section>

                <!-- Leather Bomber Jackets Men's -->
                <section class="rlg-products-section">
                    <h2 class="rlg-section-title">Leather Bomber Jackets Men's</h2>
                    <?php
                    $term = get_term_by('slug', 'bomber-jackets', 'product_cat');
                    if ($term) {
                        echo do_shortcode('[basel_products items_per_page="8" layout="carousel" slides_per_view="4" autoplay="no" taxonomies="' . $term->term_id . '"]');
                    }
                    ?>
                </section>

                <!-- Women's Biker Jackets -->
                <section class="rlg-products-section">
                    <h2 class="rlg-section-title">Women's Biker Jackets</h2>
                    <?php
                    $term = get_term_by('slug', 'leather-biker-jackets-for-women', 'product_cat');
                    if ($term) {
                        echo do_shortcode('[basel_products items_per_page="8" layout="carousel" slides_per_view="4" autoplay="no" taxonomies="' . $term->term_id . '"]');
                    }
                    ?>
                </section>

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

.rlg-products-section {
    margin: 50px 0;
}

.rlg-section-title {
    text-align: center;
    font-size: 40px;
    margin: 0 0 30px;
    font-weight: 600;
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
// Read More toggle functionality
document.addEventListener('DOMContentLoaded', function() {
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

