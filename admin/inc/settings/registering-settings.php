<?php
// Hook into the admin init to register settings
add_action('admin_init', 'register_custom_settings');

function register_custom_settings()
{
    // Register settings
    register_setting('general_settings', 'property_style');
    register_setting('general_settings', 'col_width_desktop');
    register_setting('general_settings', 'col_width_mobile');
    register_setting('general_settings', 'container_width');
    register_setting('general_settings', 'enable_image_import');
    register_setting('general_settings', 'enable_document_import');
    register_setting('general_settings', 'login_auth_to_view_property'); // Register custom code input

    // Add General Settings Section
    add_settings_section(
        'general_settings_section',
        'General Settings',
        'general_settings_section_callback',
        'general_settings'
    );

    // Add Settings Fields
    add_settings_field(
        'property_style',
        'Style Type',
        'property_style_callback',
        'general_settings',
        'general_settings_section'
    );

    add_settings_field(
        'login_auth_to_view_property',
        'Auth Shortcode',
        'auth_shortcode_callback',
        'general_settings',
        'general_settings_section',
    );

    add_settings_field(
        'col_width_desktop',
        'Columns In Desktop',
        'col_width_desktop_callback',
        'general_settings',
        'general_settings_section'
    );

    add_settings_field(
        'col_width_mobile',
        'Columns In Mobile',
        'col_width_mobile_callback',
        'general_settings',
        'general_settings_section'
    );

    add_settings_field(
        'container_width',
        'Container Width',
        'container_width_callback',
        'general_settings',
        'general_settings_section'
    );

    // Add true/false toggle for image
    add_settings_field(
        'enable_image_import',
        'Import Images',
        'enable_image_import_callback',
        'general_settings',
        'general_settings_section'
    );

    // Add true/false toggle for image
    add_settings_field(
        'enable_document_import',
        'Import Documents',
        'enable_document_import_callback',
        'general_settings',
        'general_settings_section'
    );


    // Advanced settings
    register_setting('advanced_settings', 'advanced_option_1');
    register_setting('advanced_settings', 'advanced_option_2');

    add_settings_section(
        'advanced_settings_section',
        'Advanced Settings',
        'advanced_settings_section_callback',
        'advanced_settings'
    );

    add_settings_field(
        'advanced_option_1',
        'Primary Color',
        'advanced_option_1_callback',
        'advanced_settings',
        'advanced_settings_section'
    );

    add_settings_field(
        'advanced_option_2',
        'Secondary Color',
        'advanced_option_2_callback',
        'advanced_settings',
        'advanced_settings_section'
    );


    // Tab settings
    register_setting('tab_settings', 'tab_background_color');
    register_setting('tab_settings', 'tab_text_color');
    register_setting('tab_settings', 'tab_padding');
    register_setting('tab_settings', 'tab_margin');
    register_setting('tab_settings', 'tab_border');

    add_settings_section(
        'tab_settings_section',
        'Tab Settings',
        'tab_settings_section_callback',
        'tab_settings'
    );

    add_settings_field(
        'tab_background_color',
        'Background Color',
        'tab_background_color_callback',
        'tab_settings',
        'tab_settings_section'
    );

    add_settings_field(
        'tab_text_color',
        'Text Color',
        'tab_text_color_callback',
        'tab_settings',
        'tab_settings_section'
    );

    add_settings_field(
        'tab_padding',
        'Padding',
        'tab_padding_callback',
        'tab_settings',
        'tab_settings_section'
    );

    add_settings_field(
        'tab_margin',
        'Margin',
        'tab_margin_callback',
        'tab_settings',
        'tab_settings_section'
    );

    add_settings_field(
        'tab_border',
        'Border',
        'tab_border_callback',
        'tab_settings',
        'tab_settings_section'
    );

    // Tab content settings
    register_setting('tab_content_settings', 'content_background_color');
    register_setting('tab_content_settings', 'content_text_color');
    register_setting('tab_content_settings', 'content_padding');
    register_setting('tab_content_settings', 'content_margin');
    register_setting('tab_content_settings', 'content_border');

    add_settings_section(
        'tab_content_settings_section',
        'Tab Content Settings',
        'tab_content_settings_section_callback',
        'tab_content_settings'
    );

    add_settings_field(
        'content_background_color',
        'Background Color',
        'content_background_color_callback',
        'tab_content_settings',
        'tab_content_settings_section'
    );

    add_settings_field(
        'content_text_color',
        'Text Color',
        'content_text_color_callback',
        'tab_content_settings',
        'tab_content_settings_section'
    );

    add_settings_field(
        'content_padding',
        'Padding',
        'content_padding_callback',
        'tab_content_settings',
        'tab_content_settings_section'
    );

    add_settings_field(
        'content_margin',
        'Margin',
        'content_margin_callback',
        'tab_content_settings',
        'tab_content_settings_section'
    );

    add_settings_field(
        'content_border',
        'Border',
        'content_border_callback',
        'tab_content_settings',
        'tab_content_settings_section'
    );



    // Add this within the register_custom_settings function
// Archive Style settings
    register_setting('archive_style_settings', 'archive_border_style');
    register_setting('archive_style_settings', 'title_tag');
    register_setting('archive_style_settings', 'archive_background_style');
    register_setting('archive_style_settings', 'archive_border');
    register_setting('archive_style_settings', 'archive_padding');
    register_setting('archive_style_settings', 'archive_margin');
    register_setting('archive_style_settings', 'archive_shadow');
    register_setting('archive_style_settings', 'archive_image_size');
    register_setting('archive_style_settings', 'archive_date_view');

    add_settings_section(
        'archive_style_settings_section',
        'Archive Style Settings',
        'archive_style_settings_section_callback',
        'archive_style_settings'
    );

    add_settings_field(
        'title_tag',
        'Title tag',
        'title_tag_style_callback',
        'archive_style_settings',
        'archive_style_settings_section'
    );

    add_settings_field(
        'archive_border_style',
        'Border Style',
        'archive_border_style_callback',
        'archive_style_settings',
        'archive_style_settings_section'
    );

    add_settings_field(
        'archive_background_style',
        'Background Style',
        'archive_background_style_callback',
        'archive_style_settings',
        'archive_style_settings_section'
    );

    add_settings_field(
        'archive_border',
        'Border',
        'archive_border_callback',
        'archive_style_settings',
        'archive_style_settings_section'
    );

    add_settings_field(
        'archive_padding',
        'Padding',
        'archive_padding_callback',
        'archive_style_settings',
        'archive_style_settings_section'
    );

    add_settings_field(
        'archive_margin',
        'Margin',
        'archive_margin_callback',
        'archive_style_settings',
        'archive_style_settings_section'
    );

    add_settings_field(
        'archive_shadow',
        'Shadow',
        'archive_shadow_callback',
        'archive_style_settings',
        'archive_style_settings_section'
    );

    add_settings_field(
        'archive_image_size',
        'Image Size',
        'archive_image_size_callback',
        'archive_style_settings',
        'archive_style_settings_section'
    );

    add_settings_field(
        'archive_date_view',
        'Date View',
        'archive_date_view_callback',
        'archive_style_settings',
        'archive_style_settings_section'
    );
}