<?php


function handle_categories($categories_data, $post_id) {
    $category_ids = array();
    $parent_categories = array();
    
    // First create or get parent categories
    foreach ($categories_data as $category) {
        if ($category['ParentID'] == 0) {
            $category_term = get_term_by('name', $category['Name'], 'category');
            if (!$category_term) {
                // If category doesn't exist, create it
                $new_category = wp_insert_term($category['Name'], 'category', array('parent' => $category['ParentID']));
                if (!is_wp_error($new_category)) {
                    $parent_categories[$category['ID']] = $new_category['term_id'];
                }
            } else {
                $parent_categories[$category['ID']] = $category_term->term_id;
            }
        }
    }

    // Then create or get child categories
    foreach ($categories_data as $category) {
        if ($category['ParentID'] != 0) {
            $parent_id = $parent_categories[$category['ParentID']] ?? 0;
            $category_term = get_term_by('name', $category['Name'], 'category');
            if (!$category_term) {
                // If category doesn't exist, create it
                $new_category = wp_insert_term($category['Name'], 'category', array('parent' => $parent_id));
                if (!is_wp_error($new_category)) {
                    $category_ids[] = $new_category['term_id'];
                }
            } else {
                $category_ids[] = $category_term->term_id;
            }
        }
    }

    wp_set_post_categories($post_id, $category_ids);
}