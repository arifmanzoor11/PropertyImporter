<?php render_photo_style_two(get_the_ID()); ?>

<div class="property-details-container">
<br>
<a href="#" class="back-to-search" style="display: none;">Back to Search</a>
    <h1 class="property-two-title" style="margin-top: 20px; font-size:2.5rem">
        <?php the_title(); ?>
    </h1>
    <div class="property-details-row">
        <div class="property-column-8">
            <div class="property-meta">
                <span class="property-date"><?php the_date(); ?></span>
            </div>
            <div class="property-market-status">
            <br>
                <?php
                $market_status_meta = get_post_meta(get_the_ID(), 'MarketStatus', true);
                if (!empty($market_status_meta)) : ?>
                    <?php if ($market_status_meta == 'Available'): ?>
                        <span class="market-status-badge" style="background: green; line-height: 1.5; padding: 10px 20px; border-radius: 5px; color: white; font-size: 13px;">
                            <?php echo esc_html($market_status_meta); ?>
                        </span>
                        <br>
                
                    <?php elseif ($market_status_meta == 'Under Offer - Solicitors Instructed'): ?>
                        <span class="market-status-badge" style="background: red; line-height: 1.5; padding: 10px 20px; border-radius: 5px; color: white; font-size: 13px;">
                            <?php echo esc_html($market_status_meta); ?>
                        </span>
                        <br>
                
                    <?php elseif ($market_status_meta == 'Sold (Show on Web)'): ?>
                        <span class="market-status-badge" style="background: grey; line-height: 1.5; padding: 10px 20px; border-radius: 5px; color: white; font-size: 13px;">
                            <?php echo esc_html($market_status_meta); ?>
                        </span>
                        <br>
                
                    <?php else: ?>
                        <span class="market-status-badge" style="background: black; line-height: 1.5; padding: 10px 20px; border-radius: 5px; color: white; font-size: 13px;">
                            <?php echo esc_html($market_status_meta); ?>
                        </span>
                        <br>
                    <?php endif; ?>
                <?php endif; ?>
                
            </div>
            
            <?php
            // Fetch and display the 'Bullets' meta field if available
            $bullets_meta = get_post_meta(get_the_ID(), 'Bullets', true);
            if (!empty($bullets_meta)) :
                $bullets = maybe_unserialize($bullets_meta);
                if (is_array($bullets)) : ?>
                    <div class="property-bullets">
                        <h3>Key Points</h3>
                        <ul>
                            <?php foreach ($bullets as $bullet) :
                                if (!empty($bullet['BulletPoint'])) : ?>
                                    <li><?php echo esc_html($bullet['BulletPoint']); ?></li>
                                <?php endif;
                            endforeach; ?>
                        </ul>
                    </div>
                <?php endif;
            endif; ?>

            <div class="property-content-single">
            <?php 
            // echo $owners_data = get_post_meta(get_the_ID(), 'Owners', true); 
            $system_detail = get_post_meta(get_the_ID(), 'SystemDetail', true);
            $system_detail = maybe_unserialize($system_detail);

                // Display Account Managers
            if (isset($system_detail['AccountManagers']) && is_array($system_detail['AccountManagers'])) {
                echo '<ul>';
                foreach ($system_detail['AccountManagers'] as $manager) {
                    if (is_array($manager)) {
                        $name = isset($manager['Name']) ? esc_html($manager['Name']) : 'N/A';
                        $email = isset($manager['Email']) ? esc_html($manager['Email']) : 'N/A';
                        $telephone = isset($manager['Telephone']) ? esc_html($manager['Telephone']) : 'N/A';
                        echo '<h5 style="color: black;">Call ' . $name . ' on ' . $telephone . ' or email on ' . $email . '<br></h5>';
                    }
                }
                echo '</ul>';
            }
            ?>
                <?php the_content(); ?>
            </div>

           
        </div>
        <div class="property-column-4">

        <?php 
        $size_data = get_post_meta(get_the_ID(), 'Size', true);
        $size_data = maybe_unserialize($size_data);
        
        if (!is_array($size_data)) {
            return; // Exit if the size data is not an array
        }
        
        echo '<div class="property-size-data">';
        
        $dimension_name = isset($size_data['Dimension']['Name']) ? esc_html($size_data['Dimension']['Name']) : '';
        
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
            echo '<h3>Size: ' . esc_html($size_label) . ' ' . $dimension_name . '</h3>';
        }
        
        // If you still want to show TotalSize separately
        if (!empty($size_data['TotalSize'])) {
            echo '<p>Total Size: ' . esc_html($size_data['TotalSize']) . ' ' . $dimension_name . '</p>';
        }
        
        echo '</div>';
        
            $document_media = get_post_meta(get_the_ID(), 'DocumentMedia', true);
            if (!empty($document_media) && is_array($document_media)) {

                echo '<div>';
        
                // Loop through each attachment ID
                foreach ($document_media as $attachment_id) {
                    // Get the attachment URL
                    $attachment_url = wp_get_attachment_url($attachment_id);
                    // Get the attachment title
                    $attachment_title = get_the_title($attachment_id);
        
                    if ($attachment_url) {
                        // Display the media item
                        echo '<a class="property-two-button" href="' . esc_url($attachment_url) . '" target="_blank">Download brochure</a>';
                    }
                }
        
                echo '</div>';

            }

            $system_detail = get_post_meta(get_the_ID(), 'SystemDetail', true);
            $system_detail = maybe_unserialize($system_detail);
            $get_all_document = unserialize(get_post_meta(get_the_ID(), 'document_urls_serialized', true));

            if (!empty($get_all_document) && is_array($get_all_document)) {
                $total_documents = count($get_all_document); // Count documents
                echo '<ul>';
                
                $count = 1;
                foreach ($get_all_document as $document_url) {
                    $label = ($total_documents > 1) ? 'Download Brochure ' . $count : 'Download Brochure';
                    echo '<a class="property-two-button" href="' . esc_url($document_url) . '" target="_blank">' . esc_html($label) . '</a>';
                    $count++;
                }

                echo '</ul>';
            } 

                // Display Account Managers
            if (isset($system_detail['AccountManagers']) && is_array($system_detail['AccountManagers'])) {
                echo '<ul>';
                foreach ($system_detail['AccountManagers'] as $manager) {
                    if (is_array($manager)) {
                        $name = isset($manager['Name']) ? esc_html($manager['Name']) : 'N/A';
                        $email = isset($manager['Email']) ? esc_html($manager['Email']) : 'N/A';
                        $telephone = isset($manager['Telephone']) ? esc_html($manager['Telephone']) : 'N/A';
                        // echo '<h3>Call ' . $name . ' on ' . $telephone . ' or email on ' . $email . '<br></h3>';
                        echo '<a class="property-two-button" href="mailto:' . $email . '" target="_blank">Email '. $name .'</a>';
                        echo '<a class="property-two-button" href="tel:' . $telephone . '" target="_blank">Call '. $name .'</a>';
                    }
                }
                echo '</ul>';
            }
            ?>
        </div>
   
    </div>
    <div style="margin-bottom:40px">
        <?php render_map_view(get_the_ID()); ?>
    </div>
</div>
</div>
