<?php

function renderAgentFields($post) {
    // Retrieve and unserialize the agent data from the post meta
    $serialized_agents = get_post_meta($post->ID, 'Agent_Data', true);
    $agents = unserialize($serialized_agents);

    // Check if agents data is valid and is an array
    if (!is_array($agents)) {
        $agents = array();
    }

    // Display existing agents
    foreach ($agents as $index => $agent) {
        ?>
        <h3><?php echo sprintf(__('Agent %d', 'textdomain'), $index + 1); ?></h3>
        <table class="form-table">
            <?php
            // Define all the meta fields related to agents
            $fields = array(
                'RegisteredName' => 'Registered Name',
                'Contact' => 'Contact',
                'Telephone' => 'Telephone',
                'Mobile' => 'Mobile',
                'Email' => 'Email',
                'Logo' => 'Logo',
                'Main' => 'Main',
                'Photo' => 'Photo',
                'Position' => 'Position',
                'Department' => 'Department',
                'Facebook' => 'Facebook',
                'Twitter' => 'Twitter',
                'LinkedIn' => 'LinkedIn',
                'YouTube' => 'YouTube',
                'GooglePlus' => 'GooglePlus'
            );

            // Loop through each field and render the input field
            foreach ($fields as $field_key => $field_label) {
                // Use the agent's data or default to empty if not set
                $value = isset($agent[$field_key]) ? $agent[$field_key] : '';
                ?>
                <tr>
                    <th scope="row">
                        <label for="<?php echo esc_attr("agent_{$index}_{$field_key}"); ?>"><?php echo esc_html($field_label); ?></label>
                    </th>
                    <td>
                        <input type="text" id="<?php echo esc_attr("agent_{$index}_{$field_key}"); ?>" name="agent_data[<?php echo esc_attr($index); ?>][<?php echo esc_attr($field_key); ?>]" value="<?php echo esc_attr($value); ?>" class="widefat" />
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
    }

    // Display a form to add a new agent
    ?>
    <h3><?php _e('Add New Agent', 'textdomain'); ?></h3>
    <table class="form-table">
        <?php
        // Render fields for a new agent
        foreach ($fields as $field_key => $field_label) {
            ?>
            <tr>
                <th scope="row">
                    <label for="<?php echo esc_attr("new_agent_{$field_key}"); ?>"><?php echo esc_html($field_label); ?></label>
                </th>
                <td>
                    <input type="text" id="<?php echo esc_attr("new_agent_{$field_key}"); ?>" name="new_agent[<?php echo esc_attr($field_key); ?>]" value="" class="widefat" />
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <p>
        <button type="button" id="add-agent-button" class="button button-primary"><?php _e('Add Agent', 'textdomain'); ?></button>
    </p>

    <script type="text/javascript">
        document.getElementById('add-agent-button').addEventListener('click', function() {
            // Collect new agent data
            var newAgent = {};
            <?php foreach ($fields as $field_key => $field_label) { ?>
                newAgent['<?php echo $field_key; ?>'] = document.getElementById('new_agent_<?php echo $field_key; ?>').value;
            <?php } ?>

            // Get existing agents from the form
            var agentData = document.querySelectorAll('input[name^="agent_data["]');
            var agents = {};
            var agentIndex = -1;
            agentData.forEach(function(input) {
                var match = input.name.match(/agent_data\[(\d+)\]/);
                if (match) {
                    agentIndex = match[1];
                    if (!agents[agentIndex]) {
                        agents[agentIndex] = {};
                    }
                    agents[agentIndex][input.name.match(/\[(.+?)\]$/)[1]] = input.value;
                }
            });

            // Add new agent
            agents.push(newAgent);

            // Serialize data and set back to hidden field (could use AJAX or direct form submit here)
            console.log('Updated agents:', agents);
            alert('New agent added (check console for details)');
        });
    </script>
    <?php
}

?>
