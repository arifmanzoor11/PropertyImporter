<?php
function manage_import_page() {
    global $wpdb;

    // Start the output buffer
    ob_start(); ?>
    <div class="wrap manage-import-container">
        <h1 class="wp-heading-inline">Manage Import Data</h1>
        
        <hr class="wp-header-end">
        
        <?php 
        // Define the main table name
        $table_name = $wpdb->prefix . 'manage_import';
        $meta_table_name = $wpdb->prefix . 'import_meta';
    

        // Handle deletion of selected data and files
        if (isset($_POST['delete_selected']) && isset($_POST['selected_ids'])) {
            $selected_ids = $_POST['selected_ids'];
            $deleted_count = 0;
            
            foreach ($selected_ids as $delete_id) {
                $delete_id = intval($delete_id);

                // Get the URL of the file before deleting the database entry
                $delete_url = $wpdb->get_var($wpdb->prepare("SELECT url FROM $table_name WHERE id = %d", $delete_id));
                $upload_dir = wp_upload_dir();
                $file_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $delete_url);

                // Delete the database entry
                $deleted = $wpdb->delete($table_name, array('id' => $delete_id));

                if ($deleted) {
                    $deleted_count++;
                    // Delete the associated metadata
                    $wpdb->delete($meta_table_name, array('import_id' => $delete_id));
                    
                    // Delete the file associated with the record
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                }
            }
            
            // Show success message
            echo '<div class="notice notice-success is-dismissible"><p>' . 
                  sprintf(_n('%d item deleted successfully.', '%d items deleted successfully.', $deleted_count), $deleted_count) . 
                  '</p></div>';
        }

        // Retrieve data from the manage_import table
        $imported_data = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC", ARRAY_A);
        // Check if there's data to display
        if (!empty($imported_data)) { ?>
            <div class="tablenav top">
                <div class="alignleft actions bulkactions">
                    <form method="post" id="delete-form">
                        <input type="submit" class="button action" name="delete_selected" value="Delete Selected" onclick="return confirm('Are you sure you want to delete the selected items?');">
                    </div>
                </div>
            
            <table class="wp-list-table widefat fixed striped posts">
                <thead>
                    <tr>
                        <th scope="col" class="manage-column column-cb check-column">
                            <input type="checkbox" id="select_all">
                        </th>
                        <th scope="col" class="">ID</th>
                        <th scope="col" class="manage-column">Name</th>
                        <th scope="col" class="manage-column">URL</th>
                        <th scope="col" class="manage-column">Time</th>
                        <th scope="col" class="manage-column">Import Type</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($imported_data as $data) {
                    // Retrieve associated metadata
                    $import_id = $data['id'];
                    $meta_data = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value FROM $meta_table_name WHERE import_id = %d", $import_id), ARRAY_A);
                  
                    // Initialize meta values
                    $imported_count = '';
                    $updated_count = '';
                    $total_entries = '';
                    $import_duration = '';
                    $import_date = '';
                    $file_name = '';
                    $import_type = '';

                    // Assign meta values to variables
                    foreach ($meta_data as $meta) {
                        switch ($meta['meta_key']) {
                            case 'imported_count':
                                $imported_count = esc_html($meta['meta_value']);
                                break;
                            case 'updated_count':
                                $updated_count = esc_html($meta['meta_value']);
                                break;
                            case 'total_entries':
                                $total_entries = esc_html($meta['meta_value']);
                                break;
                            case 'import_duration':
                                $import_duration = esc_html($meta['meta_value']);
                                break;
                            case 'import_date':
                                $import_date = esc_html($meta['meta_value']);
                                break;
                            case 'file_name':
                                $file_name = esc_html($meta['meta_value']);
                                break;
                            case 'import_type':
                                $import_type = esc_html($meta['meta_value']);
                                break;
                        }
                    }

                    // Convert duration into rounded time with units
                    $duration = intval($import_duration);
                    if ($duration >= 3600) {
                        $rounded_duration = round($duration / 3600, 1) . ' hours';
                    } elseif ($duration >= 60) {
                        $rounded_duration = round($duration / 60, 1) . ' minutes';
                    } else {
                        $rounded_duration = $duration . ' seconds';
                    }

                    // Convert import date to the desired format
                    $formatted_date = date('D j M g:i A', strtotime($import_date));
                    ?>
                    <tr class="import-item">
                        <td><input type="checkbox" name="selected_ids[]" value="<?php echo esc_attr($data['id']); ?>"></td>
                        <td><?php echo esc_html($data['id']); ?></td>
                        <td><?php echo esc_html($data['name']); ?></td>
                        <td>
                            <a href="<?php echo esc_url($data['url']); ?>" target="_blank" class="import-file-link">
                                <?php echo empty($file_name) ? basename(esc_html($data['url'])) : esc_html($file_name); ?>
                            </a>
                        </td>
                        <td><?php echo esc_html($formatted_date); ?></td>
                        <td><?php echo esc_html($import_type); ?></td>
                    </tr>
                    <tr class="import-details">
                        <td colspan="6" class="import-details-content">
                            <div class="import-details-grid">
                                <div class="import-detail-item">
                                    <strong>Duration:</strong> <?php echo $rounded_duration; ?>
                                </div>
                                <?php if (!empty($imported_count)): ?>
                                <div class="import-detail-item">
                                    <strong>Uploaded Entries:</strong> <?php echo $imported_count; ?>
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($updated_count)): ?>
                                <div class="import-detail-item">
                                    <strong>Updated Entries:</strong> <?php echo $updated_count; ?>
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($total_entries)): ?>
                                <div class="import-detail-item">
                                    <strong>Total Entries:</strong> <?php echo $total_entries; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            </form>
        <?php } else { ?>
            <div class="no-items-found">
                <div class="no-items-icon">
                    <span class="dashicons dashicons-database-import"></span>
                </div>
                <h2>No import listings found</h2>
                <p>You haven't imported any data yet. Once you import data, it will appear here.</p>
            </div>
        <?php } ?>
    </div>

    <style>
    .manage-import-container {
        padding: 20px 0;
    }
    .import-details-content {
        background-color: #f9f9f9;
        padding: 12px 15px;
        border-bottom: 1px solid #e5e5e5;
    }
    .import-details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        grid-gap: 15px;
    }
    .import-detail-item {
        margin-bottom: 5px;
    }
    .import-file-link {
        display: inline-block;
        max-width: 250px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .no-items-found {
        text-align: center;
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 5px;
        padding: 40px 20px;
        margin: 20px 0;
    }
    .no-items-icon {
        margin-bottom: 20px;
    }
    .no-items-icon .dashicons {
        font-size: 50px;
        width: 50px;
        height: 50px;
        color: #999;
    }
    .no-items-found h2 {
        margin-bottom: 15px;
        color: #23282d;
    }
    .no-items-found p {
        color: #777;
        font-size: 14px;
    }
    </style>

    <script>
    jQuery(document).ready(function($) {
        // Handle "Select All" functionality
        $("#select_all").on("click", function() {
            $("input[name='selected_ids[]']").prop("checked", $(this).prop("checked"));
        });
        
        // Update "Select All" checkbox when individual checkboxes change
        $(document).on("click", "input[name='selected_ids[]']", function() {
            if($("input[name='selected_ids[]']").length === $("input[name='selected_ids[]']:checked").length) {
                $("#select_all").prop("checked", true);
            } else {
                $("#select_all").prop("checked", false);
            }
        });
    });
    </script>
    <?php
    // Flush the output buffer and display the content
    ob_end_flush();
}
?>