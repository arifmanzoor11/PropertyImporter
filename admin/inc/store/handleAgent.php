<?php

function handleAgentData($post_id, $property_data) {
    // Clear existing 'Agent' meta field to avoid duplication
    delete_post_meta($post_id, 'Agent_Data');

    // Initialize an array to store all agent data
    $agents = array();

    // Loop through each agent and add their data to the array
    foreach ($property_data['Agents'] as $agent) {
        // Collect agent data
        $agent_data = array(
            'RegisteredName' => !empty($agent['RegisteredName']) ? $agent['RegisteredName'] : '',
            'Contact' => !empty($agent['Contact']) ? $agent['Contact'] : '',
            'Telephone' => !empty($agent['Telephone']) ? $agent['Telephone'] : '',
            'Mobile' => !empty($agent['Mobile']) ? $agent['Mobile'] : '',
            'Email' => !empty($agent['Email']) ? $agent['Email'] : '',
            'Logo' => !empty($agent['Logo']) ? $agent['Logo'] : '',
            'Main' => isset($agent['Main']) ? ($agent['Main'] ? '1' : '0') : '0',
            'Photo' => !empty($agent['Photo']) ? $agent['Photo'] : '',
            'Position' => !empty($agent['Position']) ? $agent['Position'] : '',
            'Department' => !empty($agent['Department']) ? $agent['Department'] : '',
            'Facebook' => !empty($agent['Facebook']) ? $agent['Facebook'] : '',
            'Twitter' => !empty($agent['Twitter']) ? $agent['Twitter'] : '',
            'LinkedIn' => !empty($agent['LinkedIn']) ? $agent['LinkedIn'] : '',
            'YouTube' => !empty($agent['YouTube']) ? $agent['YouTube'] : '',
            'GooglePlus' => !empty($agent['GooglePlus']) ? $agent['GooglePlus'] : ''
        );

        // Add agent data to the array
        $agents[] = $agent_data;
    }

    // Serialize the agents array
    $serialized_agents = serialize($agents);

    // Store the serialized data in a single meta field
    add_post_meta($post_id, 'Agent_Data', $serialized_agents);
}

?>
