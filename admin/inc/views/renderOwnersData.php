<?php
function renderOwnersData($post) {
    // Retrieve the serialized owners data
    $owners = unserialize(get_post_meta($post->ID, 'Owners', true));

    if (!empty($owners)) {
        foreach ($owners as $index => $owner) { ?>
            <div class="owner-entry">
                <h4>Owner <?php echo ($index + 1); ?></h4>
                <?php
                foreach ($owner as $field => $value) {
                    $field_id = 'Owners[' . $index . '][' . $field . ']';
                    ?>
                    <p>
                        <label for="<?php echo $field_id; ?>"><?php echo $field; ?></label>
                        <input type="text" id="<?php echo $field_id; ?>" name="<?php echo $field_id; ?>" value="<?php echo esc_attr($value); ?>" class="widefat" />
                    </p>
                <?php } ?>
            </div>
        <?php }
    } else {
        echo '<p>No owner data available.</p>';
    }
}