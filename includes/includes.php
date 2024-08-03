<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!is_admin()) {
    return; // Ensure this file only runs in the admin area
}

/*
 * Define plugin path constant if not already defined.
 */
if (!defined('CF7SUBMITTRACK_PATH')) {
    define('CF7SUBMITTRACK_PATH', plugin_dir_path(__FILE__));
}

/*
 * Include core functionality files.
 */
include_once CF7SUBMITTRACK_PATH . 'includes/compatibility-checks.php';
include_once CF7SUBMITTRACK_PATH . 'includes/consent-notify.php';
include_once CF7SUBMITTRACK_PATH . 'includes/languages-util.php';
include_once CF7SUBMITTRACK_PATH . 'includes/form-insights-functions.php';

/*
 * Include admin functionality files.
 */
include_once CF7SUBMITTRACK_PATH . 'includes/admin/form-insights-admin.php';

?>
