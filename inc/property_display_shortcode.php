<?php

/**
 * Renders taxonomy blocks with images
 * 
 * @param array $term_ids Array of taxonomy term IDs
 * @param string $taxonomy The taxonomy name
 */
function render_taxonomy_blocks($term_ids, $taxonomy = 'property_type') {
    echo '<div class="row">';
    foreach ($term_ids as $term_id) {
        $term = get_term($term_id, $taxonomy);
        if ($term && !is_wp_error($term)) { ?>
            <div class="col-lg-4">
            <?php global $post;
                $post_slug = $post->post_name; ?>
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

/**
 * Returns size taxonomy terms that match min and max size criteria
 * 
 * @param int $min_size Minimum size value
 * @param int $max_size Maximum size value
 * @return array Array of matching term IDs
 */
function get_matching_size_terms($min_size, $max_size) {
    // Get all size taxonomy terms
    $size_terms = get_terms(array(
        'taxonomy' => 'size',
        'hide_empty' => false,
    ));
    
    $matched_term_ids = array();
    
    foreach ($size_terms as $term) {
        // Extract numeric value from the term name
        if (preg_match('/(\d+)/', $term->name, $matches)) {
            $size_value = intval($matches[1]);
            
            // Check if size matches min and max criteria
            if ((!$min_size || $size_value >= $min_size) && 
                (!$max_size || $size_value <= $max_size)) {
                $matched_term_ids[] = $term->term_id;
            }
        }
    }
    
    return $matched_term_ids;
}

/**
 * Builds the tax query array based on URL parameters
 * 
 * @param array $included_taxonomies Array of taxonomy names to include
 * @param int $min_size Minimum size value
 * @param int $max_size Maximum size value
 * @return array Tax query array
 */
function build_tax_query($included_taxonomies, $min_size, $max_size) {
    $tax_query = array('relation' => 'AND');
    $has_tax_query = false;
    
    // Add taxonomy filters (excluding size, which we'll handle separately)
    foreach ($included_taxonomies as $taxonomy) {
        if ($taxonomy !== 'size' && isset($_GET[$taxonomy]) && !empty($_GET[$taxonomy])) {
            $tax_query[] = array(
                'taxonomy' => $taxonomy,
                'field' => 'slug',
                'terms' => sanitize_text_field($_GET[$taxonomy])
            );
            $has_tax_query = true;
        }
    }
    
    // Handle size taxonomy filtering based on min_size and max_size
    if (!empty($min_size) || !empty($max_size)) {
        $matched_term_ids = get_matching_size_terms($min_size, $max_size);
        
        if (!empty($matched_term_ids)) {
            $tax_query[] = array(
                'taxonomy' => 'size',
                'field' => 'term_id',
                'terms' => $matched_term_ids,
                'operator' => 'IN'
            );
            $has_tax_query = true;
        }
    }
    
    return array('tax_query' => $tax_query, 'has_tax_query' => $has_tax_query);
}

/**
 * Gets meta query from shortcode attributes
 * 
 * @param string $meta_filters Meta filters string from shortcode
 * @return array Meta query array
 */
function get_meta_query_from_shortcode($meta_filters) {
    $meta_query = array();
    
    if (!empty($meta_filters)) {
        $filters = explode(';', $meta_filters);
        
        foreach ($filters as $filter) {
            if (empty(trim($filter))) continue;
            
            $pairs = explode(',', $filter);
            $meta = array();
            
            foreach ($pairs as $pair) {
                if (strpos($pair, ':') !== false) {
                    list($k, $v) = explode(':', $pair);
                    $meta[trim($k)] = trim($v);
                }
            }
            
            if (!empty($meta['key']) && isset($meta['value'])) {
                $meta_query[] = array(
                    'key' => $meta['key'],
                    'value' => $meta['value'],
                    'compare' => isset($meta['compare']) ? $meta['compare'] : '='
                );
            }
        }
    }
    
    return $meta_query;
}

function property_display_shortcode($atts)
{
    // Default attributes
    $atts = shortcode_atts(array(
        'posts_per_page' => '-1',
        'column' => '3',
        'slider'=> false,
        'featured' => false,
        'show_filters' => true,
        'property_type' => '',
        'location' => '',
        'min_size' => '',
        'max_size' => '',
        'meta_filters'=> '',
        'orderby' => 'date',
        'order' => 'DESC',
        'cat_show' => '',
        'excerpt_text_align'=> 'left',
        'taxonomy_include' => 'property_type,location,tenure'
    ), $atts);
    
    // Get current filter values from URL
    $current_property_type = isset($_GET['property_type']) ? sanitize_text_field($_GET['property_type']) : '';
    $current_location = isset($_GET['location']) ? sanitize_text_field($_GET['location']) : '';
    $current_tenure = isset($_GET['tenure']) ? sanitize_text_field($_GET['tenure']) : '';
    $current_sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : '';
    $current_min_size = isset($_GET['min_size']) ? intval($_GET['min_size']) : '';
    $current_max_size = isset($_GET['max_size']) ? intval($_GET['max_size']) : '';
    $atts['show_filters'] = filter_var($atts['show_filters'], FILTER_VALIDATE_BOOLEAN);
    ob_start(); ?>
    
    <div class="property-display-container">
        <?php if ($atts['show_filters']): ?>
            <div class="property-filters">
                <div style="text-align: right;margin-bottom: 10px;">
                    <button type="button" class="reset_filter" onclick="resetFilters()">Reset Search</button>
                </div>
                <div class="select-container">
                <?php
                    // Get taxonomies from taxonomy_include parameter (excluding size for now)
                    $included_taxonomies = array_map('trim', explode(',', $atts['taxonomy_include']));
                    $included_taxonomies = array_filter($included_taxonomies, function($tax) {
                        return $tax !== 'size';
                    });

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
                        $size_options = array(0, 1000, 2500, 5000, 10000, 999999);
                        $size_labels = array(
                            '0 sq ft',
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
                                selected($current_min_size, $size, false),
                                $size_labels[$index]
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
            
            // Initialize tax_query array with AND relation
            $tax_query = array('relation' => 'AND');
            $has_tax_query = false;
            
            // Check if any filter is active
            $has_active_filters = false;
            
            // Add taxonomy filters (excluding size, which we'll handle separately)
            foreach ($included_taxonomies as $taxonomy) {
                if (isset($_GET[$taxonomy]) && !empty($_GET[$taxonomy])) {
                    $tax_query[] = array(
                        'taxonomy' => $taxonomy,
                        'field' => 'slug',
                        'terms' => sanitize_text_field($_GET[$taxonomy])
                    );
                    $has_tax_query = true;
                    $has_active_filters = true;
                }
            }

            if (!empty($current_min_size) || !empty($current_max_size)) {
                $has_active_filters = true;
                

                // Query all posts that have the Dimension field
                $all_size_posts = get_posts(array(
                    'post_type'      => 'property',
                    'posts_per_page' => -1,
                    'post_status'    => 'publish',
                    'meta_key'       => 'Size',
                    'fields'         => 'ids',
                ));
               
                $matched_post_ids = [];
                foreach ($all_size_posts as $post_id) {
                    // echo $post_id;
                    $dimension_data_serialized = get_post_meta($post_id, 'Size', true);
                    $dimension_data = maybe_unserialize($dimension_data_serialized);
                  
                    if (is_array($dimension_data)) {
                        $min = isset($dimension_data['MinSize']) ? intval($dimension_data['MinSize']) : 0;
                        $max = isset($dimension_data['MaxSize']) ? intval($dimension_data['MaxSize']) : 0;
            
                        $is_match = true;
            
                        if (!empty($current_min_size) && $max < $current_min_size) {
                            $is_match = false;
                        }
            
                        if (!empty($current_max_size) && $min > $current_max_size) {
                            $is_match = false;
                        }
            
                        if ($is_match) {
                            $matched_post_ids[] = $post_id;
                        }
                    }
                }
               
                // If we found matching post IDs, apply them to the main query
                if (!empty($matched_post_ids)) {
                    $args['post__in'] = $matched_post_ids;
                } 
            }
            
            // Add sorting
            if (!empty($current_sort)) {
                $has_active_filters = true;
                list($orderby, $order) = explode('-', $current_sort);
                $args['orderby'] = $orderby;
                $args['order'] = strtoupper($order);
            }
            
            // Only add meta filters from shortcode if no other filters are active
            $meta_query = array();
            if (!$has_active_filters && !empty($atts['meta_filters'])) {
                $filters = explode(';', $atts['meta_filters']); // separate each filter group
                
                foreach ($filters as $filter) {
                    if (empty(trim($filter))) continue;
                    
                    $pairs = explode(',', $filter);
                    $meta = array();
                    
                    foreach ($pairs as $pair) {
                        if (strpos($pair, ':') !== false) {
                            list($k, $v) = explode(':', $pair);
                            $meta[trim($k)] = trim($v);
                        }
                    }
                    
                    if (!empty($meta['key']) && isset($meta['value'])) {
                        $meta_query[] = array(
                            'key' => $meta['key'],
                            'value' => $meta['value'],
                            'compare' => isset($meta['compare']) ? $meta['compare'] : '='
                        );
                    }
                }
            }
            
            // Add meta_query to args if not empty
            if (!empty($meta_query)) {
                if (count($meta_query) > 1) {
                    $meta_query['relation'] = 'AND';
                }
                $args['meta_query'] = $meta_query;
            }
            
            // Add tax_query to args if any taxonomy filters are applied
            if ($has_tax_query) {
                $args['tax_query'] = $tax_query;
            }
            
            if ($atts['show_filters'] && !$has_active_filters && $atts['cat_show']) {
                // Show taxonomy blocks until filters are used
                $term_ids = array_map('intval', explode(',', $atts['cat_show']));
                render_taxonomy_blocks($term_ids);
            } 
            else { 
                 $query = new WP_Query($args);
                if ($query->have_posts()): ?>
                <?php if($atts['slider']) : ?>
                    <div class="slider">
                <?php endif; ?>
                    <?php
                    while ($query->have_posts()):
                        $query->the_post();
                        include plugin_dir_path(__DIR__) . 'template-parts/property.php';
                    endwhile; ?>
                <?php if($atts['slider']) : ?>
                    </div>
                <?php endif; ?>

                   <?php wp_reset_postdata();
                else:
                    echo '<div class="col-12"><p>No properties found matching your criteria.</p></div>';
                endif;
            }
            ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('display_properties', 'property_display_shortcode');