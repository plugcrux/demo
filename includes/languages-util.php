<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/*
 * Function to load plugin text domain for translation.
 */
if (!function_exists('cf7submittrack_load_textdomain')) {
    function cf7submittrack_load_textdomain() {
        load_plugin_textdomain('cf7-fit-insights-tracker', false, dirname(plugin_basename(__FILE__)) . '/../languages');
    }
    add_action('plugins_loaded', 'cf7submittrack_load_textdomain');
}

?>
