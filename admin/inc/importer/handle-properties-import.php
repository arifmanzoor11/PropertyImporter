<?php
add_action('wp_ajax_nopriv_properties_import', 'handle_properties_import_ajax');
add_action('wp_ajax_properties_import', 'handle_properties_import_ajax');

// Handle single property entry via AJAX
function handle_properties_import_ajax() {
    // Check user permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'You do not have permission to manage options.'));
        return;
    }

    // Ensure necessary data is provided
    if (isset($_POST['import_id']) && isset($_POST['property_data'])) {
        $import_id = intval($_POST['import_id']);
        $property_data = $_POST['property_data'];

        // Validate and sanitize the property data
        $property_id = isset($property_data['ID']) ? intval($property_data['ID']) : '';
        $title = isset($property_data['Address']['DisplayAddress']) ? sanitize_text_field($property_data['Address']['DisplayAddress']) : 'No Title';

        // Output the current property title being processed
        error_log('Processing Property: ' . $title);

        // Check if a post with the same ID exists
        $existing_post_id = get_posts(array(
            'post_type'      => 'property',
            'meta_key'       => 'ID',
            'meta_value'     => $property_id,
            'fields'         => 'ids',
            'posts_per_page' => 1
        ));

        // Initialize the counters
        $imported_count = 0;
        $updated_count = 0;

        if ($existing_post_id) {
            // Update existing post
            $post_id = wp_update_post(array(
                'ID'           => $existing_post_id[0],
                'post_title'   => $title,
                'post_content' => isset($property_data['Description']) 
                ? nl2br( $property_data['Description']) 
                : 'No Description',
                // 'post_content' => isset($property_data['Description']) ? sanitize_text_field($property_data['Description']) : 'No Description',
                'post_status'  => 'publish'
            ));

            if (!is_wp_error($post_id)) {
                $updated_count++;
                $message = $title . ' has been updated.';
            } else {
                error_log('Post update error: ' . $post_id->get_error_message());
                wp_send_json_error(array('message' => 'Failed to update the post.'));
                return;
            }
        } else {
            // Insert new post
            $post_id = wp_insert_post(array(
                'post_type'    => 'property',
                'post_title'   => $title,
                'post_content' => isset($property_data['Description']) 
                ? nl2br( $property_data['Description']) 
                : 'No Description',
                'post_status'  => 'publish'
            ));

            if (!is_wp_error($post_id)) {
                $imported_count++;
                $message = $title . ' has been uploaded.';
            } else {
                error_log('Post insert error: ' . $post_id->get_error_message());
                wp_send_json_error(array('message' => 'Failed to insert the post.'));
                return;
            }
        }

        // Update meta fields for the property
        update_property_meta_fields($post_id, $property_data);

        // Log the counts for debugging
        error_log('Imported Count: ' . $imported_count);
        error_log('Updated Count: ' . $updated_count);

        // Respond with success message and counts
        wp_send_json_success(array(
            'message' => $message,
            'imported_count' => $imported_count,
            'updated_count' => $updated_count
        ));
    } else {
        wp_send_json_error(array('message' => 'No property data or import ID provided.'));
    }
}
add_action('wp_ajax_log_import_meta', 'log_import_meta');

function log_import_meta() {
    global $wpdb;

    // Start output buffering
    ob_start();

    // Validate and sanitize inputs
    $import_id = isset($_POST['import_id']) ? intval($_POST['import_id']) : 0;
    $imported_count = isset($_POST['imported_count']) ? intval($_POST['imported_count']) : 0;
    $updated_count = isset($_POST['updated_count']) ? intval($_POST['updated_count']) : 0;
    $duration = isset($_POST['duration']) ? wp_strip_all_tags($_POST['duration']) : '';


    // Ensure the table exists
    $table_name = $wpdb->prefix . 'import_meta';

    // Log the incoming data for debugging
    error_log("Logging import metadata: Import ID: $import_id, Imported Count: $imported_count, Updated Count: $updated_count");

    // Prepare the data to insert
    $meta_data = array(
        array(
            'import_id'  => $import_id,
            'meta_key'   => 'imported_count',
            'meta_value' => $imported_count,
        ),
        array(
            'import_id'  => $import_id,
            'meta_key'   => 'updated_count',
            'meta_value' => $updated_count,
        ),
        array(
            'import_id'  => $import_id,
            'meta_key'   => 'file_name',
            'meta_value' => 'Single Property Entry',
        ),
        array(
            'import_id'  => $import_id,
            'meta_key'   => 'duration',
            'meta_value' => $duration,
        ),
    );

    // Insert each row into the database
    foreach ($meta_data as $row) {
        error_log("Inserting data: " . print_r($row, true)); // Log data to insert
        $inserted = $wpdb->insert($table_name, $row);
        
        // Check if the insertion was successful
        if ($inserted === false) {
            error_log("Failed to insert data: " . print_r($row, true));
            error_log("Insert failed: " . $wpdb->last_error); // Log the last error
            wp_send_json_error(array('message' => 'Failed to log import metadata.'));
            return;
        } else {
            error_log("Inserted data successfully: " . print_r($row, true));
        }
    }

    // Prepare and send a success response
    $response = array('message' => 'Metadata logged successfully.');

    error_log("Response being sent: " . json_encode($response['message']));
    wp_send_json_success($response);
}
