<?php
function manage_import_page() {
    global $wpdb;

    // Start the output buffer
    ob_start(); ?>
    <br>
    <h1>Manage Import Data</h1>
    <?php 
    // Define the main table name
    $table_name = $wpdb->prefix . 'manage_import';
    $meta_table_name = $wpdb->prefix . 'import_meta';

    // Handle deletion of selected data and files
    if (isset($_POST['delete_selected']) && isset($_POST['selected_ids'])) {
        $selected_ids = $_POST['selected_ids'];
        foreach ($selected_ids as $delete_id) {
            $delete_id = intval($delete_id);

            // Get the URL of the file before deleting the database entry
            $delete_url = $wpdb->get_var($wpdb->prepare("SELECT url FROM $table_name WHERE id = %d", $delete_id));
            $upload_dir = wp_upload_dir();
            $file_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $delete_url);

            // Delete the database entry
            $deleted = $wpdb->delete($table_name, array('id' => $delete_id));

            if ($deleted) {
                // Delete the associated metadata
                $wpdb->delete($meta_table_name, array('import_id' => $delete_id));
                
                // Delete the file associated with the record
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
        }
        
        // Refresh the page to update the list
        echo '<meta http-equiv="refresh" content="0">';
        echo '<p>Selected data and associated files deleted successfully.</p>';
    }

    // Retrieve data from the manage_import table
    $imported_data = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

    // Check if there's data to display
    if (!empty($imported_data)) {
        echo "<h2>Imported Data</h2>";

        echo '<form method="post">';
        echo '<input type="submit" class="button button-primary" name="delete_selected" value="Delete Selected">';
        echo '<br>';
        echo '<br>';
        echo '<table class="import-table">';
        echo '<thead>';
        echo '<tr>';
        echo '<th><input type="checkbox" id="select_all"></th>';
        echo '<th>ID</th>';
        echo '<th>Name</th>';
        echo '<th>URL</th>';
        echo '<th>Time</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

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

            echo '<tr>';
            echo '<td><input type="checkbox" name="selected_ids[]" value="' . esc_attr($data['id']) . '"></td>';
            echo '<td>' . esc_html($data['id']) . '</td>';
            echo '<td>' . esc_html($data['name']) . '</td>';
            echo '<td><a href="' . esc_url($data['url']) . '" target="_blank">' . esc_html($data['url']) . '</a></td>';
            echo '<td>' . esc_html($formatted_date) . '</td>';
            echo '</tr>';

            // Open a new row for the additional data
            echo '<tr>';
            echo '<td colspan="2"> </td>';
            echo '<td> Duration: ' . $rounded_duration .'</td>';
            echo '<td>';
            // Check if any of the values exist before displaying the data
            if (!empty($imported_count) || !empty($updated_count) || !empty($total_entries)) {
                // Display "Imported Entries" if it exists
                if (!empty($imported_count)) {
                    echo 'Uploaded Entries: ' . $imported_count;
                }
            
                // Display "Updated Entries" if it exists, with a preceding comma if "Imported Entries" was also displayed
                if (!empty($updated_count)) {
                    if (!empty($imported_count)) {
                        echo ', ';
                    }
                    echo 'Updated Entries: ' . $updated_count;
                }
            
                // Display "Total Entries" if it exists, with a preceding comma if either of the previous fields were displayed
                if (!empty($total_entries)) {
                    if (!empty($imported_count) || !empty($updated_count)) {
                        echo ', ';
                    }
                    echo 'Total Entries: ' . $total_entries;
                }
            }
            echo '</td>';
            
            echo '<td> ' .  esc_html($import_type)  . '</td>';
            echo '</tr>';
        }            

        echo '</tbody>';
        echo '</table>';
        echo '</form>';
    } else {
        echo '<p>No data found.</p>';
    }

    // JavaScript to handle "Select All" functionality
    echo '<script>
        document.getElementById("select_all").addEventListener("click", function() {
            var checkboxes = document.querySelectorAll("input[name=\'selected_ids[]\']");
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        });
    </script>';

    // Flush the output buffer and display the content
    ob_end_flush();
}
?>
