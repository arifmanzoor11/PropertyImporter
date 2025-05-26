<?php 

function properties_import($file_url, $import_id) {
    if (!current_user_can('manage_options')) {
        error_log("[IMPORT ERROR] Permission denied for user.");
        wp_send_json_error(['message' => 'You do not have permission to manage options.']);
        return;
    }

    error_log("[IMPORT START] Starting property import. Import ID: $import_id");

    $imported_count = 0;
    $updated_count = 0;
    $errors = [];

    if (is_string($file_url)) {
        // Check if it looks like a URL
        if (filter_var($file_url, FILTER_VALIDATE_URL)) {
            $response = wp_remote_get($file_url);
            if (is_wp_error($response)) {
                $error_msg = $response->get_error_message();
                error_log("[IMPORT ERROR] Failed to fetch URL: $error_msg");
                wp_send_json_error(['message' => "Failed to fetch URL: $error_msg"]);
                return;
            }
    
            $body = wp_remote_retrieve_body($response);
            $decoded = json_decode($body, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $error_msg = json_last_error_msg();
                error_log("[IMPORT ERROR] JSON decode failed from URL: $error_msg");
                wp_send_json_error(['message' => "JSON Decode Error: $error_msg"]);
                return;
            }
    
            $properties = $decoded;
        } else {
            // Assume it's raw JSON string
            $decoded = json_decode($file_url, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $error_msg = json_last_error_msg();
                error_log("[IMPORT ERROR] JSON decode failed from string: $error_msg");
                wp_send_json_error(['message' => "JSON Decode Error: $error_msg"]);
                return;
            }
    
            $properties = $decoded;
        }
    } else {
        $properties = $file_url; // Already decoded array
    }
    
    if (empty($properties) || !is_array($properties)) {
        error_log("[IMPORT ERROR] No valid property data found.");
        wp_send_json_error(['message' => 'No valid property data found.']);
        return;
    }

    foreach ($properties as $property_data) {
        $property_id = isset($property_data['ID']) ? intval($property_data['ID']) : 0;
        $title = isset($property_data['Address']['DisplayAddress']) ? sanitize_text_field($property_data['Address']['DisplayAddress']) : 'No Title';
        $description = isset($property_data['Description']) ? wp_kses_post($property_data['Description']) : 'No Description';

        error_log("[IMPORT] Processing Property ID: $property_id | Title: $title");

        if (!$property_id) {
            $msg = "Skipped property with missing ID.";
            error_log("[IMPORT WARNING] $msg");
            $errors[] = $msg;
            continue;
        }

        $existing_post = get_posts([
            'post_type'      => 'property',
            'meta_key'       => 'ID',
            'meta_value'     => $property_id,
            'fields'         => 'ids',
            'posts_per_page' => 1
        ]);

        if ($existing_post) {
            $post_id = wp_update_post([
                'ID'           => $existing_post[0],
                'post_title'   => $title,
                'post_content' => nl2br($description),
                'post_status'  => 'publish',
            ], true);

            if (is_wp_error($post_id)) {
                $msg = "Update failed for Property ID $property_id: " . $post_id->get_error_message();
                error_log("[IMPORT ERROR] $msg");
                $errors[] = $msg;
                continue;
            }

            error_log("[IMPORT] Updated Property ID $property_id (Post ID $post_id)");
            $updated_count++;
        } else {
            $post_id = wp_insert_post([
                'post_type'    => 'property',
                'post_title'   => $title,
                'post_content' => nl2br($description),
                'post_status'  => 'publish',
            ], true);

            if (is_wp_error($post_id)) {
                $msg = "Insert failed for Property ID $property_id: " . $post_id->get_error_message();
                error_log("[IMPORT ERROR] $msg");
                $errors[] = $msg;
                continue;
            }

            error_log("[IMPORT] Inserted Property ID $property_id (Post ID $post_id)");
            $imported_count++;
        }

        update_post_meta($post_id, 'ID', $property_id);

        if (function_exists('update_property_meta_fields')) {
            update_property_meta_fields($post_id, $property_data);
            error_log("[IMPORT] Meta fields updated for Post ID $post_id");
        } else {
            error_log("[IMPORT WARNING] update_property_meta_fields() function not found.");
        }
    }

    error_log("[IMPORT END] Imported: $imported_count | Updated: $updated_count | Errors: " . count($errors));
    
   $response = array(
    'message'        => "Import completed.",
    'imported_count' => $imported_count,
    'updated_count'  => $updated_count,
    'errors'         => $errors
    );

    error_log("[IMPORT RESULT] " . print_r($response, true));

    return $response; // Don't send JSON here — handle that at the top level

    
}
