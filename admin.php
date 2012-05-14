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

  if( function_exists('wp_get_theme') ):
    $themedata = wp_get_theme();
	  $theme_title = $themedata->title;
  else:
	  $themedata = get_theme_data(get_template_directory() .'/style.css');
	  $theme_title = $themedata['Title'];
	endif;

	$theme_shortname = strtolower(preg_replace('/ /', '_', $theme_title));
	
	return $theme_shortname;

}

/******
** UpThemes Framework Version
*************************************/
define('UPTHEMES_VER', '2.1.5');

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

  if( function_exists('wp_get_theme') ):
    $themedata = wp_get_theme();
  
    $version = $themedata->Version;
    $name = $themedata->Name;
  
    //If theme is child theme this is the name of the parent theme
    $template = $themedata->Template;
  
    $theme_title = $themedata->Name;
    $theme_shortname = strtolower(preg_replace('/ /', '_', $name));
    $theme_version = $version;
  
    if( !empty($template) ):
      $theme_template = $template;
    endif;
  
  else:
    $themedata = get_theme_data(get_template_directory() .'/style.css');
    $theme_title = $themedata['Title'];
    $theme_shortname = strtolower(preg_replace('/ /', '_', $theme_title));
    $theme_version = $themedata['Version'];
    $theme_template = $themedata['Template'];
  endif;

  if( !defined('UPTHEMES_NAME') )
  	define('UPTHEMES_NAME', $theme_title);
  if( !defined('TEMPLATENAME') )
  	define('TEMPLATENAME', $theme_title);
  if( !defined('UPTHEMES_SHORT_NAME') )
	  define('UPTHEMES_SHORT_NAME', $theme_shortname);
  if( !defined('UPTHEMES_THEME_VER') )
  	define('UPTHEMES_THEME_VER', $theme_version);

	if( file_exists(get_template_directory().'/admin/admin.php') ):
		if( !defined('THEME_PATH') );
		  define( 'THEME_PATH' , get_template_directory() );
		if( !defined('THEME_DIR') );
  		define( 'THEME_DIR' , get_template_directory_uri() );
	elseif( file_exists(get_stylesheet_directory().'/admin/admin.php') ):
		if( !defined('THEME_PATH') );
  		define( 'THEME_PATH' , get_stylesheet_directory() );
		if( !defined('THEME_DIR') );
  		define( 'THEME_DIR' , get_stylesheet_directory_uri() );
	endif;

}

add_action('upfw_admin_init','upfw_generate_theme_data',1);
add_action('upfw_theme_init','upfw_generate_theme_data',1);

/******
** Gentlemen, start your engines
*************************************/
function upfw_engines_init(){

	include_once('library/custom.php');
	include_once('library/theme-options.php');
	include_once('library/theme-customizer.php');

}

add_action('upfw_theme_init','upfw_engines_init',10);
add_action('upfw_admin_init','upfw_engines_init',10);

/******
** Add CSS and Javascript includes
*************************************/
function upfw_queue_scripts_styles(){
	
	$upthemes =  THEME_DIR.'/admin/';
	
	wp_enqueue_style('up_framework',$upthemes."css/up_framework.css");

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
