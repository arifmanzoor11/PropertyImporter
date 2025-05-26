<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header(); ?>

<?php
$auth_shortcode = get_option('login_auth_to_view_property');
// Check if the user is logged in
if($auth_shortcode){ 

if (!is_user_logged_in()) {
    echo do_shortcode('[rev_slider alias="slider-1-11"][/rev_slider]');
    ?>
    <br>
    <div id="login-form"
        style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <?php
        
        if (!empty($auth_shortcode)) {
            echo do_shortcode($auth_shortcode);
        } ?>
    </div>
    <?php
    get_footer(); ?>
    <?php exit;
} ?>
<?php } ?>

<div id="site-content">
    <?php
    if (have_posts()):
        while (have_posts()):
            the_post();
            $property_style = get_option('property_style');

            if ($property_style == 'style_1') {
                include_once plugin_dir_path(__FILE__) . 'page-style/property-style-one.php';
            } elseif ($property_style == 'style_2') {
                include_once plugin_dir_path(__FILE__) . 'page-style/property-style-two.php';
            }
        endwhile;
    else:
        echo '<p>No property found.</p>';
    endif;
    ?>
</div>

<?php get_footer(); ?>