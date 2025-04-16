<?php
function update_property_meta_fields($post_id, $property_data) {
    // Update meta fields
    update_post_meta($post_id, 'ID', isset($property_data['ID']) ? $property_data['ID'] : '');
    update_post_meta($post_id, 'FileRef', isset($property_data['FileRef']) ? $property_data['FileRef'] : '');
    
    if (isset($property_data['MarketStatus']['Name'])) {
        // Update the post meta with the 'MarketStatus' name
        update_post_meta($post_id, 'MarketStatus', $property_data['MarketStatus']['Name']);
        
        // Remove all existing 'MarketStatus' taxonomy terms
        wp_set_post_terms($post_id, array(), 'MarketStatus');
    
        // Get the 'MarketStatus' name
        $market_status_name = $property_data['MarketStatus']['Name'];
    
        // Set the 'MarketStatus' taxonomy term using 'MarketStatus' name
        wp_set_object_terms($post_id, $market_status_name, 'MarketStatus');
        
        // If the status is 'Withdrawn', set the post to 'draft'
        if (strtolower($market_status_name) === 'withdrawn') {
            wp_update_post(array(
                'ID'          => $post_id,
                'post_status' => 'draft'
            ));
        }
    }
    
    update_post_meta($post_id, 'LastUnavailableDate', isset($property_data['LastUnavailableDate']) ? $property_data['LastUnavailableDate'] : '');
    update_post_meta($post_id, 'LastAvailableDate', isset($property_data['LastAvailableDate']) ? $property_data['LastAvailableDate'] : '');
    update_post_meta($post_id, 'DisplayUntil', isset($property_data['DisplayUntil']) ? $property_data['DisplayUntil'] : '');
    update_post_meta($post_id, 'Website', isset($property_data['Website']) ? $property_data['Website'] : '');
    update_post_meta($post_id, 'DataRoom', isset($property_data['DataRoom']) ? $property_data['DataRoom'] : '');
    
    if (isset($property_data['Featured'])) {
        update_post_meta($post_id, 'Featured', $property_data['Featured']);
    }

    // Handle DocumentMedia
    if (isset($property_data['DocumentMedia']) && is_array($property_data['DocumentMedia'])) {
        handleDocumentMediaUpload($property_data['DocumentMedia'], $post_id);
    }

    // Handle Categories
    if (isset($property_data['Categories']) && is_array($property_data['Categories'])) {
        handlePropertyCategories($property_data['Categories'], $post_id);
    }

    // Handle Photos
    if (isset($property_data['Photos']) && is_array($property_data['Photos'])) {
        import_photos($property_data['Photos'], $post_id);
    }

    // Handle PropertyTypes
    if (isset($property_data['PropertyTypes']) && is_array($property_data['PropertyTypes'])) {
        handlePropertyTypes($post_id, $property_data);
    }

    // Handle Agents
    if (isset($property_data['Agents']) && is_array($property_data['Agents'])) {
        handleAgentData($post_id, $property_data);
    }

    // Handle Address
    if (isset($property_data['Address']) && is_array($property_data['Address'])) {
        handleAddressData($post_id, $property_data);
        // Handle Location
        handlePropertyLocation($post_id, $property_data);
    }

    // Handle Size
    if (isset($property_data['Size']) && is_array($property_data['Size'])) {
        handleSizeData($post_id, $property_data);
    }

    // Handle SystemDetail
    if (isset($property_data['SystemDetail']) && is_array($property_data['SystemDetail'])) {
        handleSystemDetailData($post_id, $property_data);
    }

    // Handle BusinessRates
    if (isset($property_data['Additional']['BusinessRates']) && is_array($property_data['Additional']['BusinessRates'])) {
        handleBusinessRatesData($post_id, $property_data['Additional']);
    }

    // Handle Tenure
    if (isset($property_data['Tenure']) && is_array($property_data['Tenure'])) {
        handleTenureData($post_id, $property_data);
    }

    // Handle Owners
    if (isset($property_data['Owners']) && is_array($property_data['Owners'])) {
        handleOwnersData($post_id, $property_data['Owners']);
    }

    // Handle Additional Information
    if (isset($property_data['Additional'])) {
        $additional = $property_data['Additional'];
        if (isset($additional['Info']) && is_array($additional['Info'])) {
            foreach ($additional['Info'] as $info) {
                if (!empty($info['Description'])) {
                    add_post_meta($post_id, 'AdditionalInfo', $info['Description'] . ': ' . $info['Information']);
                }
            }
        }
        if (isset($additional['Bullets']) && is_array($additional['Bullets'])) {
            handleBulletsPoints($post_id, $property_data);
        }
    }
}
?>
