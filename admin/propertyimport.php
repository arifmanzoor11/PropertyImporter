<?php
if ( ! current_user_can( 'manage_options' ) ) {
    return;
}

if ( isset( $_POST['import_properties'] ) ) {
    import_properties_from_api();
}

function enqueue_properties_import_script() {
    // Enqueue the script
    wp_enqueue_script('properties-import-script', get_template_directory_uri() . '/js/properties-import.js', array('jquery'), null, true);

    // Localize the script with the AJAX URL and a nonce
    wp_localize_script('properties-import-script', 'properties_import_vars', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('properties_import_nonce')
    ));
}
add_action('admin_enqueue_scripts', 'enqueue_properties_import_script');
?>
<div class="wrap">
    <div id="loading" style="display: none;">
        <img id="loading-image" src="https://iili.io/HvEsomv.gif" height="100" width="100" alt="Loading...">
        <p class="message">Processing your request...</p>
        <div class="progress-bar-wrapper">
            <div class="progress-bar"></div>
        </div>
    </div>

    <div class="upload">
        <h1>Manual Property Import</h1>
        <div class="import-instructions">
            <p class="description">Follow these steps to import your properties:</p>
            <ol class="instruction-steps">
                <li>Prepare a JSON file containing your property data</li>
                <li>Upload the file using the drag & drop area below or browse to select</li>
                <li>Click "Upload File" to process your data</li>
                <li>Review and confirm the import on the next screen</li>
            </ol>
            <div class="notice notice-info inline">
                <p><strong>Note:</strong> Your JSON file should follow the required format. Each property should include mandatory fields like title, description, price, and location.</p>
            </div>
        </div>
        <form id="upload_form" enctype="multipart/form-data" method="post">
            <div class="upload_formdiv">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-upload" viewBox="0 0 16 16">
                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                    <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z"/>
                </svg>
                <h3>Drop your JSON file here or click to browse</h3>
                <input type="file" id="properties_file" name="properties_file" accept=".json" class="custom-file-input">
                <input type="hidden" name="action" value="upload_properties_file">
            </div>
            <div class="file-info" style="display: none;">
                <span id="selected-file-name"></span>
                <button type="button" class="button-link" onclick="clearFileSelection()">Remove</button>
            </div>
            <button type="submit" id="upload_file_button" class="button button-primary button-large">
                <span class="dashicons dashicons-upload"></span> Upload File
            </button>
        </form>
    </div>

    <div class="import" style="display: none;">
        <h1>Import Properties</h1>
        <form id="import_form" method="post">
            <input type="hidden" id="uploaded_file_url" name="file_url">
            <input type="hidden" id="import_id" name="import_id">
            <button type="submit" id="import_properties_button">Import Properties</button>
        </form>
    </div>

    <div id="response"></div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function (e){
        jQuery("#upload_form").on('submit', function(e) {
            e.preventDefault();
            var fileName = jQuery("#properties_file").val();
            if (fileName) {
                var formData = new FormData(this);
               
                jQuery.ajax({
                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                    type: "POST",
                    data: formData,
                    action: 'upload_properties_file',
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function() {
                        jQuery("#loading").show();
                    },
                    success: function(response) {
                        jQuery("#loading").hide();
                        if (response.success) {
                            jQuery("#uploaded_file_url").val(response.data.file_url);
                            jQuery("#import_id").val(response.data.import_id);
                            jQuery(".upload").hide();
                            jQuery(".import").show();
                        } else {
                            jQuery('#response').html(response);
                        }
                    },
                    error: function(xhr, status, error) {
                        jQuery("#loading").hide();
                        console.log('Error - ' + xhr.status + ': ' + xhr.statusText);
                    }
                });
            } else {
                alert("No file selected");
            }
        });

        jQuery("#import_form").on('submit', function(e) {
            e.preventDefault();
            
            var fileUrl = jQuery("#uploaded_file_url").val();
            var import_id = jQuery("#import_id").val();
            
            if (fileUrl) {
                // Fetch the JSON content from the file URL
                jQuery.getJSON(fileUrl, function(data) {
                    if (data && Array.isArray(data)) {
                        // Loop through each entry in the JSON file
                        data.forEach(function(entry, index) {
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
                                    // Handle the progress event to display real-time updates
                                    xhr.onprogress = function(e) {
                                        if (e.lengthComputable) {
                                            jQuery("#response").append(e.target.responseText);
                                        }
                                    };
                                    return xhr;
                                },
                                beforeSend: function() {
                                    jQuery("#loading").show();
                                    if (index === 0) {
                                        jQuery("#response").html(''); // Clear previous responses on first request
                                    }
                                },
                                success: function(response) {
                                    jQuery("#loading").hide();
                                    if (response) {
                                        try {
                                            let statusClass = '';
                                            let message = '';
                                            
                                            // Handle response object
                                            if (typeof response === 'object') {
                                                if (response.success) {
                                                    statusClass = 'success';
                                                    message = response.data.message;
                                                    
                                                    // Add import stats if available
                                                    if (response.data.imported_count !== undefined || response.data.updated_count !== undefined) {
                                                        message += '<div class="import-stats">';
                                                        if (response.data.imported_count !== undefined) {
                                                            message += `<span>New: ${response.data.imported_count}</span>`;
                                                        }
                                                        if (response.data.updated_count !== undefined) {
                                                            message += `<span>Updated: ${response.data.updated_count}</span>`;
                                                        }
                                                        message += '</div>';
                                                    }
                                                } else {
                                                    statusClass = 'error';
                                                    message = response.data || 'Import failed';
                                                }
                                            } else {
                                                message = response;
                                            }

                                            jQuery("#response").append(
                                                `<div class="notice notice-${statusClass} import-entry">
                                                    <p><strong>Entry ${index + 1}:</strong> ${message}</p>
                                                </div>`
                                            );
                                        } catch (e) {
                                            console.error('Error parsing response:', e);
                                            jQuery("#response").append(
                                                `<div class="notice notice-error import-entry">
                                                    <p><strong>Entry ${index + 1}:</strong> Error processing response</p>
                                                </div>`
                                            );
                                        }
                                    }
                                },
                                error: function(xhr, status, error) {
                                    jQuery("#loading").hide();
                                    console.log('Error with entry ' + (index + 1) + ' - ' + xhr.status + ': ' + xhr.statusText);
                                }
                            });
                        });
                    } else {
                        alert("The file does not contain valid data");
                    }
                }).fail(function() {
                    alert("Failed to retrieve the file. Please check the URL.");
                });
            } else {
                alert("No file URL found");
            }
        });

    });

</script>