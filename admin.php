<?php

global $up_theme_options;

$up_theme_options = array();

function register_theme_options( $options ){
    global $up_theme_options;
    $up_theme_options = array_merge( $up_theme_options, $options );
}

function register_theme_option_tab( $args ){
    global $up_tabs;
    extract( $args );
    if($name):
        $up_tabs[] = $args;
        return true;
    endif;
}

function get_current_theme_id(){

	$get_up_theme = get_theme_data(TEMPLATEPATH .'/style.css');
	$theme_title = $get_up_theme['Title'];
	
	if( !defined('THEME_TITLE') )
		define('THEME_TITLE',$theme_title);
	$theme_shortname = strtolower(preg_replace('/ /', '_', $theme_title));
	
	return $theme_shortname;

}

/******
** UpThemes Framework Version
*************************************/
define('UPTHEMES_VER', '2.0.2');

/******
** Theme init hook
*************************************/
function upfw_theme_init(){
	do_action('upfw_theme_init');
}

/******
** Admin init hook
*************************************/
function upfw_admin_init(){
	do_action('upfw_admin_init');
}

/******
** Initialization adds hooks to
** two places: theme and admin
*************************************/
function upfw_init(){
	
	if( is_admin() ):
		add_action('after_setup_theme','upfw_admin_init');
	else:
		add_action('after_setup_theme','upfw_theme_init');
	endif;
}

add_action('after_setup_theme','upfw_init',1);

/******
** Set up theme data
*************************************/
function upfw_generate_theme_data(){
	
	$get_up_theme = get_theme_data(TEMPLATEPATH .'/style.css');
	$theme_title = $get_up_theme['Title'];
	$theme_shortname = strtolower(preg_replace('/ /', '_', $theme_title));
	$theme_version = $get_up_theme['Version'];
	$theme_template = $get_up_theme['Template'];
	define('UPTHEMES_NAME', $theme_title);
	define('TEMPLATENAME', $theme_title);
	define('UPTHEMES_SHORT_NAME', $theme_shortname);
	define('UPTHEMES_THEME_VER', $theme_version);

	if( file_exists(TEMPLATEPATH.'/admin/admin.php') ):
		define( 'THEME_PATH' , TEMPLATEPATH );
		define( 'THEME_DIR' , get_template_directory_uri() );
	elseif( file_exists(STYLESHEETPATH.'/admin/admin.php') ):
		define( 'THEME_PATH' , STYLESHEETPATH );
		define( 'THEME_DIR' , get_stylesheet_directory_uri() );
	endif;
	
	// Detect child theme info
	if(STYLESHEETPATH != TEMPLATEPATH): 
	    $get_up_theme = get_theme_data(STYLESHEETPATH .'/style.css');
	    $theme_title = $get_up_theme['Title'];
	    $theme_shortname = strtolower(preg_replace('/ /', '_', $theme_title));
	    $theme_version = $get_up_theme['Version'];
	    $theme_template = $get_up_theme['Template'];
	    define('CHILD_NAME', $theme_title);
	    define('CHILD_SHORT_NAME', $theme_shortname);
	    define('CHILD_THEME_VER', $theme_version);
	    define('CHILD_PATH', STYLESHEETPATH);
	endif;

}

add_action('upfw_admin_init','upfw_generate_theme_data',1);
add_action('upfw_theme_init','upfw_generate_theme_data',1);

/******
** Gentlemen, start your engines
*************************************/
function upfw_engines_init(){

	require_once('library/custom.php');
	require_once('library/theme-options.php');

}

add_action('upfw_theme_init','upfw_engines_init',10);
add_action('upfw_admin_init','upfw_engines_init',10);

/******
** Add CSS and Javascript includes
*************************************/
function upfw_queue_scripts_styles(){
	
	$upthemes =  THEME_DIR.'/admin/';
	
	wp_enqueue_style('up_framework',$upthemes."css/up_framework.css");
	
	//Check if theme-options/style.css exists and load it
	if(file_exists(THEME_PATH ."/theme-options/style.css")):
		wp_enqueue_style('theme_options',THEME_DIR."/theme-options/style.css");
	endif;

	wp_enqueue_style('farbtastic');
	wp_enqueue_script('jquery-color');
	wp_enqueue_script('upfw', $upthemes . "js/up_framework.js", array('farbtastic'));
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_style('thickbox');

}

add_action('admin_print_scripts-appearance_page_upfw-settings','upfw_queue_scripts_styles',40);

function upfw_setup_theme_options(){

	$up_options_db = get_option('up_themes_'.UPTHEMES_SHORT_NAME);
			
	global $up_options;
		
	//Check if options are stored properly
	if( isset($up_options_db) && is_array($up_options_db) ):
		
	    $up_options = (object) $up_options_db;
	
	else:
	
		do_action('upfw_theme_activation');
	
	endif;

}

add_action('upfw_theme_init','upfw_setup_theme_options',100);
add_action('upfw_admin_init','upfw_setup_theme_options',100);