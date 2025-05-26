<?php // Function to define API request parameters
function define_parameters() {
    // Get the EndIndex value
    $end_index = get_option('auto_import_index_end');

    // Build the base array
    $options = array(
        "DisplayOptions" => array(
            "Additional" => get_option('auto_import_additional', true),
            "Categories" => get_option('auto_import_categories', true),
            "Photos" => get_option('auto_import_photos', true),
            "DocumentMedia" => get_option('auto_import_document_media', true),
            "Floors" => get_option('auto_import_floors', true),
            "Agents" => get_option('auto_import_agents', true),
            "Owners" => get_option('auto_import_owners', true),
            "Solicitors" => get_option('auto_import_solicitors', true),
            "Auction" => get_option('auto_import_auction', true),
            "SystemDetails" => get_option('auto_import_system_details', true),
            "UploadDescription" => get_option('auto_import_upload_description', true),
        ),
        "FilterOptions" => array(
            "PropertyTypes" => get_option('auto_import_property_types', []),
            "DateLastLetOrSold" => get_option('auto_import_date_last_let_or_sold', null),
            "InactiveOnly" => get_option('auto_import_inactive_only', false),
            "ActiveOnly" => get_option('auto_import_active_only', true),
            "StartIndex" => get_option('auto_import_index_start', 0),
        ),
    );
    
    // Conditionally add "EndIndex" if it is not empty
    if (!empty($end_index)) {
        $options['FilterOptions']['EndIndex'] = $end_index;
    }

    return $options;
}