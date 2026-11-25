<?php

if(!defined( 'WMCFA_ABSPATH')) exit;

// Scripts and styles

add_action('admin_enqueue_scripts', function(){
    wp_enqueue_style('wmcfa-admin-style',        WMCFA_URI.'/assets/css/admin.css', array(), WMCFA_PLUGIN_VERSION, 'all');
});

?>
