<?php 
function get_bearer_token() {
    $debug = [];
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        error_log('[Bearer Token] Request must be a POST method.');
        return false;
    }
    // Allow overrides via POST for testing

    $token_url = get_option('auto_import_token_url', '');
    $client_id =  get_option('auto_import_client_id', '');;
    $client_secret =  get_option('auto_import_client_secret', '');
    $grant_type = 'client_credentials';

    // Validate required values
    if (empty($token_url) || empty($client_id) || empty($client_secret)) {
        error_log('[Bearer Token] Missing required credentials or token URL.');
        return false;
    }
    $debug[] = 'Bearer token retrieved.';
    
    // Send request
    $response = wp_remote_post($token_url, array(
        'body' => array(
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'grant_type' => $grant_type,
        ),
    ));

    if (is_wp_error($response)) {
        error_log('[Bearer Token] WP Error: ' . $response->get_error_message());
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // Log and return token or failure
    if (isset($data['access_token'])) {
        error_log('[Bearer Token] Access token received successfully.');
        return $data['access_token'];
    } else {
        error_log('[Bearer Token] Response missing access_token: ' . $body);
    }

    return false;
}