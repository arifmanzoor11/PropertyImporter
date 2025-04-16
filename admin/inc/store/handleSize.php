<?php

function handleSizeData($post_id, $property_data) {
    // Clear existing 'Size' meta field to avoid duplication
    delete_post_meta($post_id, 'Size');

    // Build dimension name
    $dimension_name = !empty($property_data['Size']['Dimension']['Name']) ? $property_data['Size']['Dimension']['Name'] : '';

    // Build min/max size
    $min_size = !empty($property_data['Size']['MinSize']) ? $property_data['Size']['MinSize'] : 0.0;
    $max_size = !empty($property_data['Size']['MaxSize']) ? $property_data['Size']['MaxSize'] : 0.0;

    // Build a human-readable taxonomy value
    $taxonly = $min_size . ' - ' . $max_size . ' ' . $dimension_name;

    // Collect full size data
    $size_data = array(
        'Dimension' => array(
            'ID' => !empty($property_data['Size']['Dimension']['ID']) ? $property_data['Size']['Dimension']['ID'] : 0,
            'Name' => $dimension_name
        ),
        'MinSize' => $min_size,
        'MaxSize' => $max_size,
        'TotalSize' => !empty($property_data['Size']['TotalSize']) ? $property_data['Size']['TotalSize'] : 0.0,
        'EavesHeight' => !empty($property_data['Size']['EavesHeight']) ? $property_data['Size']['EavesHeight'] : 0.0,
        'EavesDimension' => !empty($property_data['Size']['EavesDimension']) ? $property_data['Size']['EavesDimension'] : 0,
        'ReceptionRooms' => !empty($property_data['Size']['ReceptionRooms']) ? $property_data['Size']['ReceptionRooms'] : 0,
        'Bathrooms' => !empty($property_data['Size']['Bathrooms']) ? $property_data['Size']['Bathrooms'] : 0,
        'Parking' => array(
            'Parking' => !empty($property_data['Size']['Parking']['Parking']) ? $property_data['Size']['Parking']['Parking'] : 'Unknown',
            'Spaces' => !empty($property_data['Size']['Parking']['Spaces']) ? $property_data['Size']['Parking']['Spaces'] : 0
        ),
        'TaxOnly' => $taxonly
    );

    // Serialize the size data
    $serialized_size = serialize($size_data);

    // Store the serialized data in a single meta field
    add_post_meta($post_id, 'Size', $serialized_size);

    // --- Store MaxSize into taxonomy 'size' ---
    if ($max_size > 0) {
        $size_term = $max_size . ' ' . $dimension_name; // e.g., "1200 sqft"

        // Check if term exists, create if not
        if (!term_exists($size_term, 'size')) {
            wp_insert_term($size_term, 'size');
        }

        // Assign the term to the post
        wp_set_object_terms($post_id, $size_term, 'size', false); // false replaces existing terms
    }
}
