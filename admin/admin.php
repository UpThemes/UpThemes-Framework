<?php

/******
** UpThemes Framework Version
*************************************/
define('UPTHEMES_VER', '2.0');

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
		define( 'THEME_DIR' , get_bloginfo("template_directory") );
	elseif( file_exists(STYLESHEETPATH.'/admin/admin.php') ):
		define( 'THEME_PATH' , STYLESHEETPATH );
		define( 'THEME_DIR' , get_bloginfo("stylesheet_directory") );
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
** Upload error msg
*************************************/
function upfw_upload_error(){

	$uploads_dir = wp_upload_dir();
	$error = $uploads_dir['error'];
	echo '<div id="message" class="error"><p>' . $error . '</p></div>';
	return false;
	
}

/******
** Upload folder permissions 
** error msg
*************************************/
function upfw_permissions_error(){

	echo '<div id="message" class="error"><p>' . __('It looks like your uploads folder does not have proper permissions. Please set your uploads folder, typically located at [wp-install]/wp-content/uploads/, to 775 or greater.','upfw') . '</p></div>';
	return false;
	
}

/******
** Upload folder created 
** success msg
*************************************/
function upfw_uploads_folder_created(){

	$uploads_dir = wp_upload_dir();
	$base_upload_dir = $uploads_dir['basedir']."/upfw";
	
	echo '<div id="message" class="update-nag">' . __('UpThemes Framework uploads folder created successfully! Your new folder is located at '.$base_upload_dir.'.','upfw') . '</div>';
	return false;
	
}

/******
** Set the uploads directory 
** for media from themes
*************************************/
function upfw_set_uploads_dir(){

	$uploads_dir = wp_upload_dir();
	
	if( $uploads_dir['error'] )
		add_action( 'admin_notices', 'upfw_upload_error', 1, 1 );
	else{
	
		$base_upload_dir = $uploads_dir['basedir']."/upfw";
		$base_upload_url = $uploads_dir['baseurl']."/upfw";
	
		if(!is_dir($base_upload_dir) ){
		
			if( !is_writeable( $uploads_dir['basedir'] ) ) 
				add_action( 'admin_notices', 'upfw_permissions_error', 1, 1 );
			else{
				$oldumask = umask(0);
				@mkdir($base_upload_dir, 0777);
				umask($oldumask);
				
				if( is_writeable( $base_upload_dir ) )
					add_action( 'admin_notices', 'upfw_uploads_folder_created',1 , 1 );
				
			}
		}
	}
	
	if($base_upload_dir)
		define('UPFW_UPLOADS_DIR',$base_upload_dir, true);
	
	if($base_upload_url)
		define('UPFW_UPLOADS_URL',$base_upload_url, true);

}

add_action('upfw_theme_init','upfw_set_uploads_dir',2);
add_action('upfw_admin_init','upfw_set_uploads_dir',2);

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
	
	if( !defined('DISABLE_SEO_ENGINE') )
		require_once('library/engines/seo-engine.php');

	
	if(function_exists('upfw_dbwidget_setup'))
            add_action('wp_dashboard_setup', 'upfw_dbwidget_setup' );
	
        if(function_exists('default_theme_layouts'))
            add_action('init','default_theme_layouts',1);

}

add_action('upfw_theme_init','upfw_engines_init',10);
add_action('upfw_admin_init','upfw_engines_init',10);

/******
** Conditional to test if we're on
** an UpThemes Framework page
*************************************/
function is_upthemes_page(){

    if(is_admin()):
        if(isset($_REQUEST['page']))$page = $_REQUEST['page'];
        if(!empty($page)):
            if( $page =='upthemes' || $page=='upthemes-buy' || $page =='upthemes-docs' ):
                    return true;
            else:
                    return false;
            endif;
        endif;
    endif;
		
}

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
	
	wp_enqueue_script('jquery.history',
		$upthemes."js/jquery.history.js",
		array('jquery'));
	
	wp_enqueue_script('jquery.color',
		$upthemes."js/jquery.color.js",
		array('jquery'));

	wp_enqueue_script('ajaxupload',
		$upthemes."js/ajaxupload.js",
		array('jquery'));
	
	wp_enqueue_script('upfw', 
		$upthemes . "js/up_framework.js", 
		array('farbtastic','jquery.history','ajaxupload'));
		
    /* For Typography Engine */
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-widget');
    wp_enqueue_script('jquery-ui-mouse');
    wp_enqueue_script('jquery-ui-slider', get_bloginfo('template_directory').'/admin/js/jquery.ui.slider.js', array('jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-mouse'));
    wp_enqueue_style('up-slider', get_bloginfo('template_directory').'/admin/css/ui-themes/smoothness/style.css');

}

