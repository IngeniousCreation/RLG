/**
 * Custom Jacket Form JavaScript
 */

jQuery(document).ready(function($) {

    // Open modal
    $('#rlg-open-customize-form').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        // Prevent any add-to-cart behavior
        $(this).removeClass('disabled loading added');

        // Set form load timestamp for spam prevention
        $('#rlg-form-load-time').val(Math.floor(Date.now() / 1000));

        $('#rlg-customize-modal').fadeIn(300);
        $('body').css('overflow', 'hidden');

        return false;
    });
    
    // Close modal
    $('#rlg-close-customize-form, .rlg-modal-overlay').on('click', function(e) {
        e.preventDefault();
        $('#rlg-customize-modal').fadeOut(300);
        $('body').css('overflow', '');
    });
    
    // Close on ESC key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#rlg-customize-modal').is(':visible')) {
            $('#rlg-customize-modal').fadeOut(300);
            $('body').css('overflow', '');
        }
    });
    
    // Update file input label when file selected
    $('#rlg-design-file').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        if (fileName) {
            $(this).siblings('.rlg-file-text').text(fileName);
        } else {
            $(this).siblings('.rlg-file-text').text('Upload Design File');
        }
    });
    
    // Handle form submission
    $('#rlg-custom-jacket-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $submitBtn = $form.find('.rlg-submit-button');
        var $message = $('#rlg-form-message');
        
        // Disable submit button
        $submitBtn.prop('disabled', true).text('Sending...');
        $message.hide().removeClass('success error');
        
        // Prepare form data
        var formData = new FormData(this);
        formData.append('action', 'rlg_custom_jacket_form');
        
        // Send AJAX request
        $.ajax({
            url: rlgCustomForm.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $message
                        .addClass('success')
                        .html('<strong>Success</strong>' + response.data.message)
                        .fadeIn();

                    // Reset form
                    $form[0].reset();
                    $('#rlg-design-file').siblings('.rlg-file-text').text('Upload Design File (JPG/PNG only)');

                    // Close modal after 4 seconds
                    setTimeout(function() {
                        $('#rlg-customize-modal').fadeOut(300);
                        $('body').css('overflow', '');
                        $message.hide();
                    }, 4000);
                } else {
                    $message
                        .addClass('error')
                        .html('<strong>Validation Error</strong>' + response.data.message)
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
                } else if (xhr.status === 413) {
                    errorMsg = 'File is too large. Please upload a smaller file.';
                } else if (xhr.status === 500) {
                    errorMsg = 'Server error. Please try again later.';
                }

                $message
                    .addClass('error')
                    .html('<strong>Error</strong>' + errorMsg)
                    .fadeIn();

                // Scroll to error message
                $message[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            },
            complete: function() {
                // Re-enable submit button
                $submitBtn.prop('disabled', false).text('Get Started');
            }
        });
    });
    
});

