<?php
function show_categories_fn($atts) {
    // Accept comma-separated term IDs and optional taxonomy
    $atts = shortcode_atts([
        'ids' => '', // comma-separated term IDs
        'taxonomy' => 'property'
    ], $atts);

    $term_ids = array_filter(array_map('intval', explode(',', $atts['ids'])));
    $taxonomy = sanitize_key($atts['taxonomy']);

    if (empty($term_ids)) return '';

    ob_start();

    echo '<div class="row">';

    foreach ($term_ids as $term_id) {
        $term = get_term($term_id);
        if ($term && !is_wp_error($term)) {
            $term_link = get_post_type_archive_link($taxonomy)  . '?property_type='. $term->slug;
          ?>
            <div class="col-lg-4">
                <a class="filter-term" href="<?php echo esc_url($term_link); ?>" data-term-slug="<?php echo esc_attr($term->slug); ?>">
                    <?php
                    $image_id = get_term_meta($term->term_id, 'term_image', true);
                    if ($image_id) {
                        echo wp_get_attachment_image($image_id, 'full');
                    }
                    ?>
                    <h4><?php echo esc_html($term->name); ?></h4>
                </a>
            </div>
            <?php
        }
    }

    echo '</div>';

    return ob_get_clean();
}
add_shortcode('show_categories', 'show_categories_fn');
