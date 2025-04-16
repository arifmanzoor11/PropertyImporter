<?php
function render_system_detail_data($post_id) {
    // Fetch the 'SystemDetail' meta field
    $system_detail = get_post_meta($post_id, 'SystemDetail', true);

    if (empty($system_detail)) {
        return; // Exit if no system detail data is available
    }

    $system_detail = maybe_unserialize($system_detail);

    if (!is_array($system_detail)) {
        return; // Exit if the system detail data is not an array
    }

    echo '<div class="property-system-detail-data">';
    echo '<h3>System Details</h3>';

    // Display Account Managers
    if (isset($system_detail['AccountManagers']) && is_array($system_detail['AccountManagers'])) {
        echo '<h4>Account Managers</h4>';
        echo '<ul>';
        foreach ($system_detail['AccountManagers'] as $manager) {
            if (is_array($manager)) {
                $name = isset($manager['Name']) ? esc_html($manager['Name']) : 'N/A';
                $email = isset($manager['Email']) ? esc_html($manager['Email']) : 'N/A';
                $telephone = isset($manager['Telephone']) ? esc_html($manager['Telephone']) : 'N/A';
                echo '<li><strong>Name:</strong> ' . $name . '<br>';
                echo '<strong>Email:</strong> <a href="mailto:' . $email . '">' . $email . '</a><br>';
                echo '<strong>Telephone:</strong> ' . $telephone . '</li>';
            }
        }
        echo '</ul>';
    }

    // Display Partner
    if (isset($system_detail['Partner']) && is_array($system_detail['Partner'])) {
        $partner_id = isset($system_detail['Partner']['ID']) ? esc_html($system_detail['Partner']['ID']) : 'N/A';
        $partner_name = isset($system_detail['Partner']['Name']) ? esc_html($system_detail['Partner']['Name']) : 'N/A';
        echo '<p><strong>Partner ID:</strong> ' . $partner_id . '</p>';
        echo '<p><strong>Partner Name:</strong> ' . $partner_name . '</p>';
    }

    // Display Dates
    $date_fields = [
        'DateRegistered' => 'Date Registered',
        'DateUpdated' => 'Date Updated',
    ];

    foreach ($date_fields as $key => $label) {
        if (isset($system_detail[$key]) && !empty($system_detail[$key])) {
            $date = date('Y-m-d', strtotime($system_detail[$key])); // Format date
            echo '<p><strong>' . esc_html($label) . ':</strong> ' . esc_html($date) . '</p>';
        }
    }

    echo '</div>';
}