if( is_admin() && is_upthemes_page() ):
	add_action('upfw_theme_init','upfw_queue_scripts_styles',40);
	add_action('upfw_admin_init','upfw_queue_scripts_styles',40);
endif;

/******
** Show gallery images
*************************************/
function show_gallery_images(){
	global $wpdb; 

	$theme = THEME_DIR;
	
	$path = UPFW_UPLOADS_DIR."/";
	$dir_handle = @opendir($path) or die("Unable to open folder. Please make sure your uploads folder exists and has permissions of 775 or greater.");
	
	while (false !== ($file = readdir($dir_handle))):
	
		if($file == "index.php") continue;
		if($file == ".") continue;
		if($file == "..") continue;
		if($file == "list.php") continue;
		if($file == "Thumbs.db") continue;
		$list .= '<a class="preview" href="'. UPFW_UPLOADS_URL . "/". $file . '"><img src="' . UPFW_UPLOADS_URL . "/" . $file . '" /></a>';

	endwhile;
    
    $list .= '<div class="clear"></div>';
    
	echo $list;
	
	closedir($dir_handle);
	die();

}

add_action('wp_ajax_show_gallery_images','show_gallery_images');

/******
** Create Framework Options Pages
*************************************/
function upfw_create_options_tabs(){

	// Discover Options Files and Create Tabs Array
	if( is_admin() ):
	    $path = THEME_PATH."/theme-options/";
	    $directory = @opendir($path) or wp_die("Cannot open theme-options folder in the ".UPTHEMES_NAME." folder.");
	    while (false !== ($file = readdir($directory))) {
			if(!preg_match('/_/', $file)) continue;
			if(preg_match('/_notes/', $file)) continue;
			if(preg_match('/.DS/', $file)) continue;
	        
	        //Take the extension off
	        $file = explode('.php', $file);
	        
	        //Separate the ordinal
	        $file = explode('_', $file[0]);
	        $order = $file[1];
	        //Define the shortname
	        $shortname = $file[0];
	        
	        //Define the title
	        $file = explode('-', $shortname);
	        $title = '';
	        foreach ($file as $part):
	            $title .= $part." ";
	        endforeach;
	        $title = ucwords($title);
	        
	        //Add tab to array
	        global $up_tabs;
	        $up_tabs[$order] =  array(trim($title) => $shortname);
	        $title = '';
	    }
	    closedir($directory);
	    
	    //Sort tab order
	    global $up_tabs;
	    ksort($up_tabs);
	endif;

}

add_action('upfw_admin_init','upfw_create_options_tabs',50);

/******
** Install default theme options
** if not already set
*************************************/
function upfw_set_defaults(){

	if( !get_option('up_themes_'.UPTHEMES_SHORT_NAME) && $_GET['page']!="upthemes" ):
	
		//Redirect to options page where defaults will automatically be set
		header('Location: '.get_bloginfo('url').'/wp-admin/admin.php?page=upthemes');
		exit;
	
	endif;

}

add_action('upfw_theme_activation', 'upfw_set_defaults',2);

