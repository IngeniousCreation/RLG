<?php
/**
 * Custom Jacket Form Display
 * Adds customize button and form modal on single product pages
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add "Customize" button after add to cart button
 */
function rlg_add_customize_button() {
    global $product;

    if (!$product) {
        return;
    }

    // Categories to exclude from showing customize button
    $excluded_categories = array('gloves', 'shoes', 'belts', 'bag', 'bags', 'accessories');

    // Get product categories
    $product_categories = wp_get_post_terms($product->get_id(), 'product_cat', array('fields' => 'slugs'));

    // Check if product is in any excluded category
    if (!empty($product_categories) && !is_wp_error($product_categories)) {
        foreach ($product_categories as $category_slug) {
            if (in_array($category_slug, $excluded_categories)) {
                return; // Don't show button for excluded categories
            }
        }
    }

    ?>
    <button type="button" class="rlg-customize-button" id="rlg-open-customize-form">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
        </svg>
        <span>Customize This Jacket</span>
    </button>
    <?php
}
add_action('woocommerce_after_add_to_cart_button', 'rlg_add_customize_button', 10);

/**
 * Add measurement notice after customize button
 */
function rlg_add_measurement_notice() {
    global $product;

    if (!$product) {
        return;
    }

    // Categories to exclude
    $excluded_categories = array('gloves', 'shoes', 'belts', 'bag', 'bags', 'accessories');

    // Get product categories
    $product_categories = wp_get_post_terms($product->get_id(), 'product_cat', array('fields' => 'slugs'));

    // Check if product is in any excluded category
    if (!empty($product_categories) && !is_wp_error($product_categories)) {
        foreach ($product_categories as $category_slug) {
            if (in_array($category_slug, $excluded_categories)) {
                return; // Don't show notice for excluded categories
            }
        }
    }

    ?>
    <div class="rlg-measurement-notice">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="rlg-notice-icon">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="16" x2="12" y2="12"></line>
            <line x1="12" y1="8" x2="12.01" y2="8"></line>
        </svg>
        <p class="rlg-notice-text">All our products are made-to-order and cannot be returned due to size issues, so please ensure your measurements are accurate before placing an order.</p>
    </div>
    <?php
}
add_action('woocommerce_after_add_to_cart_button', 'rlg_add_measurement_notice', 20);

/**
 * Add product accordion after measurement notice
 */
function rlg_add_product_accordion() {
    global $product;

    if (!$product) {
        return;
    }

    // Categories to exclude
    $excluded_categories = array('gloves', 'shoes', 'belts', 'bag', 'bags', 'accessories');

    // Get product categories
    $product_categories = wp_get_post_terms($product->get_id(), 'product_cat', array('fields' => 'slugs'));

    // Check if product is in any excluded category
    if (!empty($product_categories) && !is_wp_error($product_categories)) {
        foreach ($product_categories as $category_slug) {
            if (in_array($category_slug, $excluded_categories)) {
                return; // Don't show accordion for excluded categories
            }
        }
    }

    // Get product short description for specifications
    $short_description = $product->get_short_description();

    ?>
    <div class="rlg-product-accordion">
        <details class="rlg-accordion-item">
            <summary class="rlg-accordion-title">
                <span class="rlg-accordion-title-text">PRODUCT SPECIFICATION</span>
                <span class="rlg-accordion-icon">
                    <svg class="rlg-icon-plus" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 448 512" fill="currentColor">
                        <path d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path>
                    </svg>
                    <svg class="rlg-icon-minus" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 448 512" fill="currentColor">
                        <path d="M416 208H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h384c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path>
                    </svg>
                </span>
            </summary>
            <div class="rlg-accordion-content">
                <?php
                if (!empty($short_description)) {
                    echo wp_kses_post($short_description);
                } else {
                    echo '<p>No specifications available.</p>';
                }
                ?>
            </div>
        </details>

        <details class="rlg-accordion-item">
            <summary class="rlg-accordion-title">
                <span class="rlg-accordion-title-text">CARE GUIDE</span>
                <span class="rlg-accordion-icon">
                    <svg class="rlg-icon-plus" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 448 512" fill="currentColor">
                        <path d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path>
                    </svg>
                    <svg class="rlg-icon-minus" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 448 512" fill="currentColor">
                        <path d="M416 208H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h384c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path>
                    </svg>
                </span>
            </summary>
            <div class="rlg-accordion-content">
                <ul>
                    <li><strong>Cleaning:</strong> Wipe with a soft, damp cloth. Avoid harsh chemicals or abrasive materials.</li>
                    <li><strong>Storage:</strong> Store in a cool, dry place away from direct sunlight to prevent fading.</li>
                    <li><strong>Conditioning:</strong> For leather products, apply leather conditioner every 3-6 months to maintain suppleness.</li>
                    <li><strong>Water Protection:</strong> Avoid prolonged exposure to water. If wet, allow to air dry naturally.</li>
                    <li><strong>Professional Care:</strong> For deep cleaning or repairs, consult a professional leather care specialist.</li>
                </ul>
            </div>
        </details>
    </div>
    <?php
}
add_action('woocommerce_after_add_to_cart_button', 'rlg_add_product_accordion', 30);

