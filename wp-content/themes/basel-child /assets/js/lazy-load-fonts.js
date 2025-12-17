/**
 * Lazy Load Fonts on User Interaction
 * Loads fonts only when user interacts with the page (scroll, mousemove, click, tap)
 *
 * @version 1.0.0
 */
(function() {
    'use strict';

    // Block Google Sans font loading immediately
    if (window.fetch) {
        var originalFetch = window.fetch;
        window.fetch = function(url) {
            if (typeof url === 'string' && (url.includes('googlesans') || url.includes('Google%20Sans'))) {
                console.log('🚫 Blocked Google Sans font fetch:', url);
                return Promise.reject(new Error('Google Sans font loading blocked'));
            }
            return originalFetch.apply(this, arguments);
        };
    }

    // Block any stylesheet that tries to load Google Sans
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.tagName === 'LINK' && node.rel === 'stylesheet') {
                    // Check if stylesheet contains Google Sans after it loads
                    node.addEventListener('load', function() {
                        try {
                            var sheet = node.sheet || node.styleSheet;
                            if (sheet && sheet.cssRules) {
                                for (var i = 0; i < sheet.cssRules.length; i++) {
                                    var rule = sheet.cssRules[i];
                                    if (rule.cssText && (rule.cssText.includes('Google Sans') || rule.cssText.includes('googlesans'))) {
                                        console.log('🚫 Removing stylesheet with Google Sans:', node.href);
                                        node.remove();
                                        break;
                                    }
                                }
                            }
                        } catch(e) {
                            // Cross-origin stylesheet, can't check
                        }
                    });
                }
            });
        });
    });

    observer.observe(document.documentElement, {
        childList: true,
        subtree: true
    });

    var fontsLoaded = false;
    var fontUrls = window.baselLazyFontUrls || {};

    /**
     * Load all fonts
     */
    function loadFonts() {
        if (fontsLoaded) return;
        fontsLoaded = true;

        console.log('🎨 Loading fonts on user interaction...');

        // Load Google Fonts
        if (fontUrls.google_fonts) {
            var googleLink = document.createElement('link');
            googleLink.rel = 'stylesheet';
            googleLink.href = fontUrls.google_fonts;
            googleLink.id = 'xts-google-fonts-lazy';
            document.head.appendChild(googleLink);
            console.log('✓ Google Fonts loaded');
        }

        // Load Typekit
        if (fontUrls.typekit) {
            var typekitLink = document.createElement('link');
            typekitLink.rel = 'stylesheet';
            typekitLink.href = fontUrls.typekit;
            typekitLink.id = 'basel-typekit-lazy';
            document.head.appendChild(typekitLink);
            console.log('✓ Typekit fonts loaded');
        }

        // Load Font Awesome
        if (fontUrls.font_awesome) {
            var faLink = document.createElement('link');
            faLink.rel = 'stylesheet';
            faLink.href = fontUrls.font_awesome;
            faLink.id = 'vc_font_awesome_5-lazy';
            document.head.appendChild(faLink);
            console.log('✓ Font Awesome loaded');
        }

        // Load Font Awesome Shims
        if (fontUrls.font_awesome_shims) {
            var faShimsLink = document.createElement('link');
            faShimsLink.rel = 'stylesheet';
            faShimsLink.href = fontUrls.font_awesome_shims;
            faShimsLink.id = 'vc_font_awesome_5_shims-lazy';
            document.head.appendChild(faShimsLink);
            console.log('✓ Font Awesome Shims loaded');
        }

        // Load CDN Font Awesome
        if (fontUrls.font_awesome_cdn) {
            var faCdnLink = document.createElement('link');
            faCdnLink.rel = 'stylesheet';
            faCdnLink.href = fontUrls.font_awesome_cdn;
            faCdnLink.id = 'font-awesome-cdn-lazy';
            document.head.appendChild(faCdnLink);
            console.log('✓ Font Awesome CDN loaded');
        }

        // Load icon fonts (basel-font and simple-line-icons)
        loadIconFonts();

        // Load plugin fonts (Side Cart, etc.) - as fallback
        loadPluginFonts();

        // Remove event listeners after loading
        removeEventListeners();
    }

    /**
     * Load icon fonts via @font-face
     */
    function loadIconFonts() {
        var themeUrl = fontUrls.theme_url || window.location.origin + '/wp-content/themes/basel';
        var version = fontUrls.version || '1.0';

        var iconFontsCss = '@font-face {' +
            'font-weight: normal;' +
            'font-style: normal;' +
            'font-family: "simple-line-icons";' +
            'src: url("' + themeUrl + '/fonts/Simple-Line-Icons.woff2?v=' + version + '") format("woff2"),' +
            'url("' + themeUrl + '/fonts/Simple-Line-Icons.woff?v=' + version + '") format("woff");' +
            'font-display: swap;' +
            '}' +
            '@font-face {' +
            'font-weight: normal;' +
            'font-style: normal;' +
            'font-family: "basel-font";' +
            'src: url("' + themeUrl + '/fonts/basel-font.woff2?v=' + version + '") format("woff2"),' +
            'url("' + themeUrl + '/fonts/basel-font.woff?v=' + version + '") format("woff");' +
            'font-display: swap;' +
            '}';

        var styleElement = document.createElement('style');
        styleElement.id = 'basel-icon-fonts-lazy';
        styleElement.textContent = iconFontsCss;
        document.head.appendChild(styleElement);
        console.log('✓ Icon fonts loaded');
    }

    /**
     * Load plugin fonts (Side Cart, etc.)
     */
    function loadPluginFonts() {
        // Side Cart font
        var sideCartFontCss = '@font-face {' +
            'font-family: "Woo-Side-Cart";' +
            'src: url("' + window.location.origin + '/wp-content/plugins/side-cart-woocommerce/assets/css/fonts/Woo-Side-Cart.ttf?qq7fgp") format("truetype"),' +
            'url("' + window.location.origin + '/wp-content/plugins/side-cart-woocommerce/assets/css/fonts/Woo-Side-Cart.woff?qq7fgp") format("woff");' +
            'font-weight: normal;' +
            'font-style: normal;' +
            'font-display: block;' +
            '}';

        var styleElement = document.createElement('style');
        styleElement.id = 'plugin-fonts-lazy';
        styleElement.textContent = sideCartFontCss;
        document.head.appendChild(styleElement);
        console.log('✓ Plugin fonts loaded');
    }

    /**
     * User interaction events to trigger font loading
     */
    var events = ['scroll', 'mousemove', 'touchstart', 'click', 'keydown'];

    /**
     * Add event listeners for user interactions
     */
    function addEventListeners() {
        events.forEach(function(event) {
            window.addEventListener(event, loadFonts, { passive: true, once: true });
        });
    }

    /**
     * Remove event listeners
     */
    function removeEventListeners() {
        events.forEach(function(event) {
            window.removeEventListener(event, loadFonts);
        });
    }

    /**
     * Initialize when DOM is ready
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', addEventListeners);
    } else {
        addEventListeners();
    }

    // NO TIMEOUT - Fonts load ONLY on user interaction

})();

