<?php
// Add meta boxes to the 'property' post type
add_action('add_meta_boxes', 'add_property_meta_boxes');

function add_property_meta_boxes() {
    add_meta_box(
        'property_details',
        __('Property Details', 'textdomain'),
        'property_details_meta_box_callback',
        'property'
    );
}

function property_details_meta_box_callback($post) {
    wp_nonce_field('save_property_details', 'property_details_nonce');

    // Define fields
    $property_fields = array(
        "ID",
        "FileRef",
        "MarketStatus",
        "LastUnavailableDate",
        "LastAvailableDate",
        "DisplayUntil",
        "Website",
        "DataRoom",
        "Residential",
        "Featured"
    );

    $agent_fields = array(
        "AgentName",
        "AgentLocation",
        "AgentDealsClosed",
        "AgentTotalSales",
        "AgentCommission",
        "AgentStatus",
        "AgentRole"
    );

    $photo_field = "Photos";
    ?>

    <div id="property-details-tabs" class="property-details-tabs">
        <ul>
            <li><a href="#property-tab">Property</a></li>
            <li><a href="#agent-tab">Agent</a></li>
            <li><a href="#address-tab">Address</a></li>
            <li><a href="#owners-tab">Owners</a></li>
            <li><a href="#size-tab">Size</a></li>
            <li><a href="#systemDetailData-tab">System Details</a></li>
            <li><a href="#businessrates">Business Rates</a></li>
            <li><a href="#photos-tab">Photos</a></li>
            <li><a href="#renderTenureData-tab">Tenure</a></li>
            <li><a href="#document-media-tab">Documents</a></li>
        </ul>

        <div id="property-tab">
            <?php renderPropertyFields($post, $property_fields); ?>
        </div>

        <div id="agent-tab">
            <?php renderAgentFields($post); ?>
        </div>

        <div id="address-tab">
            <?php renderAddressField($post); ?>
        </div>

        <div id="owners-tab">
            <?php renderOwnersData($post); ?>
        </div>

        <div id="size-tab">
            <?php renderSizeData($post); ?>
        </div>
        
        <div id="systemDetailData-tab">
            <?php renderSystemDetailData($post); ?>
        </div>

        <div id="businessrates">
            <?php renderBusinessRatesData($post); ?>
        </div>

        <div id="photos-tab">
            <?php renderPhotoField($post, $photo_field); ?>
            <?php
                // Retrieve the serialized data
                $photo_urls_serialized = get_post_meta($post->ID, 'photo_urls_serialized', true);

                // Unserialize the data to get the array
                $photo_urls = unserialize($photo_urls_serialized);

                // Check if there are any photo URLs and print them
                if (!empty($photo_urls)) {
                    echo '<ul>';
                    foreach ($photo_urls as $url) {
                        echo '<li><img style="width:100%" src="' . esc_url($url) . '"></li>';
                    }
                    echo '</ul>';
                } else {
                    echo 'No photo URLs found.';
                }
                ?>
        </div>

        <div id="renderTenureData-tab">
            <?php renderTenureData($post); ?>
        </div>

        <div id="document-media-tab">
            <?php renderDocumentMediaFields($post); ?>
            <button type="button" class="button add-document-button">Add Document</button>
            <?php
                // Retrieve the serialized data
                $document_urls_serialized = get_post_meta($post->ID, 'document_urls_serialized', true);
                // Unserialize the data to get the array
                $document_urls = unserialize($document_urls_serialized);
                // Check if there are any photo URLs and print them
                if (!empty($document_urls)) {
                    echo '<ul>';
                    foreach ($document_urls as $url) {
                        echo '<br><a href="' . esc_url($url) . '">' . esc_html($url) . '</a>';
                    }
                    echo '</ul>';
                } else {
                    echo 'No photo URLs found.';
                }
                ?>

        </div>
    </div> 

    <?php
    // Add jQuery UI Tabs initialization script and Add Owner button functionality
    echo '<script>
            jQuery(document).ready(function($) {
                $("#property-details-tabs").tabs();
                $(".add-owner-button").click(function() {
                    var ownerIndex = $(".owner-entry").length;
                    var newOwnerHtml = \'<div class="owner-entry"><h4>Owner \' + (ownerIndex + 1) + \'</h4>\' +
                        \'<p><label for="Owners[\' + ownerIndex + \'][RegisteredName]">RegisteredName</label>\' +
                        \'<input type="text" id="Owners[\' + ownerIndex + \'][RegisteredName]" name="Owners[\' + ownerIndex + \'][RegisteredName]" value="" class="widefat" /></p>\' +
                        \'<p><label for="Owners[\' + ownerIndex + \'][Logo]">Logo</label>\' +
                        \'<input type="text" id="Owners[\' + ownerIndex + \'][Logo]" name="Owners[\' + ownerIndex + \'][Logo]" value="" class="widefat" /></p>\' +
                        \'<p><label for="Owners[\' + ownerIndex + \'][ContactID]">ContactID</label>\' +
                        \'<input type="text" id="Owners[\' + ownerIndex + \'][ContactID]" name="Owners[\' + ownerIndex + \'][ContactID]" value="" class="widefat" /></p>\' +
                        \'</div>\';
                    $(newOwnerHtml).insertBefore(this);
                });
            });
          </script>';
}

// Save meta box data
add_action('save_post', 'save_property_details');

function save_property_details($post_id) {
    if (!isset($_POST['property_details_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['property_details_nonce'], 'save_property_details')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = array(
        "ID",
        "FileRef",
        "MarketStatus",
        "LastUnavailableDate",
        "LastAvailableDate",
        "DisplayUntil",
        "Website",
        "DataRoom",
        "Residential",
        "PropertyTypes",
        "Tenure",
        "Address",
        "Size",
        "Featured",
        "Photos"
    );

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}


// Add custom mime types
function cc_mime_types($mimes) {
    $mimes['json'] = 'application/json';
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

