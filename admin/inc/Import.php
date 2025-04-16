<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
include_once(plugin_dir_path(__FILE__) . 'importer/handle-file-upload.php');
include_once(plugin_dir_path(__FILE__) . 'importer/handle-properties-import.php');
include_once(plugin_dir_path(__FILE__) . 'importer/update_property_meta.php');
