<?php
// Prevent direct access to the file
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
include_once(plugin_dir_path(__FILE__) . 'settings/admin-menu-registration.php');
include_once(plugin_dir_path(__FILE__) . 'settings/callbacks-sections.php');
include_once(plugin_dir_path(__FILE__) . 'settings/registering-settings.php');
include_once(plugin_dir_path(__FILE__) . 'settings/rendering-settings-page.php');
include_once(plugin_dir_path(__FILE__) . 'settings/callbacks-fields.php');
