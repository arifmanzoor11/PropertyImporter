<?php
function handlePropertyTypes($post_id, $property_data) {
    // Remove all existing 'property_type' taxonomy terms
    wp_set_post_terms($post_id, array(), 'property_type');

    // Check if 'PropertyTypes' exists and is an array
    if (isset($property_data['PropertyTypes']) && is_array($property_data['PropertyTypes'])) {
        $property_types = array();

        // Loop through each property type and collect the names directly
        foreach ($property_data['PropertyTypes'] as $type) {
            if (!empty($type)) {
                $property_types[] = $type['Name'];
            }
        }

        // Set the new 'property_type' taxonomy terms for the post
        if (!empty($property_types)) {
            wp_set_object_terms($post_id, $property_types, 'property_type');
        }
    }
}
