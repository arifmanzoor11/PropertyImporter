
    <div class="wrap documentation-page">
        <h1 class="wp-heading-inline">Property Importer Documentation</h1>
        <hr class="wp-header-end">

        <div class="documentation-grid">
            <!-- Shortcode Section -->
            <div class="doc-section full-width">
                <h2><span class="dashicons dashicons-shortcode"></span> Shortcode Usage</h2>
                <div class="doc-content">
                    <h3>Basic Usage</h3>
                    <div class="code-example">
                        <code>[display_properties]</code>
                    </div>

                    <h3>Available Parameters</h3>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th>Parameter</th>
                                <th>Default</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>posts_per_page</code></td>
                                <td>9</td>
                                <td>Number of properties to display per page</td>
                            </tr>
                            <tr>
                                <td><code>column</code></td>
                                <td>3</td>
                                <td>Number of columns in the grid</td>
                            </tr>
                            <tr>
                                <td><code>show_filters</code></td>
                                <td>true</td>
                                <td>Show/hide the filter options</td>
                            </tr>
                            <tr>
                                <td><code>taxonomy_include</code></td>
                                <td>property_type,location,tenure,size</td>
                                <td>Comma-separated list of taxonomies to include in filters</td>
                            </tr>
                        </tbody>
                    </table>

                    <h3>Example with Parameters</h3>
                    <div class="code-example">
                        <code>[display_properties posts_per_page="12" column="4" show_filters="true" taxonomy_include="property_type,location"]</code>
                    </div>
                </div>
            </div>

            <!-- Settings Section -->
            <div class="doc-section">
                <h2><span class="dashicons dashicons-admin-settings"></span> Plugin Settings</h2>
                <div class="doc-content">
                    <h3>General Settings</h3>
                    <ul class="settings-list">
                        <li><strong>Import Interval:</strong> Set how often the automatic import runs</li>
                        <li><strong>API Settings:</strong> Configure your API credentials and endpoints</li>
                        <li><strong>Import Options:</strong> Choose what data to import and update</li>
                    </ul>

                    <h3>Archive Style Settings</h3>
                    <ul class="settings-list">
                        <li><strong>Layout:</strong> Grid or List view for property archives</li>
                        <li><strong>Columns:</strong> Number of columns in grid view</li>
                        <li><strong>Image Size:</strong> Thumbnail dimensions</li>
                    </ul>
                </div>
            </div>

            <!-- Data Format Section -->
            <div class="doc-section">
                <h2><span class="dashicons dashicons-media-code"></span> JSON Format</h2>
                <div class="doc-content">
                    <h3>Required Structure</h3>
                    <pre><code>{
  "properties": [
    {
      "title": "Property Title",
      "description": "Full description",
      "price": "250000",
      "size": "1500",
      "location": "Property Address",
      "property_type": "commercial",
      "features": ["Feature 1", "Feature 2"]
    }
  ]
}</code></pre>
                </div>
            </div>
        </div>
    </div>

    <style>
  
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

    .full-width {
        grid-column: 1 / -1;
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

    .code-example {
        background: #f6f7f7;
        padding: 15px;
        border: 1px solid #dcdcde;
        border-radius: 3px;
        margin: 10px 0;
    }

    .code-example code {
        display: block;
        white-space: pre-wrap;
        font-family: Consolas, Monaco, monospace;
    }

    .widefat {
        width: 100%;
        margin: 15px 0;
        border-collapse: collapse;
    }

    .widefat th, .widefat td {
        padding: 12px;
        text-align: left;
        border: 1px solid #ccd0d4;
    }

    .widefat th {
        background: #f8f9fa;
    }

    .settings-list {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .settings-list li {
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f0f0f1;
    }

    .settings-list li:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    pre {
        background: #f6f7f7;
        padding: 15px;
        border: 1px solid #dcdcde;
        border-radius: 3px;
        overflow-x: auto;
        margin: 10px 0;
    }

    @media screen and (max-width: 782px) {
        .documentation-grid {
            grid-template-columns: 1fr;
        }
        
        .widefat {
            display: block;
            overflow-x: auto;
        }
    }
    </style>
