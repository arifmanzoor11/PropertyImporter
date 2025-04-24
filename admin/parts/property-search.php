<?php
function search_properties_enqueue_styles() {
    $dir = plugin_dir_url(__DIR__);
    
    // Enqueue jQuery
    wp_enqueue_script('jquery');
    
    // Register property-search-filter script
    wp_register_script('property-search-filter', $dir . '/assets/js/property-search-filter.js', array('jquery'), null, true);
    
    // Localize the property-search-filter script to pass AJAX URL
    wp_localize_script('property-search-filter', 'ajax_obj', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]);

    // Enqueue the localized script
    wp_enqueue_script('property-search-filter');
}
add_action('wp_enqueue_scripts', 'search_properties_enqueue_styles');

// AJAX function to handle property search
function ajax_property_search() {
    
    $post_type = 'property';
    $search_query = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : ''; 
    $min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : '0';
    $max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : '500';
    $location = isset($_GET['location']) ? sanitize_text_field($_GET['location']) : '';
    $bedrooms = isset($_GET['bedrooms']) ? intval($_GET['bedrooms']) : '';

    // Query args
    $args = [
        'post_type' => $post_type,
        'posts_per_page' => 6,
        's' => $search_query, 
    ];

    if ($location) {
        $args['meta_query'][] = [
            'key' => 'location',
            'value' => $location,
            'compare' => 'LIKE',
        ];
    }

    $query = new WP_Query($args);
    $placeholder_image = get_template_directory_uri() . '/parts/images/640x480.png';
    if ($query->have_posts()) {
        echo '<div class="row">';
        while ($query->have_posts()) {
            $query->the_post(); ?>
            <div class="col mb-5 col-md-4">
                <div class="card">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" style="text-decoration: none;">
                        <div style="background: url(<?php echo esc_url($post_thumbnail); ?>) center center / cover; height: 320px;"></div>
                        <div class="card-body">
                            <h5><?php the_title(); ?></h5>
                           <p>Bedrooms: <?php echo esc_html($bedrooms); ?></p>
                            <p>Location: <?php echo esc_html($location); ?></p>
                            <a href="<?php the_permalink(); ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </a>
                </div>
            </div>
            <?php
        }
        echo '</div>';
    } else {
        echo '<p>No properties found.</p>';
    }
    
    wp_reset_postdata();
    die();
}

add_action('wp_ajax_property_search', 'ajax_property_search');
add_action('wp_ajax_nopriv_property_search', 'ajax_property_search');

// Shortcode for property search form
function property_search_shortcode() {
    ob_start(); ?>
    <form method="get" id="property-filter">
        <input type="text" name="s" placeholder="Search by keyword..." />
        <input type="text" name="location" placeholder="Location" />
        <button type="submit">Search Properties</button>
    </form>

    <?php // Fetch categories for the 'categories' taxonomy associated with the 'property' post type
        $args = array(
            'taxonomy'   => 'property_type', // Use the correct taxonomy name
            'hide_empty' => false,        // Show all categories, even if empty
        );

        $cats = get_terms($args);
        foreach($cats as $cat) { ?>
            <a href="<?php echo get_category_link( $cat->term_id ) ?>">
                <?php echo $cat->name; ?>
            </a>
        <?php } ?>  
    <div id="property-results"></div>

    <?php
    return ob_get_clean();
}
add_shortcode('property_search', 'property_search_shortcode');
