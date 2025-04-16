<?php
function render_owners_photos($post_id) {
    // Fetch the 'Owners' meta field
    $owners_meta = get_post_meta($post_id, 'Owners', true);

    if (empty($owners_meta)) {
        return; // Exit if no owners data is available
    }

    $owners_meta = maybe_unserialize($owners_meta);

    if (!is_array($owners_meta)) {
        return; // Exit if the owners data is not an array
    }

    echo '<div class="property-owners-photos">';
    echo '<h3>Owner Photos</h3>';

    // Check if the data is an array and contains elements
    if (isset($owners_meta[0]) && is_array($owners_meta[0])) {
        $owner = $owners_meta[0]; // Assuming there's only one owner in the array

        // Assuming the 'Logo' field contains a comma-separated string of photo IDs
        if (isset($owner['Logo']) && !empty($owner['Logo'])) {
            $photo_ids = explode(',', $owner['Logo']); // Convert the string to an array of photo IDs

            if (is_array($photo_ids)) {
                echo '<div>';

                foreach ($photo_ids as $photo_id) {
                    $photo_url = wp_get_attachment_url(trim($photo_id)); // Get the photo URL
                    if ($photo_url) {
                        echo '<div><img src="' . esc_url($photo_url) . '" alt="Owner Photo" style="max-width: 200px; height: auto;"></div>';
                    }
                }

                echo '</div>';
            }
        } else {
            echo '<p>No photos available.</p>';
        }
    } else {
        echo '<p>No owner data available.</p>';
    }

    echo '</div>';
}
