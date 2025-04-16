<?php
function my_plugin_activate() {
    global $wpdb;
    global $propertyImport;
    $propertyImport = '1.0';    

    // Table names with the WordPress prefix
    $table_name_manage_import = $wpdb->prefix . 'manage_import';
    $table_name_import_meta = $wpdb->prefix . 'import_meta';
    
    // Charset and collation setup
    $charset_collate = $wpdb->get_charset_collate();

    // SQL to create manage_import table
    $sql_manage_import = "CREATE TABLE $table_name_manage_import (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        name tinytext NOT NULL,
        url varchar(255) DEFAULT '' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    // SQL to create import_meta table
    $sql_import_meta = "CREATE TABLE $table_name_import_meta (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        import_id mediumint(9) NOT NULL,
        meta_key varchar(255) NOT NULL,
        meta_value longtext NOT NULL,
        PRIMARY KEY  (id),
        KEY import_id (import_id),
        FOREIGN KEY (import_id) REFERENCES $table_name_manage_import(id) ON DELETE CASCADE
    ) $charset_collate;";

    // Load the upgrade functions and execute SQL
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql_manage_import);
    dbDelta($sql_import_meta);

    // Store the version of the plugin in the options table
    add_option('propertyImport', $propertyImport);
}
register_activation_hook(__FILE__, 'my_plugin_activate');

function my_plugin_deactivate() {
    // global $wpdb;

    // // Table names with the WordPress prefix
    // $table_name_manage_import = $wpdb->prefix . 'manage_import';
    // $table_name_import_meta = $wpdb->prefix . 'import_meta';

    // // SQL to drop the tables
    // $wpdb->query("DROP TABLE IF EXISTS $table_name_import_meta");
    // $wpdb->query("DROP TABLE IF EXISTS $table_name_manage_import");

    // // Optionally, remove the version option
    // delete_option('propertyImport');
}
register_deactivation_hook(__FILE__, 'my_plugin_deactivate');
