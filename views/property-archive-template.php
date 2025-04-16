<?php
get_header(); // Include the header
?>
<div id="site-content">
<?php echo do_shortcode('[rev_slider alias="slider-1-11"][/rev_slider]') ?>
<br>
<?php if ( have_posts() ) : ?>
<div class="container">
<?php echo do_shortcode('[display_properties]') ?>
</div>
<?php
else :
    echo '<p>No properties found.</p>';
endif;
?>
</div>
<?php
get_footer(); // Include the footer
