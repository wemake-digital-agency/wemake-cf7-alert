<?php

if(!defined( 'WMCFA_ABSPATH')) exit;

// Scripts and styles

add_action('admin_enqueue_scripts', function(){
    wp_enqueue_style('wmcfa-admin-style',        WMCFA_URI.'/assets/css/admin.css', array(), WMCFA_PLUGIN_VERSION, 'all');
    wp_enqueue_script('wmcfa-admin-js', 	        WMCFA_URI.'/assets/js/admin.js', array(), WMCFA_PLUGIN_VERSION, true);
});

// Admin footer

add_action('admin_footer', function(){
    ?>
    <script>
        var wmcfa_language = {
            "update_error": "<?php _e('Error. Please try again later.', WMCFA_PLUGIN_SLUG); ?>",
        };
    </script>
    <?php
});

// Update

//require_once(WMCFA_ABSPATH . '/inc/admin_update.php');
require_once(WMCFA_ABSPATH . '/inc/admin_update_plugin_github.php');

?>
