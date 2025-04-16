<div class="single-property-view">
            <?php render_photo_data(get_the_ID()); ?>
            <h1 style="margin-top:20px"><?php the_title(); ?></h1>
            <div class="property-meta">
                <span class="property-date"><?php the_date(); ?></span>
            </div>
            <div class="property-market-status">
            <?php
            // Fetch the 'MarketStatus' meta field
            $market_status_meta = get_post_meta(get_the_ID(), 'MarketStatus', true);
            if (!empty($market_status_meta)) { 
                if($market_status_meta == 'Available'){
                ?>
                    <span style="background: green; line-height: 4; padding: 10px 20px;
                    border-radius: 5px; color: white; font-size: 13px;"><?php echo $market_status_meta ?></span>
                <?php
            } }
            ?>
        </div>

            <div class="property-content">
                <?php the_content(); ?>
            </div>

            <div class="property-bullets">
                <?php
                // Fetch the 'Bullets' meta field
                $bullets_meta = get_post_meta(get_the_ID(), 'Bullets', true);
                if (!empty($bullets_meta)) {
                    $bullets = maybe_unserialize($bullets_meta);

                    if (is_array($bullets)) {
                        echo '<h3>Key Points</h3>';
                        echo '<ul>';

                        foreach ($bullets as $bullet) {
                            if (!empty($bullet['BulletPoint'])) {
                                echo '<li>' . esc_html($bullet['BulletPoint']) . '</li>';
                            }
                        }

                        echo '</ul>';
                    }
                }
                ?>
            </div>

            <!-- Tab System Start -->
            <div class="property-tabs">
                <ul class="tab-list">
                    <li class="active"><a href="#tab-map-view">Map View</a></li>
                    <li><a href="#tab-owners">Owners</a></li>
                    <!-- <li><a href="#tab-company">Company</a></li> -->
                    <li><a href="#tab-system">System</a></li>
                    <li><a href="#tab-tenure">Tenure</a></li>
                    <li><a href="#tab-size">Size</a></li>
                    <li><a href="#tab-agent">Agent</a></li>
                    <li><a href="#tab-address">Address</a></li>
                    <li><a href="#tab-documents">Document</a></li>
                </ul>

                <div class="tab-content">
                    <div id="tab-map-view" class="tab-pane active">
                        <?php render_map_view(get_the_ID()); ?>
                    </div>
                    <div id="tab-owners" class="tab-pane">
                        <?php render_owners_data(get_the_ID()); ?>
                    </div>
                    <div id="tab-company" class="tab-pane">
                        <?php render_company_data(get_the_ID()); ?>
                    </div>
                    <div id="tab-system" class="tab-pane">
                        <?php render_system_detail_data(get_the_ID()); ?>
                    </div>
                    <div id="tab-tenure" class="tab-pane">
                        <?php render_tenure_data(get_the_ID()); ?>
                    </div>
                    <div id="tab-size" class="tab-pane">
                        <?php render_size_data(get_the_ID()); ?>
                    </div>
                    <div id="tab-agent" class="tab-pane">
                        <?php render_agent_data(get_the_ID()); ?>
                    </div>
                    <div id="tab-address" class="tab-pane">
                        <?php render_property_address_data(get_the_ID()); ?>
                    </div>
                    <div id="tab-documents" class="tab-pane">
                        <?php render_document_media(get_the_ID()); ?>
                    </div>
                </div>
            </div>
            <!-- Tab System End -->

            <div class="property-details">
                <?php
                // Define an array of meta keys you want to display
                $keys_to_display = array(
                    // 'ID',
                    // 'FileRef',
                    // 'MarketStatus',
                    // 'LastUnavailableDate',
                    // 'LastAvailableDate',
                    // 'DisplayUntil',
                    // 'Website',
                    // 'DataRoom',
                    // 'Photos',
                    // 'Featured',
                    // 'DocumentMedia',
                    // 'Agent_Data',
                    // 'Address',
                    // 'Size',
                    // 'SystemDetail',
                    // 'Tenure',
                    // 'Owners',
                    // 'AdditionalInfo',
                );

                // Display each specified key
                foreach ($keys_to_display as $key) {
                    $meta_value = get_post_meta(get_the_ID(), $key, true);
                    if (!empty($meta_value)) {
                        echo '<h4>' . esc_html($key) . '</h4>';
                        $value = maybe_unserialize($meta_value);

                        if (is_array($value)) {
                            echo '<pre>' . print_r($value, true) . '</pre>';
                        } else {
                            echo '<p>' . esc_html($value) . '</p>';
                        }
                    }
                }

                if (get_post_meta(get_the_ID(), 'property_price', true)) : ?>
                    <p><strong>Price:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'property_price', true)); ?></p>
                <?php endif; ?>
                
                <!-- Add more custom fields as needed -->
            </div>
            <!-- <pre> -->
                <?php
                // print_r(get_post_meta(get_the_ID())); ?>
            <!-- </pre> -->
        </div>