<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header(); ?>

<?php
// Check if the user is logged in
if (!is_user_logged_in()) {
    echo do_shortcode('[rev_slider alias="slider-1-11"][/rev_slider]');
    ?>
    <br>
    <div id="login-form"
        style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h4>Login to View Content</h4>
        <?php
        $custom_shortcode = get_option('custom_code');
        if (!empty($custom_shortcode)) {
            echo do_shortcode($custom_shortcode); ?>
            <a href="#" id="show-registration">Create an Account</a> | 
            <a href="#" id="show-forgot-pw">Forgot Password?</a>
            
            <?php
        } else { ?>
            <?php
            wp_login_form([
                'redirect' => esc_url(get_permalink()),
                'label_log_in' => 'Login',
            ]);
        } ?>
    </div>

    <!-- Hidden Registration Form -->
    <div id="registration-form"
        style="display: none; max-width: 400px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h4>Create an Account</h4>
        <?php $reg_code = get_option('reg_code'); echo do_shortcode($reg_code); ?>
        Already have an account? <a href="#" id="show-login">Login</a>
    </div>

    <div id="forgot-pw-form"
        style="display:none; max-width: 400px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h4>Create an Account</h4>
        <?php $forgot_pw_code = get_option('forgot_pw_code'); echo do_shortcode($forgot_pw_code); ?>
        <a href="#" id="show-fw-registration">Register Now </a> | <a href="#" id="show-fw-login">Login</a>
    </div>


    <?php
    get_footer(); ?>
    <script>
        jQuery(document).ready(function ($) {
            $("#show-registration").click(function (e) {
                e.preventDefault();
                $("#registration-form").fadeIn();
                $("#login-form").fadeOut();
                $("#forgot-pw-form").fadeOut();
            });
            $("#show-fw-registration").click(function (e) {
                e.preventDefault();
                $("#registration-form").fadeIn();
                $("#login-form").fadeOut();
                $("#forgot-pw-form").fadeOut();
            });

            $("#show-login").click(function (e) {
                e.preventDefault();
                $("#login-form").fadeIn();
                $("#registration-form").fadeOut();
                $("#forgot-pw-form").fadeOut();
            });
            $("#show-fw-login").click(function (e) {
                e.preventDefault();
                $("#login-form").fadeIn();
                $("#registration-form").fadeOut();
                $("#forgot-pw-form").fadeOut();
            });

            $("#show-forgot-pw").click(function (e) {
                e.preventDefault();
                $("#forgot-pw-form").fadeIn();
                $("#registration-form").fadeOut();
                $("#login-form").fadeOut();
            });
        });
    </script>
    <?php exit;
} ?>



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