<?php   

// Hook into WordPress init to register the custom post type and taxonomy
add_action( 'init', 'register_properties_post_type' );
function register_properties_post_type() {
    // Register the custom post type
    register_post_type( 'property', array(
        'labels' => array(
            'name' => __( 'Properties' ),
            'singular_name' => __( 'Property' ),
            'featured_image'           => __( 'Featured Image', 'TEXTDOMAINHERE' ),
            'set_featured_image'       => __( 'Set featured image', 'TEXTDOMAINHERE' ),
            'remove_featured_image'    => __( 'Remove featured image', 'TEXTDOMAINHERE' ),
            'use_featured_image'       => __( 'Use as featured image', 'TEXTDOMAINHERE' ),
        ),
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields' ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 85,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
        'show_in_rest'        => true,
        'menu_icon' => 'dashicons-building',
          
        // This is where we add taxonomies to our CPT
        'taxonomies'         => array( 'post_tag', 'property_type' ), // Added 'property_type' taxonomy
        'supports'           => array( 'title', 'editor', 'custom-fields', 'thumbnail' ) // Added 'thumbnail' support
    ));

    // Register the custom taxonomy 'property_type'
    register_taxonomy( 'property_type', 'property', array(
        'labels' => array(
            'name'              => __( 'Property Types' ),
            'singular_name'     => __( 'Property Type' ),
            'search_items'      => __( 'Search Property Types' ),
            'all_items'         => __( 'All Property Types' ),
            'parent_item'       => __( 'Parent Property Type' ),
            'parent_item_colon' => __( 'Parent Property Type:' ),
            'edit_item'         => __( 'Edit Property Type' ),
            'update_item'       => __( 'Update Property Type' ),
            'add_new_item'      => __( 'Add New Property Type' ),
            'new_item_name'     => __( 'New Property Type Name' ),
            'menu_name'         => __( 'Property Types' ),
        ),
        'hierarchical'      => true, // Set to true for a category-like structure
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'property-type' ),
    ));

     // Register the custom taxonomy 'property_type'
     register_taxonomy( 'location', 'property', array(
        'labels' => array(
            'name'              => __( 'Location' ),
            'singular_name'     => __( 'Location' ),
            'search_items'      => __( 'Search Location' ),
            'all_items'         => __( 'All Location' ),
            'parent_item'       => __( 'Parent Location' ),
            'parent_item_colon' => __( 'Parent Location:' ),
            'edit_item'         => __( 'Edit Location' ),
            'update_item'       => __( 'Update Location' ),
            'add_new_item'      => __( 'Add New Location' ),
            'new_item_name'     => __( 'New Location Name' ),
            'menu_name'         => __( 'Locations' ),
        ),
        'hierarchical'      => true, // Set to true for a category-like structure
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'location' ),
    ));

 // Register a different custom taxonomy 'categories'
 register_taxonomy( 'tenure', 'property', array(
    'labels' => array(
        'name'              => __( 'Tenure' ),
        'singular_name'     => __( 'Tenure' ),
        'search_items'      => __( 'Search Tenure' ),
        'all_items'         => __( 'All Tenure' ),
        'parent_item'       => __( 'Parent Tenure' ),
        'parent_item_colon' => __( 'Parent Tenure:' ),
        'edit_item'         => __( 'Edit Tenure' ),
        'update_item'       => __( 'Update Tenure' ),
        'add_new_item'      => __( 'Add New Tenure' ),
        'new_item_name'     => __( 'New Tenure Name' ),
        'menu_name'         => __( 'Tenures' ),
    ),
    'hierarchical'      => true, // Set to true for a category-like structure
    'public'            => true,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'tenure' ),
));


    // Register a different custom taxonomy 'categories'
    register_taxonomy( 'categories', 'property', array(
        'labels' => array(
            'name'              => __( 'Categories' ),
            'singular_name'     => __( 'Category' ),
            'search_items'      => __( 'Search Categories' ),
            'all_items'         => __( 'All Categories' ),
            'parent_item'       => __( 'Parent Category' ),
            'parent_item_colon' => __( 'Parent Category:' ),
            'edit_item'         => __( 'Edit Category' ),
            'update_item'       => __( 'Update Category' ),
            'add_new_item'      => __( 'Add New Category' ),
            'new_item_name'     => __( 'New Category Name' ),
            'menu_name'         => __( 'Categories' ),
        ),
        'hierarchical'      => true, // Set to true for a category-like structure
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'categories' ),
    ));

    // Register a different custom taxonomy 'categories'
    register_taxonomy( 'MarketStatus', 'property', array(
        'labels' => array(
            'name'              => __( 'Market Status' ),
            'singular_name'     => __( 'Market Status' ),
            'search_items'      => __( 'Search MarketStatus' ),
            'all_items'         => __( 'All MarketStatus' ),
            'parent_item'       => __( 'Parent MarketStatus' ),
            'parent_item_colon' => __( 'Parent MarketStatus:' ),
            'edit_item'         => __( 'Edit MarketStatus' ),
            'update_item'       => __( 'Update MarketStatus' ),
            'add_new_item'      => __( 'Add New MarketStatus' ),
            'new_item_name'     => __( 'New MarketStatus Name' ),
            'menu_name'         => __( 'Market Status' ),
        ),
        'hierarchical'      => true, // Set to true for a category-like structure
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'market-status' ),
    ));

    // Register the custom taxonomy 'size'
    register_taxonomy('size', 'property', array(
        'labels' => array(
            'name'              => __('Sizes'),
            'singular_name'     => __('Size'),
            'search_items'      => __('Search Sizes'),
            'all_items'         => __('All Sizes'),
            'parent_item'       => __('Parent Size'),
            'parent_item_colon' => __('Parent Size:'),
            'edit_item'         => __('Edit Size'),
            'update_item'       => __('Update Size'),
            'add_new_item'      => __('Add New Size'),
            'new_item_name'     => __('New Size Name'),
            'menu_name'         => __('Sizes'),
        ),
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'property-size'),
    ));

}

// Hook into admin menu to add import page
add_action( 'admin_menu', 'properties_import_menu' );
