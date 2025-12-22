<?php
/**
 * Form Submissions Admin Page
 * Display form submissions in WordPress admin
 *
 * @package Basel Child
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add admin menu for form submissions
 */
function rlg_add_form_submissions_menu() {
    add_menu_page(
        'Form Submissions',           // Page title
        'Form Submissions',           // Menu title
        'manage_options',             // Capability
        'rlg-form-submissions',       // Menu slug
        'rlg_form_submissions_page',  // Callback function
        'dashicons-email-alt',        // Icon
        25                            // Position
    );
}
add_action('admin_menu', 'rlg_add_form_submissions_menu');

/**
 * Display form submissions page
 */
function rlg_form_submissions_page() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'rlg_form_submissions';
    
    // Handle status update
    if (isset($_POST['update_status']) && isset($_POST['submission_id']) && isset($_POST['new_status'])) {
        check_admin_referer('rlg_update_status');
        $submission_id = intval($_POST['submission_id']);
        $new_status = sanitize_text_field($_POST['new_status']);
        
        $wpdb->update(
            $table_name,
            array('status' => $new_status),
            array('id' => $submission_id),
            array('%s'),
            array('%d')
        );
        
        echo '<div class="notice notice-success is-dismissible"><p>Status updated successfully!</p></div>';
    }
    
    // Handle delete
    if (isset($_POST['delete_submission']) && isset($_POST['submission_id'])) {
        check_admin_referer('rlg_delete_submission');
        $submission_id = intval($_POST['submission_id']);
        
        $wpdb->delete(
            $table_name,
            array('id' => $submission_id),
            array('%d')
        );
        
        echo '<div class="notice notice-success is-dismissible"><p>Submission deleted successfully!</p></div>';
    }
    
    // Get filter parameters
    $form_type_filter = isset($_GET['form_type']) ? sanitize_text_field($_GET['form_type']) : '';
    $status_filter = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';
    $search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
    
    // Build query
    $where = array('1=1');
    if ($form_type_filter) {
        $where[] = $wpdb->prepare('form_type = %s', $form_type_filter);
    }
    if ($status_filter) {
        $where[] = $wpdb->prepare('status = %s', $status_filter);
    }
    if ($search) {
        $where[] = $wpdb->prepare(
            '(customer_name LIKE %s OR customer_email LIKE %s OR customer_phone LIKE %s OR product_name LIKE %s)',
            '%' . $wpdb->esc_like($search) . '%',
            '%' . $wpdb->esc_like($search) . '%',
            '%' . $wpdb->esc_like($search) . '%',
            '%' . $wpdb->esc_like($search) . '%'
        );
    }
    
    $where_clause = implode(' AND ', $where);
    
    // Pagination
    $per_page = 20;
    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset = ($current_page - 1) * $per_page;
    
    // Get total count
    $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE $where_clause");
    $total_pages = ceil($total_items / $per_page);
    
    // Get submissions
    $submissions = $wpdb->get_results(
        "SELECT * FROM $table_name WHERE $where_clause ORDER BY created_at DESC LIMIT $per_page OFFSET $offset"
    );
    
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Form Submissions</h1>
        
        <!-- Filters -->
        <form method="get" style="margin: 20px 0;">
            <input type="hidden" name="page" value="rlg-form-submissions">
            
            <select name="form_type" style="margin-right: 10px;">
                <option value="">All Form Types</option>
                <option value="contact_form" <?php selected($form_type_filter, 'contact_form'); ?>>Contact Form</option>
                <option value="customize_jacket" <?php selected($form_type_filter, 'customize_jacket'); ?>>Customize Jacket</option>
            </select>
            
            <select name="status" style="margin-right: 10px;">
                <option value="">All Statuses</option>
                <option value="new" <?php selected($status_filter, 'new'); ?>>New</option>
                <option value="read" <?php selected($status_filter, 'read'); ?>>Read</option>
                <option value="replied" <?php selected($status_filter, 'replied'); ?>>Replied</option>
                <option value="completed" <?php selected($status_filter, 'completed'); ?>>Completed</option>
            </select>
            
            <input type="search" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Search..." style="margin-right: 10px;">
            
            <button type="submit" class="button">Filter</button>
            <a href="?page=rlg-form-submissions" class="button">Reset</a>
        </form>
        
        <p><strong>Total Submissions:</strong> <?php echo number_format($total_items); ?></p>

        <!-- Submissions Table -->
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th style="width: 120px;">Form Type</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Product</th>
                    <th style="width: 100px;">Status</th>
                    <th style="width: 150px;">Date</th>
                    <th style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($submissions)) : ?>
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 40px;">No submissions found.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($submissions as $submission) : ?>
                        <tr>
                            <td><?php echo esc_html($submission->id); ?></td>
                            <td>
                                <?php
                                echo $submission->form_type === 'contact_form'
                                    ? '<span style="color: #2271b1;">Contact Form</span>'
                                    : '<span style="color: #d63638;">Customize Jacket</span>';
                                ?>
                            </td>
                            <td><?php echo esc_html($submission->customer_name); ?></td>
                            <td><a href="mailto:<?php echo esc_attr($submission->customer_email); ?>"><?php echo esc_html($submission->customer_email); ?></a></td>
                            <td><?php echo esc_html($submission->customer_phone); ?></td>
                            <td>
                                <?php
                                if ($submission->product_id) {
                                    echo '<a href="' . get_edit_post_link($submission->product_id) . '" target="_blank">' . esc_html($submission->product_name) . '</a>';
                                } else {
                                    echo '—';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $status_colors = array(
                                    'new' => '#d63638',
                                    'read' => '#2271b1',
                                    'replied' => '#00a32a',
                                    'completed' => '#646970'
                                );
                                $color = isset($status_colors[$submission->status]) ? $status_colors[$submission->status] : '#646970';
                                ?>
                                <span style="color: <?php echo $color; ?>; font-weight: 600;">
                                    <?php echo esc_html(ucfirst($submission->status)); ?>
                                </span>
                            </td>
                            <td><?php echo esc_html(date('M j, Y g:i A', strtotime($submission->created_at))); ?></td>
                            <td>
                                <a href="#" class="button button-small" onclick="rlgViewSubmission(<?php echo $submission->id; ?>); return false;">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if ($total_pages > 1) : ?>
            <div class="tablenav bottom">
                <div class="tablenav-pages">
                    <?php
                    $page_links = paginate_links(array(
                        'base' => add_query_arg('paged', '%#%'),
                        'format' => '',
                        'prev_text' => '&laquo;',
                        'next_text' => '&raquo;',
                        'total' => $total_pages,
                        'current' => $current_page
                    ));
                    echo $page_links;
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal for viewing submission details -->
    <div id="rlg-submission-modal" style="display: none; position: fixed; z-index: 100000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
        <div style="background-color: #fff; margin: 50px auto; padding: 0; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto; border-radius: 5px; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
            <div style="padding: 20px; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center;">
                <h2 style="margin: 0;">Submission Details</h2>
                <button onclick="rlgCloseModal()" style="background: none; border: none; font-size: 24px; cursor: pointer;">&times;</button>
            </div>
            <div id="rlg-submission-content" style="padding: 20px;">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <script>
    function rlgViewSubmission(id) {
        document.getElementById('rlg-submission-modal').style.display = 'block';
        document.getElementById('rlg-submission-content').innerHTML = '<p>Loading...</p>';

        jQuery.post(ajaxurl, {
            action: 'rlg_get_submission_details',
            submission_id: id
        }, function(response) {
            if (response.success) {
                document.getElementById('rlg-submission-content').innerHTML = response.data.html;
            } else {
                document.getElementById('rlg-submission-content').innerHTML = '<p>Error loading submission.</p>';
            }
        });
    }

    function rlgCloseModal() {
        document.getElementById('rlg-submission-modal').style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        var modal = document.getElementById('rlg-submission-modal');
        if (event.target == modal) {
            rlgCloseModal();
        }
    }
    </script>
    <?php
}

