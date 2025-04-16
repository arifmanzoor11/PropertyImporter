<?php
// Step 1: Handle File Upload
add_action('wp_ajax_nopriv_upload_properties_file', 'upload_properties_file');
add_action('wp_ajax_upload_properties_file', 'upload_properties_file');


function upload_properties_file() {
    global $wpdb;

    // Ensure the user has the correct permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'Permission denied.'));
    }

    // Check if a file was uploaded manually
    if (!empty($_FILES['properties_file']['tmp_name'])) {
        $uploaded_file = $_FILES['properties_file']['tmp_name'];
        $upload_dir = wp_upload_dir(); // Get the upload directory

        // Define the folder where you want to store the file
        $folder_name = 'properties_uploads';
        $folder_path = $upload_dir['basedir'] . '/' . $folder_name;

        // Create the folder if it doesn't exist
        if (!file_exists($folder_path)) {
            mkdir($folder_path, 0755, true);
        }

        // Generate a unique file name using date, time, and seconds
        $file_name = pathinfo($_FILES['properties_file']['name'], PATHINFO_FILENAME);
        $file_extension = pathinfo($_FILES['properties_file']['name'], PATHINFO_EXTENSION);
        $unique_name = $file_name . '-' . date('Ymd-His') . '.' . $file_extension;

        // Set the target file path with the unique name
        $target_file = $folder_path . '/' . $unique_name;
        
        // Move the uploaded file to the target directory
        if (move_uploaded_file($uploaded_file, $target_file)) {
            $file_url = $upload_dir['baseurl'] . '/' . $folder_name . '/' . $unique_name;

            // Insert data into the manage_import table
            $table_name = $wpdb->prefix . 'manage_import';
            $data = array(
                'name' => $unique_name,
                'url' => $file_url,
            );
            $wpdb->insert($table_name, $data);
            $my_id = $wpdb->insert_id;

            // global $wpdb;
            // Define the table name
            $table_name = $wpdb->prefix . 'import_meta';
            
            // Prepare the data for each insert
            $data = array(
                array(
                    'import_id'   => $my_id,
                    'meta_key'    => 'import_type',
                    'meta_value'  =>  'Manual Imported',
                ),
                array(
                    'import_id'   => $my_id,
                    'meta_key'    => 'import_date',
                    'meta_value'  => current_time('mysql'),
                ),
                
            );
            
            // Insert each row into the database
            foreach ($data as $row) {
                $wpdb->insert($table_name, $row);
            }

            // Get all the output and send it as part of the success response
            $output = ob_get_clean();
            // Send success response
            wp_send_json_success(array('file_url' => $file_url, 'import_id' => $my_id));
        } else {
            wp_send_json_error(array('message' => 'File upload failed.'));
        }
    } else {
        wp_send_json_error(array('message' => 'No file uploaded.'));
    }
}
// function upload_properties_file() {
//     global $wpdb;

//     // Ensure the user has the correct permissions
//     if ( ! current_user_can( 'manage_options' ) ) {
//         wp_send_json_error(array('message' => 'Permission denied.'));
//     }

//     // Check if a file was uploaded
//     if (!empty($_FILES['properties_file']['tmp_name'])) {
//         $uploaded_file = $_FILES['properties_file']['tmp_name'];
//         $upload_dir = wp_upload_dir(); // Get the upload directory

//         // Define the folder where you want to store the file
//         $folder_name = 'properties_uploads';
//         $folder_path = $upload_dir['basedir'] . '/' . $folder_name;

//         // Create the folder if it doesn't exist
//         if (!file_exists($folder_path)) {
//             mkdir($folder_path, 0755, true);
//         }

//         // Generate a unique file name using date, time, and seconds
//         $file_name = pathinfo($_FILES['properties_file']['name'], PATHINFO_FILENAME);
//         $file_extension = pathinfo($_FILES['properties_file']['name'], PATHINFO_EXTENSION);
//         $unique_name = $file_name . '-' . date('Ymd-His') . '.' . $file_extension;

//         // Set the target file path with the unique name
//         $target_file = $folder_path . '/' . $unique_name;
        
//         // Move the uploaded file to the target directory
//         if (move_uploaded_file($uploaded_file, $target_file)) {
//             $file_url = $upload_dir['baseurl'] . '/' . $folder_name . '/' . $unique_name;

//             // Insert data into the manage_import table
//             $table_name = $wpdb->prefix . 'manage_import';
//             $data = array(
//                 'name' => $unique_name,
//                 'url' => $file_url,
//             );
//             $wpdb->insert($table_name, $data);
//             $my_id = $wpdb->insert_id;

//             // Send success response
//             wp_send_json_success(array('file_url' => $file_url, 'import_id' => $my_id));

//         } else {
//             wp_send_json_error(array('message' => 'File upload failed.'));
//         }
//     } else {
//         wp_send_json_error(array('message' => 'No file uploaded.'));
//     }
// }



