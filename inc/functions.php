<?php

if(!defined( 'WMCFA_ABSPATH')) exit;

function wmcfa_developer_mode(){
	if($_SERVER['REMOTE_ADDR']=='91.245.79.74') return true;
	return false;
}

function wmcfa_admin_perm(){
	if(current_user_can('administrator')){
		return true;
	}
	return false;
}

function wmcfa_ajax_return($data){
	echo json_encode($data);
	exit;
}

function wmcfa_get_ajax_action_url($action, $parameters = array(), $echo = true){

    $action_url = admin_url('/admin-ajax.php?action='.$action.'&_wpnonce='.wp_create_nonce($action));

    if(count($parameters)){
        foreach($parameters as $par_k=>$par){
            $action_url .= '&'.$par_k.'='.$par;
        }
    }

    if($echo){
        echo $action_url;
    }else{
        return $action_url;
    }

}

function wmcfa_recursive_files_search($dir_path, $filter = '*.*', $data = array()){

    // Find folders

    if(count($folders = glob($dir_path.'/*', GLOB_ONLYDIR))){
        foreach($folders as $folder){
            $data = wmcfa_recursive_files_search($folder, $filter, $data);
        }
    }

    // Find files

    if(count($files = glob($dir_path.'/'.$filter))){
        foreach($files as $file){
            $data[] = $file;
        }
    }

    return $data;

}

function wmcfa_get_maintenance_status(){
    return get_option('wmcfa_maintenance_on');
}

function wmcfa_set_maintenance_status($status){
    update_option('wmcfa_maintenance_on', $status ? 1 : 0);
}

function wmcfa_basename($file_path){
    return preg_replace('/(.*)\//', '', $file_path);
}

function wmcfa_get_plugin_row_path(){
    return WMCFA_PLUGIN_SLUG.'/'.preg_replace('/\-/', '_', WMCFA_PLUGIN_SLUG).'.php';
}

?>