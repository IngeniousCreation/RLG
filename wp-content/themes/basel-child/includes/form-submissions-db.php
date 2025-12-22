<?php
/**
 * Form Submissions Database Handler
 * Creates and manages form submissions table
 *
 * @package Basel Child
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create form submissions table
 */
function rlg_create_form_submissions_table() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'rlg_form_submissions';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        form_type varchar(50) NOT NULL,
        submission_data longtext NOT NULL,
        customer_name varchar(255) DEFAULT NULL,
        customer_email varchar(255) DEFAULT NULL,
        customer_phone varchar(50) DEFAULT NULL,
        product_id bigint(20) UNSIGNED DEFAULT NULL,
        product_name varchar(255) DEFAULT NULL,
        status varchar(20) DEFAULT 'new',
        ip_address varchar(100) DEFAULT NULL,
        user_agent text DEFAULT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY form_type (form_type),
        KEY customer_email (customer_email),
        KEY status (status),
        KEY created_at (created_at)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

/**
 * Save form submission to database
 *
 * @param string $form_type Type of form (contact_form or customize_jacket)
 * @param array $data Form data
 * @return int|false Submission ID or false on failure
 */
function rlg_save_form_submission($form_type, $data) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'rlg_form_submissions';
    
    // Extract common fields
    $customer_name = '';
    $customer_email = '';
    $customer_phone = '';
    $product_id = null;
    $product_name = '';
    
    if ($form_type === 'contact_form') {
        $customer_name = isset($data['contact_name']) && isset($data['contact_lname']) 
            ? trim($data['contact_name'] . ' ' . $data['contact_lname']) 
            : '';
        $customer_email = isset($data['contact_email']) ? $data['contact_email'] : '';
        $customer_phone = isset($data['contact_phone']) ? $data['contact_phone'] : '';
    } elseif ($form_type === 'customize_jacket') {
        $customer_name = isset($data['customer_name']) ? $data['customer_name'] : '';
        $customer_email = isset($data['customer_email']) ? $data['customer_email'] : '';
        $customer_phone = isset($data['customer_phone']) ? $data['customer_phone'] : '';
        $product_id = isset($data['product_id']) ? intval($data['product_id']) : null;
        $product_name = isset($data['product_name']) ? $data['product_name'] : '';
    }
    
    // Get IP address
    $ip_address = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    }
    
    // Get user agent
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    
    // Insert data
    $result = $wpdb->insert(
        $table_name,
        array(
            'form_type' => $form_type,
            'submission_data' => json_encode($data, JSON_UNESCAPED_UNICODE),
            'customer_name' => $customer_name,
            'customer_email' => $customer_email,
            'customer_phone' => $customer_phone,
            'product_id' => $product_id,
            'product_name' => $product_name,
            'status' => 'new',
            'ip_address' => $ip_address,
            'user_agent' => $user_agent,
        ),
        array(
            '%s', // form_type
            '%s', // submission_data
            '%s', // customer_name
            '%s', // customer_email
            '%s', // customer_phone
            '%d', // product_id
            '%s', // product_name
            '%s', // status
            '%s', // ip_address
            '%s', // user_agent
        )
    );
    
    if ($result) {
        return $wpdb->insert_id;
    }
    
    return false;
}

/**
 * Run on theme activation
 */
add_action('after_switch_theme', 'rlg_create_form_submissions_table');

/**
 * Run on admin init to ensure table exists
 */
add_action('admin_init', 'rlg_create_form_submissions_table');

