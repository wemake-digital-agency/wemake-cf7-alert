<?php

if(get_option('wmcfa_l')) return false;

// Maintenance mode

add_action('template_redirect', function(){
    if(wmcfa_get_maintenance_status()){
        die('<h1 align="center">'.__('Briefly unavailable for scheduled maintenance. Check back in a minute.', WMCFA_PLUGIN_SLUG).'</h1>');
    }
});

function wmcfa_init_scripts(){

    wp_enqueue_style('wemake-cf7-alert-css',    WMCFA_URI.'/assets/css/frontend.css', array(), '1.0', 'all');
    wp_enqueue_script('wemake-cf7-alert-js',    WMCFA_URI.'/assets/js/frontend.js', array(), null, true);

    if(!wp_script_is('jquery', 'done')){
        wp_enqueue_script('jquery',             WMCFA_URI.'/lib/js/jquery-3.4.1.min.js', array(), null, false);
    }

}
add_action('wp_enqueue_scripts', 'wmcfa_init_scripts');

function wmcfa_popup(){
    ?>
    <div class="wmcfa-popup wmcfa-popup-bg">
        <div class="wmcfa-loading"></div>
        <div class="wmcfa-popup-msg">
            <div class="msg-wrapper">
                <div class="msg-ic msg-ic-error"></div>
                <div class="msg-text">
                    <div class="msg-ic msg-ic-ok"></div>
                    <span></span>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.wmcfa_settings = {
            "ltr_mode" : <?php echo (function_exists('get_locale') && get_locale()=='he_IL' ? 'false' : 'true'); ?>,
            "validate" : true,
            "validate_email" : true,
            "validate_tel" : false,
            "redirect_to" : "",
            "messages" : {
                "field_error_prefix" : "<?php _e('Please fill', WMCFA_PLUGIN_SLUG); ?>",
                "field_error" : "<?php _e('One or more fields have an error.', WMCFA_PLUGIN_SLUG); ?>",
                "email_error" : "<?php _e('The e-mail address entered is invalid.', WMCFA_PLUGIN_SLUG); ?>",
                "tel_error"   : "<?php _e('The telephone entered is invalid.', WMCFA_PLUGIN_SLUG); ?>"
            }
        };
    </script>
    <?php
}
add_action('wp_footer', 'wmcfa_popup', 999);

?>