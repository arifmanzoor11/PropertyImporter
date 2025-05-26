<?php

// Enable Image Toggle Callback
function enable_image_import_callback() {
    $enable_image_import = get_option('enable_image_import');
    $checked = ($enable_image_import) ? 'checked' : '';
    echo "<input type='checkbox' name='enable_image_import' value='1' $checked />";
}
// Enable Image Toggle Callback
function enable_document_import_callback() {
    $enable_document_import = get_option('enable_document_import');
    $checked = ($enable_document_import) ? 'checked' : '';
    echo "<input type='checkbox' name='enable_document_import' value='1' $checked />";
}

function property_style_callback() {
    $option = get_option('property_style'); 
    ?>
    <select name="property_style" id="property_style" style="width:100%">
        <option value="style_1" <?php selected($option, 'style_1'); ?>>Style One</option>
        <option value="style_2" <?php selected($option, 'style_2'); ?>>Style Two</option>
        <option value="style_3" <?php selected($option, 'style_3'); ?>>Style Three</option>
        <option value="style_4" <?php selected($option, 'style_4'); ?>>Style Four</option>
    </select>   
    <?php
}

// Custom Code Input Callback
function auth_shortcode_callback() {
    $login_auth_to_view_property = get_option('login_auth_to_view_property', '');
    echo "<textarea style='min-width: 25rem;' name='login_auth_to_view_property' rows='5' style='width:100%'>" . esc_textarea($login_auth_to_view_property) . "</textarea><br><small>If you enter a shortcode here, it will not allow user to view the property unless the user is logged in.</small>";
}

// Custom Code Input Callback
function custom_reg_code_callback() {
    $reg_code = get_option('reg_code', '');
    echo "<textarea style='min-width: 25rem;' name='reg_code' rows='5' style='width:100%'>" . esc_textarea($reg_code) . "</textarea><br><small>If you enter a shortcode here, it will override the built-in login page used in the content protection page.</small>";
}

function forgot_pw_shortcodecode_callback() {
    $forgot_pw_code = get_option('forgot_pw_code', '');
    echo "<textarea style='min-width: 25rem;' name='forgot_pw_code' rows='5' style='width:100%'>" . esc_textarea($forgot_pw_code) . "</textarea><br><small>If you enter a shortcode here, it will override the built-in login page used in the content protection page.</small>";
}

function col_width_desktop_callback()  {
    $option = get_option('col_width_desktop'); 
    ?>
    <select name="col_width_desktop" id="col_width_desktop" style="width:100%">
        <option value="column_3" <?php selected($option, 'column_3'); ?>>Four</option>
        <option value="column_4" <?php selected($option, 'column_4'); ?>>Three</option>
        <option value="column_6" <?php selected($option, 'column_6'); ?>>Two</option>
        <option value="column_12" <?php selected($option, 'column_12'); ?>>One</option>
    </select>   
    <?php
}

function col_width_mobile_callback()  {
    $option = get_option('col_width_mobile'); 
    ?>
    <select name="col_width_mobile" id="col_width_mobile" style="width:100%">
        <option value="column_6" <?php selected($option, 'column_6'); ?>>Two</option>
        <option value="column_12" <?php selected($option, 'column_12'); ?>>One</option>
    </select>   
    <?php
}

function container_width_callback()  {
    $option = get_option('container_width'); 
    ?>
    <select name="container_width" id="container_width" style="width:100%">
        <option value="full_width" <?php selected($option, 'full_width'); ?>>Full Width</option>
        <option value="in_grid" <?php selected($option, 'in_grid'); ?>>In Grid</option>
    </select>   
    <?php
}

function tab_background_color_callback() {
    $option = get_option('tab_background_color');
    echo '<input type="color" name="tab_background_color" value="' . esc_attr($option) . '" />';
}

function tab_text_color_callback() {
    $option = get_option('tab_text_color');
    echo '<input type="color" name="tab_text_color" value="' . esc_attr($option) . '" />';
}

function tab_padding_callback() {
    $option = get_option('tab_padding');
    echo '<input type="text" name="tab_padding" value="' . esc_attr($option) . '" placeholder="e.g., 10px 15px" />';
}

function tab_margin_callback() {
    $option = get_option('tab_margin');
    echo '<input type="text" name="tab_margin" value="' . esc_attr($option) . '" placeholder="e.g., 10px 15px" />';
}

function tab_border_callback() {
    $option = get_option('tab_border');
    echo '<input type="text" name="tab_border" value="' . esc_attr($option) . '" placeholder="e.g., 1px solid #000" />';
}

