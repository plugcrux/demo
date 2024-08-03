<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!is_admin()) {
    return; // Ensure this file only runs in the admin area
}

/**
 * Retrieves a list of contact forms created using Contact Form 7.
 *
 * @return array Array of contact forms, each containing 'id' and 'title'.
 */
if (!function_exists('cf7submittrack_get_contact_forms')) {
    function cf7submittrack_get_contact_forms() {
        $contact_forms = [];

        if (class_exists('WPCF7')) {
            $args = array(
                'post_type' => 'wpcf7_contact_form',
                'order' => 'ASC',
                'posts_per_page' => -1,
            );

            $forms_query = new WP_Query($args);

            if ($forms_query->have_posts()) {
                while ($forms_query->have_posts()) {
                    $forms_query->the_post();
                    $contact_forms[] = array(
                        'id' => intval(get_the_ID()), // Validate as integer
                        'title' => sanitize_text_field(get_the_title()), // Sanitize text
                    );
                }
                wp_reset_postdata();
            }
        }

        return $contact_forms;
    }
}

/**
 * Handles actions before sending mail through Contact Form 7.
 *
 * @param object $contact_form Contact form object.
 */
if (!function_exists('cf7submittrack_before_send_mail')) {
    function cf7submittrack_before_send_mail($contact_form) {
        $submission = WPCF7_Submission::get_instance();

        if ($submission) {
            $form_id = intval($contact_form->id()); // Validate as integer

            $count_options = get_option('cf7submittrack_count', array());
            $date_options = get_option('cf7submittrack_date', array());

            if (isset($count_options[$form_id])) {
                $count_options[$form_id] = intval($count_options[$form_id]) + 1; // Validate as integer
            } else {
                $count_options[$form_id] = 1; // Default value
            }
            $date_options[$form_id] = current_time('mysql'); // Current time in MySQL format

            update_option('cf7submittrack_count', $count_options);
            update_option('cf7submittrack_date', $date_options);
        }
    }
    add_action('wpcf7_before_send_mail', 'cf7submittrack_before_send_mail');
}

/**
 * Retrieves the total number of submissions for a given Contact Form 7 form.
 *
 * @param int $form_id ID of the Contact Form 7 form.
 * @return int Total number of submissions.
 */
if (!function_exists('cf7submittrack_get_count')) {
    function cf7submittrack_get_count($form_id) {
        $count_options = get_option('cf7submittrack_count', array());
        return isset($count_options[$form_id]) ? intval($count_options[$form_id]) : 0; // Default value if the option doesn't exist
    }
}

/**
 * Retrieves the date of the last submission for a given Contact Form 7 form.
 *
 * @param int $form_id ID of the Contact Form 7 form.
 * @return string Date of the last submission.
 */
if (!function_exists('cf7submittrack_get_date')) {
    function cf7submittrack_get_date($form_id) {
        $date_options = get_option('cf7submittrack_date', array());
        return isset($date_options[$form_id]) ? esc_html($date_options[$form_id]) : esc_html__('N/A', 'cf7-fit-insights-tracker'); // Default value if the option doesn't exist
    }
}
?>