/**
 * Add product share section after accordion
 */
function rlg_add_product_share() {
    global $product;

    if (!$product) {
        return;
    }

    // Get product URL and image
    $product_url = get_permalink($product->get_id());
    $product_image = wp_get_attachment_url($product->get_image_id());
    $product_title = $product->get_name();

    // Encode URLs for sharing
    $encoded_url = urlencode($product_url);
    $encoded_image = urlencode($product_image);
    $encoded_title = urlencode($product_title);

    ?>
    <div class="rlg-product-share">
        <span class="rlg-share-title">Share</span>
        <ul class="rlg-social-icons">
            <li class="rlg-social-facebook">
                <a rel="noopener noreferrer nofollow" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $encoded_url; ?>" target="_blank" title="Facebook">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    <span class="rlg-social-icon-name">Facebook</span>
                </a>
            </li>
            <li class="rlg-social-twitter">
                <a rel="noopener noreferrer nofollow" href="https://twitter.com/share?url=<?php echo $encoded_url; ?>&text=<?php echo $encoded_title; ?>" target="_blank" title="Twitter">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                    </svg>
                    <span class="rlg-social-icon-name">Twitter</span>
                </a>
            </li>
            <li class="rlg-social-pinterest">
                <a rel="noopener noreferrer nofollow" href="https://pinterest.com/pin/create/button/?url=<?php echo $encoded_url; ?>&media=<?php echo $encoded_image; ?>&description=<?php echo $encoded_title; ?>" target="_blank" title="Pinterest">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"/>
                    </svg>
                    <span class="rlg-social-icon-name">Pinterest</span>
                </a>
            </li>
            <li class="rlg-social-linkedin">
                <a rel="noopener noreferrer nofollow" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $encoded_url; ?>&title=<?php echo $encoded_title; ?>" target="_blank" title="LinkedIn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                    <span class="rlg-social-icon-name">LinkedIn</span>
                </a>
            </li>
            <li class="rlg-social-telegram">
                <a rel="noopener noreferrer nofollow" href="https://telegram.me/share/url?url=<?php echo $encoded_url; ?>&text=<?php echo $encoded_title; ?>" target="_blank" title="Telegram">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                    </svg>
                    <span class="rlg-social-icon-name">Telegram</span>
                </a>
            </li>
        </ul>
    </div>
    <?php
}
add_action('woocommerce_after_add_to_cart_button', 'rlg_add_product_share', 40);

/**
 * Add custom jacket form modal
 */
