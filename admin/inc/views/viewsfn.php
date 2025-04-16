<?php
function renderPropertyFields($post, $fields) {

// Retrieve and unserialize the BulletsPoints meta data
$BulletsPoints = get_post_meta($post->ID, 'Bullets', true);
$BulletsPoints = !empty($BulletsPoints) ? unserialize($BulletsPoints) : [];

// Render Bullets Points section
?>
<div>
<h4>Bullets Points</h4>

    <?php if (!empty($BulletsPoints)): ?>
        <?php foreach ($BulletsPoints as $manager): ?>
            <?php if (!empty($manager['Description'])): ?>
                <p><?php echo esc_html($manager['Description']); ?>: <?php echo esc_html($manager['BulletPoint']); ?></p>
            <?php endif; ?>
            <?php if (!empty($manager['Name'])): ?>
                <p><strong>Name:</strong> <?php echo esc_html($manager['BulletPoint']); ?></p>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No account managers available.</p>
    <?php endif; ?>
</div>

<?php

    foreach ($fields as $field) {
        $value = get_post_meta($post->ID, $field, true);
       
        if (in_array($field, array("LastUnavailableDate", "LastAvailableDate",)) && !empty($value)) {
            $date = new DateTime($value);
            $value = $date->format('Y-m-d');
        }  ?>
        <p>
            <label for="<?php echo $field; ?>"><?php echo $field; ?></label>
            <?php if (in_array($field, array("LastUnavailableDate", "LastAvailableDate", "DisplayUntil"))) { ?>
                <input type="date" id="<?php echo $field; ?>" name="<?php echo $field; ?>" value="<?php echo esc_attr($value); ?>" class="widefat" />
            <?php } elseif ($field === "Photos") { ?>
                <div class="custom-photo-gallery">
                    <?php
                    if (!empty($value)) {
                        $photos = explode(', ', $value);
                        foreach ($photos as $photo_id) {
                            $photo_url = wp_get_attachment_url($photo_id);
                            ?>
                            <div class="custom-photo-item">
                                <img src="<?php echo esc_url($photo_url); ?>" style="max-width:100%;height:auto;">
                            </div>
                            <?php
                        }
                    }
                    ?>
                    <input type="hidden" id="<?php echo $field; ?>" name="<?php echo $field; ?>" value="<?php echo esc_attr($value); ?>">
                    <button type="button" class="button custom-upload-button" data-field="<?php echo $field; ?>">Add Photos</button>
                </div>
            <?php } else { ?>
                <input type="text" id="<?php echo $field; ?>" name="<?php echo $field; ?>" value="<?php echo esc_attr($value); ?>" class="widefat" />
            <?php } ?>
        </p>
        <?php
    }
}


// Function to render photo field
function renderPhotoField($post, $field) {
    // Get the first image and additional images (serialized URLs)
    $first_image = get_post_meta($post->ID, 'first_image', true);
    $additional_images = get_post_meta($post->ID, 'photo_urls_serialized', true);
    
    // If additional images exist, unserialize them
    $additional_images_array = !empty($additional_images) ? unserialize($additional_images) : array();
    ?>
    <div class="custom-photo-gallery">
        <?php
        // Display the first image
        if (!empty($first_image)) {
            // Check if it's an attachment ID or a URL
            if (is_numeric($first_image)) {
                $first_image_url = wp_get_attachment_url($first_image);
            } else {
                $first_image_url = $first_image; // It's a URL
            }
            echo '<div class="custom-photo-item">';
            echo '<img src="' . esc_url($first_image_url) . '" style="max-width:100%;height:auto;">';
            echo '<p>First Image</p>';
            echo '</div>';
        }

        // Display additional images
        if (!empty($additional_images_array)) {
            foreach ($additional_images_array as $photo_url) {
                echo '<div class="custom-photo-item">';
                echo '<img src="' . esc_url($photo_url) . '" style="max-width:100%;height:auto;">';
                echo '</div>';
            }
        }
        ?>
    </div>
    <?php
}


function renderDocumentMediaFields($post) {
    $document_media = get_post_meta($post->ID, 'DocumentMedia');
    
    if (empty($document_media)) {
        $document_media = array();
    }

    foreach ($document_media as $outer_index => $attachment_ids_array) {
        // Since $attachment_ids_array is itself an array, loop through it
        foreach ($attachment_ids_array as $index => $attachment_id) {
            $attachment_url = wp_get_attachment_url($attachment_id);
            $attachment_title = get_the_title($attachment_id);

            if ($attachment_url) {
                ?>
                <div class="document-media-entry">
                    <h4>Document <?php echo ($outer_index + 1); ?></h4>
                    <p>
                        <label for="DocumentMedia[<?php echo $outer_index; ?>][<?php echo $index; ?>][Title]">Title</label>
                        <input type="text" id="DocumentMedia[<?php echo $outer_index; ?>][<?php echo $index; ?>][Title]" name="DocumentMedia[<?php echo $outer_index; ?>][<?php echo $index; ?>][Title]" value="<?php echo esc_attr($attachment_title); ?>" class="widefat" readonly />
                    </p>
                    <p>
                        <label for="DocumentMedia[<?php echo $outer_index; ?>][<?php echo $index; ?>][URL]">File</label>
                        <input type="text" id="DocumentMedia[<?php echo $outer_index; ?>][<?php echo $index; ?>][URL]" name="DocumentMedia[<?php echo $outer_index; ?>][<?php echo $index; ?>][URL]" value="<?php echo esc_attr($attachment_url); ?>" class="widefat" readonly />
                    </p>
                    <p>
                        <a href="<?php echo esc_url($attachment_url); ?>" target="_blank">View File</a>
                    </p>
                </div>
                <?php
            }
        }
    }
}

