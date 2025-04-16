<?php
function render_tenure_data($post_id) {
    // Fetch the 'Tenure' meta field
    $tenure_data = get_post_meta($post_id, 'Tenure', true);

    if (empty($tenure_data)) {
        return; // Exit if no tenure data is available
    }

    $tenure_data = maybe_unserialize($tenure_data);

    if (!is_array($tenure_data)) {
        return; // Exit if the tenure data is not an array
    }

    echo '<div class="property-tenure-data">';
    echo '<h3>Tenure Information</h3>';

    // Define fields to display
    $fields = [
        'Currency' => 'Currency',
        'Tenure' => 'Tenure',
        'ForSale' => 'For Sale',
        'ForSalePriceFrom' => 'For Sale Price From',
        'ForSalePriceTo' => 'For Sale Price To',
        'ForRent' => 'For Rent',
        'ForRentPriceFrom' => 'For Rent Price From',
        'ForRentPriceTo' => 'For Rent Price To',
        'UserDefinedTenure1' => 'User Defined Tenure 1',
        'UserDefinedTenure1From' => 'User Defined Tenure 1 From',
        'UserDefinedTenure1To' => 'User Defined Tenure 1 To',
        'UserDefinedTenure2' => 'User Defined Tenure 2',
        'UserDefinedTenure2From' => 'User Defined Tenure 2 From',
        'UserDefinedTenure2To' => 'User Defined Tenure 2 To',
        'LeaseholdPurchasePrice' => 'Leasehold Purchase Price',
        'ServiceCharge' => 'Service Charge',
        'ServiceChargeTerm' => 'Service Charge Term',
        'Comments' => 'Comments',
    ];

    // Function to display nested array fields
    function display_nested_field($array, $key, $label) {
        if (isset($array[$key]) && is_array($array[$key])) {
            $name = isset($array[$key]['Name']) ? esc_html($array[$key]['Name']) : 'N/A';
            echo '<p><strong>' . esc_html($label) . ':</strong> ' . $name . '</p>';
        }
    }

    // Display fields with nested arrays
    display_nested_field($tenure_data, 'ForSaleTerm', 'For Sale Term');
    display_nested_field($tenure_data, 'ForRentTerm', 'For Rent Term');
    display_nested_field($tenure_data, 'UserDefinedTenure1Term', 'User Defined Tenure 1 Term');
    display_nested_field($tenure_data, 'UserDefinedTenure2Term', 'User Defined Tenure 2 Term');
    display_nested_field($tenure_data, 'LeaseType', 'Lease Type');

    // Display other fields
    foreach ($fields as $key => $label) {
        if (isset($tenure_data[$key]) && !is_array($tenure_data[$key]) && !empty($tenure_data[$key])) {
            echo '<p><strong>' . esc_html($label) . ':</strong> ' . esc_html($tenure_data[$key]) . '</p>';
        }
    }

    echo '</div>';
}
