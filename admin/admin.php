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

	require_once('library/options/options.php');
	require_once('library/widgets/dashboard.php');
	
	if( !defined('DISABLE_LAYOUT_ENGINE') )
		require_once('library/engines/layout-engine.php');
	
	if( !defined('DISABLE_STYLE_ENGINE') )
		require_once('library/engines/style-engine.php');
	
	if( !defined('DISABLE_TYPOGRAPHY_ENGINE') )
		require_once('library/engines/typography-engine.php');

	require_once('custom.php');
	require_once('theme-options.php');
	
	if( function_exists('upfw_dbwidget_setup') )
            add_action('wp_dashboard_setup', 'upfw_dbwidget_setup' );
	
    if( function_exists('default_theme_layouts') )
        add_action('init','default_theme_layouts',1);

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
		
    /* For Typography Engine */
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-widget');
    wp_enqueue_script('jquery-ui-mouse');
    wp_enqueue_script('jquery-ui-slider');
    wp_enqueue_style('up-slider', get_template_directory_uri().'/admin/css/ui-themes/smoothness/style.css');

}

add_action('admin_print_scripts-appearance_page_upfw-settings','upfw_queue_scripts_styles',40);

/**
 * Get current template context
 * 
 * Returns a string containing the context of the
 * current page template. This string is useful for several
 * purposes, including applying an ID to the HTML
 * body tag, and adding a contextual $name to calls
 * to get_header(), get_footer(), get_sidebar(), 
 * and get_template_part_file(), in order to 
 * facilitate Child Themes overriding default Theme
 * template part files.
 * 
 * @param	none
 * @return	string	current page template context
 */
function upfw_get_template_context() {

	$context = 'index';
	
	if ( is_front_page() ) {
		// Front Page
		$context = 'front-page';
	} else if ( is_date() ) {
		// Date Archive Index
		$context = 'date';
	} else if ( is_author() ) {
		// Author Archive Index
		$context = 'author';
	} else if ( is_category() ) {
		// Category Archive Index
		$context = 'category';
	} else if ( is_tag() ) {
		// Tag Archive Index
		$context = 'tag';
	} else if ( is_tax() ) {
		// Taxonomy Archive Index
		$context = 'taxonomy';
	} else if ( is_archive() ) {
		// Archive Index
		$context = 'archive';
	} else if ( is_search() ) {
		// Search Results Page
		$context = 'search';
	} else if ( is_404() ) {
		// Error 404 Page
		$context = '404';
	} else if ( is_attachment() ) {
		// Attachment Page
		$context = 'attachment';
	} else if ( is_single() ) {
		// Single Blog Post
		$context = 'single';
	} else if ( is_page() ) {
		// Static Page
		$context = 'page';
	} else if ( is_home() ) {
		// Blog Posts Index
		$context = 'home';
	}
	
	return $context;
}

add_action('wp_ajax_show_gallery_images','show_gallery_images');

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

/******
** Bootstrap static framework pages
*************************************/
function upthemes_admin_home() {require_once('home.php');}
function upthemes_admin_docs(){require_once('docs.php');}
function upthemes_admin_buy(){require_once('buy.php');}
function upthemes_admin_import_export(){require_once('import-export.php');}

/******
** Find default options
*************************************/
function find_defaults($options){
    global $up_defaults;
    //print_r($options);
}

/******
** RSS URL: rss('return') will return 
** the value and not echo it.
*************************************/
function upfw_rss($i = ''){
    global $up_options;
    if($up_options->feedburner):
        $rss = "http://feeds.feedburner.com/".$up_options->feedburner;
    else:
        $rss = get_bloginfo_rss('rss2_url');
    endif;
    if($i == 'return'): return $rss; else: echo $rss; endif;
}

/******
** RSS Subscribe URL: rss_email('return') 
** will return the value and not echo it.
*************************************/
function upfw_rss_email($i = ''){
    global $up_options;
    if($up_options->feedburner):
        $rssemail = "http://www.feedburner.com/fb/a/emailverifySubmit?feedId=" . $up_options->feedburner;
    else:
        $rssemail = "#";
    endif;
    if($i == 'return'): return $rssemail; else: echo $rssemail; endif;
}

/******
** Admin header hook
*************************************/
function upfw_admin_header(){
	do_action('upfw_admin_header');
}

/******
** Open admin header
*************************************/
function upfw_admin_header_open(){ ?>
	<div id="up_header" class="polish"><?php
}

add_action('upfw_admin_header','upfw_admin_header_open',1);

/******
** Set admin header title
*************************************/
function upfw_admin_header_title(){ ?>
	<div id="icon-upfw" class="icon32 icon32-upfw"></div>
	<h2><?php _e("Theme Options","upfw"); ?></h2> <?php
}

add_action('upfw_admin_header','upfw_admin_header_title',100);

/******
** Create admin header links
*************************************/
function upfw_admin_header_links(){ ?>
	<ul id="up_topnav">
		<?php do_action('upfw_admin_header_links'); ?>
	</ul><!-- /#up_topnav --><?php
}

add_action('upfw_admin_header','upfw_admin_header_links',50);

/******
** Close admin header
*************************************/
function upfw_admin_header_close(){ ?>

			<div class="clear"></div>
		
		</div><!-- /#up_header --><?php

}

add_action('upfw_admin_header','upfw_admin_header_close',500);

/******
** Add default header links
*************************************/
function upfw_default_header_links(){ ?>

	<li class="support"><a href="http://upthemes.com/forum/"><?php _e("Support","upfw"); ?></a></li>
	<li class="documentation"><a href="<?php echo get_admin_url(); ?>admin.php?page=upthemes-docs"><?php _e("Theme Documentation","upfw"); ?></a></li>
	<li class="buy-themes"><a href="<?php echo get_admin_url(); ?>admin.php?page=upthemes-buy"><?php _e("Buy Themes","upfw"); ?></a></li> <?php	

}

add_action('upfw_admin_header_links','upfw_default_header_links');