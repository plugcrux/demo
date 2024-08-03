<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!is_admin()) {
    return; // Ensure this file only runs in the admin area
}

/*
 * Admin notice for user information.
 */
if (!function_exists('cf7submittrack_admin_notice')) {
    function cf7submittrack_admin_notice() {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php echo esc_html(__('Thank you for using WP Contact Form 7 - Insights Tracker. No data is transmitted to third-party servers without your consent.', 'cf7-fit-insights-tracker')); ?></p>
        </div>
        <?php
    }
    add_action('admin_notices', 'cf7submittrack_admin_notice');
}
?>
