<?php 
// Define the custom intervals based on database value
function my_custom_cron_intervals($schedules) {
    // Get the interval from database
    
    // Create an array of allowed intervals
    $allowed_intervals = array(
        '3600' => 'Hourly',
        '7200' => 'Two Hours',
        '14400' => 'Four Hours',
        '21600' => 'Six Hours',
        '43200' => 'Twice Daily',
        '86400' => 'Daily',
        '604800' => 'Weekly'
    );
    
    $import_interval = get_option('auto_import_interval', '14400'); // Default to 4 hours if not set
    $import_interval = in_array($import_interval, array_keys($allowed_intervals)) ? $import_interval : '14400';

    // Add the custom interval from database
    $schedules['custom_interval'] = array(
        'interval' => intval($import_interval),
        'display' => __($allowed_intervals[$import_interval] ?? 'Custom Interval')
    );

    return $schedules;
}
add_filter('cron_schedules', 'my_custom_cron_intervals');



// Function to be called by the cron job
function my_cron_job_function() {
    error_log('Cron executed at: ' . current_time('mysql'));
    download_and_import_properties_file();
}
add_action('my_cron_hook', 'my_cron_job_function');



// Schedule an event if it's not already scheduled
function my_cron_schedule_activation() {
    // First, clear any existing schedules
    $timestamp = wp_next_scheduled('my_cron_hook');
    if ($timestamp) {
        wp_unschedule_event($timestamp, 'my_cron_hook');
    }
    
    // Check if custom_interval exists
    $schedules = apply_filters('cron_schedules', []);
    if (!isset($schedules['custom_interval'])) {
        error_log('Custom interval NOT registered!');
    } else {
        error_log('Custom interval is set to: ' . $schedules['custom_interval']['interval']);
    }
    
    // Only schedule if not already scheduled
    if (!wp_next_scheduled('my_cron_hook')) {
        wp_schedule_event(time(), 'custom_interval', 'my_cron_hook');
    }
}
register_activation_hook(__FILE__, 'my_cron_schedule_activation');

// Clear scheduled event upon plugin deactivation
function my_cron_schedule_deactivation() {
    $timestamp = wp_next_scheduled('my_cron_hook');
    wp_unschedule_event($timestamp, 'my_cron_hook');
}
register_deactivation_hook(__FILE__, 'my_cron_schedule_deactivation');

// Function to update cron schedule when interval is changed
function update_cron_schedule() {
    // This will force the schedule to be recreated with the new interval
    my_cron_schedule_deactivation();
    my_cron_schedule_activation();
}
add_action('update_option_auto_import_interval', 'update_cron_schedule');
