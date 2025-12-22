<?php
/**
 * Custom Jacket Form Email Handler
 * Handles form submission and sends email
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle custom jacket form submission via AJAX
 */
function rlg_handle_custom_jacket_form() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'rlg_custom_jacket_form')) {
        wp_send_json_error(array('message' => 'Security check failed. Please refresh the page and try again.'));
        return;
    }

    // Rate limiting - prevent spam submissions
    $user_ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $rate_limit_key = 'rlg_form_submit_' . md5($user_ip);
    $submission_count = get_transient($rate_limit_key);

    if ($submission_count && $submission_count >= 3) {
        wp_send_json_error(array('message' => 'Too many submissions. Please wait 10 minutes before trying again.'));
        return;
    }

    // Honeypot check (anti-bot)
    if (!empty($_POST['website'])) {
        wp_send_json_error(array('message' => 'Spam detected.'));
        return;
    }

    // Time-based check (form must be filled for at least 3 seconds)
    $form_load_time = intval($_POST['form_load_time'] ?? 0);
    if ($form_load_time > 0 && (time() - $form_load_time) < 3) {
        wp_send_json_error(array('message' => 'Form submitted too quickly. Please try again.'));
        return;
    }

    // Sanitize form data
    $name = sanitize_text_field($_POST['name'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $phone = sanitize_text_field($_POST['phone'] ?? '');
    $company = sanitize_text_field($_POST['company'] ?? '');
    $quantity = sanitize_text_field($_POST['quantity'] ?? '');
    $country = sanitize_text_field($_POST['country'] ?? '');
    $gender = sanitize_text_field($_POST['gender'] ?? '');
    $message = sanitize_textarea_field($_POST['message'] ?? '');
    $product_id = intval($_POST['product_id'] ?? 0);
    $product_title = sanitize_text_field($_POST['product_title'] ?? '');

    // Validation errors array
    $errors = array();

    // Validate email
    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!is_email($email)) {
        $errors[] = 'Please enter a valid email address.';
    } elseif (rlg_is_disposable_email($email)) {
        $errors[] = 'Disposable email addresses are not allowed.';
    }

    // Validate phone
    if (empty($phone)) {
        $errors[] = 'Phone number is required.';
    } elseif (!preg_match('/^[0-9\s\+\-\(\)]{10,20}$/', $phone)) {
        $errors[] = 'Please enter a valid phone number (10-20 digits).';
    }

    // Validate company
    if (empty($company)) {
        $errors[] = 'Company/Organisation is required.';
    } elseif (strlen($company) < 2) {
        $errors[] = 'Company name must be at least 2 characters.';
    } elseif (strlen($company) > 100) {
        $errors[] = 'Company name is too long (max 100 characters).';
    } elseif (rlg_contains_non_english($company)) {
        $errors[] = 'Company name must be in English.';
    }

    // Validate quantity
    $valid_quantities = array('1', '2-5', '6-10', '10-30', '30+');
    if (empty($quantity)) {
        $errors[] = 'Quantity is required.';
    } elseif (!in_array($quantity, $valid_quantities)) {
        $errors[] = 'Please select a valid quantity.';
    }

    // Validate country
    $valid_countries = array('France, Metropolitan', 'Germany', 'Australia', 'Canada', 'United Kingdom', 'United States');
    if (empty($country)) {
        $errors[] = 'Country is required.';
    } elseif (!in_array($country, $valid_countries)) {
        $errors[] = 'Please select a valid country.';
    }

    // Validate gender
    $valid_genders = array('Male', 'Female');
    if (empty($gender)) {
        $errors[] = 'Gender is required.';
    } elseif (!in_array($gender, $valid_genders)) {
        $errors[] = 'Please select a valid gender.';
    }

    // Validate name (optional but if provided, check it)
    if (!empty($name)) {
        if (strlen($name) < 2) {
            $errors[] = 'Name must be at least 2 characters.';
        } elseif (strlen($name) > 100) {
            $errors[] = 'Name is too long (max 100 characters).';
        } elseif (rlg_contains_non_english($name)) {
            $errors[] = 'Name must be in English.';
        } elseif (!preg_match('/^[a-zA-Z\s\-\.]+$/', $name)) {
            $errors[] = 'Name contains invalid characters.';
        }
    }

    // Validate message (optional but check length)
    if (!empty($message)) {
        if (strlen($message) > 2000) {
            $errors[] = 'Message is too long (max 2000 characters).';
        }
        // Check for non-English characters
        if (rlg_contains_non_english($message)) {
            $errors[] = 'Message must be in English.';
        }
        // Check for spam patterns
        if (preg_match('/(viagra|cialis|casino|lottery|winner|click here|buy now)/i', $message)) {
            $errors[] = 'Message contains prohibited content.';
        }
    }

    // Check for spam patterns in company name
    if (preg_match('/(viagra|cialis|casino|lottery|seo service|buy now)/i', $company)) {
        $errors[] = 'Company name contains prohibited content.';
    }

    // Return validation errors
    if (!empty($errors)) {
        wp_send_json_error(array('message' => implode('<br>', $errors)));
        return;
    }
    
    // Handle file upload with validation
    $attachment_id = 0;
    $attachment_url = '';

    if (!empty($_FILES['design_file']['name'])) {
        $file = $_FILES['design_file'];

        // Validate file upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $upload_errors = array(
                UPLOAD_ERR_INI_SIZE => 'File is too large (server limit).',
                UPLOAD_ERR_FORM_SIZE => 'File is too large.',
                UPLOAD_ERR_PARTIAL => 'File upload was interrupted. Please try again.',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
                UPLOAD_ERR_NO_TMP_DIR => 'Server error: Missing temporary folder.',
                UPLOAD_ERR_CANT_WRITE => 'Server error: Failed to write file.',
                UPLOAD_ERR_EXTENSION => 'File upload blocked by server extension.',
            );
            $error_message = $upload_errors[$file['error']] ?? 'Unknown file upload error.';
            wp_send_json_error(array('message' => $error_message));
            return;
        }

        // Validate file size (max 10MB)
        $max_file_size = 10 * 1024 * 1024; // 10MB in bytes
        if ($file['size'] > $max_file_size) {
            wp_send_json_error(array('message' => 'File is too large. Maximum size is 10MB.'));
            return;
        }

        // Validate file type - Only JPG and PNG
        $allowed_types = array(
            'image/jpeg',
            'image/jpg',
            'image/png',
        );

        $file_type = mime_content_type($file['tmp_name']);
        if (!in_array($file_type, $allowed_types)) {
            wp_send_json_error(array('message' => 'Invalid file type. Only JPG and PNG images are allowed.'));
            return;
        }

        // Validate file extension
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed_extensions = array('jpg', 'jpeg', 'png');
        if (!in_array($file_ext, $allowed_extensions)) {
            wp_send_json_error(array('message' => 'Invalid file extension. Only .jpg and .png files are allowed.'));
            return;
        }

        // Sanitize filename
        $safe_filename = sanitize_file_name($file['name']);
        if ($safe_filename !== $file['name']) {
            $file['name'] = $safe_filename;
        }

        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $upload = wp_handle_upload($file, array('test_form' => false));

        if (isset($upload['error'])) {
            wp_send_json_error(array('message' => 'File upload failed: ' . $upload['error']));
            return;
        }

        if (isset($upload['file'])) {
            $attachment_id = wp_insert_attachment(array(
                'post_mime_type' => $upload['type'],
                'post_title' => $safe_filename,
                'post_content' => '',
                'post_status' => 'inherit'
            ), $upload['file']);

            $attachment_url = $upload['url'];
        }
    }
    
    // Prepare email content
    $to = defined('RLG_CUSTOM_FORM_EMAIL') ? RLG_CUSTOM_FORM_EMAIL : get_option('admin_email');
    $subject = 'New Custom Jacket Order Request - ' . $product_title;
    
    $email_body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #000; color: #fff; padding: 20px; text-align: center; }
            .content { background: #f9f9f9; padding: 20px; margin-top: 20px; }
            .field { margin-bottom: 15px; }
            .label { font-weight: bold; color: #000; }
            .value { color: #555; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>New Custom Jacket Order Request</h2>
            </div>
            <div class='content'>
                <div class='field'>
                    <span class='label'>Product:</span>
                    <span class='value'>{$product_title}</span>
                </div>
                <div class='field'>
                    <span class='label'>Name:</span>
                    <span class='value'>{$name}</span>
                </div>
                <div class='field'>
                    <span class='label'>Email:</span>
                    <span class='value'>{$email}</span>
                </div>
                <div class='field'>
                    <span class='label'>Phone:</span>
                    <span class='value'>{$phone}</span>
                </div>
                <div class='field'>
                    <span class='label'>Company/Organisation:</span>
                    <span class='value'>{$company}</span>
                </div>
                <div class='field'>
                    <span class='label'>Quantity:</span>
                    <span class='value'>{$quantity}</span>
                </div>
                <div class='field'>
                    <span class='label'>Country:</span>
                    <span class='value'>{$country}</span>
                </div>
                <div class='field'>
                    <span class='label'>Gender:</span>
                    <span class='value'>{$gender}</span>
                </div>
                <div class='field'>
                    <span class='label'>Description:</span>
                    <span class='value'>{$message}</span>
                </div>
                " . ($attachment_url ? "<div class='field'><span class='label'>Design File:</span><span class='value'><a href='{$attachment_url}'>Download File</a></span></div>" : "") . "
            </div>
        </div>
    </body>
    </html>
    ";
    
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>',
        'Reply-To: ' . $email
    );

    // Save to database
    $submission_data = array(
        'product_id' => $product_id,
        'product_name' => $product_title,
        'customer_name' => $name,
        'customer_email' => $email,
        'customer_phone' => $phone,
        'chest' => $chest,
        'waist' => $waist,
        'shoulder' => $shoulder,
        'sleeve' => $sleeve,
        'jacket_length' => $jacket_length,
        'additional_notes' => $additional_notes,
        'attachment_url' => $attachment_url,
    );

    $submission_id = rlg_save_form_submission('customize_jacket', $submission_data);

    // Send email
    $sent = wp_mail($to, $subject, $email_body, $headers);

    if ($sent) {
        // Increment rate limit counter
        $new_count = $submission_count ? $submission_count + 1 : 1;
        set_transient($rate_limit_key, $new_count, 600); // 10 minutes

        wp_send_json_success(array('message' => 'Your custom order request has been submitted successfully! We will contact you soon.'));
    } else {
        wp_send_json_error(array('message' => 'Failed to send email. Please try again or contact us directly.'));
    }
}

/**
 * Check if email is from a disposable email provider
 */
function rlg_is_disposable_email($email) {
    $disposable_domains = array(
        'tempmail.com', 'guerrillamail.com', '10minutemail.com', 'mailinator.com',
        'throwaway.email', 'temp-mail.org', 'fakeinbox.com', 'trashmail.com',
        'yopmail.com', 'maildrop.cc', 'getnada.com', 'sharklasers.com',
        'guerrillamailblock.com', 'spam4.me', 'grr.la', 'discard.email'
    );

    $email_domain = substr(strrchr($email, "@"), 1);
    return in_array(strtolower($email_domain), $disposable_domains);
}

/**
 * Check if text contains Chinese or Russian characters
 */
function rlg_contains_non_english($text) {
    // Check for Chinese characters (CJK Unified Ideographs)
    if (preg_match('/[\x{4E00}-\x{9FFF}\x{3400}-\x{4DBF}\x{20000}-\x{2A6DF}\x{2A700}-\x{2B73F}\x{2B740}-\x{2B81F}\x{2B820}-\x{2CEAF}\x{F900}-\x{FAFF}\x{2F800}-\x{2FA1F}]/u', $text)) {
        return true;
    }

    // Check for Russian/Cyrillic characters
    if (preg_match('/[\x{0400}-\x{04FF}\x{0500}-\x{052F}\x{2DE0}-\x{2DFF}\x{A640}-\x{A69F}\x{1C80}-\x{1C8F}]/u', $text)) {
        return true;
    }

    return false;
}

add_action('wp_ajax_rlg_custom_jacket_form', 'rlg_handle_custom_jacket_form');
add_action('wp_ajax_nopriv_rlg_custom_jacket_form', 'rlg_handle_custom_jacket_form');