function rlg_add_custom_jacket_form_modal() {
    if (!is_product()) {
        return;
    }
    
    global $product;
    $product_id = $product ? $product->get_id() : 0;
    $product_title = $product ? $product->get_name() : '';
    
    ?>
    <!-- Custom Jacket Form Modal -->
    <div id="rlg-customize-modal" class="rlg-modal" style="display: none;">
        <div class="rlg-modal-overlay"></div>
        <div class="rlg-modal-content">
            <button class="rlg-modal-close" id="rlg-close-customize-form">&times;</button>
            <h2 class="rlg-modal-title">Customize Your Jacket</h2>
            
            <form id="rlg-custom-jacket-form" class="rlg-custom-form" enctype="multipart/form-data">
                <input type="hidden" name="product_id" value="<?php echo esc_attr($product_id); ?>">
                <input type="hidden" name="product_title" value="<?php echo esc_attr($product_title); ?>">
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('rlg_custom_jacket_form'); ?>">
                <input type="hidden" name="form_load_time" id="rlg-form-load-time" value="">

                <!-- Honeypot field (hidden from users, bots will fill it) -->
                <input type="text" name="website" id="rlg-website" value="" style="display:none !important;" tabindex="-1" autocomplete="off">
                
                <div class="rlg-form-row">
                    <div class="rlg-form-field rlg-col-50">
                        <input type="text" name="name" id="rlg-name" placeholder="Name" class="rlg-input">
                    </div>
                    <div class="rlg-form-field rlg-col-50">
                        <input type="email" name="email" id="rlg-email" placeholder="Email *" required class="rlg-input">
                    </div>
                </div>
                
                <div class="rlg-form-row">
                    <div class="rlg-form-field rlg-col-50">
                        <input type="tel" name="phone" id="rlg-phone" placeholder="Phone Number *" required class="rlg-input">
                    </div>
                    <div class="rlg-form-field rlg-col-50">
                        <input type="text" name="company" id="rlg-company" placeholder="Company Or Organisation *" required class="rlg-input">
                    </div>
                </div>
                
                <div class="rlg-form-row">
                    <div class="rlg-form-field rlg-col-50">
                        <select name="quantity" id="rlg-quantity" required class="rlg-select">
                            <option value="">Quantity *</option>
                            <option value="1">1</option>
                            <option value="2-5">2-5</option>
                            <option value="6-10">6-10</option>
                            <option value="10-30">10-30</option>
                            <option value="30+">30+</option>
                        </select>
                    </div>
                    <div class="rlg-form-field rlg-col-50">
                        <select name="country" id="rlg-country" required class="rlg-select">
                            <option value="">Country *</option>
                            <option value="France, Metropolitan">France, Metropolitan</option>
                            <option value="Germany">Germany</option>
                            <option value="Australia">Australia</option>
                            <option value="Canada">Canada</option>
                            <option value="United Kingdom">United Kingdom</option>
                            <option value="United States">United States</option>
                        </select>
                    </div>
                </div>
                
                <div class="rlg-form-row">
                    <div class="rlg-form-field rlg-col-50">
                        <select name="gender" id="rlg-gender" required class="rlg-select">
                            <option value="">Gender *</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="rlg-form-field rlg-col-50">
                        <label for="rlg-design-file" class="rlg-file-label">
                            <span class="rlg-file-text">Upload Design File (JPG/PNG only)</span>
                            <input type="file" name="design_file" id="rlg-design-file" class="rlg-file-input" accept="image/jpeg,image/jpg,image/png,.jpg,.jpeg,.png">
                        </label>
                    </div>
                </div>
                
                <div class="rlg-form-row">
                    <div class="rlg-form-field rlg-col-100">
                        <textarea name="message" id="rlg-message" rows="4" placeholder="Description: Please write description for your custom order here." class="rlg-textarea"></textarea>
                    </div>
                </div>
                
                <div class="rlg-form-row">
                    <div class="rlg-form-field rlg-col-100">
                        <button type="submit" class="rlg-submit-button">Get Started</button>
                    </div>
                </div>
                
                <div id="rlg-form-message" class="rlg-form-message" style="display: none;"></div>
            </form>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'rlg_add_custom_jacket_form_modal');

/**
 * Enqueue custom jacket form styles and scripts
 */
function rlg_enqueue_custom_jacket_form_assets() {
    if (!is_product()) {
        return;
    }

    // CSS is now in child theme style.css - no need to enqueue separate file
    wp_enqueue_script('rlg-custom-jacket-form', get_stylesheet_directory_uri() . '/assets/js/custom-jacket-form.js', array('jquery'), '1.0.1', true);

    wp_localize_script('rlg-custom-jacket-form', 'rlgCustomForm', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('rlg_custom_jacket_form')
    ));
}
add_action('wp_enqueue_scripts', 'rlg_enqueue_custom_jacket_form_assets');

