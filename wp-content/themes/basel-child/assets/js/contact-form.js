/**
 * Contact Form Handler
 *
 * @package Basel Child
 * @since 1.0.0
 */

(function($) {
    'use strict';

    $(document).ready(function() {

        // Set form load timestamp for spam prevention
        var formLoadTime = Math.floor(Date.now() / 1000);

        // Handle contact form submission
        $('#rlgContactForm').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);
            var $submitBtn = $form.find('.rlg-submit-btn');
            var $message = $form.find('.rlg-form-message');
            var originalBtnText = $submitBtn.find('span').text();

            // Disable submit button
            $submitBtn.prop('disabled', true);
            $submitBtn.find('span').text('Sending...');

            // Hide previous messages
            $message.hide().removeClass('success error');

            // Get form data
            var formData = {
                action: 'rlg_contact_form_submit',
                contact_name: $('#contact_name').val().trim(),
                contact_lname: $('#contact_lname').val().trim(),
                contact_email: $('#contact_email').val().trim(),
                contact_phone: $('#contact_phone').val().trim(),
                contact_message: $('#contact_message').val().trim(),
                form_load_time: formLoadTime,
                rlg_contact_nonce: $('input[name="rlg_contact_nonce"]').val()
            };

            // Send AJAX request
            $.ajax({
                url: ajax_object.ajax_url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        $message.addClass('success')
                               .html('<strong>Success!</strong> ' + response.data.message)
                               .fadeIn();

                        // Reset form
                        $form[0].reset();

                        // Scroll to success message
                        $message[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                    } else {
                        // Show error message
                        $message.addClass('error')
                               .html('<strong>Validation Error</strong> ' + response.data.message)
                               .fadeIn();

                        // Scroll to error message
                        $message[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                },
                error: function(xhr, status, error) {
                    var errorMsg = 'An unexpected error occurred. Please try again.';

                    if (xhr.responseJSON && xhr.responseJSON.data && xhr.responseJSON.data.message) {
                        errorMsg = xhr.responseJSON.data.message;
                    } else if (xhr.status === 0) {
                        errorMsg = 'Network error. Please check your internet connection.';
                    } else if (xhr.status === 500) {
                        errorMsg = 'Server error. Please try again later.';
                    } else if (xhr.status === 429) {
                        errorMsg = 'Too many requests. Please wait a moment and try again.';
                    }

                    // Show error message
                    $message.addClass('error')
                           .html('<strong>Error</strong> ' + errorMsg)
                           .fadeIn();

                    // Scroll to error message
                    $message[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                },
                complete: function() {
                    // Re-enable submit button
                    $submitBtn.prop('disabled', false);
                    $submitBtn.find('span').text(originalBtnText);
                }
            });
        });

    });

})(jQuery);

