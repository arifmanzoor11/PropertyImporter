<?php
/*
Plugin Name: Property Importer
Description: Imports properties from an external API into a custom post type in WordPress.
Version: 2.5
Author: Arif M.
*/
// Prevent direct access to the file
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
include_once(plugin_dir_path(__FILE__) . 'import-main.php');
include_once(plugin_dir_path(__FILE__) . 'inc/tableDb.php');

include_once(plugin_dir_path(__FILE__) . 'admin/inc/PropertiesSettings.php');
include_once(plugin_dir_path(__FILE__) . 'admin/inc/DeleteProperties.php');
include_once(plugin_dir_path(__FILE__) . 'admin/inc/ManageImport.php');
include_once(plugin_dir_path(__FILE__) . 'admin/inc/Import.php');
include_once(plugin_dir_path(__FILE__) . 'admin/inc/MetaBox.php');
include_once(plugin_dir_path(__FILE__) . 'admin/inc/AutoImport.php');

// Admin Views
include_once(plugin_dir_path(__FILE__) . 'admin/inc/views/viewsfn.php');
include_once(plugin_dir_path(__FILE__) . 'admin/inc/views/renderAgentFields.php');
include_once(plugin_dir_path(__FILE__) . 'admin/inc/views/renderAddressField.php');
include_once(plugin_dir_path(__FILE__) . 'admin/inc/views/renderSizeData.php');
include_once(plugin_dir_path(__FILE__) . 'admin/inc/views/renderSystemDetailData.php');
include_once(plugin_dir_path(__FILE__) . 'admin/inc/views/renderBusinessRatesData.php');
include_once(plugin_dir_path(__FILE__) . 'admin/inc/views/renderTenureData.php');
include_once(plugin_dir_path(__FILE__) . 'admin/inc/views/renderOwnersData.php');
// Admin Parts
include_once(plugin_dir_path(__FILE__) . 'admin/parts/property-search.php');
// include_once(plugin_dir_path(__FILE__) . 'admin/inc/views/renderOwnersData.php');

// Admin Store
include_once(plugin_dir_path(__FILE__) . 'admin/inc/store/handleLocation.php');
include_once(plugin_dir_path(__FILE__) . 'admin/inc/store/handlePropertyCategories.php');
include_once(plugin_dir_path(__FILE__) . 'admin/inc/store/HanldePhotos.php');
include_once(plugin_dir_path(__FILE__) . 'admin/inc/store/handleDocument.php');
include_once(plugin_dir_path(__FILE__) . 'admin/inc/store/HandleCategory.php');
include_once(plugin_dir_path(__FILE__) . 'admin/inc/store/handleAgent.php');
include_once(plugin_dir_path(__FILE__) . 'admin/inc/store/handleAddress.php');
include_once(plugin_dir_path(__FILE__) . 'admin/inc/store/handleSize.php');
include_once(plugin_dir_path(__FILE__) . 'admin/inc/store/handleSystemDetailData.php');
include_once(plugin_dir_path(__FILE__) . 'admin/inc/store/handleBusinessRatesData.php');
include_once(plugin_dir_path(__FILE__) . 'admin/inc/store/handleTenureData.php');
include_once(plugin_dir_path(__FILE__) . 'admin/inc/store/handleOwnersData.php');
include_once(plugin_dir_path(__FILE__) . 'admin/inc/store/handlePropertyTypes.php');
include_once(plugin_dir_path(__FILE__) . 'admin/inc/store/handleBulletsPoints.php');


// Renders Views
include_once(plugin_dir_path(__FILE__) . 'views/renders/render_document_media.php');
include_once(plugin_dir_path(__FILE__) . 'views/renders/render_agent_data.php');
include_once(plugin_dir_path(__FILE__) . 'views/renders/render_property_address_data.php');
include_once(plugin_dir_path(__FILE__) . 'views/renders/render_size_data.php');
include_once(plugin_dir_path(__FILE__) . 'views/renders/render_tenure_data.php');
include_once(plugin_dir_path(__FILE__) . 'views/renders/render_system_detail_data.php');
include_once(plugin_dir_path(__FILE__) . 'views/renders/render_company_data.php');
include_once(plugin_dir_path(__FILE__) . 'views/renders/render_owners_data.php');
include_once(plugin_dir_path(__FILE__) . 'views/renders/render_photo_data.php');
include_once(plugin_dir_path(__FILE__) . 'views/renders/render_photo_style_two.php');
include_once(plugin_dir_path(__FILE__) . 'views/renders/render_map_view.php');
include_once(plugin_dir_path(__FILE__) . 'register-post-type.php');


include_once(plugin_dir_path(__FILE__) . 'schedule-task.php');

