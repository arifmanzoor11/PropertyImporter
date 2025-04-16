<?php
function handlePropertyCategories($property_data, $post_id) {
    wp_set_post_terms($post_id, array(), 'categories');

    // Check if 'PropertyTypes' exists and is an array
    if (isset($property_data['Categories']) && is_array($property_data['Categories'])) {
        $property_types = array();

        // Loop through each property type and collect the names directly
        foreach ($property_data['Categories'] as $type) {
            if (!empty($type)) {
                $property_types[] = $type['Name'];
            }
        }

        // Set the new 'property_type' taxonomy terms for the post
        if (!empty($categories)) {
            wp_set_object_terms($post_id, $categories, 'categories');
        }
    }
}
