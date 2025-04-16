<?php
function handlePropertyLocation($post_id, $property_data) {
    // Remove all existing 'location' taxonomy terms
    wp_set_post_terms($post_id, array(), 'location');

    // Check if 'Address' and 'Level2' exist and contain the 'Name'
    if (!empty($property_data['Address']['Town'])) {
        // Get the 'Level2' address name
        $address_name = $property_data['Address']['Town'];
        // die();
        // Set the 'location' taxonomy term using 'Level2' address name
        wp_set_object_terms($post_id, $address_name, 'location');
    }
}
