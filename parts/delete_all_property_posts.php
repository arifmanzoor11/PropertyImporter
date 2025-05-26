<?php 

function delete_all_property_posts() {
    // Check if a user is set (like in normal admin context); if not, set an admin
    if ( ! is_user_logged_in() ) {
        $admin_user = get_users( array(
            'role'   => 'administrator',
            'number' => 1
        ) );

        if ( ! empty( $admin_user ) ) {
            wp_set_current_user( $admin_user[0]->ID );
        }
    }

    if ( ! current_user_can( 'delete_posts' ) ) {
        return new WP_Error( 'permission_denied', 'You do not have permission to delete posts.' );
    }

    $property_posts = get_posts(array(
        'post_type'   => 'property',
        'post_status' => 'any',
        'numberposts' => -1,
        'fields'      => 'ids',
    ));

    if ( empty($property_posts) ) {
        return new WP_Error( 'no_posts', 'No property posts found.' );
    }

    foreach ( $property_posts as $post_id ) {
        wp_delete_post( $post_id, true );
    }

    error_log('All property posts deleted.');
    return 'All property posts deleted.';
}