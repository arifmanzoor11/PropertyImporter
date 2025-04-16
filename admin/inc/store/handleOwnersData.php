<?php
function handleOwnersData($post_id, $property_data) {
    // Clear existing 'Owners' meta field to avoid duplication
    delete_post_meta($post_id, 'Owners');

    // Initialize an array to store all owner data
    $owners = array();

    // print_r($property_data);
    // die();

    // Loop through each owner and add their data to the array
    foreach ($property_data as $owner) {
        // Collect owner data
        $owner_data = array(
            'RegisteredName' => !empty($owner['RegisteredName']) ? $owner['RegisteredName'] : '',
            'Logo' => !empty($owner['Logo']) ? $owner['Logo'] : '',
            'ContactID' => !empty($owner['ContactID']) ? $owner['ContactID'] : '',
        );

        // Add owner data to the array
        $owners[] = $owner_data;
    }

    // Serialize the owners array
    $serialized_owners = serialize($owners);

    // Store the serialized data in a single meta field
    add_post_meta($post_id, 'Owners', $serialized_owners);
}
