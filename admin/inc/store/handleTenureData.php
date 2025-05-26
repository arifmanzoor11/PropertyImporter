<?php

function handleTenureData($post_id, $property_data) {
    // Clear existing 'Tenure' meta field to avoid duplication
    delete_post_meta($post_id, 'Tenure');

    // Initialize tenure_data array
    $tenure_data = array(
        'Currency' => !empty($property_data['Tenure']['Currency']) ? $property_data['Tenure']['Currency'] : '',
        'Tenure' => !empty($property_data['Tenure']['Tenure']) ? $property_data['Tenure']['Tenure'] : 'Unknown',
        'ForSale' => isset($property_data['Tenure']['ForSale']) ? $property_data['Tenure']['ForSale'] : '',
        'ForSalePriceFrom' => isset($property_data['Tenure']['ForSalePriceFrom']) ? $property_data['Tenure']['ForSalePriceFrom'] : 0.0,
        'ForSalePriceTo' => isset($property_data['Tenure']['ForSalePriceTo']) ? $property_data['Tenure']['ForSalePriceTo'] : 0.0,
        'ForSaleTerm' => array(
            'ID' => isset($property_data['Tenure']['ForSaleTerm']['ID']) ? $property_data['Tenure']['ForSaleTerm']['ID'] : 0,
            'Name' => !empty($property_data['Tenure']['ForSaleTerm']['Name']) ? $property_data['Tenure']['ForSaleTerm']['Name'] : ''
        ),
        'ForRent' => isset($property_data['Tenure']['ForRent']) ? $property_data['Tenure']['ForRent'] : '',
        'ForRentPriceFrom' => isset($property_data['Tenure']['ForRentPriceFrom']) ? $property_data['Tenure']['ForRentPriceFrom'] : 0.0,
        'ForRentPriceTo' => isset($property_data['Tenure']['ForRentPriceTo']) ? $property_data['Tenure']['ForRentPriceTo'] : 0.0,
        'ForRentTerm' => array(
            'ID' => isset($property_data['Tenure']['ForRentTerm']['ID']) ? $property_data['Tenure']['ForRentTerm']['ID'] : 0,
            'Name' => !empty($property_data['Tenure']['ForRentTerm']['Name']) ? $property_data['Tenure']['ForRentTerm']['Name'] : ''
        ),
        'UserDefinedTenure1' => isset($property_data['Tenure']['UserDefinedTenure1']) ? $property_data['Tenure']['UserDefinedTenure1'] : '',
        'UserDefinedTenure1From' => isset($property_data['Tenure']['UserDefinedTenure1From']) ? $property_data['Tenure']['UserDefinedTenure1From'] : 0.0,
        'UserDefinedTenure1To' => isset($property_data['Tenure']['UserDefinedTenure1To']) ? $property_data['Tenure']['UserDefinedTenure1To'] : 0.0,
        'UserDefinedTenure1Term' => array(
            'ID' => isset($property_data['Tenure']['UserDefinedTenure1Term']['ID']) ? $property_data['Tenure']['UserDefinedTenure1Term']['ID'] : 0,
            'Name' => !empty($property_data['Tenure']['UserDefinedTenure1Term']['Name']) ? $property_data['Tenure']['UserDefinedTenure1Term']['Name'] : ''
        ),
        'UserDefinedTenure2' => isset($property_data['Tenure']['UserDefinedTenure2']) ? $property_data['Tenure']['UserDefinedTenure2'] : '',
        'UserDefinedTenure2From' => isset($property_data['Tenure']['UserDefinedTenure2From']) ? $property_data['Tenure']['UserDefinedTenure2From'] : 0.0,
        'UserDefinedTenure2To' => isset($property_data['Tenure']['UserDefinedTenure2To']) ? $property_data['Tenure']['UserDefinedTenure2To'] : 0.0,
        'UserDefinedTenure2Term' => array(
            'ID' => isset($property_data['Tenure']['UserDefinedTenure2Term']['ID']) ? $property_data['Tenure']['UserDefinedTenure2Term']['ID'] : 0,
            'Name' => !empty($property_data['Tenure']['UserDefinedTenure2Term']['Name']) ? $property_data['Tenure']['UserDefinedTenure2Term']['Name'] : ''
        ),
        'LeaseholdPurchasePrice' => isset($property_data['Tenure']['LeaseholdPurchasePrice']) ? $property_data['Tenure']['LeaseholdPurchasePrice'] : 0.0,
        'LeaseType' => array(
            'ID' => isset($property_data['Tenure']['LeaseType']['ID']) ? $property_data['Tenure']['LeaseType']['ID'] : 0,
            'Name' => !empty($property_data['Tenure']['LeaseType']['Name']) ? $property_data['Tenure']['LeaseType']['Name'] : ''
        ),
        'ServiceCharge' => isset($property_data['Tenure']['ServiceCharge']) ? $property_data['Tenure']['ServiceCharge'] : 0.0,
        'ServiceChargeTerm' => !empty($property_data['Tenure']['ServiceChargeTerm']) ? $property_data['Tenure']['ServiceChargeTerm'] : '',
        'Comments' => !empty($property_data['Tenure']['Comments']) ? $property_data['Tenure']['Comments'] : ''
    );

    // Serialize the Tenure data
    $serialized_tenure = serialize($tenure_data);

    // Store the serialized data in a single meta field
    add_post_meta($post_id, 'Tenure', $serialized_tenure);

    // Clear existing terms
    wp_set_post_terms($post_id, array(), 'tenure');

    // Add to 'tenure_status' taxonomy based on ForSale and ForRent values
    if ($property_data['Tenure']['ForSale'] === true) { // Check for boolean true
        wp_set_object_terms($post_id, 'Sale', 'tenure');
        update_post_meta($post_id, 'Price', $tenure_data['ForSalePriceFrom']);
    }  
    
    if ($property_data['Tenure']['ForRent'] === true) { // Check for boolean true
        wp_set_object_terms($post_id, 'Rent', 'tenure');
        update_post_meta($post_id, 'Price', $tenure_data['ForRentPriceFrom']);
    }
}
