<?php

function handleAddressData($post_id, $property_data) {
    // Clear existing 'Address' meta field to avoid duplication
    delete_post_meta($post_id, 'Address');

    // Collect address data
    $address_data = array(
        'Level1' => array(
            'ID' => !empty($property_data['Address']['Level1']['ID']) ? $property_data['Address']['Level1']['ID'] : 0,
            'Name' => !empty($property_data['Address']['Level1']['Name']) ? $property_data['Address']['Level1']['Name'] : ''
        ),
        'Level2' => array(
            'ID' => !empty($property_data['Address']['Level2']['ID']) ? $property_data['Address']['Level2']['ID'] : 0,
            'Name' => !empty($property_data['Address']['Level2']['Name']) ? $property_data['Address']['Level2']['Name'] : '',
            'Level1Link' => !empty($property_data['Address']['Level2']['Level1Link']) ? $property_data['Address']['Level2']['Level1Link'] : 0
        ),
        'Country' => array(
            'ID' => !empty($property_data['Address']['Country']['ID']) ? $property_data['Address']['Country']['ID'] : 0,
            'Name' => !empty($property_data['Address']['Country']['Name']) ? $property_data['Address']['Country']['Name'] : ''
        ),
        'UPRN' => !empty($property_data['Address']['UPRN']) ? $property_data['Address']['UPRN'] : '',
        'BuildingName' => !empty($property_data['Address']['BuildingName']) ? $property_data['Address']['BuildingName'] : '',
        'SecondaryName' => !empty($property_data['Address']['SecondaryName']) ? $property_data['Address']['SecondaryName'] : '',
        'Street' => !empty($property_data['Address']['Street']) ? $property_data['Address']['Street'] : '',
        'District' => !empty($property_data['Address']['District']) ? $property_data['Address']['District'] : '',
        'Town' => !empty($property_data['Address']['Town']) ? $property_data['Address']['Town'] : '',
        'County' => !empty($property_data['Address']['County']) ? $property_data['Address']['County'] : '',
        'Postcode' => !empty($property_data['Address']['Postcode']) ? $property_data['Address']['Postcode'] : '',
        'DisplayAddress' => !empty($property_data['Address']['DisplayAddress']) ? $property_data['Address']['DisplayAddress'] : '',
        'IndustrialEstateID' => !empty($property_data['Address']['IndustrialEstateID']) ? $property_data['Address']['IndustrialEstateID'] : 0,
        'Longitude' => !empty($property_data['Address']['Longitude']) ? $property_data['Address']['Longitude'] : 0.0,
        'Latitude' => !empty($property_data['Address']['Latitude']) ? $property_data['Address']['Latitude'] : 0.0,
        'Northings' => !empty($property_data['Address']['Northings']) ? $property_data['Address']['Northings'] : 0.0,
        'Eastings' => !empty($property_data['Address']['Eastings']) ? $property_data['Address']['Eastings'] : 0.0,
        'What3Words' => !empty($property_data['Address']['What3Words']) ? $property_data['Address']['What3Words'] : ''
    );
    // print_r($address_data);
    // die($address_data);
    // Serialize the address data
    $serialized_address = serialize($address_data);

    // Store the serialized data in a single meta field
    add_post_meta($post_id, 'Address', $serialized_address);
}

?>
