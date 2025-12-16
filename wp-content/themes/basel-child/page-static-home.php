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
                    <a href="https://realleathergarments.co.uk/mens-brown-leather-military-vest/" class="rlg-banner-link">
                        <picture>
                            <source media="(max-width: 768px)" srcset="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/banner-mobile.jpg">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/banner-desktop.jpg" alt="Real Leather Garments Banner" width="1600" height="400" loading="eager">
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

                <!-- Category Sections -->
                <?php
                $categories = rlg_get_homepage_categories();

                foreach ($categories as $cat) :
                    if (empty($cat['term_id'])) continue; // Skip if category doesn't exist
                ?>
                <section class="rlg-products-section rlg-category-section" id="<?php echo esc_attr($cat['id']); ?>">
                    <h2 class="rlg-section-title"><?php echo esc_html($cat['name']); ?></h2>
                    <?php
                    echo do_shortcode('[basel_products items_per_page="' . $cat['items'] . '" layout="carousel" slides_per_view="4" autoplay="no" taxonomies="' . $cat['term_id'] . '"]');
                    ?>
                </section>
                <?php endforeach; ?>

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

@media (max-width: 768px) {
    .rlg-section-title {
        font-size: 28px;
        margin: 0 0 20px;
    }
    
    .rlg-products-section {
        margin: 30px 0;
    }
}
</style>

<?php
get_footer();
?>

