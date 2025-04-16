<?php
function render_document_media($post_id) {
    // Get the DocumentMedia meta field
    $document_media = get_post_meta($post_id, 'DocumentMedia', true);

    if (!empty($document_media) && is_array($document_media)) {
        echo '<div class="property-document-media">';
        echo '<h3>Document Media</h3>';
        echo '<div>';

        // Loop through each attachment ID
        foreach ($document_media as $attachment_id) {
            // Get the attachment URL
            $attachment_url = wp_get_attachment_url($attachment_id);
            // Get the attachment title
            $attachment_title = get_the_title($attachment_id);

            if ($attachment_url) {
                // Display the media item
                echo '<a class="button" href="' . esc_url($attachment_url) . '" target="_blank">Download File ' . esc_html($attachment_title) . '</a>';
            }
        }

        echo '</div>';
        echo '</div>';
    }
}
