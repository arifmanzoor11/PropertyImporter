<?php

function renderSystemDetailData($post) {
    // Retrieve the stored SystemDetail data
    $serialized_system_detail = get_post_meta($post->ID, 'SystemDetail', true);

    if ($serialized_system_detail) {
        // Unserialize the data
        $system_detail_data = unserialize($serialized_system_detail);

        ?>
        <h4>System Detail</h4>

        <!-- Account Managers -->
        <div>
            <h5>Account Managers</h5>
            <?php if (!empty($system_detail_data['AccountManagers'])): ?>
                <?php foreach ($system_detail_data['AccountManagers'] as $manager): ?>
                    <p><strong>Name:</strong> <?php echo esc_html($manager['Name']); ?></p>
                    <p><strong>Email:</strong> <?php echo esc_html($manager['Email']); ?></p>
                    <p><strong>Telephone:</strong> <?php echo esc_html($manager['Telephone']); ?></p>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No account managers available.</p>
            <?php endif; ?>
        </div>

        <!-- Partner -->
        <div>
            <h5>Partner</h5>
            <p><strong>Partner ID:</strong> <?php echo esc_html($system_detail_data['Partner']['ID']); ?></p>
            <p><strong>Partner Name:</strong> <?php echo esc_html($system_detail_data['Partner']['Name']); ?></p>
        </div>

        <!-- Dates -->
        <div>
            <h5>Dates</h5>
            <p><strong>Date Registered:</strong> <?php echo esc_html($system_detail_data['DateRegistered']); ?></p>
            <p><strong>Date Updated:</strong> <?php echo esc_html($system_detail_data['DateUpdated']); ?></p>
        </div>
        <?php
    } else {
        ?>
        <p>No system details available.</p>
        <?php
    }
}
