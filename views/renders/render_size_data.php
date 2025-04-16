<?php
function render_size_data($post_id) {
    // Fetch the 'Size' meta field
    $size_data = get_post_meta($post_id, 'Size', true);

    if (empty($size_data)) {
        return; // Exit if no size data is available
    }

    $size_data = maybe_unserialize($size_data);

    if (!is_array($size_data)) {
        return; // Exit if the size data is not an array
    }

    echo '<div class="property-size-data">';
    echo '<h3>Size Information</h3>';

    // Display Dimension
    if (isset($size_data['Dimension']) && is_array($size_data['Dimension'])) {
        $dimension_id = isset($size_data['Dimension']['ID']) ? esc_html($size_data['Dimension']['ID']) : 'N/A';
        $dimension_name = isset($size_data['Dimension']['Name']) ? esc_html($size_data['Dimension']['Name']) : 'N/A';
        // echo '<p><strong>Dimension ID:</strong> ' . $dimension_id . '</p>';
        // echo '<p><strong>Dimension Name:</strong> ' . $dimension_name . '</p>';
    }

   // Define fields to display with dimensions after size fields
$fields = [
    'MinSize' => 'Minimum Size',
    'MaxSize' => 'Maximum Size',
    'TotalSize' => 'Total Size',
];
$dimension_name = isset($size_data['Dimension']['Name']) ? esc_html($size_data['Dimension']['Name']) : 'N/A';

// Display size fields
foreach ($fields as $key => $label) {
    if (isset($size_data[$key]) && !empty($size_data[$key])) {
        echo '<p><strong>' . esc_html($label) . ':</strong> ' . esc_html($size_data[$key]) .' '. $dimension_name . '</p>';
     }
}

// Define other fields to display
$other_fields = [
    'EavesHeight' => 'Eaves Height',
    'EavesDimension' => 'Eaves Dimension',
    'ReceptionRooms' => 'Reception Rooms',
    'Bathrooms' => 'Bathrooms',
];

// Display other fields
foreach ($other_fields as $key => $label) {
    if (isset($size_data[$key]) && !empty($size_data[$key])) {
        echo '<p><strong>' . esc_html($label) . ':</strong> ' . esc_html($size_data[$key])  . '</p>';
    }
}


    // Display Parking
    if (isset($size_data['Parking']) && is_array($size_data['Parking'])) {
        $parking_status = isset($size_data['Parking']['Parking']) ? esc_html($size_data['Parking']['Parking']) : 'N/A';
        $parking_spaces = isset($size_data['Parking']['Spaces']) ? esc_html($size_data['Parking']['Spaces']) : 'N/A';
        echo '<p><strong>Parking:</strong> ' . $parking_status . ' (Spaces: ' . $parking_spaces . ')</p>';
    }

    echo '</div>';
}
