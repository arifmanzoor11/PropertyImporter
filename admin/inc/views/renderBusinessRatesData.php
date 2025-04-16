<?php

function renderBusinessRatesData($post) {
    // Retrieve the stored BusinessRates data
    $serialized_business_rates = get_post_meta($post->ID, 'BusinessRates', true);

    if ($serialized_business_rates) {
        // Unserialize the data
        $business_rates_data = unserialize($serialized_business_rates);

        ?>
        <h4>Business Rates</h4>

        <!-- Current Rateable Value -->
        <div>
            <p><strong>Current Rateable Value:</strong> <?php echo esc_html(number_format($business_rates_data['CurrentRateableValue'], 2)); ?></p>
        </div>

        <!-- Rates Payable -->
        <div>
            <p><strong>Rates Payable:</strong> <?php echo esc_html(number_format($business_rates_data['RatesPayable'], 2)); ?></p>
        </div>

        <!-- Comments -->
        <div>
            <p><strong>Comments:</strong> <?php echo esc_html($business_rates_data['Comments']); ?></p>
        </div>
        <?php
    } else {
        ?>
        <p>No business rates information available.</p>
        <?php
    }
}
