<?php
function handleBusinessRatesData($post_id, $property_data) {
    // Clear existing 'BusinessRates' meta field to avoid duplication
    delete_post_meta($post_id, 'BusinessRates');

    // Collect BusinessRates data
    $business_rates_data = array(
        'CurrentRateableValue' => isset($property_data['Additional']['BusinessRates']['CurrentRateableValue']) ? $property_data['Additional']['BusinessRates']['CurrentRateableValue'] : null,
        'RatesPayable' => isset($property_data['Additional']['BusinessRates']['RatesPayable']) ? $property_data['Additional']['BusinessRates']['RatesPayable'] : null,
        'Comments' => isset($property_data['Additional']['BusinessRates']['Comments']) ? $property_data['Additional']['BusinessRates']['Comments'] : null
    );

    // Remove empty fields from the array
    $business_rates_data = array_filter($business_rates_data, function($value) {
        return !is_null($value) && $value !== '';
    });

    // If the resulting array is not empty, serialize and store it
    if (!empty($business_rates_data)) {
        $serialized_business_rates = serialize($business_rates_data);
        add_post_meta($post_id, 'BusinessRates', $serialized_business_rates);
    }
}