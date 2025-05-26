<?php // Function to insert metadata into the database
function insert_metadata($import_id, $wpdb, $import_result = []) {
    $meta_data = array(
        array(
            'import_id' => $import_id,
            'meta_key' => 'import_type',
            'meta_value' => 'Auto Imported',
        ),
        array(
            'import_id' => $import_id,
            'meta_key' => 'import_date',
            'meta_value' => current_time('mysql'),
        ),
        array(
            'import_id' => $import_id,
            'meta_key' => 'imported_count',
            'meta_value' => isset($import_result['imported_count']) ? $import_result['imported_count'] : 0,
        ),
        array(
            'import_id' => $import_id,
            'meta_key' => 'updated_count',
            'meta_value' => isset($import_result['updated_count']) ? $import_result['updated_count'] : 0,
        ),
    );

    foreach ($meta_data as $row) {
        $wpdb->insert($wpdb->prefix . 'import_meta', $row);
    }
}