<?php 
if (!current_user_can('manage_options')) {
    return;
}
?>
<div class="wrap">
    <h1 class="wp-heading-inline"><b>Property Importer Dashboard</b></h1>
    <hr class="wp-header-end">
    
    <div class="dashboard-description">
        <p class="description">Welcome to the Property Importer dashboard. This tool helps you manage and import properties from various sources. Choose from the options below to get started:</p>
        <div class="notice notice-info inline">
            <p><span class="dashicons dashicons-info"></span> New to Property Importer? Start with Manual Import for single uploads or configure Settings for automated imports.</p>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="card-header">
                <span class="dashicons dashicons-upload"></span>
                <h2>Manual Import</h2>
            </div>
            <div class="card-content">
                <p>Import properties manually by uploading JSON file or using API.</p>
                <a href="<?php echo admin_url('admin.php?page=manually-import'); ?>" class="button button-primary">Go to Manual Import</a>
            </div>
        </div>
        <div class="dashboard-card">
            <div class="card-header">
                <span class="dashicons dashicons-admin-settings"></span>
                <h2>Settings</h2>
            </div>
            <div class="card-content">
            <p>Configure automatic property imports like API Keys, URL'S, Import Interval,Import Options and more.</p>
                <a href="<?php echo admin_url('admin.php?page=settings'); ?>" class="button button-primary">Configure Auto Import</a>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-header">
                <span class="dashicons dashicons-list-view"></span>
                <h2>Manage Import</h2>
            </div>
            <div class="card-content">
                <p>View and manage imported properties.</p>
                <a href="<?php echo admin_url('admin.php?page=manage-import'); ?>" class="button button-primary">View Imports</a>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-header">
                <span class="dashicons dashicons-trash"></span>
                <h2>Delete Properties</h2>
            </div>
            <div class="card-content">
                <p>Remove all imported properties from the system.</p>
                <a href="<?php echo admin_url('admin.php?page=delete-properties'); ?>" class="button button-warning">Delete Properties</a>
            </div>
        </div>
    </div>
</div>

<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        padding: 20px 0;
    }

    .dashboard-card {
        background: #fff;
        border: 1px solid #ccd0d4;
        box-shadow: 0 1px 1px rgba(0,0,0,0.04);
    }

    .card-header {
        border-bottom: 1px solid #ccd0d4;
        padding: 15px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
    }

    .card-header .dashicons {
        font-size: 24px;
        width: 24px;
        height: 24px;
        margin-right: 10px;
        color: #2271b1;
    }

    .card-header h2 {
        margin: 0;
        font-size: 16px;
        line-height: 1.4;
    }

    .card-content {
        padding: 15px;
    }

    .card-content p {
        margin: 0 0 15px 0;
        color: #50575e;
    }

    .button-warning {
        background: #dc3232;
        border-color: #dc3232;
        color: #fff;
    }

    .button-warning:hover {
        background: #c92424;
        border-color: #c92424;
        color: #fff;
    }

    @media screen and (max-width: 782px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    .dashboard-description {
        margin: 20px 0;
        max-width: 800px;
    }
    
    .dashboard-description .description {
        font-size: 14px;
        line-height: 1.5;
        color: #50575e;
        margin-bottom: 15px;
    }
    
    .dashboard-description .notice {
        margin: 10px 0;
        padding: 10px 12px;
    }
    
    .dashboard-description .notice .dashicons {
        color: #72aee6;
        margin-right: 5px;
        vertical-align: middle;
    }
</style>