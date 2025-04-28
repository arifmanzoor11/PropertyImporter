<div class="col-xl-<?php echo $atts['column'] ?> col-lg-6 mb-4">
    <div class="property-card">
        <div class="property-content">
      <a style="text-decoration:none" href="<?php the_permalink(); ?>">
        <?php $photo_url = get_post_meta(get_the_ID(), 'first_image', true); ?>
        <div style="background: url('<?php echo esc_url($photo_url); ?>'); background-size: cover; background-position: center; height: 320px;"></div>   
       
        <?php $terms = get_the_terms(get_the_ID(), 'location');
                if (!empty($terms) && !is_wp_error($terms)) {
                    echo '<p style="color:#000; margin-bottom:5px; background:#009fc2;padding:10px;color:#fff; margin-bottom:5px">' . esc_html($terms[0]->name) . '</p>';
            } ?>
             <div class="text-container">
            <?php 
            $size_data = get_post_meta(get_the_ID(), 'Size', true);
           
            if (is_serialized($size_data)) {
                $size_data = unserialize($size_data);
                
            }
            $dimension_name = $size_data['Dimension']['Name'];
            // Handle custom display for Min/Max Size
            $min = isset($size_data['MinSize']) && !empty($size_data['MinSize']) ? $size_data['MinSize'] : null;
            $max = isset($size_data['MaxSize']) && !empty($size_data['MaxSize']) ? $size_data['MaxSize'] : null;
            
            $size_label = '';
            
            if ($min && $max) {
                $size_label = ($min == $max) ? $max : "$min - $max";
            } elseif ($max) {
                $size_label = $max;
            } elseif ($min) {
                $size_label = $min;
            }
            
            if ($size_label) {
                echo '<p style="color:#000; margin-bottom:5px;">' . esc_html($size_label) . ' ' . $dimension_name . '</p>';
            }
            if (is_array($size_data)) {
               // echo '<p style="color:#000; margin-bottom:5px;">Total: ' . esc_html($size_data['TotalSize']) . ' ' . esc_html($size_data['Dimension']['Name']) . '</p>';
            }
            $bullets_data = get_post_meta(get_the_ID(), 'Bullets', true);
            $unserialized_bullets = maybe_unserialize($bullets_data);
            if (!empty($unserialized_bullets) && is_array($unserialized_bullets)) {
                $first_bullet = reset($unserialized_bullets);
                if (isset($first_bullet['BulletPoint'])) {
                    echo '<p style="color:#000; margin-bottom:5px;">' . esc_html($first_bullet['BulletPoint']) . '</p>';
                }
            }
            ?>
            <?php
            $market_status_meta = get_post_meta(get_the_ID(), 'MarketStatus', true);
            if (!empty($market_status_meta)):
                $status_colors = [
                    'Available' => 'green',
                    'Under Offer - Solicitors Instructed' => 'red',
                    'Sold (Show on Web)' => 'grey'
                ];
                $color = isset($status_colors[$market_status_meta]) ? $status_colors[$market_status_meta] : 'black';
                $label = ($market_status_meta == 'Sold (Show on Web)') ? 'Sold' : $market_status_meta;
                ?>
                <span class="market-status-badge" style="background: <?php echo $color; ?>; 
                                    line-height: 1.5; padding: 5px 10px; border-radius: 5px; color: white; 
                                    font-size: 13px; display: inline-block; margin-bottom: 5px; display:table;">
                    <?php echo esc_html($label); ?>
                </span>
            <?php endif; ?>
            </a>
            <a style="background-color: #009fc2; border-color: #009fc2;
            color: #ffffff; padding: 5px 15px;margin: 0 0 10px;
            border-radius: 3px;" href="<?php the_permalink(); ?>" class="btn btn-primary">View Details</a>
            </div>
        </div>
    </div>
</div>