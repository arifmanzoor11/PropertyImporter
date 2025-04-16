<?php

function renderAddressField($post) {
    // Get the serialized address data from the post meta
    $serialized_address = get_post_meta($post->ID, 'Address', true);

    // Unserialize the address data
    $address_data = maybe_unserialize($serialized_address);

    // Check if address data exists
    if (!empty($address_data)) {
        ?>
        <h4>Address</h4>
        <table class="form-table">
            <tr>
                <th>Level 1</th>
                <td><?php echo esc_html($address_data['Level1']['Name']); ?></td>
            </tr>
            <tr>
                <th>Level 2</th>
                <td><?php echo esc_html($address_data['Level2']['Name']); ?></td>
            </tr>
            <tr>
                <th>Country</th>
                <td><?php echo esc_html($address_data['Country']['Name']); ?></td>
            </tr>
            <tr>
                <th>UPRN</th>
                <td><?php echo esc_html($address_data['UPRN']); ?></td>
            </tr>
            <tr>
                <th>Building Name</th>
                <td><?php echo esc_html($address_data['BuildingName']); ?></td>
            </tr>
            <tr>
                <th>Secondary Name</th>
                <td><?php echo esc_html($address_data['SecondaryName']); ?></td>
            </tr>
            <tr>
                <th>Street</th>
                <td><?php echo esc_html($address_data['Street']); ?></td>
            </tr>
            <tr>
                <th>District</th>
                <td><?php echo esc_html($address_data['District']); ?></td>
            </tr>
            <tr>
                <th>Town</th>
                <td><?php echo esc_html($address_data['Town']); ?></td>
            </tr>
            <tr>
                <th>County</th>
                <td><?php echo esc_html($address_data['County']); ?></td>
            </tr>
            <tr>
                <th>Postcode</th>
                <td><?php echo esc_html($address_data['Postcode']); ?></td>
            </tr>
            <tr>
                <th>Display Address</th>
                <td><?php echo esc_html($address_data['DisplayAddress']); ?></td>
            </tr>
            <tr>
                <th>Longitude</th>
                <td><?php echo esc_html($address_data['Longitude']); ?></td>
            </tr>
            <tr>
                <th>Latitude</th>
                <td><?php echo esc_html($address_data['Latitude']); ?></td>
            </tr>
            <tr>
                <th>What3Words</th>
                <td><?php echo esc_html($address_data['What3Words']); ?></td>
            </tr>
        </table>
        <?php
    } else {
        // If no address data is found
        echo '<p>No address data available.</p>';
    }
}

?>
