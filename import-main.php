<?php
include_once plugin_dir_path(__FILE__) . 'parts/define_parameters.php';
include_once plugin_dir_path(__FILE__) . 'parts/get_bearer_token.php'; // only once
include_once plugin_dir_path(__FILE__) . 'parts/make_api_request.php';
include_once plugin_dir_path(__FILE__) . 'parts/process_response.php';
include_once plugin_dir_path(__FILE__) . 'parts/insert_metadata.php';
include_once plugin_dir_path(__FILE__) . 'parts/trigger_import_process.php';
include_once plugin_dir_path(__FILE__) . 'parts/properties_import.php';
include_once plugin_dir_path(__FILE__) . 'parts/delete_all_property_posts.php';
function manage_auto_import() {
    ?>
   <div class="wrap">
    <h2 style="margin-bottom: 20px;">Auto Import Testing</h2>
    <button id="start-import-button" class="button button-primary" style="padding: 10px 20px; font-size: 16px;">
        Start Import
    </button>
    
    <div id="import-status" style="margin-top: 15px; font-weight: 600;"></div>
    <div id="loading" style="display: none; margin-top: 20px;">
        <div class="spinner-loader"></div>
        <p style="margin-top: 10px;">Import in progress...</p>
    </div>
    <div id="response" style="margin-top: 20px;"></div>
</div>

<style>
    .spinner-loader {
        width: 40px;
        height: 40px;
        border: 4px solid #e0e0e0;
        border-top: 4px solid #0073aa;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto;
    }

    @keyframes spin {
        0%   { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>


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
                    jQuery("#loading").show();
                    jQuery('#response').html('Import in progress. Please dont reload the page');
                },
                success: function(response) {
                    if (response.success) {
                        jQuery('#response').html('Import Compeleted...');
                        jQuery("#loading").hide();
                    } else {
                        jQuery('#response').html('Import failed: ' + response.data.message);
                    }
                },
                error: function() {
                    jQuery('#response').html('Import failed due to an error.');
                }
            });
        });
    });


    </script>
    <?php
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

   // Step 6: Delete all properties
    $delete_before_import = get_option('auto_import_delete_before_import', '');
    if ($delete_before_import == 'true') {

        $delete_all_property_posts = delete_all_property_posts();

        if (is_wp_error($delete_all_property_posts)) {
            $error_message = $delete_all_property_posts->get_error_message();

            // Skip return only if the message matches "No property posts found."
            if (stripos($error_message, 'No property posts found.') === false) {
                error_log('[Property Import] Delete failed: ' . $error_message);
                return 'Error: Delete failed.';
            } else {
                error_log('[Property Import] No property posts found to delete. Continuing import...');
            }
        }
    }

    // Step 7: Trigger import
        $import_result = trigger_import_process($file_path, $wpdb);
        
        if (!$import_result) {
            error_log('[Property Import] Import process failed.');
            return 'Error: Import process could not be triggered.';
        }
        error_log('[Property Import] Import successfully triggered.');

        return 'Success: Property feed downloaded and import process started.';
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