<?php
function manage_auto_import_settings() {
    // Check if the form has been submitted
    if (isset($_POST['auto_import_settings'])) {
        // Sanitize and save the input values
        $import_interval = sanitize_text_field($_POST['import_interval']);
        $client_id = sanitize_text_field($_POST['client_id']);
        $client_secret = sanitize_text_field($_POST['client_secret']);
        $url_type = sanitize_text_field($_POST['url_type']);
        $index_start = sanitize_text_field($_POST['index_start']);
        $index_end = sanitize_text_field($_POST['index_end']);
        $token_url = esc_url_raw($_POST['token_url']);
        $property_url = esc_url_raw($_POST['property_url']);

        // Save the checkbox settings
        $additional = isset($_POST['additional']) ? 'true' : 'false';
        $categories = isset($_POST['categories']) ? 'true' : 'false';
        $photos = isset($_POST['photos']) ? 'true' : 'false';
        $document_media = isset($_POST['document_media']) ? 'true' : 'false';
        $floors = isset($_POST['floors']) ? 'true' : 'false';
        $agents = isset($_POST['agents']) ? 'true' : 'false';
        $owners = isset($_POST['owners']) ? 'true' : 'false';
        $solicitors = isset($_POST['solicitors']) ? 'true' : 'false';
        $auction = isset($_POST['auction']) ? 'true' : 'false';
        $system_details = isset($_POST['system_details']) ? 'true' : 'false';
        $upload_description = isset($_POST['upload_description']) ? 'true' : 'false';

        $date_last_let_or_sold = !empty($_POST['date_last_let_or_sold']) ? $_POST['date_last_let_or_sold'] : null;
        $inactive_only = isset($_POST['inactive_only']) ? 'true' : 'false';
        $active_only = isset($_POST['active_only']) ? 'true' : 'false';
        

        // Save the settings to the options table
        update_option('auto_import_interval', $import_interval);
        update_option('auto_import_client_id', $client_id);
        update_option('auto_import_client_secret', $client_secret);
        update_option('auto_import_url_type', $url_type);
        update_option('auto_import_index_start', $index_start);
        update_option('auto_import_index_end', $index_end);
        update_option('auto_import_additional', $additional);
        update_option('auto_import_categories', $categories);
        update_option('auto_import_photos', $photos);
        update_option('auto_import_document_media', $document_media);
        update_option('auto_import_floors', $floors);
        update_option('auto_import_agents', $agents);
        update_option('auto_import_owners', $owners);
        update_option('auto_import_solicitors', $solicitors);
        update_option('auto_import_auction', $auction);
        update_option('auto_import_system_details', $system_details);
        update_option('auto_import_upload_description', $upload_description);

        update_option('auto_import_token_url', $token_url);
        update_option('auto_import_property_url', $property_url);

        // Update the options after form submission

        update_option('auto_import_date_last_let_or_sold', !empty($date_last_let_or_sold) ? $date_last_let_or_sold : null);
        update_option('auto_import_inactive_only', $inactive_only);
        update_option('auto_import_active_only', $active_only);

        echo '<div class="updated notice"><p>Settings saved successfully!</p></div>';
    } else {
        // Retrieve the current settings
        $import_interval = get_option('auto_import_interval', '');
        $client_id = get_option('auto_import_client_id', '');
        $client_secret = get_option('auto_import_client_secret', '');
        $url_type = get_option('auto_import_url_type', 'URL');
        $index_start = get_option('auto_import_index_start', '0');
        $index_end = get_option('auto_import_index_end', '100');
        $token_url = get_option('auto_import_token_url', '');
        $property_url = get_option('auto_import_property_url', '');
        $additional = get_option('auto_import_additional', 'true');
        $categories = get_option('auto_import_categories', 'true');
        $photos = get_option('auto_import_photos', 'true');
        $document_media = get_option('auto_import_document_media', 'true');
        $floors = get_option('auto_import_floors', 'true');
        $agents = get_option('auto_import_agents', 'true');
        $owners = get_option('auto_import_owners', 'true');
        $solicitors = get_option('auto_import_solicitors', 'true');
        $auction = get_option('auto_import_auction', 'true');
        $system_details = get_option('auto_import_system_details', 'true');
        $upload_description = get_option('auto_import_upload_description', 'true');

        $date_last_let_or_sold = get_option('auto_import_date_last_let_or_sold', null);
        $inactive_only = get_option('auto_import_inactive_only', false);
        $active_only = get_option('auto_import_active_only', true);
    }
    ?>

    <div class="wrap">
        <h1>Auto Import Settings</h1>

        <!-- Tab Navigation -->
        <h2 class="nav-tab-wrapper">
            <a href="#general" class="nav-tab nav-tab-active">General</a>
            <a href="#import" class="nav-tab">Import</a>
        </h2>

        <!-- General Tab Content -->
        <form method="post" action="">
            <div id="general" class="tab-content" style="display: block;">
                <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="token_url">Token URL</label></th>
                    <td>
                        <input type="url" id="token_url" name="token_url" value="<?php echo esc_attr($token_url); ?>" class="regular-text" required />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="property_url">Property URL</label></th>
                    <td>
                        <input type="url" id="property_url" name="property_url" value="<?php echo esc_attr($property_url); ?>" class="regular-text" required />
                    </td>
                </tr>
                    <tr valign="top">
                        <th scope="row"><label for="import_interval">Import Interval</label></th>
                        <td>
                            <select id="import_interval" name="import_interval" required>
                                <option value="3600" <?php selected($import_interval, '3600'); ?>>Hourly</option>
                                <option value="7200" <?php selected($import_interval, '7200'); ?>>Two Hours</option>
                                <option value="14400" <?php selected($import_interval, '14400'); ?>>Four Hours</option>
                                <option value="21600" <?php selected($import_interval, '21600'); ?>>Six Hours</option>
                                <option value="43200" <?php selected($import_interval, '43200'); ?>>Twice Daily</option>
                                <option value="86400" <?php selected($import_interval, '86400'); ?>>Daily</option>
                                <option value="604800" <?php selected($import_interval, '604800'); ?>>Weekly</option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="client_id">Client ID</label></th>
                        <td>
                            <input type="text" id="client_id" placeholder="*******************" name="client_id" value="<?php echo esc_attr($client_id); ?>" class="regular-text" required />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="client_secret">Client Secret</label></th>
                        <td>
                            <input type="password" id="client_secret" placeholder="*************" name="client_secret" value="<?php echo esc_attr($client_secret); ?>" class="regular-text" required />
                        </td>
                    </tr>
                </table>
            </div>

        <!-- Import Tab Content -->
        <div id="import" class="tab-content" style="display: none;">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="url_type">Image Size</label></th>
                    <td>
                        <select id="url_type" name="url_type" required>
                            <option value="URL" <?php selected($url_type, 'URL'); ?>>Medium</option>
                            <option value="URLFullSize" <?php selected($url_type, 'URLFullSize'); ?>>Full Size</option>
                            <option value="URLSmall" <?php selected($url_type, 'URLSmall'); ?>>Small</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="index_start">Index Start</label></th>
                    <td>
                        <input type="number" id="index_start" name="index_start" value="<?php echo esc_attr($index_start); ?>" class="regular-text" required />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="index_end">Index End</label></th>
                    <td>
                        <input type="number" id="index_end" name="index_end" value="<?php echo esc_attr($index_end); ?>" class="regular-text" />
                    <br><small>If the value is empty, all properties will be imported.</small>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Import Options</th>
                    <td>
                        <label><input type="checkbox" name="additional" <?php checked($additional, 'true'); ?>> Additional</label><br>
                        <label><input type="checkbox" name="categories" <?php checked($categories, 'true'); ?>> Categories</label><br>
                        <label><input type="checkbox" name="photos" <?php checked($photos, 'true'); ?>> Photos</label><br>
                        <label><input type="checkbox" name="document_media" <?php checked($document_media, 'true'); ?>> Document Media</label><br>
                        <label><input type="checkbox" name="floors" <?php checked($floors, 'true'); ?>> Floors</label><br>
                        <label><input type="checkbox" name="agents" <?php checked($agents, 'true'); ?>> Agents</label><br>
                        <label><input type="checkbox" name="owners" <?php checked($owners, 'true'); ?>> Owners</label><br>
                        <label><input type="checkbox" name="solicitors" <?php checked($solicitors, 'true'); ?>> Solicitors</label><br>
                        <label><input type="checkbox" name="auction" <?php checked($auction, 'true'); ?>> Auction</label><br>
                        <label><input type="checkbox" name="system_details" <?php checked($system_details, 'true'); ?>> System Details</label><br>
                        <label><input type="checkbox" name="upload_description" <?php checked($upload_description, 'true'); ?>> Upload Description</label><br>
                         <label>Date Last Let or Sold: <input type="date" name="date_last_let_or_sold" value="<?php echo esc_attr($date_last_let_or_sold); ?>"></label><br>
                        <label><input type="checkbox" name="inactive_only" <?php checked($inactive_only, 'true'); ?>> Inactive Only</label><br>
                        <label><input type="checkbox" name="active_only" <?php checked($active_only, 'true'); ?>> Active Only</label><br>

                    
                    </td>
                </tr>
            </table>
            </div>
            <?php submit_button('Save Settings', 'primary', 'auto_import_settings'); ?>
            </form>
       
    </div>

    <script>
        jQuery(document).ready(function($) {
            // Handle tab switching
            $('.nav-tab').click(function(e) {
                e.preventDefault();
                $('.nav-tab').removeClass('nav-tab-active');
                $(this).addClass('nav-tab-active');

                // Show and hide content based on clicked tab
                $('.tab-content').hide();
                var activeTab = $(this).attr('href');
                $(activeTab).show();
            });
        });
    </script>

    <?php
}