/**
 * AJAX handler to get submission details
 */
function rlg_get_submission_details_ajax() {
    global $wpdb;

    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'Unauthorized'));
    }

    $submission_id = isset($_POST['submission_id']) ? intval($_POST['submission_id']) : 0;

    if (!$submission_id) {
        wp_send_json_error(array('message' => 'Invalid submission ID'));
    }

    $table_name = $wpdb->prefix . 'rlg_form_submissions';
    $submission = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $submission_id));

    if (!$submission) {
        wp_send_json_error(array('message' => 'Submission not found'));
    }

    $data = json_decode($submission->submission_data, true);

    ob_start();
    ?>
    <div style="margin-bottom: 20px;">
        <h3>Customer Information</h3>
        <table class="widefat" style="margin-bottom: 20px;">
            <tr>
                <th style="width: 200px;">Name:</th>
                <td><?php echo esc_html($submission->customer_name); ?></td>
            </tr>
            <tr>
                <th>Email:</th>
                <td><a href="mailto:<?php echo esc_attr($submission->customer_email); ?>"><?php echo esc_html($submission->customer_email); ?></a></td>
            </tr>
            <tr>
                <th>Phone:</th>
                <td><?php echo esc_html($submission->customer_phone); ?></td>
            </tr>
            <?php if ($submission->product_name) : ?>
            <tr>
                <th>Product:</th>
                <td>
                    <a href="<?php echo get_edit_post_link($submission->product_id); ?>" target="_blank">
                        <?php echo esc_html($submission->product_name); ?>
                    </a>
                </td>
            </tr>
            <?php endif; ?>
            <tr>
                <th>IP Address:</th>
                <td><?php echo esc_html($submission->ip_address); ?></td>
            </tr>
            <tr>
                <th>Submitted:</th>
                <td><?php echo esc_html(date('F j, Y g:i A', strtotime($submission->created_at))); ?></td>
            </tr>
        </table>

        <h3>Form Data</h3>
        <table class="widefat">
            <?php
            foreach ($data as $key => $value) {
                // Skip nonce and action fields
                if (in_array($key, array('action', 'rlg_contact_nonce', 'rlg_custom_form_nonce'))) {
                    continue;
                }

                // Format key
                $label = ucwords(str_replace('_', ' ', $key));

                // Format value
                if (is_array($value)) {
                    $value = implode(', ', $value);
                }

                echo '<tr>';
                echo '<th style="width: 200px;">' . esc_html($label) . ':</th>';
                echo '<td>' . nl2br(esc_html($value)) . '</td>';
                echo '</tr>';
            }
            ?>
        </table>

        <h3>Update Status</h3>
        <form method="post" action="?page=rlg-form-submissions">
            <?php wp_nonce_field('rlg_update_status'); ?>
            <input type="hidden" name="submission_id" value="<?php echo $submission->id; ?>">
            <select name="new_status" style="margin-right: 10px;">
                <option value="new" <?php selected($submission->status, 'new'); ?>>New</option>
                <option value="read" <?php selected($submission->status, 'read'); ?>>Read</option>
                <option value="replied" <?php selected($submission->status, 'replied'); ?>>Replied</option>
                <option value="completed" <?php selected($submission->status, 'completed'); ?>>Completed</option>
            </select>
            <button type="submit" name="update_status" class="button button-primary">Update Status</button>
        </form>

        <h3 style="margin-top: 20px;">Delete Submission</h3>
        <form method="post" action="?page=rlg-form-submissions" onsubmit="return confirm('Are you sure you want to delete this submission? This action cannot be undone.');">
            <?php wp_nonce_field('rlg_delete_submission'); ?>
            <input type="hidden" name="submission_id" value="<?php echo $submission->id; ?>">
            <button type="submit" name="delete_submission" class="button button-link-delete">Delete Submission</button>
        </form>
    </div>
    <?php
    $html = ob_get_clean();

    wp_send_json_success(array('html' => $html));
}
add_action('wp_ajax_rlg_get_submission_details', 'rlg_get_submission_details_ajax');


