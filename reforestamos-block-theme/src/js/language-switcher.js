/**
 * Language Switcher JavaScript
 *
 * @package Reforestamos
 */

(function($) {
    'use strict';

    /**
     * Language Switcher Handler
     */
    const LanguageSwitcher = {
        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
            this.updateUI();
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            // Handle language button clicks
            $(document).on('click', '.language-switcher .lang-btn', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const lang = $button.data('lang');
                
                if (!lang || $button.hasClass('active')) {
                    return;
                }
                
                LanguageSwitcher.switchLanguage(lang, $button.attr('href'));
            });
        },

        /**
         * Switch language
         *
         * @param {string} lang Language code
         * @param {string} url URL to redirect to
         */
        switchLanguage: function(lang, url) {
            // Show loading state
            $('.language-switcher .lang-btn').prop('disabled', true);
            
            // Send AJAX request to set language
            $.ajax({
                url: reforestamosI18n.ajaxurl,
                type: 'POST',
                data: {
                    action: 'reforestamos_switch_language',
                    nonce: reforestamosI18n.nonce,
                    lang: lang
                },
                success: function(response) {
                    if (response.success) {
                        // Redirect to the URL (which will have the lang parameter)
                        window.location.href = url;
                    } else {
                        console.error('Language switch failed:', response.data.message);
                        $('.language-switcher .lang-btn').prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                    $('.language-switcher .lang-btn').prop('disabled', false);
                }
            });
        },

        /**
         * Update UI based on current language
         */
        updateUI: function() {
            const currentLang = reforestamosI18n.currentLang || 'es';
            
            // Update active state
            $('.language-switcher .lang-btn').removeClass('active');
            $('.language-switcher .lang-btn[data-lang="' + currentLang + '"]').addClass('active');
            
            // Update HTML lang attribute
            $('html').attr('lang', currentLang === 'es' ? 'es-MX' : 'en-US');
        }
    };

    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        LanguageSwitcher.init();
    });

})(jQuery);
