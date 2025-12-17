/**
 * Lazy Load GLightbox for Size Chart
 * Loads GLightbox library only on user interaction (scroll, click, mousemove, touchstart)
 */

(function() {
    'use strict';

    let lightboxLoaded = false;
    let lightboxInstance = null;

    /**
     * Load GLightbox CSS
     */
    function loadLightboxCSS() {
        if (document.getElementById('glightbox-css')) {
            return;
        }

        const link = document.createElement('link');
        link.id = 'glightbox-css';
        link.rel = 'stylesheet';
        link.href = 'https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/css/glightbox.min.css';
        document.head.appendChild(link);
    }

    /**
     * Load GLightbox JavaScript
     */
    function loadLightboxJS(callback) {
        if (window.GLightbox) {
            if (callback) callback();
            return;
        }

        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/glightbox@3.2.0/dist/js/glightbox.min.js';
        script.onload = function() {
            console.log('GLightbox loaded successfully');
            if (callback) callback();
        };
        script.onerror = function() {
            console.error('Failed to load GLightbox');
        };
        document.body.appendChild(script);
    }

    /**
     * Initialize GLightbox for size chart
     */
    function initializeLightbox() {
        if (lightboxInstance) {
            return;
        }

        if (!window.GLightbox) {
            console.error('GLightbox not loaded yet');
            return;
        }

        // Initialize GLightbox
        lightboxInstance = GLightbox({
            selector: '.rlg-size-chart-button',
            touchNavigation: true,
            loop: false,
            autoplayVideos: false,
            zoomable: true,
            draggable: true,
            closeButton: true,
            closeOnOutsideClick: true,
            skin: 'clean',
            moreLength: 0
        });

        console.log('GLightbox initialized for size chart');
    }

    /**
     * Load lightbox on user interaction
     */
    function loadLightboxOnInteraction() {
        if (lightboxLoaded) {
            return;
        }

        lightboxLoaded = true;

        // Load CSS immediately
        loadLightboxCSS();

        // Load JS and initialize
        loadLightboxJS(function() {
            initializeLightbox();
        });

        // Remove event listeners after first interaction
        document.removeEventListener('scroll', loadLightboxOnInteraction);
        document.removeEventListener('mousemove', loadLightboxOnInteraction);
        document.removeEventListener('touchstart', loadLightboxOnInteraction);
        document.removeEventListener('click', loadLightboxOnInteraction);
    }

    /**
     * Setup event listeners for lazy loading
     */
    function setupLazyLoading() {
        // Load on user interaction
        document.addEventListener('scroll', loadLightboxOnInteraction, { passive: true, once: true });
        document.addEventListener('mousemove', loadLightboxOnInteraction, { passive: true, once: true });
        document.addEventListener('touchstart', loadLightboxOnInteraction, { passive: true, once: true });
        document.addEventListener('click', loadLightboxOnInteraction, { passive: true, once: true });

        console.log('Lazy loading setup for GLightbox - will load on user interaction');
    }

    /**
     * Initialize on DOM ready
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupLazyLoading);
    } else {
        setupLazyLoading();
    }

})();

