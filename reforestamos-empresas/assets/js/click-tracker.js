/**
 * Company Click Tracker
 * 
 * Tracks clicks on company logos and links via AJAX
 * 
 * @package Reforestamos_Empresas
 */

(function($) {
    'use strict';
    
    /**
     * Track company click
     */
    function trackClick(companyId, clickType) {
        $.ajax({
            url: reforestamosComp.ajaxUrl,
            type: 'POST',
            data: {
                action: 'track_company_click',
                company_id: companyId,
                click_type: clickType,
                nonce: reforestamosComp.nonce
            },
            success: function(response) {
                if (response.success) {
                    console.log('Click tracked:', response.data);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error tracking click:', error);
            }
        });
    }
    
    /**
     * Initialize click tracking
     */
    $(document).ready(function() {
        // Track clicks on company logos
        $('.company-logo-link, .company-grid-item a').on('click', function(e) {
            var $link = $(this);
            var companyId = $link.data('company-id');
            
            if (companyId) {
                trackClick(companyId, 'logo');
            }
        });
        
        // Track clicks on company profile links
        $('.company-profile-link').on('click', function(e) {
            var $link = $(this);
            var companyId = $link.data('company-id');
            
            if (companyId) {
                trackClick(companyId, 'profile');
            }
        });
        
        // Track clicks on company website links
        $('.company-website-link').on('click', function(e) {
            var $link = $(this);
            var companyId = $link.data('company-id');
            
            if (companyId) {
                trackClick(companyId, 'website');
            }
        });
        
        // Track clicks on company contact links
        $('.company-contact-link').on('click', function(e) {
            var $link = $(this);
            var companyId = $link.data('company-id');
            
            if (companyId) {
                trackClick(companyId, 'contact');
            }
        });
    });
    
})(jQuery);
