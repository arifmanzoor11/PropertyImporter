<?php
function renderSizeData($post) {
    // Retrieve and unserialize the 'Size' meta field
    $size_data = get_post_meta($post->ID, 'Size', true);
    if (!empty($size_data)) {
        $size_data = unserialize($size_data);
    } else {
        $size_data = array(); // Ensure $size_data is an array even if empty
    }
    ?>
    <h4>Size Information</h4>
    <table class="form-table">
        <tr>
            <th scope="row"><label for="Dimension">Dimension</label></th>
            <td><?php echo !empty($size_data['Dimension']['Name']) ? esc_html($size_data['Dimension']['Name']) : ''; ?></td>
        </tr>
        <tr>
            <th scope="row"><label for="MinSize">Min Size</label></th>
            <td><?php echo !empty($size_data['MinSize']) ? esc_html($size_data['MinSize']) : ''; ?></td>
        </tr>
        <tr>
            <th scope="row"><label for="MaxSize">Max Size</label></th>
            <td><?php echo !empty($size_data['MaxSize']) ? esc_html($size_data['MaxSize']) : ''; ?></td>
        </tr>
        <tr>
            <th scope="row"><label for="TotalSize">Total Size</label></th>
            <td><?php echo !empty($size_data['TotalSize']) ? esc_html($size_data['TotalSize']) : ''; ?></td>
        </tr>
        <tr>
            <th scope="row"><label for="EavesHeight">Eaves Height</label></th>
            <td><?php echo !empty($size_data['EavesHeight']) ? esc_html($size_data['EavesHeight']) : ''; ?></td>
        </tr>
        <tr>
            <th scope="row"><label for="EavesDimension">Eaves Dimension</label></th>
            <td><?php echo !empty($size_data['EavesDimension']) ? esc_html($size_data['EavesDimension']) : ''; ?></td>
        </tr>
        <tr>
            <th scope="row"><label for="ReceptionRooms">Reception Rooms</label></th>
            <td><?php echo !empty($size_data['ReceptionRooms']) ? esc_html($size_data['ReceptionRooms']) : ''; ?></td>
        </tr>
        <tr>
            <th scope="row"><label for="Bathrooms">Bathrooms</label></th>
            <td><?php echo !empty($size_data['Bathrooms']) ? esc_html($size_data['Bathrooms']) : ''; ?></td>
        </tr>
        <tr>
            <th scope="row"><label for="Parking">Parking</label></th>
            <td><?php echo !empty($size_data['Parking']['Parking']) ? esc_html($size_data['Parking']['Parking']) : ''; ?></td>
        </tr>
        <tr>
            <th scope="row"><label for="ParkingSpaces">Parking Spaces</label></th>
            <td><?php echo !empty($size_data['Parking']['Spaces']) ? esc_html($size_data['Parking']['Spaces']) : ''; ?></td>
        </tr>
    </table>
    <?php
}
