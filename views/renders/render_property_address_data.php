<?php
function render_property_address_data($post_id) {
    // Fetch the 'Address' meta field
    $address_data = get_post_meta($post_id, 'Address', true);

    if (empty($address_data)) {
        return; // Exit if no address data is available
    }

    $address_data = maybe_unserialize($address_data);

    if (!is_array($address_data)) {
        return; // Exit if the address data is not an array
    }

    echo '<div class="property-address-data">';
    echo '<h3>Property Address Information</h3>';

    // Define fields to display
    $fields = [
        'BuildingName' => 'Building Name',
        'SecondaryName' => 'Secondary Name',
        'Street' => 'Street',
        'District' => 'District',
        'Town' => 'Town',
        'County' => 'County',
        'Postcode' => 'Postcode',
        'DisplayAddress' => 'Display Address',
        'Longitude' => 'Longitude',
        'Latitude' => 'Latitude',
        'Northings' => 'Northings',
        'Eastings' => 'Eastings',
        'What3Words' => 'What3Words',
    ];

    // Display nested Level1
    if (isset($address_data['Level1']) && is_array($address_data['Level1'])) {
        $level1_name = isset($address_data['Level1']['Name']) ? esc_html($address_data['Level1']['Name']) : 'N/A';
        echo '<p><strong>Level 1:</strong> ' . $level1_name . '</p>';
    }

    // Display nested Level2
    if (isset($address_data['Level2']) && is_array($address_data['Level2'])) {
        $level2_name = isset($address_data['Level2']['Name']) ? esc_html($address_data['Level2']['Name']) : 'N/A';
        echo '<p><strong>Level 2:</strong> ' . $level2_name . '</p>';
    }

    // Display nested Country
    if (isset($address_data['Country']) && is_array($address_data['Country'])) {
        $country_name = isset($address_data['Country']['Name']) ? esc_html($address_data['Country']['Name']) : 'N/A';
        echo '<p><strong>Country:</strong> ' . $country_name . '</p>';
    }

    // Display other fields
    foreach ($fields as $key => $label) {
        if (isset($address_data[$key]) && !empty($address_data[$key])) {
            echo '<p><strong>' . esc_html($label) . ':</strong> ' . esc_html($address_data[$key]) . '</p>';
        }
    }

    echo '</div>';
}
