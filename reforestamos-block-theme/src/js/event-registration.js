/**
 * Event Registration Form Handler
 *
 * Handles AJAX submission of event registration forms.
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        // Handle form submission
        $('.event-registration-form-inner').on('submit', function(e) {
            e.preventDefault();
            
            const $form = $(this);
            const $button = $form.find('button[type="submit"]');
            const $message = $form.find('.form-message');
            const originalButtonText = $button.text();
            
            // Disable button and show loading state
            $button.prop('disabled', true).text(reforestamosEventReg.processing);
            $message.html('').removeClass('alert alert-success alert-danger');
            
            // Get form data
            const formData = $form.serialize();
            
            // Submit via AJAX
            $.ajax({
                url: reforestamosEventReg.ajaxurl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        $message
                            .addClass('alert alert-success')
                            .html(response.data.message);
                        
                        // Reset form
                        $form[0].reset();
                        
                        // Scroll to message
                        $('html, body').animate({
                            scrollTop: $message.offset().top - 100
                        }, 500);
                    } else {
                        // Show error message
                        $message
                            .addClass('alert alert-danger')
                            .html(response.data.message || reforestamosEventReg.error);
                    }
                },
                error: function() {
                    // Show generic error
                    $message
                        .addClass('alert alert-danger')
                        .html(reforestamosEventReg.error);
                },
                complete: function() {
                    // Re-enable button
                    $button.prop('disabled', false).text(originalButtonText);
                }
            });
        });
        
        // Real-time email validation
        $('input[type="email"]').on('blur', function() {
            const $input = $(this);
            const email = $input.val();
            
            if (email && !isValidEmail(email)) {
                $input.addClass('is-invalid');
                if (!$input.next('.invalid-feedback').length) {
                    $input.after('<div class="invalid-feedback">Por favor, ingresa un correo electrónico válido.</div>');
                }
            } else {
                $input.removeClass('is-invalid');
                $input.next('.invalid-feedback').remove();
            }
        });
        
        // Helper function to validate email
        function isValidEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
    });
    
})(jQuery);