function properties_import_menu() {
    // Add Import Properties page
    add_menu_page( 'PI Dashboard', 'PI Dashboard', 'manage_options', 'import-properties', 'import_properties_page','dashicons-admin-site-alt' );
    // Add Delete Properties submenu under Import Properties
    add_submenu_page( 'import-properties', 'Manually Import', 'Manually Import', 'manage_options', 'manually-import', 'manage_import_import' );
    add_submenu_page( 'import-properties', 'Auto Import', 'Auto Import', 'manage_options', 'auto-import', 'manage_auto_import' );
    add_submenu_page( 'import-properties', 'Settings', 'Settings', 'manage_options', 'settings', 'manage_auto_import_settings' );
    add_submenu_page( 'import-properties', 'Manage Import', 'Manage Import', 'manage_options', 'manage-import', 'manage_import_page' );
    add_submenu_page( 'import-properties', 'Delete All Properties', 'Delete All Properties', 'manage_options', 'delete-properties', 'delete_properties_page' );
    add_submenu_page( 'import-properties', 'Documentation', 'Documentation', 'manage_options', 'documentation-properties', 'documentation_properties_page' );
}

function documentation_properties_page() {
    include_once(plugin_dir_path(__FILE__) . 'admin/documentation_properties_page.php');
}
function import_properties_page() {
    include_once(plugin_dir_path(__FILE__) . 'admin/import_properties_page.php');
}

function manage_import_import() {
    include_once(plugin_dir_path(__FILE__) . 'admin/propertyimport.php');
}

add_action( 'admin_enqueue_scripts', 'easy_propertyimport_load_admin_style' );
function easy_propertyimport_load_admin_style() {
    $dir = plugin_dir_url(__FILE__);
    wp_enqueue_style( 'easy-propertyimport_url-admin', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), // No dependencies
    '4.1.0-rc.0' );
    wp_enqueue_style( 'easy-propertyimport_url-admin' );
    wp_register_script( 'easy-propertyimport_url-admin', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), // Depends on jQuery
    '4.1.0-rc.0',
    true );
    wp_enqueue_script( 'easy-propertyimport_url-admin' );
    wp_enqueue_style( 'easy-propertyimport-admin', $dir . 'admin/assets/css/adminPropertyImport.css',  array(),
    '1.0.0' );
    wp_enqueue_style( 'easy-propertyimport-admin' );
    wp_register_script('adminProperty-Import-js', $dir . 'admin/assets/js/adminPropertyImport.js', array('jquery', 'select2-js'),
    '1.0.0',
    true );
    wp_enqueue_script( 'adminProperty-Import-custom' );
    wp_enqueue_media();
    wp_enqueue_script('jquery-ui-tabs');
    wp_enqueue_style('jquery-ui-css', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');

       // Localize the script with the AJAX URL and a nonce
       wp_localize_script('properties-import-script', 'properties_import_vars', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('properties_import_nonce')
    ));
    
}

