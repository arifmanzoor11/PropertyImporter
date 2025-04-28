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


// Main function to download and import properties file via POST method
function download_and_import_properties_file() {
    global $wpdb;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        error_log('[Property Import] Request must be POST.');
        return 'Error: Invalid request method. Please use POST.';
    }

    // Step 1: Get Bearer Token
    $bearer_token = get_bearer_token();
    if (!$bearer_token) {
        error_log('[Property Import] Failed to retrieve Bearer token.');
        return 'Error: Could not retrieve Bearer token.';
    }

    // Step 2: Define default parameters
    $parameters = define_parameters();

    // Step 3: Allow testing with POST parameter overrides
    if (!empty($_POST['override_params'])) {
        $override_data = json_decode(stripslashes($_POST['override_params']), true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $parameters = array_merge($parameters, $override_data);
            error_log('[Property Import] Parameters overridden for testing: ' . print_r($parameters, true));
        } else {
            error_log('[Property Import] Invalid JSON in override_params: ' . $_POST['override_params']);
            return 'Error: Invalid override_params JSON.';
        }
    }

    // Step 4: Make the API request
    $response = make_api_request($bearer_token, $parameters);
    if (is_wp_error($response)) {
        error_log('[Property Import] API request failed: ' . $response->get_error_message());
        return 'Error: API request failed.';
    }

    // Step 5: Process response and save file
    $file_path = process_response($response, $wpdb);
    if (!$file_path || !file_exists($file_path)) {
        error_log('[Property Import] Failed to process response or file not found.');
        return 'Error: Could not process API response or locate file.';
    }

    // Step 6: Trigger import
    $import_result = trigger_import_process($file_path, $wpdb);
    if (!$import_result) {
        error_log('[Property Import] Import process failed.');
        return 'Error: Import process could not be triggered.';
    }

    error_log('[Property Import] Import successfully triggered.');
    return 'Success: Property feed downloaded and import process started.';
}

// Function to define API request parameters
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

// Function to process the API response
function process_response($response, $wpdb) {
    $body = wp_remote_retrieve_body($response);

    // Save the response to a file
    $upload_dir = wp_upload_dir();
    $file_name = 'property_feed_' . date('Ymd_His') . '.json';
    $file_path = $upload_dir['basedir'] . '/' . $file_name;
    file_put_contents($file_path, $body);

    // Insert download information into the database
    $table_name = $wpdb->prefix . 'manage_import';
    $data = array(
        'name' => $file_name,
        'url' => $upload_dir['baseurl'] . '/' . $file_name,
    );
    $wpdb->insert($table_name, $data);
    $import_id = $wpdb->insert_id;

    // Log import details in the wp_import_meta table
    insert_metadata($import_id, $wpdb);

    return $file_path;
}

// Function to insert metadata into the database
function insert_metadata($import_id, $wpdb) {
    $meta_data = array(
        array(
            'import_id' => $import_id,
            'meta_key' => 'import_type',
            'meta_value' => 'Auto Imported',
        ),
        array(
            'import_id' => $import_id,
            'meta_key' => 'import_date',
            'meta_value' => current_time('mysql'),
        ),
    );

    foreach ($meta_data as $row) {
        $wpdb->insert($wpdb->prefix . 'import_meta', $row);
    }
}

// Function to trigger the import process
function trigger_import_process($file_path, $wpdb) {
    $file_url = wp_upload_dir()['baseurl'] . '/' . basename($file_path);
    $import_id = $wpdb->insert_id; // Get last insert ID

    // Return a JSON response
    wp_send_json_success(array(
        'file_url' => $file_url,
        'import_id' => $import_id,
        'message' => 'Property feed downloaded and import process started.'
    ));

    error_log('Property feed downloaded and import process started for file: ' . $file_path);
}