function content_background_color_callback() {
    $option = get_option('content_background_color');
    echo '<input type="color" name="content_background_color" value="' . esc_attr($option) . '" />';
}

function content_text_color_callback() {
    $option = get_option('content_text_color');
    echo '<input type="color" name="content_text_color" value="' . esc_attr($option) . '" />';
}

function content_padding_callback() {
    $option = get_option('content_padding');
    echo '<input type="text" name="content_padding" value="' . esc_attr($option) . '" placeholder="e.g., 10px 15px" />';
}

function content_margin_callback() {
    $option = get_option('content_margin');
    echo '<input type="text" name="content_margin" value="' . esc_attr($option) . '" placeholder="e.g., 10px 15px" />';
}

function content_border_callback() {
    $option = get_option('content_border');
    echo '<input type="text" name="content_border" value="' . esc_attr($option) . '" placeholder="e.g., 1px solid #000" />';
}



function advanced_option_1_callback() {
    $option = get_option('advanced_option_1');
    echo '<input type="color" name="advanced_option_1" value="' . esc_attr($option) . '" />';
}

function advanced_option_2_callback() {
    $option = get_option('advanced_option_2');
    echo '<input type="color" name="advanced_option_2" value="' . esc_attr($option) . '" />';
}

function title_tag_style_callback() {
    $option = get_option('title_tag');
    ?>
    <select name="title_tag" id="title_tag" style="width:100%">
        <option value="h1" <?php selected($option, 'h1'); ?>>H1</option>
        <option value="h2" <?php selected($option, 'h2'); ?>>H2</option>
        <option value="h3" <?php selected($option, 'h3'); ?>>H3</option>
        <option value="h4" <?php selected($option, 'h4'); ?>>H4</option>
        <option value="h5" <?php selected($option, 'h5'); ?>>H5</option>
        <option value="h6" <?php selected($option, 'h6'); ?>>H6</option>
        <option value="p" <?php selected($option, 'p'); ?>>P</option>
    </select>
    <?php
}

function archive_border_style_callback() {
    $option = get_option('archive_border_style');
    ?>
    <select name="archive_border_style" id="archive_border_style" style="width:100%">
        <option value="solid" <?php selected($option, 'solid'); ?>>Solid</option>
        <option value="dashed" <?php selected($option, 'dashed'); ?>>Dashed</option>
        <option value="dotted" <?php selected($option, 'dotted'); ?>>Dotted</option>
        <option value="double" <?php selected($option, 'double'); ?>>Double</option>
        <option value="none" <?php selected($option, 'none'); ?>>None</option>
    </select>
    <?php
}

function archive_background_style_callback() {
    $option = get_option('archive_background_style');
    ?>
    <input type="color" name="archive_background_style" value="<?php echo esc_attr($option); ?>" placeholder="e.g., #ffffff or rgba(255,255,255,0.8)" />
    <?php
}

function archive_border_callback() {
    $option = get_option('archive_border');
    ?>
    <input type="text" name="archive_border" value="<?php echo esc_attr($option); ?>" placeholder="e.g., 1px solid #000" />
    <?php
}

function archive_padding_callback() {
    $option = get_option('archive_padding');
    ?>
    <input type="text" name="archive_padding" value="<?php echo esc_attr($option); ?>" placeholder="e.g., 20px" />
    <?php
}

function archive_margin_callback() {
    $option = get_option('archive_margin');
    ?>
    <input type="text" name="archive_margin" value="<?php echo esc_attr($option); ?>" placeholder="e.g., 20px" />
    <?php
}

function archive_shadow_callback() {
    $option = get_option('archive_shadow');
    ?>
    <input type="text" name="archive_shadow" value="<?php echo esc_attr($option); ?>" placeholder="e.g., 0px 4px 6px rgba(0, 0, 0, 0.1)" />
    <?php
}

function archive_image_size_callback() {
    $option = get_option('archive_image_size');
    ?>
    <select name="archive_image_size" id="archive_image_size" style="width:100%">
        <option value="thumbnail" <?php selected($option, 'thumbnail'); ?>>Thumbnail</option>
        <option value="medium" <?php selected($option, 'medium'); ?>>Medium</option>
        <option value="large" <?php selected($option, 'large'); ?>>Large</option>
        <option value="full" <?php selected($option, 'full'); ?>>Full</option>
    </select>
    <?php
}

function archive_date_view_callback() {
    $option = get_option('archive_date_view');
    ?>
    <select name="archive_date_view" id="archive_date_view" style="width:100%">
        <option value="show" <?php selected($option, 'show'); ?>>Show</option>
        <option value="hide" <?php selected($option, 'hide'); ?>>Hide</option>
    </select>
    <?php
}


