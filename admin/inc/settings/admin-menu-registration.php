<?php
// Hook into the admin menu to add the settings page
add_action('admin_menu', 'register_custom_settings_page');

function register_custom_settings_page() {
    add_submenu_page(
        'edit.php?post_type=property', // Parent slug
        'Property Settings', // Page title
        'Settings', // Menu title
        'edit_posts', // Capability (edit_posts is a lower capability that Editors and above have)
        'property-settings', // Menu slug
        'render_custom_settings_page' // Callback function
    );
    // add submenu called documtation 
    add_submenu_page(
        'edit.php?post_type=property', // Parent slug
        'Documentation', // Page title
        'Documentation', // Menu title
        'edit_posts', // Capability (edit_posts is a lower capability that Editors and above have)
        'property-documentation', // Menu slug
        'render_custom_documentation_page' // Callback function
    );
}


function render_custom_documentation_page() {
    // Check if the user has the required capability
    if (!current_user_can('edit_posts')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    // Include the settings page content
    include_once(plugin_dir_path(__FILE__) . 'documentation-page.php');
}