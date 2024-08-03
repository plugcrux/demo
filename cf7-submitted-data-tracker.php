<?php
/*
 * Plugin Name: Form Insights Tracker
 * Description: Gain in-depth insights into your Contact Form 7 submissions. Track overall submission counts and the most recent submission time to enhance form performance.
 * Version: 1.0.0
 * Author: Plugcrux
 * License: GPLv2
 * Text Domain: cf7-fit-insights-tracker
 * Domain Path: /languages/
 */

if (!defined('ABSPATH')) {
    exit;
}

/*
 * Ensure this file only runs in the admin area.
 */
if (!is_admin()) {
    return;
}

/*
 * Define plugin path constant.
 */
define('CF7SUBMITTRACK_PATH', plugin_dir_path(__FILE__));

/*
 * Include core functionality files.
 */
include_once CF7SUBMITTRACK_PATH . 'includes/includes.php';

/*
 * Register plugin activation hook.
 */
if (!function_exists('cf7submittrack_activation_hook')) {
    function cf7submittrack_activation_hook() {
        update_option('cf7submittrack_count', array());
        update_option('cf7submittrack_date', array());
    }
    register_activation_hook(__FILE__, 'cf7submittrack_activation_hook');
}

?>
