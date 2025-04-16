<?php
function handleBulletsPoints($post_id, $property_data) {
    // Clear existing 'Bullets' meta field to avoid duplication
    delete_post_meta($post_id, 'Bullets');

    // Collect and filter BulletPoints data
    $bullets_data = array();
    
    if (isset($property_data['Additional']['Bullets']) && is_array($property_data['Additional']['Bullets'])) {
        foreach ($property_data['Additional']['Bullets'] as $bullet) {
            // Only add BulletPoints where BulletPoint is not empty
            if (!empty($bullet['BulletPoint'])) {
                $bullets_data[] = array(
                    'Description' => isset($bullet['Description']) ? $bullet['Description'] : '',
                    'BulletPoint' => $bullet['BulletPoint'],
                    'Header' => isset($bullet['Header']) ? $bullet['Header'] : '',
                    'HeaderIcon' => isset($bullet['HeaderIcon']) ? $bullet['HeaderIcon'] : ''
                );
            }
        }
    }

    // Serialize the Bullets data
    $serialized_bullets_data = serialize($bullets_data);

    // Store the serialized data in a single meta field
    add_post_meta($post_id, 'Bullets', $serialized_bullets_data);
}