// Create an admin page with a button to trigger the import manually
function manage_auto_import() {
    ?>
    <div class="wrap">
        <h2>Auto Import Testing</h2>
        <button id="start-import-button" class="button button-primary">Start Import</button>
        <div id="import-status"></div>
        <div id="loading" style="display:none;">Loading...</div>
        <div id="response"></div>
    </div>

    <script type="text/javascript">
    jQuery(document).ready(function(jQuery) {
        jQuery('#start-import-button').on('click', function() {
            jQuery.ajax({
                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                method: 'POST',
                data: {
                    action: 'manual_properties_import'  // This triggers the PHP function
                },
                beforeSend: function() {
                    jQuery('#import-status').html('Import in progress...');
                },
                success: function(response) {
                    if (response.success) {
                        // Pass the file_url and import_id from the PHP response to the form submission handler
                        var formData = {
                            file_url: response.data.file_url,
                            import_id: response.data.import_id
                        };

                         // Call the submission function directly
                        submitImportForm(formData);

                        // jQuery('#import-status').html(response.data.message);
                    } else {
                        jQuery('#import-status').html('Import failed: ' + response.data.message);
                    }
                },
                error: function() {
                    jQuery('#import-status').html('Import failed due to an error.');
                }
            });
        });
    });

    function submitImportForm(formData) {
    var fileUrl = formData.file_url; 
    var import_id = formData.import_id;
    var imported_count = 0; // Initialize imported count
    var updated_count = 0; // Initialize updated count
    if (fileUrl) {
        // Fetch the JSON content from the file URL
        jQuery.getJSON(fileUrl, function(data) {
            if (data && Array.isArray(data)) {
                // Function to process entries sequentially
                function processNextEntry(index) {
                    if (index >= data.length) {
                        // If all entries have been processed, show completion message
                        jQuery("#response").append('<p>Import complete. Processed ' + data.length + ' entries.</p>');
                        jQuery("#loading").hide();

                        // Call the function after import is completed
                        onImportComplete(import_id, imported_count, updated_count); // Pass necessary data
                        return;
                    }

                    var entry = data[index];
                    var formData = {
                        action: 'properties_import',
                        import_id: import_id,
                        property_data: entry // Each entry from the JSON file
                    };

                    // Send each entry via AJAX
                    jQuery.ajax({
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        type: "POST",
                        data: formData,
                        xhr: function() {
                            var xhr = new window.XMLHttpRequest();
                            xhr.onprogress = function(e) {
                                if (e.lengthComputable) {
                                    jQuery("#response").append(e.target.responseText); // Real-time updates
                                }
                            };
                            return xhr;
                        },
                        beforeSend: function() {
                            if (index === 0) {
                                jQuery("#response").html(''); // Clear previous responses
                                jQuery("#loading").show(); // Show the loading spinner on first request
                            }
                        },
                        success: function(response) {
                            if (response && response.success) {
                                jQuery("#response").append('<p>Entry ' + (index + 1) + ': ' + response.data.message + '</p>');
                                imported_count++; // Increment imported count
                                // Proceed to the next entry
                                processNextEntry(index + 1);
                            } else {
                                alert('Error with entry ' + (index + 1) + ': ' + (response.data.message || 'Unknown error'));
                                processNextEntry(index + 1); // Proceed to the next entry even on error
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('Error with entry ' + (index + 1) + ' - ' + xhr.status + ': ' + xhr.statusText);
                            alert('Error with entry ' + (index + 1) + ' - ' + xhr.statusText);
                            processNextEntry(index + 1); // Continue even after an error
                        }
                    });
                }

                // Start processing the first entry
                processNextEntry(0);
            } else {
                alert("The file does not contain valid data");
            }
        }).fail(function() {
            alert("Failed to retrieve the file. Please check the URL.");
        });
    } else {
        alert("No file URL found");
    }
}

// Function to be called after all imports are done
function onImportComplete(import_id, imported_count, updated_count) {
    // Add entries to the database
    jQuery.ajax({
        url: "<?php echo admin_url('admin-ajax.php'); ?>",
        type: "POST",
        data: {
            action: 'log_import_meta',
            import_id: import_id,
            imported_count: imported_count,
            updated_count: updated_count
        },
        success: function(response) {
            if (response.success) {
                console.log("Import metadata logged successfully.");
            } else {
                console.error("Failed to log import metadata: " + response.data.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error logging import metadata: " + error);
        }
    });
}
    </script>
    <?php
}


// Handle the manual import via AJAX POST request
function manual_properties_import() {

    // Ensure it's a POST request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        wp_send_json_error(array('message' => 'Invalid request method. Please use POST.'));
        return;
    }

    $message = download_and_import_properties_file();
    wp_send_json_success(array('message' => $message));
}

add_action('wp_ajax_manual_properties_import', 'manual_properties_import');

