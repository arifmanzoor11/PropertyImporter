<?php
if (!current_user_can('manage_options')) {
    return;
}
?>
<div class="wrap documentation-page">
    <h1 class="wp-heading-inline">Property Importer Documentation</h1>
    <hr class="wp-header-end">

    <div class="documentation-grid">
        <!-- Plugin Information Section -->
        <div class="doc-section full-width">
            <h2><span class="dashicons dashicons-info-outline"></span> Plugin Information</h2>
            <div class="doc-content">
                <h3>Property Importer v2.0</h3>
                <p class="plugin-description">A WordPress plugin that imports properties from an external API into a custom post type. 
                It provides various functionalities such as manual and automatic imports, settings management, 
                and property deletion. The plugin also includes custom templates, styles, and scripts for 
                displaying properties on the front-end.</p>
                
                <h4>Core Features</h4>
                <ul class="feature-list">
                    <li><span class="dashicons dashicons-yes"></span> Imports properties from an external API into a custom post type</li>
                    <li><span class="dashicons dashicons-yes"></span> Supports manual and automatic property imports</li>
                    <li><span class="dashicons dashicons-yes"></span> Includes custom templates for property display</li>
                    <li><span class="dashicons dashicons-yes"></span> Provides shortcodes for front-end display</li>
                    <li><span class="dashicons dashicons-yes"></span> Implements AJAX for dynamic interactions</li>
                </ul>

                <h4>Plugin Structure</h4>
                <pre><code>PropertyImporter/
├── import-main.php
├── inc/
│   ├── helper-functions.php
│   └── database-management.php
├── admin/
│   ├── inc/
│   │   ├── settings.php
│   │   ├── import-management.php
│   │   └── meta-boxes.php
│   ├── views/
│   └── store/
├── views/renders/
├── register-post-type.php
├── schedule-task.php
└── assets/
    ├── css/
    └── js/</code></pre>
            </div>
        </div>

        <!-- Getting Started Section -->
        <div class="doc-section">
            <h2><span class="dashicons dashicons-welcome-learn-more"></span> Getting Started</h2>
            <div class="doc-content">
                <h3>Overview</h3>
                <p>Property Importer allows you to import property listings from various sources into your WordPress site. You can import properties either manually via JSON files or automatically through API integration.</p>
                
                <h3>Key Features</h3>
                <ul>
                    <li>Manual JSON file import</li>
                    <li>Automatic API integration</li>
                    <li>Customizable import settings</li>
                    <li>Property management tools</li>
                </ul>
            </div>
        </div>

        <!-- JSON Format Section -->
        <div class="doc-section">
            <h2><span class="dashicons dashicons-media-code"></span> JSON File Format</h2>
            <div class="doc-content">
                <h3>Required Structure</h3>
                <pre><code>[
  {
    "title": "Property Title",
    "description": "Property Description",
    "price": "250000",
    "location": "Property Address",
    "property_type": "residential/commercial",
    "bedrooms": "3",
    "bathrooms": "2"
  }
]</code></pre>
                <div class="notice notice-info">
                    <p>All properties must be enclosed in an array, even if importing a single property.</p>
                </div>
            </div>
        </div>

        <!-- Manual Import Section -->
        <div class="doc-section">
            <h2><span class="dashicons dashicons-upload"></span> Manual Import Guide</h2>
            <div class="doc-content">
                <h3>Steps to Import</h3>
                <ol>
                    <li>Navigate to "Manual Import" from the dashboard</li>
                    <li>Prepare your JSON file according to the format above</li>
                    <li>Upload using the drag & drop interface or file browser</li>
                    <li>Review the import summary</li>
                    <li>Confirm to complete the import</li>
                </ol>
            </div>
        </div>

        <!-- API Integration Section -->
        <div class="doc-section">
            <h2><span class="dashicons dashicons-rest-api"></span> API Integration</h2>
            <div class="doc-content">
                <h3>Configuration</h3>
                <ol>
                    <li>Go to Settings page</li>
                    <li>Enter your API credentials</li>
                    <li>Set the import interval</li>
                    <li>Configure property mapping</li>
                    <li>Save settings to activate automatic imports</li>
                </ol>
            </div>
        </div>

        <!-- Troubleshooting Section -->
        <div class="doc-section">
            <h2><span class="dashicons dashicons-info"></span> Troubleshooting</h2>
            <div class="doc-content">
                <h3>Common Issues</h3>
                <div class="troubleshooting-item">
                    <h4>Import Fails to Start</h4>
                    <p>Ensure your JSON file follows the required format and is properly formatted.</p>
                </div>
                <div class="troubleshooting-item">
                    <h4>Images Not Importing</h4>
                    <p>Verify that image URLs are accessible and your server has sufficient permissions.</p>
                </div>
                <div class="troubleshooting-item">
                    <h4>API Connection Issues</h4>
                    <p>Double-check your API credentials and ensure your server can make external connections.</p>
                </div>
            </div>
        </div>

        <!-- Hooks and Filters Section -->
        <div class="doc-section">
            <h2><span class="dashicons dashicons-admin-plugins"></span> Hooks & Filters</h2>
            <div class="doc-content">
                <h3>Available Hooks</h3>
                <div class="hook-list">
                    <div class="hook-item">
                        <code>admin_enqueue_scripts</code>
                        <p>Enqueues admin-specific styles and scripts</p>
                    </div>
                    <div class="hook-item">
                        <code>wp_enqueue_scripts</code>
                        <p>Enqueues front-end styles and scripts</p>
                    </div>
                    <div class="hook-item">
                        <code>archive_template</code>
                        <p>Filters the archive template for properties</p>
                    </div>
                </div>
            </div>
        </div>

       <!-- Shortcodes Section -->
        <div class="doc-section">
            <h2><span class="dashicons dashicons-shortcode"></span> Shortcodes Properties</h2>
            <div class="doc-content">
                <h3>Available Shortcodes</h3>
                <div class="shortcode-example">
                    <code>[display_properties]</code>
                    <p>Displays a grid of properties with filtering options</p>
                    <h4>Parameters:</h4>
                    <ul>
                        <li><code>posts_per_page</code>: Number of properties to display (default: -1 for all)</li>
                        <li><code>column</code>: Number of columns to display properties (default: 3)</li>
                        <li><code>slider</code>: Whether to display in a slider (default: false)</li>
                        <li><code>featured</code>: Whether to show only featured properties (default: false)</li>
                        <li><code>show_filters</code>: Whether to show filters (default: true)</li>
                        <li><code>property_type</code>: Filter by property type</li>
                        <li><code>location</code>: Filter by location</li>
                        <li><code>min_size</code>: Minimum property size</li>
                        <li><code>max_size</code>: Maximum property size</li>
                        <li><code>meta_filters</code>: Additional meta filters</li>
                        <li><code>orderby</code>: Sort properties by price, date, etc.</li>
                        <li><code>order</code>: Order direction (default: DESC)</li>
                        <li><code>cat_show</code>: Categories to show</li>
                        <li><code>excerpt_text_align</code>: Excerpt text alignment (default: left)</li>
                        <li><code>taxonomy_include</code>: Taxonomies to include (default: tenure, location, property_type)</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Shortcodes Section -->
        <div class="doc-section">
            <h2><span class="dashicons dashicons-shortcode"></span> Shortcodes Categories</h2>
            <div class="doc-content">
                <h3>Available Shortcodes</h3>
                <div class="shortcode-example">
                    <code>[show_categories]</code>
                    <p>Displays a list of categories based on given term IDs, taxonomy, and optional custom archive slugs</p>
                    <h4>Parameters:</h4>
                    <ul>
                        <li><code>ids</code>: Comma-separated term IDs (required)</li>
                        <li><code>taxonomy</code>: Taxonomy to filter by (default: 'property')</li>
                        <li><code>custom_archive_slug</code>: Optional comma-separated list of custom archive URLs</li>
                    </ul>
                </div>
            </div>
        </div>



    </div>
