<?php 
// Function to make the API request
function make_api_request($bearer_token, $parameters) {
    $property_uri = get_option('auto_import_property_url', '');
    $response = wp_remote_post($property_uri, array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $bearer_token,
            'Content-Type' => 'application/json',
        ),
        'body' => json_encode($parameters),
        'timeout' => 45,
        'sslverify' => false
    ));

    if (is_wp_error($response)) {
        error_log('Failed to download property feed: ' . $response->get_error_message());
    }
    
    return $response;
}
