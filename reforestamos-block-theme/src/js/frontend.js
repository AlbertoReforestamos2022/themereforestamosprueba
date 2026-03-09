/**
 * Frontend JavaScript for Reforestamos Block Theme
 * 
 * @package Reforestamos
 */

// Initialize GLightbox for gallery blocks
document.addEventListener('DOMContentLoaded', function() {
    // Check if GLightbox is loaded
    if (typeof GLightbox !== 'undefined') {
        // Initialize GLightbox for all gallery items
        const lightbox = GLightbox({
            touchNavigation: true,
            loop: true,
            autoplayVideos: true,
            closeButton: true,
            zoomable: true,
            draggable: true,
            dragToleranceX: 40,
            dragToleranceY: 65,
            preload: true
        });
    } else {
        console.warn('GLightbox is not loaded. Gallery lightbox functionality will not work.');
    }
});
