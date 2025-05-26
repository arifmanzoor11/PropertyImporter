<?php // Function to process the API response
function process_response($response, $wpdb) {
    $body = wp_remote_retrieve_body($response);

    // Save the response to a file
    $upload_dir = wp_upload_dir();
    $file_name = 'property_feed_' . date('Ymd_His') . '.json';
    $file_path = $upload_dir['basedir'] . '/' . $file_name;
    file_put_contents($file_path, $body);

    // Insert download information into the database
    $table_name = $wpdb->prefix . 'manage_import';
    $data = array(
        'name' => $file_name,
        'url' => $upload_dir['baseurl'] . '/' . $file_name,
    );
    $wpdb->insert($table_name, $data);
    return $file_path;
}