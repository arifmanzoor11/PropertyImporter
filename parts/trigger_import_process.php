<?php 
function trigger_import_process($file_path, $wpdb) {
    $file_url = wp_upload_dir()['baseurl'] . '/' . basename($file_path);
    $import_id = $wpdb->insert_id;

    $import_result = properties_import($file_url, $import_id);

    // Now pass the result to insert_metadata
    insert_metadata($import_id, $wpdb, $import_result);

    $response = array(
        'file_url'       => $file_url,
        'import_id'      => $import_id,
        'message'        => 'Property feed downloaded and import process completed.',
        'imported_count' => $import_result['imported_count'],
        'updated_count'  => $import_result['updated_count'],
        'errors'         => $import_result['errors'],
    );

    wp_send_json_success($response);
    return $response;
}