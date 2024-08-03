<?php
// Check if WordPress is loaded to avoid direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define minimum versions
define('cf7submittrack_MIN_WP_VERSION', '4.7.0');
define('cf7submittrack_MIN_CF7_VERSION', '4.9.5');

// Check if WordPress and Contact Form 7 are loaded and verify versions
if (!function_exists('cf7submittrack_check_compatibility')) {
    function cf7submittrack_check_compatibility() {
        global $wp_version;

        // Check WordPress version
        if (version_compare($wp_version, cf7submittrack_MIN_WP_VERSION, '<')) {
            add_action('admin_notices', 'cf7submittrack_wp_version_notice');
            return;
        }

        // Check if Contact Form 7 is active and verify version
        if (defined('WPCF7_VERSION') && version_compare(WPCF7_VERSION, cf7submittrack_MIN_CF7_VERSION, '<')) {
            add_action('admin_notices', 'cf7submittrack_cf7_version_notice');
            return;
        }

        // Additional checks can be added here
    }

    // Run compatibility checks on plugins_loaded action
    add_action('plugins_loaded', 'cf7submittrack_check_compatibility');
}

// Display a notice if WordPress version is incompatible
if (!function_exists('cf7submittrack_wp_version_notice')) {
    function cf7submittrack_wp_version_notice() {
        ?>
        <div class="notice notice-error">
            <p><?php echo esc_html(sprintf(
                /* translators: %s: Minimum required WordPress version */
                __('WP Form Insights Tracker requires WordPress version %s or higher. Please update your WordPress installation.', 'cf7-fit-insights-tracker'),
                esc_html(cf7submittrack_MIN_WP_VERSION)
            )); ?></p>
        </div>
        <?php
    }
}

// Display a notice if Contact Form 7 version is incompatible
if (!function_exists('cf7submittrack_cf7_version_notice')) {
    function cf7submittrack_cf7_version_notice() {
        ?>
        <div class="notice notice-error">
            <p><?php echo esc_html(sprintf(
                /* translators: %s: Minimum required Contact Form 7 version */
                __('WP Form Insights Tracker requires Contact Form 7 version %s or higher. Please update your Contact Form 7 installation.', 'cf7-fit-insights-tracker'),
                esc_html(cf7submittrack_MIN_CF7_VERSION)
            )); ?></p>
        </div>
        <?php
    }
}
?>