function enqueue_property_styles() {

    // Check if we're on the 'property' custom post type archive or single page
    if (is_post_type_archive('property') || is_singular('property')) {
        $dir = plugin_dir_url(__FILE__);
        
        // Check if Select2 is already registered
        if (!wp_script_is('select2', 'registered')) {
            wp_enqueue_style('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), '4.1.0');
            wp_enqueue_script('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), '4.1.0', true);
        }
        
        // Enqueue custom styles and scripts
        wp_enqueue_style('property-styles', $dir . 'assets/css/property-styles.css', array(), null, 'all');
        wp_enqueue_script('property-import-js', $dir . 'assets/js/propertyImport.js', array('jquery', 'select2'), null, true);
    }
        wp_enqueue_style('property-shortcode-styles', plugin_dir_url(__FILE__) . 'assets/css/property-shortcode.css', array(), null, 'all');
        wp_enqueue_script('property-import-shortcode-js', plugin_dir_url(__FILE__) . 'assets/js/property-import-shortcode.js', array(), null, true);

}

add_action('wp_enqueue_scripts', 'enqueue_property_styles', 20);

function use_plugin_property_archive_template($template) {
    if (is_post_type_archive('property')) {
        $plugin_template = plugin_dir_path(__FILE__) . 'views/property-archive-template.php';
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
    }
    return $template;
}
add_filter('archive_template', 'use_plugin_property_archive_template');


function use_plugin_single_property_template($template) {
    if (is_singular('property')) {
        $plugin_template = plugin_dir_path(__FILE__) . 'views/single-property-template.php';
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
    }
    return $template;
}
add_filter('single_template', 'use_plugin_single_property_template');


/**
 * Filter the except length to 20 words.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
function wpdocs_custom_excerpt_length( $length ) {
	return 15;
}
add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );


function add_property_type_image_field($taxonomy) {
    if ($taxonomy !== 'property_type') return;
    ?>
    <div class="form-field term-image-wrap">
        <label for="term-image"><?php _e('Image', 'textdomain'); ?></label>
        <input type="button" class="button button-secondary term-image-upload" value="<?php _e('Upload Image', 'textdomain'); ?>" />
        <input type="hidden" id="term-image" name="term-image" value="" />
        <p class="description"><?php _e('Upload an image for this property type.', 'textdomain'); ?></p>
        <div id="term-image-preview"></div>
    </div>
    <?php
}
add_action('property_type_add_form_fields', 'add_property_type_image_field');

function edit_property_type_image_field($term, $taxonomy) {
    if ($taxonomy !== 'property_type') return;

    $image_id = get_term_meta($term->term_id, 'term_image', true);
    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'thumbnail') : '';
    ?>
    <tr class="form-field term-image-wrap">
        <th scope="row"><label for="term-image"><?php _e('Image', 'textdomain'); ?></label></th>
        <td>
            <input type="button" class="button button-secondary term-image-upload" value="<?php _e('Upload Image', 'textdomain'); ?>" />
            <input type="hidden" id="term-image" name="term-image" value="<?php echo esc_attr($image_id); ?>" />
            <p class="description"><?php _e('Upload an image for this property type.', 'textdomain'); ?></p>
            <div id="term-image-preview"><?php if ($image_url) { ?><img src="<?php echo esc_url($image_url); ?>" alt="" style="max-width: 100%;"><?php } ?></div>
        </td>
    </tr>
    <?php
}
add_action('property_type_edit_form_fields', 'edit_property_type_image_field', 10, 2);
function save_property_type_image_field($term_id) {
    if (isset($_POST['term-image']) && !empty($_POST['term-image'])) {
        update_term_meta($term_id, 'term_image', sanitize_text_field($_POST['term-image']));
    } else {
        delete_term_meta($term_id, 'term_image');
    }
}
add_action('created_property_type', 'save_property_type_image_field', 10, 2);
add_action('edited_property_type', 'save_property_type_image_field', 10, 2);

function enqueue_property_type_image_script($hook) {
    if ('edit-tags.php' === $hook || 'term.php' === $hook) {
        if (isset($_GET['taxonomy']) && $_GET['taxonomy'] === 'property_type') {
            wp_enqueue_media();
            wp_enqueue_script('taxonomy-image-script', plugin_dir_url(__FILE__) . 'assets/js/taxonomy-image.js', array('jquery'), null, true);
        }
    }
}
add_action('admin_enqueue_scripts', 'enqueue_property_type_image_script');

// Add this code after the existing functions
include_once(plugin_dir_path(__FILE__) . 'inc/property_display_shortcode.php');
include_once(plugin_dir_path(__FILE__) . 'inc/show_categories_shortcode.php');



add_filter('redirect_canonical', 'disable_redirect_on_custom_page');

function disable_redirect_on_custom_page($redirect_url) {
    if (is_front_page()) {
            return false;
    }
    return $redirect_url;
}

add_action('wp_footer', 'custom_homepage_filter_script');
function custom_homepage_filter_script() {
    // Get the front page slug and URL
    $front_page_id = get_option('page_on_front');
    $front_page_slug = get_post_field('post_name', $front_page_id);
    $homepage_url = home_url($front_page_slug);
    ?>
    <script>
    jQuery(document).ready(function ($) {
        const returnUrl = sessionStorage.getItem('returnToSearchUrl');

        if (returnUrl) {
            // Show the button if return URL exists
            $('.back-to-search').show().on('click', function (e) {
                e.preventDefault();
                window.location.href = returnUrl;
            });
        } else {
            // Hide the button just to be safe
            $('.back-to-search').hide();
        }

        const isSinglePage = $('body').hasClass('single');
        const isFrontPage = $('body').hasClass('home');
        const homepageUrl = '<?php echo esc_url($homepage_url); ?>';

        // Restore scroll position if it exists
        const scrollY = sessionStorage.getItem('scrollPos');
        if (scrollY) {
            window.scrollTo(0, parseInt(scrollY));
            sessionStorage.removeItem('scrollPos');
        }

        $('.filter-select').on('change', function () {
            const paramName = $(this).attr('name');

            const paramValue = $(this).val();
            const urlParams = new URLSearchParams(window.location.search);

            if (paramValue) {
                urlParams.set(paramName, paramValue);
            } else {
                urlParams.delete(paramName);
            }

            let newUrl = isFrontPage ? homepageUrl : window.location.pathname;

            if (urlParams.toString()) {
                newUrl += '?' + urlParams.toString();
            }

            // Save scroll position before redirect
            sessionStorage.setItem('scrollPos', window.scrollY); // Store scrolled position
            sessionStorage.setItem('returnToSearchUrl', newUrl); //  Store the filtered URL

            // Navigate to new URL
            window.location.href = newUrl;

        });
        $('.filter-term').on('click', function () {
            const paramSlug = $(this).attr('href');
                 // Save scroll position before redirect
            sessionStorage.setItem('scrollPos', window.scrollY); // Store scrolled position
            sessionStorage.setItem('returnToSearchUrl', paramSlug); //  Store the filtered URL

        });

         // If on single page, update "Back to Search" button
        if (isSinglePage) {
            const returnUrl = sessionStorage.getItem('returnToSearchUrl');
            if (returnUrl) {
                $('.back-to-search').attr('href', returnUrl);
            }
            else {
                // Redirect to fallback archive page if no session data is found
                window.location.href = '<?php echo esc_url(get_post_type_archive_link('property')); ?>';
            }

        }
    });
    </script>
    <?php
}
