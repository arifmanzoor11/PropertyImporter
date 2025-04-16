<?php
/**
 * Import photos from the provided data and attach them to a post or save the URLs.
 *
 * @param array $photos_data Array of photo data from the API.
 * @param int $post_id The post ID to attach the photos to or save the URLs.
 * @return array List of attachment IDs or URLs.
 */
function import_photos($photos_data, $post_id) {
    global $wpdb;

    // Get option for image import (true or false)
    $enable_image_import = get_option('enable_image_import', false); // Import Images option

    $upload_count = 0;
    $update_count = 0;
    $photos = array();
    $photo_urls = array(); // Store photo URLs

    $is_first_photo = true; // Flag to check if the photo is the first one (featured image)

    foreach ($photos_data as $photo) {
        // Check if 'Additional' key exists in the photo data
        $photo_key = get_option('auto_import_url_type', 'URL');
      
        if (isset($photo[$photo_key])) {
            $photo_url = $photo[$photo_key];
            $photo_name = basename($photo_url);

            if ($enable_image_import) {
                // Check for existing attachment
                $existing_attachment = get_posts(array(
                    'post_type' => 'attachment',
                    'meta_query' => array(
                        array(
                            'key' => '_wp_attached_file',
                            'value' => $photo_name,
                            'compare' => 'LIKE'
                        )
                    ),
                    'posts_per_page' => 1
                ));

                if (!empty($existing_attachment)) {
                    // Attachment already exists
                    $photos[] = $existing_attachment[0]->ID;
                    $update_count++;
                    continue;
                }

                // Download the image
                $photo_data = wp_remote_get($photo_url);
                if (is_wp_error($photo_data)) {
                    continue; // Skip on error
                }

                $photo_body = wp_remote_retrieve_body($photo_data);
                $photo_type = wp_remote_retrieve_header($photo_data, 'content-type');
                
                // Ensure the response is an image
                if (strpos($photo_type, 'image') === false) {
                    continue; // Skip if not an image
                }

                // Save the image to the uploads directory
                $upload_dir = wp_upload_dir();
                $photo_path = $upload_dir['path'] . '/' . $photo_name;
                if (file_put_contents($photo_path, $photo_body) === false) {
                    continue; // Skip if file write fails
                }

                // Prepare attachment
                $attachment = array(
                    'post_mime_type' => $photo_type,
                    'post_title'     => sanitize_file_name($photo_name),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );

                // Insert attachment
                $attach_id = wp_insert_attachment($attachment, $photo_path, $post_id);

                // Generate attachment metadata
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $attach_data = wp_generate_attachment_metadata($attach_id, $photo_path);
                wp_update_attachment_metadata($attach_id, $attach_data);

                $photos[] = $attach_id;
                $upload_count++;

                // Set the first photo as a custom meta 'first_image'
                if ($is_first_photo) {
                    update_post_meta($post_id, 'first_image', $attach_id); // Save first image
                    $is_first_photo = false;
                } else {
                    // Add remaining images to URLs array for serialization
                    $photo_urls[] = wp_get_attachment_url($attach_id); 
                }
            } else {
                // If import is disabled, store the URL instead
                if ($is_first_photo) {
                    update_post_meta($post_id, 'first_image', $photo_url); // Save the first URL as first image
                    $is_first_photo = false;
                } else {
                    $photo_urls[] = $photo_url; // Add URL to the array for remaining photos
                }
            }
        }
    }

    // Save remaining photo URLs in serialized meta 'photo_urls_serialized'
    if (!empty($photo_urls)) {
        update_post_meta($post_id, 'photo_urls_serialized', serialize($photo_urls)); // Save remaining images as serialized
    }

    // Save upload and update counts if images were imported
    if ($enable_image_import) {
        update_post_meta($post_id, 'photo_upload_count', $upload_count);
        update_post_meta($post_id, 'photo_update_count', $update_count);
    }

    return $photos;
}