</div>

<style>
.documentation-page {
    max-width: 1200px;
    margin: 20px auto;
}

.documentation-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.doc-section {
    background: #fff;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,0.04);
}

.doc-section h2 {
    margin: 0;
    padding: 15px;
    border-bottom: 1px solid #ccd0d4;
    font-size: 16px;
    line-height: 1.4;
    background: #f8f9fa;
    display: flex;
    align-items: center;
}

.doc-section h2 .dashicons {
    margin-right: 10px;
    color: #2271b1;
}

.doc-content {
    padding: 20px;
}

.doc-content h3 {
    margin: 0 0 15px;
    color: #1d2327;
}

.doc-content pre {
    background: #f6f7f7;
    padding: 15px;
    border: 1px solid #dcdcde;
    border-radius: 3px;
    overflow-x: auto;
}

.doc-content code {
    font-family: monospace;
    font-size: 13px;
}

.troubleshooting-item {
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #f0f0f1;
}

.troubleshooting-item:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.troubleshooting-item h4 {
    margin: 0 0 5px;
    color: #1d2327;
}

.troubleshooting-item p {
    margin: 0;
    color: #50575e;
}

.full-width {
    grid-column: 1 / -1;
}

.feature-list {
    list-style: none;
    padding: 0;
}

.feature-list li {
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}

.feature-list .dashicons {
    color: #46b450;
    margin-right: 10px;
}

.plugin-description {
    font-size: 14px;
    line-height: 1.6;
    color: #50575e;
    margin-bottom: 20px;
}

.hook-list {
    background: #f6f7f7;
    border: 1px solid #dcdcde;
    border-radius: 3px;
    padding: 15px;
}

.hook-item {
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #dcdcde;
}

.hook-item:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.hook-item code {
    display: inline-block;
    background: #ffffff;
    padding: 3px 8px;
    border-radius: 3px;
    border: 1px solid #dcdcde;
}

.shortcode-example {
    background: #f6f7f7;
    padding: 15px;
    border: 1px solid #dcdcde;
    border-radius: 3px;
}

.shortcode-example code {
    display: inline-block;
    background: #ffffff;
    padding: 3px 8px;
    margin-bottom: 10px;
    border-radius: 3px;
    border: 1px solid #dcdcde;
}

pre {
    background: #f6f7f7;
    padding: 15px;
    border: 1px solid #dcdcde;
    border-radius: 3px;
    overflow-x: auto;
}

code {
    font-family: Consolas, Monaco, 'Andale Mono', monospace;
    font-size: 13px;
}

@media screen and (max-width: 782px) {
    .documentation-grid {
        grid-template-columns: 1fr;
    }
}
</style>