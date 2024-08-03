<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Exit if uninstall not called from WordPress.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

/**
 * Clean up plugin options when the plugin is uninstalled.
 */

// Check if the user opted to delete plugin data on uninstall.
if (get_option('wpcf7it_delete_data_on_uninstall') === 'yes') {
    delete_option('wpcf7it_count');
    delete_option('wpcf7it_date');
    delete_option('wpcf7it_delete_data_on_uninstall');
}
?>
