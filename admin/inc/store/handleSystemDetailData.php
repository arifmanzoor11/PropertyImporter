<?php

function handleSystemDetailData($post_id, $property_data) {
    // Clear existing 'SystemDetail' meta field to avoid duplication
    delete_post_meta($post_id, 'SystemDetail');

    // Collect SystemDetail data
    $system_detail_data = array(
        'AccountManagers' => !empty($property_data['SystemDetail']['AccountManagers']) ? $property_data['SystemDetail']['AccountManagers'] : array(),
        'Partner' => array(
            'ID' => !empty($property_data['SystemDetail']['Partner']['ID']) ? $property_data['SystemDetail']['Partner']['ID'] : 0,
            'Name' => !empty($property_data['SystemDetail']['Partner']['Name']) ? $property_data['SystemDetail']['Partner']['Name'] : ''
        ),
        'DateRegistered' => !empty($property_data['SystemDetail']['DateRegistered']) ? $property_data['SystemDetail']['DateRegistered'] : '',
        'DateUpdated' => !empty($property_data['SystemDetail']['DateUpdated']) ? $property_data['SystemDetail']['DateUpdated'] : '',
    );

    // Serialize the SystemDetail data
    $serialized_system_detail = serialize($system_detail_data);

    // Store the serialized data in a single meta field
    add_post_meta($post_id, 'SystemDetail', $serialized_system_detail);
}
