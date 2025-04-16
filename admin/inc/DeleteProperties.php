<?php 

// Function to display the Delete Properties page
function delete_properties_page() {
    $deleted_items = array();
    if ( isset($_POST['delete_properties']) && check_admin_referer('delete_properties_nonce') ) {
        $deleted_items = delete_all_custom_posts();
    } ?>
    <div class="wrap">
        <h1>Delete Properties</h1>
        <form method="post" id="delete-properties-form">
            <?php wp_nonce_field('delete_properties_nonce'); ?>
            <p>
                <input type="submit" name="delete_properties" class="button button-primary" value="Delete All Properties" onclick="showLoader(); return confirm('Are you sure you want to delete all properties? This action cannot be undone.');">
            </p>
            <div id="loader" style="display: none;">
                <p>Please wait, we are deleting the properties...</p>
                <img src="<?php echo admin_url('images/spinner.gif'); ?>" alt="Loading...">
            </div>
        </form>
        <?php if (!empty($deleted_items)): ?>
            <h2>Deleted Items</h2>
            <ul>
                <?php foreach ($deleted_items as $item_title): ?>
                    <li><?php echo esc_html($item_title); ?> has been deleted.</li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    
    <script type="text/javascript">
    function showLoader() {
        document.getElementById('loader').style.display = 'block';
    }
    </script>
    <?php
}

// Function to delete all properties (custom post type) and return deleted items
function delete_all_custom_posts() {
    // Define the custom post type
    $custom_post_type = 'property'; // Replace with your custom post type

    // Get all posts of the custom post type
    $args = array(
        'post_type' => $custom_post_type,
        'post_status' => 'any',
        'numberposts' => -1,
    );

    $all_posts = get_posts($args);
    $deleted_items = array();

    // Loop through each post and delete it along with its meta and attachments
    foreach ($all_posts as $post) {
        // Get the post title before deletion
        $post_title = get_the_title($post->ID);

        // Delete attachments
        $attachments = get_attached_media('', $post->ID);
        foreach ($attachments as $attachment) {
            wp_delete_attachment($attachment->ID, true);
        }

        // Delete post meta
        $post_metas = get_post_meta($post->ID);
        foreach ($post_metas as $meta_key => $meta_value) {
            delete_post_meta($post->ID, $meta_key);
        }

        // Delete the post
        wp_delete_post($post->ID, true); // true to force delete (bypass trash)
        $deleted_items[] = $post_title; // Collect the title of the deleted item
    }

    return $deleted_items;
}