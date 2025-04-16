<?php
function render_custom_settings_page() {
    $tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';
    ?>
    <div class="wrap">
        <h1>Property Settings</h1>
        <h2 class="nav-tab-wrapper">
            <a href="?post_type=property&page=property-settings&tab=general" class="nav-tab <?php echo $tab == 'general' ? 'nav-tab-active' : ''; ?>">General</a>
            <a href="?post_type=property&page=property-settings&tab=advanced" class="nav-tab <?php echo $tab == 'advanced' ? 'nav-tab-active' : ''; ?>">Advanced</a>
            <a href="?post_type=property&page=property-settings&tab=tab-settings" class="nav-tab <?php echo $tab == 'tab-settings' ? 'nav-tab-active' : ''; ?>">Tab Settings</a>
            <a href="?post_type=property&page=property-settings&tab=tab-content" class="nav-tab <?php echo $tab == 'tab-content' ? 'nav-tab-active' : ''; ?>">Tab Content</a>
            <a href="?post_type=property&page=property-settings&tab=archive-style" class="nav-tab <?php echo $tab == 'archive-style' ? 'nav-tab-active' : ''; ?>">Archive Style</a>
        </h2>
        <form method="post" action="options.php">
            <?php
            // Load different sections based on the tab
            switch ($tab) {
                case 'tab-content':
                    settings_fields('tab_content_settings');
                    do_settings_sections('tab_content_settings');
                    break;
                case 'tab-settings':
                    settings_fields('tab_settings');
                    do_settings_sections('tab_settings');
                    break;
                case 'advanced':
                    settings_fields('advanced_settings');
                    do_settings_sections('advanced_settings');
                    break;
                case 'general':
                default:
                    settings_fields('general_settings');
                    do_settings_sections('general_settings');
                    break;
                case 'archive-style':
                    settings_fields('archive_style_settings');
                    do_settings_sections('archive_style_settings');
                break;
            }
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
    