/******
** Set up global theme options
*************************************/
function upfw_setup_theme_options(){

	$up_options_db = get_option('up_themes_'.UPTHEMES_SHORT_NAME);
			
	global $up_options;
		
	//Check if options are stored properly
	if( isset($up_options_db) && is_array($up_options_db) ):
		
	    //Check array to an object
	    foreach ($up_options_db as $k => $v) {
			$up_options -> {$k} = $v;
	    }
	
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
** Activate UpThemes Framework admin
*************************************/
function upfw_upthemes_admin() {

    $name = __('Theme Options','upfw');

	$theme_options_icon = apply_filters('theme_options_icon',THEME_DIR.'/admin/images/upfw_ico_up_16x16.png');

    add_menu_page($name, $name, '10', 'upthemes', 'upthemes_admin_home', $theme_options_icon, 59);
  
	//Create tabbed pages from array
	global $up_tabs;
	if( is_array( $up_tabs ) ):
		foreach ($up_tabs as $tab):
			foreach($tab as $title => $shortname):
				add_submenu_page('upthemes', $title, $title, '10', 'upthemes#/'.$shortname, 'upthemes_admin_'.$shortname);
			endforeach;
		endforeach;
	endif;

	//Static subpages
	add_submenu_page('upthemes', __('Import/Export','upfw'), __('Import/Export','upfw'), '10', 'upthemes#/import-export', 'upthemes_admin_import_export');
	add_submenu_page('upthemes', __('Documentation','upfw'), __('Documentation','upfw'), '10', 'upthemes-docs', 'upthemes_admin_docs');
	add_submenu_page('upthemes', __('Buy Themes','upfw'), __('Buy Themes','upfw'), '10', 'upthemes-buy', 'upthemes_admin_buy');

}

add_action('admin_menu', 'upfw_upthemes_admin',2);

/******
** Find default options
*************************************/
function find_defaults($options){
    global $up_defaults;
    print_r($options);
}

/******
** Render theme options
*************************************/
function render_options($options){
    
    foreach ($options as $value) {
        //Check if there are additional attributes
        if(is_array($value['attr'])):
            $i = $value['attr'];
            global $attr;
            //Convert array into a string
            foreach($i as $k => $v):
                $attr .= $k.'="'.$v.'" ';
            endforeach;
        endif;
        
        //Determine the type of input field
        switch ( $value['type'] ) {
            
            //Render Text Input
            case 'text': upfw_text_field($value,$attr);
            break;
            
            //Render Custom User Text Inputs
            case 'text_list': upfw_text_list($value,$attr);
            break;
            
            //Render textarea options
            case 'textarea': upfw_textarea($value,$attr);
            break;
            
            //Render select dropdowns
            case 'select': upfw_select($value,$attr);
            break;
            
            //Render multple selects
            case 'multiple': upfw_multiple($value,$attr);
            break;
        
            //Render checkboxes
            case 'checkbox': upfw_checkbox($value,$attr);
            break;
            
            //Render color picker
            case 'color': upfw_color($value,$attr);
            break;
            
            //Render upload image
            case 'image': upfw_image($value,$attr);
            break;
            
            //Render category dropdown
            case 'category': upfw_category($value,$attr);
            break;
            
            //Render categories multiple select
            case 'categories': upfw_categories($value,$attr);
            break;
            
            //Render page dropdown
            case 'page': upfw_page($value,$attr);
            break;
            
            //Render pages muliple select
            case 'pages': upfw_pages($value,$attr);
            break;
        
            //Render Form Button
            case 'submit': upfw_submit($value,$attr);
            break;

            //Render taxonomy multiple select
            case 'taxonomy': upfw_taxonomy($value,$attr);
            break;

            //Render Typography Selector
            case 'typography': upfw_typography($value,$attr);
            break;

            //Render Style Selector
            case 'styles': upfw_style($value,$attr);
            break;
            
            //Render Form Button
            case 'button': upfw_button($value,$attr);
            break;

			//Render Text Input
            case 'divider': upfw_divider($value,$attr);
            break;
                    
            //Render Layouts
            case 'layouts': upfw_layouts($value,$attr);
            break;
        
            default:
            break;
        }
        $attr = '';
    }
}

if(is_admin() && is_upthemes_page()) add_action('upfw_admin_init','upfw_save_options',3);

function upfw_save_options(){

    /* ----------------------- Form Security Check -------------------------- */
    if(isset($_POST['_wpnonce'])):
        //Check if submitted from this domain
        check_admin_referer();
        
        //Verify Form Nonce
        if (!wp_verify_nonce($_POST['_wpnonce'], 'save_upthemes') ) 
            wp_die('Security exception detected, please try again.');
            exit;
    endif;

    /* ------------------Import/Export Functions ----------------------- */
    //Restore Previous Options
    global $export_message;
    if(isset($_POST['up_restore'])):
        $current = get_option('up_themes_'.UPTHEMES_SHORT_NAME);
        $backup = get_option('up_themes_'.UPTHEMES_SHORT_NAME.'_backup');
        update_option('up_themes_'.UPTHEMES_SHORT_NAME.'_backup', $current);
        update_option('up_themes_'.UPTHEMES_SHORT_NAME, $backup);
        $export_message = "<p class='import'>" . __("Everything's back to normal now!","upfw") . "</p>";
    endif;
    
    //Restore Defaults

    if(isset($_POST['up_defaults'])):
        $current = get_option('up_themes_'.UPTHEMES_SHORT_NAME);
        update_option('up_themes_'.UPTHEMES_SHORT_NAME.'_backup', $current);
        delete_option('up_themes_'.UPTHEMES_SHORT_NAME);
        $export_message = "<p class='import'>" . __("Default options restored!","upfw") . "<span><form method='post' action=''><input class='up_restore' type='submit' name='up_restore' value='" . __("Click Here to Undo","upfw") . "'></form></span></p>";
    endif;

    /* ------------------------- Import Options Code ------------------------------- */
    if(isset($_POST['up_import']) && isset($_POST['up_import_code'])):
        $import = $_POST['up_import_code'];
        $import = base64_decode($import);
        $import = explode('~~', $import);
        
        //Check if code is legitimate
        if(count($import) == 2):
            $option_name = $import[0];
            $options = explode('||', $import[1]);
    
            foreach ($options as $option):
                $option = explode('|', $option);
                global $new_options;
                $new_options[$option[0]] = preg_replace('/"/', '\'', stripslashes($option[1]));
            endforeach;
            $current_option = get_option($option_name);
            update_option($option_name.'_backup', $current_option);
            update_option($option_name, $new_options);
            $export_message = "<p class='import'>" . __("Options Code Import Successful!","upfw") . "<span><form method='post' action=''><input class='up_restore' type='submit' name='up_restore' value='" . __("Click Here to Undo","upfw") . "'></form></span></p>";
        else:
            $export_message = "<p class='import'>" . __("Oops! Something went wrong. <span>Try pasting your import code again.</span>","upfw") . "</p>";
        endif;

    endif;

    /* ------------------------- Save Theme Options ------------------------------- */
    if(isset($_POST['up_save'])):
    
            $posts = $_POST;
            foreach($posts as $k => $v):
                //Check if option is array (mulitple selects)
                if(is_array($v)):
                    //Check if array is empty
                    $check = 0;
                    foreach($v as $key => $value):    
                        if($value != ''):
                            $check++;
                        endif;
                    endforeach;
                    //If array is not empty
                    if($check > 0  ):
                        //Remove empty array elements
                        $post[$k] = array_filter($v);
                    else:
                        $post[$k] = '';
                    endif;
                    $check = 0;
                else:
                    //Remove slashes
                    $post[$k] = preg_replace('/"/', '\'', stripslashes($v));
                endif;
            endforeach;
            //Add options array to wp_options table
            update_option('up_themes_'.UPTHEMES_SHORT_NAME, $post);
        endif;
    
    /* ---------------------- Default Options Functions  ----------------- */
    global $default_check;
    global $default_options;
    
    $option_check = get_option('up_themes_'.UPTHEMES_SHORT_NAME);	
    if($option_check):
        $default_check = true;
    else:
        $default_check = false;
    endif;

}

/******
** Remove Ugly First Link in 
** WP Sidebar Menu
*************************************/

function remove_ugly_first_link(){ ?>
    <script type="text/javascript">
	jQuery(document).ready(function(){
	    jQuery('li#toplevel_page_upthemes li.wp-first-item').remove();
	});
    </script>
<?php }

if( is_admin() )
	add_action("admin_head","remove_ugly_first_link"); 

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