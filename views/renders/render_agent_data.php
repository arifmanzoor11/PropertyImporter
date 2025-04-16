<?php
function render_agent_data($post_id) {
    // Fetch the 'Agent_Data' meta field
    $agent_data = get_post_meta($post_id, 'Agent_Data', true);

    if (empty($agent_data)) {
        return; // Exit if no agent data is available
    }

    $agent_data = maybe_unserialize($agent_data);

    if (!is_array($agent_data)) {
        return; // Exit if the agent data is not an array
    }

    echo '<div class="property-agent-data">';
    echo '<h3>Agent Information</h3>';

    // Define fields to display
    $fields = [
        'RegisteredName' => 'Registered Name',
        'Contact' => 'Contact',
        'Position' => 'Position',
        'Department' => 'Department',
    ];

    foreach ($agent_data as $agent) {
        foreach ($fields as $key => $label) {
            if (!empty($agent[$key])) {
                echo '<p><strong>' . esc_html($label) . ':</strong> ' . esc_html($agent[$key]) . '</p>';
            }
        }
    }

     // Define Email
     $fields = [
        'Email' => 'Email',
    ];

    foreach ($agent_data as $agent) {
        foreach ($fields as $key => $label) {
            if (!empty($agent[$key])) {
                echo '<p><strong>' . esc_html($label) . ':</strong> <a href="mailto:' . esc_html($agent[$key]) . '">' . esc_html($agent[$key]) . '</a></p>';
            }
        }
    }

      // Define Telephone
      $fields = [
        'Telephone' => 'Telephone',
        'Mobile' => 'Mobile',
    ];

    foreach ($agent_data as $agent) {
        foreach ($fields as $key => $label) {
            if (!empty($agent[$key])) {
                echo '<p><strong>' . esc_html($label) . ':</strong> <a href="tel:' . esc_html($agent[$key]) . '">' . esc_html($agent[$key]) . '</a></p>';
            }
        }
    }
    

    echo '</div>';
}
