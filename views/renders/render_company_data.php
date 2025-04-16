<?php
function render_company_data($post_id) {
    // Fetch the 'CompanyData' meta field
    $company_data = get_post_meta($post_id, 'CompanyData', true);

    if (empty($company_data)) {
        return; // Exit if no company data is available
    }

    $company_data = maybe_unserialize($company_data);

    if (!is_array($company_data)) {
        return; // Exit if the company data is not an array
    }

    echo '<div class="property-company-data">';
    echo '<h3>Company Information</h3>';

    // Check if the data is an array and contains elements
    if (isset($company_data[0]) && is_array($company_data[0])) {
        $data = $company_data[0];

        // Display Registered Name
        if (isset($data['RegisteredName']) && !empty($data['RegisteredName'])) {
            echo '<p><strong>Registered Name:</strong> ' . esc_html($data['RegisteredName']) . '</p>';
        }

        // Display Logo (if exists)
        if (isset($data['Logo']) && !empty($data['Logo'])) {
            // Assuming 'Logo' contains a URL or image path
            echo '<p><strong>Logo:</strong> <img src="' . esc_url($data['Logo']) . '" alt="Company Logo" style="max-width: 200px; height: auto;"></p>';
        } else {
            echo '<p><strong>Logo:</strong> No logo available</p>';
        }

        // Display Contact ID
        if (isset($data['ContactID']) && !empty($data['ContactID'])) {
            echo '<p><strong>Contact ID:</strong> ' . esc_html($data['ContactID']) . '</p>';
        }
    } else {
        echo '<p>No company data available.</p>';
    }

    echo '</div>';
}
