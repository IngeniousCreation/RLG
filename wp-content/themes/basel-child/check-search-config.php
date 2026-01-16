<?php
/**
 * Diagnostic script to check Basel search configuration
 * 
 * Access this file directly in browser:
 * https://yoursite.com/wp-content/themes/basel-child/check-search-config.php
 */

// Load WordPress
require_once('../../../../wp-load.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
	die('Access denied. You must be an administrator.');
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Basel Search Configuration Check</title>
	<style>
		body {
			font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
			max-width: 1200px;
			margin: 40px auto;
			padding: 20px;
			background: #f5f5f5;
		}
		.section {
			background: white;
			padding: 20px;
			margin-bottom: 20px;
			border-radius: 8px;
			box-shadow: 0 2px 4px rgba(0,0,0,0.1);
		}
		h1 {
			color: #333;
			border-bottom: 3px solid #0073aa;
			padding-bottom: 10px;
		}
		h2 {
			color: #0073aa;
			margin-top: 0;
		}
		.status {
			display: inline-block;
			padding: 4px 12px;
			border-radius: 4px;
			font-weight: bold;
			margin-left: 10px;
		}
		.status.ok {
			background: #46b450;
			color: white;
		}
		.status.error {
			background: #dc3232;
			color: white;
		}
		.status.warning {
			background: #ffb900;
			color: #333;
		}
		table {
			width: 100%;
			border-collapse: collapse;
		}
		th, td {
			padding: 12px;
			text-align: left;
			border-bottom: 1px solid #ddd;
		}
		th {
			background: #f9f9f9;
			font-weight: 600;
		}
		code {
			background: #f5f5f5;
			padding: 2px 6px;
			border-radius: 3px;
			font-family: 'Courier New', monospace;
		}
		.code-block {
			background: #282c34;
			color: #abb2bf;
			padding: 15px;
			border-radius: 4px;
			overflow-x: auto;
			font-family: 'Courier New', monospace;
			font-size: 13px;
		}
	</style>
</head>
<body>
	<h1>🔍 Basel Search Configuration Diagnostic</h1>

	<div class="section">
		<h2>1. Basel Theme Settings</h2>
		<table>
			<tr>
				<th>Setting</th>
				<th>Value</th>
				<th>Status</th>
			</tr>
			<tr>
				<td>AJAX Search Enabled</td>
				<td><code><?php echo basel_get_opt('search_ajax') ? 'Yes' : 'No'; ?></code></td>
				<td>
					<?php if (basel_get_opt('search_ajax')): ?>
						<span class="status ok">✓ OK</span>
					<?php else: ?>
						<span class="status error">✗ DISABLED</span>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td>Search Post Type</td>
				<td><code><?php echo basel_get_opt('search_post_type'); ?></code></td>
				<td><span class="status ok">✓</span></td>
			</tr>
			<tr>
				<td>Search Result Count</td>
				<td><code><?php echo basel_get_opt('search_ajax_result_count') ?: '5 (default)'; ?></code></td>
				<td><span class="status warning">⚠ Should be 3</span></td>
			</tr>
			<tr>
				<td>Search by SKU</td>
				<td><code><?php echo basel_get_opt('search_by_sku') ? 'Yes' : 'No'; ?></code></td>
				<td><span class="status ok">✓</span></td>
			</tr>
			<tr>
				<td>Show SKU on AJAX</td>
				<td><code><?php echo basel_get_opt('show_sku_on_ajax') ? 'Yes' : 'No'; ?></code></td>
				<td><span class="status ok">✓</span></td>
			</tr>
		</table>
	</div>

	<div class="section">
		<h2>2. AJAX Handlers Registered</h2>
		<table>
			<tr>
				<th>Action</th>
				<th>Status</th>
			</tr>
			<tr>
				<td><code>wp_ajax_basel_ajax_search</code></td>
				<td>
					<?php if (has_action('wp_ajax_basel_ajax_search')): ?>
						<span class="status ok">✓ Registered</span>
					<?php else: ?>
						<span class="status error">✗ Not Registered</span>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td><code>wp_ajax_nopriv_basel_ajax_search</code></td>
				<td>
					<?php if (has_action('wp_ajax_nopriv_basel_ajax_search')): ?>
						<span class="status ok">✓ Registered</span>
					<?php else: ?>
						<span class="status error">✗ Not Registered</span>
					<?php endif; ?>
				</td>
			</tr>
		</table>
	</div>

	<div class="section">
		<h2>3. Files Check</h2>
		<table>
			<tr>
				<th>File</th>
				<th>Status</th>
			</tr>
			<tr>
				<td><code>improved-header-search.php</code></td>
				<td>
					<?php if (file_exists(get_stylesheet_directory() . '/includes/improved-header-search.php')): ?>
						<span class="status ok">✓ Exists</span>
					<?php else: ?>
						<span class="status error">✗ Missing</span>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td><code>improved-search.css</code></td>
				<td>
					<?php if (file_exists(get_stylesheet_directory() . '/assets/css/improved-search.css')): ?>
						<span class="status ok">✓ Exists</span>
					<?php else: ?>
						<span class="status error">✗ Missing</span>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td><code>improved-search.js</code></td>
				<td>
					<?php if (file_exists(get_stylesheet_directory() . '/assets/js/improved-search.js')): ?>
						<span class="status ok">✓ Exists</span>
					<?php else: ?>
						<span class="status error">✗ Missing</span>
					<?php endif; ?>
				</td>
			</tr>
		</table>
	</div>

	<div class="section">
		<h2>4. How to Enable AJAX Search</h2>
		<p>If AJAX Search is disabled, follow these steps:</p>
		<ol>
			<li>Go to WordPress Admin → <strong>Theme Settings</strong></li>
			<li>Navigate to <strong>Header → Search</strong></li>
			<li>Enable <strong>"AJAX Search"</strong></li>
			<li>Set <strong>"Number of results"</strong> to <strong>3</strong></li>
			<li>Click <strong>"Save Changes"</strong></li>
		</ol>
	</div>

	<div class="section">
		<h2>5. Test AJAX Endpoint</h2>
		<p>Test the AJAX endpoint directly:</p>
		<div class="code-block">
<?php echo home_url('/wp-admin/admin-ajax.php?action=basel_ajax_search&query=test'); ?>
		</div>
		<p><a href="<?php echo home_url('/wp-admin/admin-ajax.php?action=basel_ajax_search&query=test'); ?>" target="_blank">Click here to test</a></p>
	</div>

	<div class="section">
		<h2>6. Next Steps</h2>
		<?php if (!basel_get_opt('search_ajax')): ?>
			<p style="color: #dc3232; font-weight: bold;">⚠️ AJAX Search is DISABLED in theme settings. Enable it first!</p>
		<?php else: ?>
			<p style="color: #46b450; font-weight: bold;">✓ AJAX Search is enabled. Clear cache and test!</p>
		<?php endif; ?>
	</div>

</body>
</html>

