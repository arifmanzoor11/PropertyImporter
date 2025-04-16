<?php
function render_owners_data($post_id) {
    // Fetch the 'Owners' meta field
    $owners_data = get_post_meta($post_id, 'Owners', true);

    if (empty($owners_data)) {
        return; // Exit if no owners data is available
    }

    $owners_data = maybe_unserialize($owners_data);

    if (!is_array($owners_data)) {
        return; // Exit if the owners data is not an array
    }

    echo '<div class="property-owners-data">';
    echo '<h3>Owners Information</h3>';

    // Check if the data is an array and contains elements
    if (isset($owners_data[0]) && is_array($owners_data[0])) {
        $owner = $owners_data[0]; // Assuming there's only one owner in the array

        // Display Registered Name
        if (isset($owner['RegisteredName']) && !empty($owner['RegisteredName'])) {
            echo '<p><strong>Registered Name:</strong> ' . esc_html($owner['RegisteredName']) . '</p>';
        }

        // Display Logo (if exists)
        if (isset($owner['Logo']) && !empty($owner['Logo'])) {
            // Assuming 'Logo' contains a URL or image path
            echo '<p><strong>Logo:</strong> <img src="' . esc_url($owner['Logo']) . '" alt="Owner Logo" style="max-width: 200px; height: auto;"></p>';
        } else {
            echo '<p><strong>Logo:</strong> No logo available</p>';
        }

        // Display Contact ID
        if (isset($owner['ContactID']) && !empty($owner['ContactID'])) {
            echo '<p><strong>Contact ID:</strong> ' . esc_html($owner['ContactID']) . '</p>';
        }
    } else {
        echo '<p>No owner data available.</p>';
    }

    echo '</div>';
}
