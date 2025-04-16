<?php
function render_map_view($post_id) {
    // Fetch the 'Address' meta field
    $address_data = get_post_meta($post_id, 'Address', true);

    if (empty($address_data)) {
        return; // Exit if no address data is available
    }

    $address_data = maybe_unserialize($address_data);

    if (!is_array($address_data)) {
        return; // Exit if the address data is not an array
    }

 

    // Define fields to display
    $fields = [
        // 'Longitude' => 'Longitude',
        // 'Latitude' => 'Latitude',
        // 'Northings' => 'Northings',
        // 'Eastings' => 'Eastings',
    ];

    $longitude = isset($address_data['Longitude']) ? esc_html($address_data['Longitude']) : null;
    $latitude = isset($address_data['Latitude']) ? esc_html($address_data['Latitude']) : null;

    // Display other fields
    foreach ($fields as $key => $label) {
        if (isset($address_data[$key]) && !empty($address_data[$key])) {
            echo '<p><strong>' . esc_html($label) . ':</strong> ' . esc_html($address_data[$key]) . '</p>';
        }
    }

    // Display the map iframe only if both longitude and latitude are available
    if ($longitude && $latitude) {
        $bbox = [
            'min_lon' => $longitude - 0.01,
            'min_lat' => $latitude - 0.01,
            'max_lon' => $longitude + 0.01,
            'max_lat' => $latitude + 0.01,
        ];

        echo '<div class="property-address-data">';
        echo '<h3>Map View</h3>';
        
        echo '<iframe
            width="100%"
            height="400px"
            frameborder="0"
            style="border:0"
            src="https://www.openstreetmap.org/export/embed.html?bbox=' . $bbox['min_lon'] . ',' . $bbox['min_lat'] . ',' . $bbox['max_lon'] . ',' . $bbox['max_lat'] . '&layer=mapnik&marker=' . $latitude . ',' . $longitude . '"
            allowfullscreen>
        </iframe>';
    }

    echo '</div>';
}
