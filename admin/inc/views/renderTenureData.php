<?php
function renderTenureData($post) {
    // Retrieve the stored Tenure data
    $serialized_tenure = get_post_meta($post->ID, 'Tenure', true);

    // Unserialize the data and check if it's an array
    $tenure_data = @unserialize($serialized_tenure);
    
    if ($tenure_data && is_array($tenure_data)) {
        ?>
        <h4>Tenure</h4>

        <!-- Currency -->
        <div>
            <p><strong>Currency:</strong> <?php echo esc_html($tenure_data['Currency']); ?></p>
        </div>

        <!-- Tenure -->
        <div>
            <p><strong>Tenure:</strong> <?php echo esc_html($tenure_data['Tenure']); ?></p>
        </div>

        <!-- For Sale -->
        <div>
            <p><strong>For Sale:</strong> <?php echo esc_html($tenure_data['ForSale'] == '1' ? 'Yes' : 'No'); ?></p>
        </div>

        <!-- For Sale Price From -->
        <div>
            <p><strong>For Sale Price From:</strong> <?php echo esc_html(number_format($tenure_data['ForSalePriceFrom'], 2)); ?></p>
        </div>

        <!-- For Sale Price To -->
        <div>
            <p><strong>For Sale Price To:</strong> <?php echo esc_html(number_format($tenure_data['ForSalePriceTo'], 2)); ?></p>
        </div>

        <!-- For Sale Term -->
        <div>
            <p><strong>For Sale Term:</strong> <?php echo esc_html($tenure_data['ForSaleTerm']['Name']); ?> (ID: <?php echo esc_html($tenure_data['ForSaleTerm']['ID']); ?>)</p>
        </div>

        <!-- For Rent -->
        <div>
            <p><strong>For Rent:</strong> <?php echo esc_html($tenure_data['ForRent'] == '1' ? 'Yes' : 'No'); ?></p>
        </div>

        <!-- For Rent Price From -->
        <div>
            <p><strong>For Rent Price From:</strong> <?php echo esc_html(number_format($tenure_data['ForRentPriceFrom'], 2)); ?></p>
        </div>

        <!-- For Rent Price To -->
        <div>
            <p><strong>For Rent Price To:</strong> <?php echo esc_html(number_format($tenure_data['ForRentPriceTo'], 2)); ?></p>
        </div>

        <!-- For Rent Term -->
        <div>
            <p><strong>For Rent Term:</strong> <?php echo esc_html($tenure_data['ForRentTerm']['Name']); ?> (ID: <?php echo esc_html($tenure_data['ForRentTerm']['ID']); ?>)</p>
        </div>

        <!-- User Defined Tenure 1 -->
        <div>
            <p><strong>User Defined Tenure 1:</strong> <?php echo esc_html($tenure_data['UserDefinedTenure1'] == '1' ? 'Yes' : 'No'); ?></p>
        </div>

        <!-- User Defined Tenure 1 Price From -->
        <div>
            <p><strong>User Defined Tenure 1 Price From:</strong> <?php echo esc_html(number_format($tenure_data['UserDefinedTenure1From'], 2)); ?></p>
        </div>

        <!-- User Defined Tenure 1 Price To -->
        <div>
            <p><strong>User Defined Tenure 1 Price To:</strong> <?php echo esc_html(number_format($tenure_data['UserDefinedTenure1To'], 2)); ?></p>
        </div>

        <!-- User Defined Tenure 1 Term -->
        <div>
            <p><strong>User Defined Tenure 1 Term:</strong> <?php echo esc_html($tenure_data['UserDefinedTenure1Term']['Name']); ?> (ID: <?php echo esc_html($tenure_data['UserDefinedTenure1Term']['ID']); ?>)</p>
        </div>

        <!-- User Defined Tenure 2 -->
        <div>
            <p><strong>User Defined Tenure 2:</strong> <?php echo esc_html($tenure_data['UserDefinedTenure2'] == '1' ? 'Yes' : 'No'); ?></p>
        </div>

        <!-- User Defined Tenure 2 Price From -->
        <div>
            <p><strong>User Defined Tenure 2 Price From:</strong> <?php echo esc_html(number_format($tenure_data['UserDefinedTenure2From'], 2)); ?></p>
        </div>

        <!-- User Defined Tenure 2 Price To -->
        <div>
            <p><strong>User Defined Tenure 2 Price To:</strong> <?php echo esc_html(number_format($tenure_data['UserDefinedTenure2To'], 2)); ?></p>
        </div>

        <!-- User Defined Tenure 2 Term -->
        <div>
            <p><strong>User Defined Tenure 2 Term:</strong> <?php echo esc_html($tenure_data['UserDefinedTenure2Term']['Name']); ?> (ID: <?php echo esc_html($tenure_data['UserDefinedTenure2Term']['ID']); ?>)</p>
        </div>

        <!-- Leasehold Purchase Price -->
        <div>
            <p><strong>Leasehold Purchase Price:</strong> <?php echo esc_html(number_format($tenure_data['LeaseholdPurchasePrice'], 2)); ?></p>
        </div>

        <!-- Lease Type -->
        <div>
            <p><strong>Lease Type:</strong> <?php echo esc_html($tenure_data['LeaseType']['Name']); ?> (ID: <?php echo esc_html($tenure_data['LeaseType']['ID']); ?>)</p>
        </div>

        <!-- Service Charge -->
        <div>
            <p><strong>Service Charge:</strong> <?php echo esc_html(number_format($tenure_data['ServiceCharge'], 2)); ?></p>
        </div>

        <!-- Service Charge Term -->
        <div>
            <p><strong>Service Charge Term:</strong> <?php echo esc_html($tenure_data['ServiceChargeTerm']); ?></p>
        </div>

        <!-- Comments -->
        <div>
            <p><strong>Comments:</strong> <?php echo esc_html($tenure_data['Comments']); ?></p>
        </div>
        <?php
    } else {
        ?>
        <p>No tenure information available.</p>
        <?php
    }
}
