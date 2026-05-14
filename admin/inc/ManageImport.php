<?php
function manage_import_page() {
    global $wpdb;

    $table_name      = $wpdb->prefix . 'manage_import';
    $meta_table_name = $wpdb->prefix . 'import_meta';

    $per_page = 10;

    // Current page
    $paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset = ($paged - 1) * $per_page;

    // Handle delete
    if (
        isset($_POST['delete_selected'], $_POST['selected_ids'], $_POST['_wpnonce']) &&
        wp_verify_nonce($_POST['_wpnonce'], 'delete_import_nonce')
    ) {
        $selected_ids = array_map('intval', $_POST['selected_ids']);
        $deleted_count = 0;

        foreach ($selected_ids as $delete_id) {

            $file_url = $wpdb->get_var(
                $wpdb->prepare("SELECT url FROM $table_name WHERE id = %d", $delete_id)
            );

            if ($file_url) {
                $upload_dir = wp_upload_dir();
                $file_path  = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $file_url);

                if ($wpdb->delete($table_name, ['id' => $delete_id], ['%d'])) {
                    $deleted_count++;

                    $wpdb->delete($meta_table_name, ['import_id' => $delete_id], ['%d']);

                    if (!empty($file_path) && file_exists($file_path)) {
                        wp_delete_file($file_path);
                    }
                }
            }
        }

        echo '<div class="notice notice-success is-dismissible"><p>' .
            sprintf(_n('%d item deleted.', '%d items deleted.', $deleted_count), $deleted_count) .
            '</p></div>';
    }

    // TOTAL COUNT (important for pagination)
    $total_items = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

    // FETCH PAGINATED DATA
    $imports = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name ORDER BY id DESC LIMIT %d OFFSET %d",
            $per_page,
            $offset
        ),
        ARRAY_A
    );

    // Get IDs for meta query
    $ids = wp_list_pluck($imports, 'id');

    $meta_grouped = [];

    if (!empty($ids)) {
        $placeholders = implode(',', array_fill(0, count($ids), '%d'));

        $all_meta = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $meta_table_name WHERE import_id IN ($placeholders)",
                $ids
            ),
            ARRAY_A
        );

        foreach ($all_meta as $meta) {
            $meta_grouped[$meta['import_id']][$meta['meta_key']] = $meta['meta_value'];
        }
    }

    $total_pages = ceil($total_items / $per_page);
    ?>

    <div class="wrap manage-import-container">
        <h1 class="wp-heading-inline">Manage Import Data</h1>
        <hr class="wp-header-end">

        <?php if (!empty($imports)) : ?>

            <form method="post">
                <?php wp_nonce_field('delete_import_nonce'); ?>

                <div class="tablenav top">
                    <input type="submit" class="button action" name="delete_selected"
                        value="Delete Selected"
                        onclick="return confirm('Are you sure?');">
                </div>

                <table class="wp-list-table widefat fixed striped import-table">
                    <thead>
                        <tr>
                            <th style="width:40px;"><input type="checkbox" id="select_all"></th>
                            <th>Import Details</th>
                            <th style="width:160px;">Stats</th>
                            <th style="width:140px;">Date</th>
                            <th style="width:120px;">Type</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($imports as $row) :

                        $meta = $meta_grouped[$row['id']] ?? [];

                        $duration = intval($meta['import_duration'] ?? 0);

                        if ($duration >= 3600) {
                            $duration_text = round($duration / 3600, 1) . ' hrs';
                        } elseif ($duration >= 60) {
                            $duration_text = round($duration / 60, 1) . ' mins';
                        } else {
                            $duration_text = $duration . ' sec';
                        }

                        $date = !empty($meta['import_date'])
                            ? date('D j M g:i A', strtotime($meta['import_date']))
                            : '-';

                        $file_name = !empty($meta['file_name'])
                            ? $meta['file_name']
                            : basename($row['url']);
                    ?>

                    <tr class="import-row">
                        <!-- Checkbox -->
                        <td>
                            <input type="checkbox" name="selected_ids[]" value="<?php echo esc_attr($row['id']); ?>">
                        </td>

                        <!-- Main Info -->
                        <td>
                            <div class="import-main">
                                <div class="import-title">
                                    <?php echo esc_html($row['name']); ?>
                                </div>

                                <div class="import-file">
                                    📄 
                                    <a href="<?php echo esc_url($row['url']); ?>" target="_blank">
                                        <?php echo esc_html($file_name); ?>
                                    </a>
                                </div>

                                <div class="import-meta">
                                    ID: <?php echo esc_html($row['id']); ?> • Duration: <?php echo esc_html($duration_text); ?>
                                </div>
                            </div>
                        </td>

                        <!-- Stats -->
                        <td>
                            <div class="import-stats">
                                <?php if (!empty($meta['imported_count'])) : ?>
                                    <span class="badge success">+<?php echo esc_html($meta['imported_count']); ?></span>
                                <?php endif; ?>

                                <?php if (!empty($meta['updated_count'])) : ?>
                                    <span class="badge warning">↻ <?php echo esc_html($meta['updated_count']); ?></span>
                                <?php endif; ?>

                                <?php if (!empty($meta['total_entries'])) : ?>
                                    <span class="badge"><?php echo esc_html($meta['total_entries']); ?> total</span>
                                <?php endif; ?>
                            </div>
                        </td>

                        <!-- Date -->
                        <td>
                            <div class="import-date"><?php echo esc_html($date); ?></div>
                        </td>

                        <!-- Type -->
                        <td>
                            <span class="type-badge">
                                <?php echo esc_html($meta['import_type'] ?? '—'); ?>
                            </span>
                        </td>
                    </tr>

                    <?php endforeach; ?>

                    </tbody>
                </table>
            </form>

            <!-- PAGINATION -->
            <div class="tablenav bottom">
                <div class="tablenav-pages">
                    <?php
                    echo paginate_links([
                        'base'      => add_query_arg('paged', '%#%'),
                        'format'    => '',
                        'current'   => $paged,
                        'total'     => $total_pages,
                        'prev_text' => '«',
                        'next_text' => '»',
                    ]);
                    ?>
                </div>
            </div>

        <?php else : ?>
            <div class="notice notice-info"><p>No import data found.</p></div>
        <?php endif; ?>
    </div>

    <script>
    jQuery(function($) {
        $('#select_all').on('click', function() {
            $('input[name="selected_ids[]"]').prop('checked', this.checked);
        });
    });
    </script>
    <style>
        .import-table td {
    vertical-align: middle;
}

.import-main {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.import-title {
    font-weight: 600;
    font-size: 14px;
    color: #1d2327;
}

.import-file a {
    color: #2271b1;
    text-decoration: none;
}

.import-file a:hover {
    text-decoration: underline;
}

.import-meta {
    font-size: 12px;
    color: #777;
}

.import-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.badge {
    background: #e5e5e5;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 11px;
}

.badge.success {
    background: #d1f7d6;
    color: #137333;
}

.badge.warning {
    background: #fff4cc;
    color: #8a6d00;
}

.type-badge {
    background: #eef2ff;
    color: #3730a3;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    display: inline-block;
}

.import-date {
    font-size: 12px;
    color: #555;
}

.import-row:hover {
    background: #f6f7f7;
}

    </style>
    <?php
}
?>