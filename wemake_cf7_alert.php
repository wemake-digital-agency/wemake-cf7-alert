<?php
/*
Plugin Name: Wemake CF7 Alert
Plugin URI: http://wemake.co.il
Version: 2.18
Author: Wemake Team
Author URI: http://wemake.co.il
License: GPL2
*/

/*
Copyright 2017  Olexandr Levchuk  (email : alex@wemake.co.il)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Constants

define("WMCFA_PLUGIN_NAME", 'Wemake CF7 Alert');
define("WMCFA_PLUGIN_SLUG", 'wemake-cf7-alert');
define("WMCFA_PLUGIN_VERSION", '2.18');
define("WMCFA_ABSPATH", dirname( __FILE__ ));
define("WMCFA_URI", plugins_url().'/'.WMCFA_PLUGIN_SLUG);
define("WMCFA_AJAX_DEBUG", true);

// PHP version

if(version_compare(phpversion(), '5.6.40', '<')){
    add_action('admin_notices', function(){
        $message = 'Your server is running PHP version '.phpversion().' but '.WMCFA_PLUGIN_NAME.' requires at least 5.6.40. The plugin does not work.';
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), esc_html( $message ) );
    });
    return false;
}

// Functions

require_once(WMCFA_ABSPATH . '/inc/functions.php');

// AJAX actions

if(isset($_GET['action']) && (function_exists('wp_doing_ajax') &&  wp_doing_ajax() || defined('DOING_AJAX'))){
    require_once(WMCFA_ABSPATH . '/inc/action.php');
}

// Languages

function wmcfa_load_textdomain(){
    if((is_admin() || is_multisite() && is_network_admin()) && function_exists('get_user_locale')){
        $locale = get_user_locale();
    }elseif(function_exists('get_locale')){
        $locale = get_locale();
    }else{
        $locale = 'en_US';
    }
    load_textdomain(WMCFA_PLUGIN_SLUG, WMCFA_ABSPATH.'/languages/'.$locale.'.mo');
}
add_action('init', 'wmcfa_load_textdomain');
add_action('get_footer', 'wmcfa_load_textdomain');

// Run controllers

add_action("wp_loaded", function(){
    if(is_admin() || is_multisite() && is_network_admin()){
        require_once(WMCFA_ABSPATH . '/inc/admin.php');
    }else{
        require_once(WMCFA_ABSPATH . '/inc/frontend.php');
    }
});

?>
