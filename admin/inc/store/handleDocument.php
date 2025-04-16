<?php
/**
 * Handle document media upload and associate with the post.
 *
 * @param array $property_data Array of document data from the API.
 * @param int $post_id The post ID to attach the documents to.
 * @return void
 */
function handleDocumentMediaUpload($property_data, $post_id) {
    // Get options for document import (true/false for file import or URL saving)
    $enable_document_import = get_option('enable_document_import', false); // true: import as file, false: save as URL

    // Initialize counters for uploads and updates
    $upload_count = 0;
    $update_count = 0;
    $document_urls = array(); // Store document URLs

    // Clear existing 'DocumentMedia' entries to avoid duplication
    delete_post_meta($post_id, 'DocumentMedia');

    foreach ($property_data as $document) {
        // Extract and sanitize the description and titles
        $description = isset($document['Description']) ? trim($document['Description']) : '';
        $urls = isset($document['URLs']) && is_array($document['URLs']) ? $document['URLs'] : array();
        $titles = isset($document['Titles']) && is_array($document['Titles']) ? $document['Titles'] : array();

        // Skip adding the meta if any required field is empty
        if (empty($description) || empty($urls) || empty($titles)) {
            continue;
        }

        $uploaded_attachment_ids = array();

        // Handle the file upload or URL saving depending on the option
        foreach ($urls as $index => $url) {
            if (!empty($url)) {
                $file_name = basename($url);

                if ($enable_document_import) {
                    // Proceed with file import logic if import is enabled (true)
                    // Check if the file already exists in the media library
                    $existing_attachment = get_posts(array(
                        'post_type' => 'attachment',
                        'meta_query' => array(
                            array(
                                'key' => '_wp_attached_file',
                                'value' => $file_name,
                                'compare' => 'LIKE',
                            ),
                        ),
                        'posts_per_page' => 1,
                        'post_status' => 'inherit',
                    ));

                    if (!empty($existing_attachment)) {
                        // If the attachment exists, use its ID and count as an update
                        $uploaded_attachment_ids[] = $existing_attachment[0]->ID;
                        $update_count++;  // Increment the update counter
                    } else {
                        // Proceed with file download and upload
                        $file = array(
                            'name' => $file_name, // Filename from the URL
                            'tmp_name' => download_url($url), // Temporarily download the file
                        );

                        // Check if the file was downloaded successfully
                        if (!is_wp_error($file['tmp_name'])) {
                            // Set the title and description in the attachment post
                            $attachment = array(
                                'post_title' => sanitize_text_field($titles[$index]),
                                'post_content' => sanitize_text_field($description),
                            );

                            // Handle the upload and attach to the post
                            $attachment_id = media_handle_sideload($file, $post_id, null, $attachment);

                            // Store the attachment ID
                            if (!is_wp_error($attachment_id)) {
                                $uploaded_attachment_ids[] = $attachment_id;
                                $upload_count++;  // Increment the upload counter
                            } else {
                                // If upload fails, keep the original URL as a fallback
                                $uploaded_attachment_ids[] = $url;
                            }

                            // Delete the temporary file
                            @unlink($file['tmp_name']);
                        } else {
                            // If download failed, keep the original URL as a fallback
                            $uploaded_attachment_ids[] = $url;
                        }
                    }
                } else {
                    // If document import is disabled (false), save the URL only
                    $document_urls[] = $url; // Save the URL as part of the post meta
                }
            }
        }

        // Store the attachment IDs in the post meta if imported
        if ($enable_document_import && !empty($uploaded_attachment_ids)) {
            update_post_meta($post_id, 'DocumentMedia', $uploaded_attachment_ids);
        }
    }

    // Save serialized document URLs meta if not importing documents as files
    if (!$enable_document_import && !empty($document_urls)) {
        update_post_meta($post_id, 'document_urls_serialized', serialize($document_urls)); // Save serialized document URLs
    }

    // Store the upload and update counts in the post meta if documents were imported
    if ($enable_document_import) {
        update_post_meta($post_id, 'document_upload_count', $upload_count);
        update_post_meta($post_id, 'document_update_count', $update_count);
    }
}