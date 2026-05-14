<style>
    .property-card {
    background: #fff;
    overflow: hidden;
    height: 100%;
    border-radius:8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.property-card__link {
    text-decoration: none;
    display: block;
}

.property-card__image {
    height: 320px;
    background-size: cover;
    background-position: center;
}

.property-card__location {
    background: #009fc2;
    color: #fff;
    padding: 10px;
    margin-bottom: 5px;
}

.property-card__content {
    padding: 15px;
}

.property-card__size,
.property-card__excerpt {
    color: #000;
    margin-bottom: 10px;
}

.market-status-badge {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 5px;
    color: #fff;
    font-size: 13px;
    line-height: 1.5;
}

.property-card__footer {
    padding: 0 15px 15px;
}

.property-card__button {
    background-color: #009fc2;
    border-color: #009fc2;
    color: #fff;
    padding: 5px 15px;
    border-radius: 3px;
}
</style>
<?php
$post_id   = get_the_ID();
$permalink = get_permalink($post_id);
$column        = !empty($atts['column']) ? (int) $atts['column'] : 4;
$excerpt_align = !empty($atts['excerpt_text_align']) ? esc_attr($atts['excerpt_text_align']) : 'left';
$photo_url = get_post_meta($post_id, 'first_image', true);
$terms     = get_the_terms($post_id, 'location');
$size_data = maybe_unserialize(get_post_meta($post_id, 'Size', true));
$bullets   = maybe_unserialize(get_post_meta($post_id, 'Bullets', true));
$market_status = get_post_meta($post_id, 'MarketStatus', true);
$dimension_name = $size_data['Dimension']['Name'] ?? '';
$min = !empty($size_data['MinSize']) ? $size_data['MinSize'] : null;
$max = !empty($size_data['MaxSize']) ? $size_data['MaxSize'] : null;
$size_label = '';
if ($min && $max) {
    $size_label = ($min == $max) ? $max : "{$min} - {$max}";
} elseif ($max) {
    $size_label = $max;
} elseif ($min) {
    $size_label = $min;
}
$first_bullet_text = '';
if (!empty($bullets) && is_array($bullets)) {
    $first_bullet = reset($bullets);

    if (!empty($first_bullet['BulletPoint'])) {
        $first_bullet_text = $first_bullet['BulletPoint'];
    }
}
$status_colors = [
    'Available'                            => '#28a745',
    'Under Offer - Solicitors Instructed' => '#dc3545',
    'Sold (Show on Web)'                  => '#6c757d',
    'Let (show on web)'                   => '#ff9800',
];

$status_labels = [
    'Sold (Show on Web)' => 'Sold',
    'Let (show on web)'  => 'Let',
];

$status_color = $status_colors[$market_status] ?? '#000';

$status_label = $status_labels[$market_status] ?? $market_status;
?>

<div class="col-xl-<?php echo esc_attr($column); ?> col-lg-6 mb-4">
    
    <div class="property-card">

        <a class="property-card__link" href="<?php echo esc_url($permalink); ?>">

            <?php if (!empty($photo_url)) : ?>
                <div 
                    class="property-card__image"
                    style="background-image: url('<?php echo esc_url($photo_url); ?>');">
                </div>
            <?php endif; ?>

            <?php if (!empty($terms) && !is_wp_error($terms)) : ?>
                <p class="property-card__location">
                    <?php echo esc_html($terms[0]->name); ?>
                </p>
            <?php endif; ?>

            <div class="property-card__content">

                <?php if (!empty($size_label)) : ?>
                    <p class="property-card__size">
                        <?php echo esc_html($size_label . ' ' . $dimension_name); ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($first_bullet_text)) : ?>
                    <p 
                        class="property-card__excerpt"
                        style="text-align: <?php echo $excerpt_align; ?>;">
                        <?php echo esc_html($first_bullet_text); ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($market_status)) : ?>
                    <span 
                        class="market-status-badge"
                        style="background-color: <?php echo esc_attr($status_color); ?>;">
                        <?php echo esc_html($status_label); ?>
                    </span>
                <?php endif; ?>

            </div>

        </a>

        <div class="property-card__footer">
            <a 
                href="<?php echo esc_url($permalink); ?>" 
                class="btn btn-primary property-card__button">
                View Details
            </a>
        </div>

    </div>
</div>