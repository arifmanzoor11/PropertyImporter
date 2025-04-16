<?php

function render_taxonomy_blocks($term_ids, $taxonomy = 'property_type')
{
    echo '<div class="row">';
    foreach ($term_ids as $term_id) {
        $term = get_term($term_id, $taxonomy);
        if ($term && !is_wp_error($term)) { ?>
            <div class="col-lg-4">
            <?php  global $post;
    $post_slug = $post->post_name;
?>
                <a class="filter-term" href="<?php echo home_url($post_slug . '?property_type='. $term->slug) ?>" data-term-slug="<?php echo esc_attr($term->slug); ?>">
                    <?php
                    $term_id = $term->term_id;
                    $image_id = get_term_meta($term_id, 'term_image', true);
                    if ($image_id) {
                        echo wp_get_attachment_image($image_id, 'full');
                    } ?>
                    <h4><?php echo $term->name; ?></h4>
                </a>
            </div>
            <?php

        }
    }
    echo '</div>';
}


function property_display_shortcode($atts)
{
    // Default attributes
    $atts = shortcode_atts(array(
        'posts_per_page' => 9,
        'column' => '3',
        'show_filters' => true,
        'property_type' => '',
        'location' => '',
        'min_size' => '',
        'max_size' => '',
        'orderby' => 'date',
        'order' => 'DESC',
        'cat_show' => '190,194,199,214,222,195',
        'taxonomy_include' => 'property_type,location,tenure,size'
    ), $atts);

    // Get current filter values from URL
    $current_property_type = isset($_GET['property_type']) ? sanitize_text_field($_GET['property_type']) : '';
    $current_location = isset($_GET['location']) ? sanitize_text_field($_GET['location']) : '';
    $current_tenure = isset($_GET['tenure']) ? sanitize_text_field($_GET['tenure']) : '';
    $current_sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : '';
    $current_min_size = isset($_GET['min_size']) ? intval($_GET['min_size']) : '';
    $current_max_size = isset($_GET['max_size']) ? intval($_GET['max_size']) : '';

    ob_start();
    ?>
    <div class="property-display-container">
        <?php if ($atts['show_filters']): ?>
            <div class="property-filters">
                <div style="text-align: right;margin-bottom: 10px;">
                    <button type="button" class="reset_filter" onclick="resetFilters()">Reset Filters</button>
                </div>
                <div class="select-container">

                    <?php
                    // Get taxonomies from taxonomy_include parameter
                    $included_taxonomies = array_map('trim', explode(',', $atts['taxonomy_include']));

                    foreach ($included_taxonomies as $taxonomy_name) {
                        $taxonomy = get_taxonomy($taxonomy_name);
                        if ($taxonomy) {
                            $terms = get_terms([
                                'taxonomy' => $taxonomy_name,
                                'hide_empty' => false,
                            ]);

                            if (!empty($terms) && !is_wp_error($terms)): ?>
                                <select name="<?php echo esc_attr($taxonomy_name); ?>" class="dropdown-property filter-select">
                                    <option value=""><?php echo esc_html($taxonomy->label); ?></option>
                                    <?php foreach ($terms as $term):
                                        $selected = '';
                                        if (isset($_GET[$taxonomy_name]) && $_GET[$taxonomy_name] === $term->slug) {
                                            $selected = 'selected';
                                        }
                                        ?>
                                        <option value="<?php echo esc_attr($term->slug); ?>" <?php echo $selected; ?>>
                                            <?php echo esc_html($term->name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php endif;
                        }
                    }
                    ?>

                    <!-- Sort Options -->
                    <select name="sort" class="dropdown-property filter-select">
                        <option value="">Sort By</option>
                        <option value="date-desc" <?php selected($current_sort, 'date-desc'); ?>>Newest First</option>
                        <option value="date-asc" <?php selected($current_sort, 'date-asc'); ?>>Oldest First</option>
                        <option value="title-asc" <?php selected($current_sort, 'title-asc'); ?>>Title A-Z</option>
                        <option value="title-desc" <?php selected($current_sort, 'title-desc'); ?>>Title Z-A</option>
                        <option value="size-asc" <?php selected($current_sort, 'size-asc'); ?>>Size (Small to Large)</option>
                        <option value="size-desc" <?php selected($current_sort, 'size-desc'); ?>>Size (Large to Small)</option>
                    </select>
                    <select name="min_size" class="dropdown-property filter-select">
                        <option value="">Min Size</option>
                        <?php
                        $size_options = array(0, 1000, 2500, 5000, 10000);
                        foreach ($size_options as $size) {
                            printf(
                                '<option value="%1$d" %2$s>%1$d sq ft</option>',
                                $size,
                                selected($current_min_size, $size, false)
                            );
                        }
                        ?>
                    </select>
                    <select name="max_size" class="dropdown-property filter-select">
                        <option value="">Max Size</option>
                        <?php
                        $size_options = array(1000, 2500, 5000, 10000, 999999);
                        $size_labels = array(
                            '1,000 sq ft',
                            '2,500 sq ft',
                            '5,000 sq ft',
                            '10,000 sq ft',
                            'Over 10,000 sq ft'
                        );
                        foreach ($size_options as $index => $size) {
                            printf(
                                '<option value="%d" %s>%s</option>',
                                $size,
                                selected($current_max_size, $size, false),
                                $size_labels[$index]
                            );
                        }
                        ?>
                    </select>
                    <!-- Size Filters -->

                </div>
            </div>
        <?php endif; ?>

        <div class="property-results row">
            <?php
            // Build query args based on URL parameters
            $args = array(
                'post_type' => 'property',
                'posts_per_page' => $atts['posts_per_page']
            );

            // Add taxonomy queries
            $tax_query = array();
            foreach ($included_taxonomies as $taxonomy) {
                if (isset($_GET[$taxonomy]) && !empty($_GET[$taxonomy])) {
                    $tax_query[] = array(
                        'taxonomy' => $taxonomy,
                        'field' => 'slug',
                        'terms' => sanitize_text_field($_GET[$taxonomy])
                    );
                }
            }
            if (!empty($tax_query)) {
                $args['tax_query'] = array(
                    'relation' => 'AND',
                    $tax_query
                );
            }

            // Add sorting
            if (!empty($current_sort)) {
                list($orderby, $order) = explode('-', $current_sort);
                $args['orderby'] = $orderby;
                $args['order'] = strtoupper($order);
            }

            // Add size meta query
            if (!empty($current_min_size) || !empty($current_max_size)) {
                $args['meta_query'] = array('relation' => 'AND');

                if (!empty($current_min_size)) {
                    $args['meta_query'][] = array(
                        'key' => 'Size',
                        'value' => $current_min_size,
                        'type' => 'NUMERIC',
                        'compare' => '>='
                    );
                }

                if (!empty($current_max_size)) {
                    $args['meta_query'][] = array(
                        'key' => 'Size',
                        'value' => $current_max_size,
                        'type' => 'NUMERIC',
                        'compare' => '<='
                    );
                }
            }
            $has_active_filters = false;

            foreach ($included_taxonomies as $taxonomy) {
                $taxonomy = trim($taxonomy);
                if (!empty($_GET[$taxonomy])) {
                    $terms = array_map('intval', (array) $_GET[$taxonomy]);
                    $tax_query[] = [
                        'taxonomy' => $taxonomy,
                        'field' => 'term_id',
                        'terms' => $terms,
                        'operator' => 'IN'
                    ];
                    $has_active_filters = true;
                }
            }

            
            if (!empty($current_min_size) || !empty($current_max_size) || !empty($current_sort)) {
                $has_active_filters = true;
            }

            if (!$atts['show_filters'] && $atts['cat_show']) {
                // Show categories (taxonomy blocks only)
                $term_ids = array_map('intval', explode(',', $atts['cat_show']));
                render_taxonomy_blocks($term_ids);
            } elseif ($atts['show_filters'] && !$has_active_filters && $atts['cat_show']) {
                // Show taxonomy blocks until filters are used
                $term_ids = array_map('intval', explode(',', $atts['cat_show']));
                render_taxonomy_blocks($term_ids);
            } else {
                $query = new WP_Query($args);
                if ($query->have_posts()):
                    while ($query->have_posts()):
                        $query->the_post();
                        include plugin_dir_path(__DIR__) . 'template-parts/property.php';
                    endwhile;
                    wp_reset_postdata();
                else:
                    echo '<div class="col-12"><p>No properties found matching your criteria.</p></div>';
                endif;
            }
            ?>
        </div>
    </div>

    <script>
        jQuery(document).ready(function ($) {
            // Handle select change
            $('.filter-select').on('change', function () {
                const paramName = $(this).attr('name');
                const paramValue = $(this).val();

                // Get current URL parameters
                const urlParams = new URLSearchParams(window.location.search);

                // Update or remove the changed parameter
                if (paramValue) {
                    urlParams.set(paramName, paramValue);
                } else {
                    urlParams.delete(paramName);
                }

                // Construct new URL
                let newUrl = window.location.pathname;
                if (urlParams.toString()) {
                    newUrl += '?' + urlParams.toString();
                }

                // Navigate to new URL
                window.location.href = newUrl;
            });
        });

        jQuery(document).ready(function ($) {
            $(".dropdown-property").select2();
        });

        function resetFilters() {
            const urlParams = new URLSearchParams(window.location.search);

            // Get list of parameters to preserve
            const preserveParams = ['page_id', 'post_type'];

            // Create new URLSearchParams with only preserved parameters
            const newParams = new URLSearchParams();
            preserveParams.forEach(param => {
                if (urlParams.has(param)) {
                    newParams.set(param, urlParams.get(param));
                }
            });

            // Construct new URL
            let newUrl = window.location.pathname;
            if (newParams.toString()) {
                newUrl += '?' + newParams.toString();
            }

            // Navigate to new URL
            window.location.href = newUrl;
        }
    </script>

    <style>
        .reset_filter {
            background-color: #087188;
            padding: 15px 30px;
            margin: 0;
            border-radius: 32px;
            font-size: 10px;
            letter-spacing: 0;
        }

        .reset_filter:hover {
            background-color: rgb(5, 62, 75);
            text-decoration: none;
            color: aliceblue;
        }

        .property-results {
            margin-top: 20px;
        }
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode('display_properties', 'property_display_shortcode');