<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!is_admin()) {
    return; // Ensure this file only runs in the admin area
}

/**
 * Add submenu page for Form Insights Tracker.
 */
if (!function_exists('cf7submittrack_add_admin_menu')) {
    function cf7submittrack_add_admin_menu() {
        add_submenu_page(
            'wpcf7',
            __('Reports', 'cf7-fit-insights-tracker'),
            __('Reports', 'cf7-fit-insights-tracker'),
            'manage_options',
            'form-insights-tracker',
            'cf7submittrack_render_admin_menu'
        );
    }
    add_action('admin_menu', 'cf7submittrack_add_admin_menu');
}

/**
 * Render the admin menu for Form Insights Tracker.
 */
if (!function_exists('cf7submittrack_render_admin_menu')) {
    function cf7submittrack_render_admin_menu() {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'cf7-fit-insights-tracker'));
        }

        if (!class_exists('WPCF7_ContactForm')) {
            echo '<div class="wrap"><h1>' . esc_html__('Form Insights Tracker', 'cf7-fit-insights-tracker') . '</h1>';
            echo '<p>' . esc_html__('Contact Form 7 is not installed or activated. Please install and activate Contact Form 7 to use this plugin.', 'cf7-fit-insights-tracker') . '</p></div>';
            return;
        }

        $active_tab = 'analytics'; // Default tab
        if (isset($_GET['tab']) && isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'cf7submittrack_tab_nonce')) {
            $active_tab = sanitize_text_field($_GET['tab']);
        }

        $nonce = wp_create_nonce('cf7submittrack_tab_nonce');
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Form Insights Tracker', 'cf7-fit-insights-tracker'); ?></h1>
            <h2 class="nav-tab-wrapper">
                <a href="?page=form-insights-tracker&tab=analytics&_wpnonce=<?php echo esc_attr($nonce); ?>" class="nav-tab <?php echo $active_tab == 'analytics' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('Analytics', 'cf7-fit-insights-tracker'); ?>
                </a>
                <a href="?page=form-insights-tracker&tab=settings&_wpnonce=<?php echo esc_attr($nonce); ?>" class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('Settings', 'cf7-fit-insights-tracker'); ?>
                </a>
            </h2>
            <br>
            <div class="tab-content">
                <?php
                if ($active_tab == 'analytics') {
                    cf7submittrack_render_analytics_tab();
                } elseif ($active_tab == 'settings') {
                    cf7submittrack_render_settings_tab();
                }
                ?>
            </div>
        </div>
        <?php
    }
}

/**
 * Render the Analytics tab content.
 */
if (!function_exists('cf7submittrack_render_analytics_tab')) {
    function cf7submittrack_render_analytics_tab() {
        $forms = cf7submittrack_get_contact_forms();

        if (empty($forms)) {
            echo '<p>' . esc_html__('No Contact Form 7 forms found.', 'cf7-fit-insights-tracker') . '</p>';
            return;
        }
        ?>
        <table class="wp-list-table widefat striped">
            <thead>
                <tr>
                    <th><?php esc_html_e('S.No', 'cf7-fit-insights-tracker'); ?></th>
                    <th><?php esc_html_e('Form Name', 'cf7-fit-insights-tracker'); ?></th>
                    <th><?php esc_html_e('Total Submissions', 'cf7-fit-insights-tracker'); ?></th>
                    <th><?php esc_html_e('Last Record Added At', 'cf7-fit-insights-tracker'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 0;
                foreach ($forms as $form) {
                    $count++;
                    $form_id = intval($form['id']); // Validate as integer
                    $form_name = sanitize_text_field($form['title']); // Sanitize text
                    $total_records = cf7submittrack_get_count($form_id); // Get validated count
                    $last_record_added = cf7submittrack_get_date($form_id); // Get sanitized date
                    ?>
                    <tr>
                        <td><?php echo esc_html($count); ?></td>
                        <td><?php echo esc_html($form_name); ?></td>
                        <td><?php echo esc_html($total_records); ?></td>
                        <td><?php echo esc_html($last_record_added); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <?php
    }
}

/**
 * Render the Settings tab content.
 */
if (!function_exists('cf7submittrack_render_settings_tab')) {
    function cf7submittrack_render_settings_tab() {
        if (isset($_POST['cf7submittrack_save_settings'])) {
            if (!isset($_POST['cf7submittrack_save_settings_nonce']) || !wp_verify_nonce($_POST['cf7submittrack_save_settings_nonce'], 'cf7submittrack_save_settings_verify')) {
                wp_die(esc_html__('Nonce verification failed.', 'cf7-fit-insights-tracker'));
            }
            $delete_data_on_uninstall = isset($_POST['cf7submittrack_delete_data_on_uninstall']) ? 'yes' : 'no';
            update_option('cf7submittrack_delete_data_on_uninstall', sanitize_text_field($delete_data_on_uninstall));
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Settings saved.', 'cf7-fit-insights-tracker') . '</p></div>';
        }

        $delete_data_on_uninstall = get_option('cf7submittrack_delete_data_on_uninstall', 'no');
        ?>
        <form method="post" action="">
            <?php wp_nonce_field('cf7submittrack_save_settings_verify', 'cf7submittrack_save_settings_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e('Delete Data on Uninstall', 'cf7-fit-insights-tracker'); ?></th>
                    <td>
                        <input type="checkbox" name="cf7submittrack_delete_data_on_uninstall" value="yes" <?php checked($delete_data_on_uninstall, 'yes'); ?> />
                        <label for="cf7submittrack_delete_data_on_uninstall"><?php esc_html_e('Yes, delete all plugin data upon uninstallation', 'cf7-fit-insights-tracker'); ?></label>
                    </td>
                </tr>
            </table>
            <?php submit_button(__('Save Settings', 'cf7-fit-insights-tracker'), 'primary', 'cf7submittrack_save_settings'); ?>
        </form>
        <?php
    }
}
?>
