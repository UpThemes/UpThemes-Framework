<?php

global $up_theme_options;

$up_theme_options = array();

/**
* UpThemes Framework Version
*/
define('UPTHEMES_VER', '2.2.2');

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

/**
 * Define Theme Title Constant
 * 
 * Set up the constant named THEME_TITLE
 * 
 * @link	http://codex.wordpress.org/Function_Reference/register_setting	Codex Reference: register_setting()
 * 
 * @uses  wp_get_theme()            http://codex.wordpress.org/Function_Reference/wp_get_theme	Codex Reference: wp_get_theme()
 * @uses  get_template_directory()  http://codex.wordpress.org/Function_Reference/get_template_directory	Codex Reference: get_template_directory()
 * @param	array		$themedata		    Holds the theme object
 * @param	string	$theme_title	    Name of the current theme
 */
function upfw_define_theme_title(){
  
  if( function_exists('wp_get_theme') ):
    $themedata = wp_get_theme();
    $theme_title = $themedata->title;
  else:
    $themedata = get_theme_data(get_template_directory() .'/style.css');
    $theme_title = $themedata['Title'];
  endif;

  if( !defined('THEME_TITLE') )
    define('THEME_TITLE',$theme_title);

}

add_action('after_setup_theme','upfw_define_theme_title');

/**
 * Return current theme ID
 * 
 * Checks theme data for theme ID and returns it
 * 
 * @uses  wp_get_theme()              http://codex.wordpress.org/Function_Reference/wp_get_theme	Codex Reference: wp_get_theme()
 * @uses  get_template_directory()    http://codex.wordpress.org/Function_Reference/get_template_directory	Codex Reference: get_template_directory()
 * @param	array		$themedata		      Holds the theme object
 * @param	string	$theme_title	      Name of current theme
 *
 * @returns  string $theme_shortname  Name of current theme
 */
function upfw_get_current_theme_id(){

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

function upfw_get_theme_options_directory_uri(){

  return trailingslashit( trailingslashit( get_template_directory_uri() ) . basename( dirname(__FILE__) ) );

}

/**
* Gentlemen, start your engines
*/
function upfw_engines_init(){

	include_once('library/theme-customizer.php');

}

add_action('after_setup_theme','upfw_engines_init',10);

/**
* Add CSS and Javascript includes
*/
function upfw_enqueue_scripts_styles(){
	
	wp_enqueue_style('up_framework',upfw_get_theme_options_directory_uri() . "css/up_framework.css");

	wp_enqueue_style('farbtastic');
	wp_enqueue_script('jquery-color');
	wp_enqueue_script('up_framework', upfw_get_theme_options_directory_uri() . "js/up_framework.js", array('farbtastic'));
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_style('thickbox');

}

add_action('admin_print_scripts-appearance_page_upfw-settings','upfw_enqueue_scripts_styles',40);

/**
 * UpThemes Framework Theme Options
 *
 * This file defines the Options for the UpThemes Framework.
 * 
 * Theme Options Functions
 * 
 *  - Define Default Theme Options
 *  - Register/Initialize Theme Options
 *  - Define Admin Settings Page
 *  - Register Contextual Help
 * 
 * @package 	UpThemes Framework
 * @copyright	Copyright (c) 2011, Chip Bennett
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		upfw 1.0
 */

/**
 * Globalize the variable that holds the Theme Options
 * 
 * @global	array	$up_theme_options	holds Theme options
 */
global $up_theme_options;
/**
 * upfw Theme Settings API Implementation
 *
 * Implement the WordPress Settings API for the 
 * upfw Theme Settings.
 * 
 * @link	http://codex.wordpress.org/Settings_API	Codex Reference: Settings API
 * @link	http://ottopress.com/2009/wordpress-settings-api-tutorial/	Otto
 * @link	http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/	Ozh
 */
function upfw_register_options(){
	include_once( 'library/options-register.php' );
	include_once( 'library/custom.php' );
}
// Settings API options initilization and validation
add_action( 'admin_init', 'upfw_register_options' );

/**
 * Setup the Theme Admin Settings Page
 * 
 * Add "upfw Options" link to the "Appearance" menu
 * 
 * @uses	upfw_get_settings_page_cap()	defined in \functions\wordpress-hooks.php
 */
function upfw_add_theme_page() {
	// Globalize Theme options page
	global $upfw_settings_page;
	// Add Theme options page
	$upfw_settings_page = add_theme_page(
		// $page_title
		// Name displayed in HTML title tag
		__( 'Theme Options', 'upfw' ), 
		// $menu_title
		// Name displayed in the Admin Menu
		__( 'Theme Options', 'upfw' ), 
		// $capability
		// User capability required to access page
		upfw_get_settings_page_cap(), 
		// $menu_slug
		// String to append to URL after "themes.php"
		'upfw-settings', 
		// $callback
		// Function to define settings page markup
		'upfw_admin_options_page'
	);
}
// Load the Admin Options page
add_action( 'admin_menu', 'upfw_add_theme_page' );

/**
 * upfw Theme Settings Page Markup
 * 
 * @uses	upfw_get_current_tab()	defined in \library\custom.php
 * @uses	upfw_get_page_tab_markup()	defined in \library\custom.php
 */
function upfw_admin_options_page() {
	global $up_tabs;
	// Determine the current page tab
	$currenttab = upfw_get_current_tab();
	// Define the page section accordingly
	$settings_section = 'upfw_' . $currenttab . '_tab';
	?>

	<div class="wrap">
		<?php upfw_get_page_tab_markup(); ?>
		<?php if ( isset( $_GET['settings-updated'] ) ) {
    			echo '<div class="updated"><p>';
				echo __( 'Theme settings updated successfully.', 'upfw' );
				echo '</p></div>';
		} ?>
		<form action="options.php" method="post">
		<?php 
			// Implement settings field security, nonces, etc.
			settings_fields("theme_" . ( upfw_get_current_theme_id() ) . "_options");
			// Output each settings section, and each
			// Settings field in each section
			do_settings_sections( $settings_section );
		?>
			<?php submit_button( __( 'Save Settings', 'upfw' ), 'primary', "theme_" . ( upfw_get_current_theme_id() ) . "_options[submit-{$currenttab}]", false ); ?>
			<?php submit_button( __( 'Reset Defaults', 'upfw' ), 'secondary', "theme_" . ( upfw_get_current_theme_id() ) . "_options[reset-{$currenttab}]", false ); ?>
		</form>
	</div>
<?php 
}

/**
 * upfw Theme Option Defaults
 * 
 * Returns an associative array that holds 
 * all of the default values for all Theme 
 * options.
 * 
 * @uses	upfw_get_option_parameters()	defined in \functions\options.php
 * 
 * @return	array	$defaults	associative array of option defaults
 */
function upfw_get_option_defaults() {
	// Get the array that holds all
	// Theme option parameters
	$option_parameters = upfw_get_option_parameters();
	// Initialize the array to hold
	// the default values for all
	// Theme options
	$option_defaults = array();
	// Loop through the option
	// parameters array
	foreach ( $option_parameters as $option_parameter ) {
		$name = $option_parameter['name'];
		// Add an associative array key
		// to the defaults array for each
		// option in the parameters array
		if( isset($option_parameter['default']) )
			$option_defaults[$name] = $option_parameter['default'];
	}
	// Return the defaults array
	return $option_defaults;
}

/**
 * upfw Theme Option Default
 * 
 * Returns an associative array that holds 
 * all of the default values for all Theme 
 * options.
 * 
 * @uses	upfw_get_option_parameters()	defined in \functions\options.php
 * 
 * @return	string	$default single default value
 */
function upfw_get_option_default($name) {
	// Get the array that holds all
	// Theme option parameters
	$option_parameters = upfw_get_option_parameters();
	// Initialize the array to hold
	// the default values for all
	// Theme options

	$option_parameter = $option_parameters[$name];

	if( isset($option_parameter['default']) )
		$default = $option_parameter['default'];

	return $default;
}

/**
 * upfw Theme Option Parameters
 * 
 * Array that holds parameters for all options for
 * upfw. The 'type' key is used to generate
 * the proper form field markup and to sanitize
 * the user-input data properly. The 'tab' key
 * determines the Settings Page on which the
 * option appears, and the 'section' tab determines
 * the section of the Settings Page tab in which
 * the option appears.
 * 
 * @return	array	$options	array of arrays of option parameters
 */
function upfw_get_option_parameters() {
	global $up_theme_options;
    return $up_theme_options;
}

/**
 * upfw Migrate Theme Options
 * 
 * For users who are upgrading from an older theme
 * that uses the UpThemes Framework, this function
 * checks for the existence of the old theme option
 * array and renames it to the new theme option
 * array.
 */

function upfw_migrate_theme_options(){

	if( is_admin() && isset($_GET['activated'] ) && $pagenow == 'themes.php' ):
	
		$theme_key = "theme_" . upfw_get_current_theme_id() . "_options";
	
		$old_upfw_options = get_option("up_themes_".UPTHEMES_SHORT_NAME);
		$new_upfw_options = get_option($theme_key);
		
		if( $old_upfw_options === false )
			return;
		 
		if( !$new_upfw_options && $old_upfw_options ):
			
			if( !update_option($theme_key, $old_upfw_options) )
				wp_die( __("Could not update theme options.","upfw") );
			
			if( get_option($theme_key) === $old_upfw_options )
				delete_option("up_themes_".UPTHEMES_SHORT_NAME);

		endif;
	
	endif;

}

add_action('switch_themes','upfw_migrate_theme_options');

/**
 * Get upfw Theme Options
 * 
 * Array that holds all of the defined values
 * for upfw Theme options. If the user 
 * has not specified a value for a given Theme 
 * option, then the option's default value is
 * used instead.
 *
 * @uses	upfw_get_option_defaults()	defined in \functions\options.php
 * 
 * @uses	get_option()
 * @uses	wp_parse_args()
 * 
 * @return	array	$upfw_options	current values for all Theme options
 */
function upfw_get_options() {
	// Get the option defaults
	$option_defaults = upfw_get_option_defaults();
	// Globalize the variable that holds the Theme options
	global $up_options;
	// Parse the stored options with the defaults
	$up_options = (object) wp_parse_args( get_option( "theme_" . upfw_get_current_theme_id() . "_options", array() ), $option_defaults );
	// Return the parsed array
	return $up_options;
}

/**
 * Separate settings by tab
 * 
 * Returns an array of tabs, each of
 * which is an indexed array of settings
 * included with the specified tab.
 *
 * @uses	upfw_get_option_parameters()	defined in \functions\options.php
 * 
 * @return	array	$settingsbytab	array of arrays of settings by tab
 */
function upfw_get_settings_by_tab() {

	global $up_tabs;

	// Initialize an array to hold
	// an indexed array of tabnames
	$settingsbytab = array();
	// Loop through the array of tabs
	foreach ( $up_tabs as $tab ) {
		$tabname = $tab['name'];
		// Add an indexed array key
		// to the settings-by-tab 
		// array for each tab name
		$tabs[] = $tabname;
	}
	// Get the array of option parameters
	$option_parameters = upfw_get_option_parameters();
	// Loop through the option parameters
	// array
	foreach ( $option_parameters as $option_parameter ) {
		// Ignore "internal" type options
		if ( in_array( $option_parameter['tab'] , $tabs ) ) {
			$optiontab = $option_parameter['tab'];
			$optionname = $option_parameter['name'];
			// Add an indexed array key to the 
			// settings-by-tab array for each
			// setting associated with each tab
			$settingsbytab[$optiontab][] = $optionname;
		}
	}
	// Return the settings-by-tab
	// array
	return $settingsbytab;
}

function upfw_get_settings_page_cap() {
	return 'edit_theme_options';
}
// Hook into option_page_capability_{option_page}
add_action( 'option_page_capability_upfw-settings', 'upfw_get_settings_page_cap' );


function upfw_text_field($value,$attr){ ?>

	<input type="text" name="theme_<?php echo upfw_get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]" value="<?php echo $value; ?>">
                
<?php
}

function upfw_text_list($value,$attr){ ?>

    <div class="text_list">
        <?php
        if( isset( $value ) ) :
            if( is_array( $value ) ):
                foreach( $value as $text ):?>
                    <div class="entry">
                        <input class="text_list" type="text" name="theme_<?php echo upfw_get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>][]" value="<?php echo $text?>" />
                        <span class="delete_text_list"><a href="#"><img src="<?php echo upfw_get_theme_options_directory_uri(); ?>images/upfw_ico_delete.png" alt="Delete Text Field" /></a></span>
                        <div class="clear"></div>
                    </div>
                <?php endforeach;
            endif;
        else:
            if( isset( $value['value'] ) ) :
                if(preg_match('/,/', $value['value'])):
                    $list = explode(', ', $value['value']);
                    foreach($list as $text):?>
                            <div class="entry">
                                <input class="text_list" type="text" name="<?php echo $attr['name']; ?>[]" id="<?php echo $attr['name']; ?>" value="<?php echo $text?>" <?php echo $attr; ?> />
                                <span class="delete_text_list"><a href="#"><img src="<?php echo upfw_get_theme_options_directory_uri(); ?>images/upfw_ico_delete.png" alt="Delete Text Field" /></a></span>
                                <div class="clear"></div>
                            </div>
                    <?php endforeach;
                else:
                    if($value['value'] == $v ):
                        $selected = ' selected = "selected"';
                    endif;
                endif;
            endif;
        endif;?>
        
    </div>

	<?php $add_text = __('Add New Field', 'upfw');?>
	<p class="add_text_list"><a href="#"><?php echo $add_text;?></a></p>

<?php
}

function upfw_textarea($value,$attr){ ?>
	<textarea name="theme_<?php echo upfw_get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]" cols="48" rows="8"><?php echo $value; ?></textarea>
<?php
}

function upfw_select($value,$attr){ ?>
<select name="theme_<?php echo upfw_get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]">
    <?php
    if ( isset( $attr['valid_options'] ) ) :
        $options = $attr['valid_options'];
        foreach( $options as $option ) : 
        ?>
            <option value="<?php echo $option['name']; ?>" <?php selected($option['name'],$value); ?>><?php echo $option['title']; ?></option>
			<?php 
		endforeach;
	else:
		_e("This option has no valid options. Please create valid options as an array inside the UpThemes Framework.","upfw");
    endif;
    ?>
</select>
<?php
}

function upfw_radio_image($value,$attr){ ?>
    <?php
    if ( isset( $attr['valid_options'] ) ) :
        $options = $attr['valid_options'];
        foreach( $options as $option ) : 
        ?>
    <label class="radio_image">
    <input type="radio" name="theme_<?php echo upfw_get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]" value="<?php echo $option['name']; ?>" <?php checked($option['name'],$value); ?>>
      <?php if( $option['image'] ) echo '<img src="' . $option['image'] . '">'; ?>
    </label>
			<?php
		endforeach;
	else:
		_e("This option has no valid options. Please create valid options as an array inside the UpThemes Framework.","upfw");
    endif;
    ?>
</select>
<?php
}

function upfw_radio($value,$attr){ ?>
    <?php
    if ( isset( $attr['valid_options'] ) ) :
        $options = $attr['valid_options'];
        foreach( $options as $option ) : 
        ?>
    <label class="radio">
      <input type="radio" name="theme_<?php echo upfw_get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]" value="<?php echo $option['name']; ?>" <?php checked($option['name'],$value); ?>> <?php echo $option['title']; ?>
    </label>
			<?php
		endforeach;
	else:
		_e("This option has no valid options. Please create valid options as an array inside the UpThemes Framework.","upfw");
    endif;
    ?>
</select>
<?php
}

function upfw_multiple($value,$attr){ ?>

    <select name="theme_<?php echo upfw_get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>][]" multiple>
        <?php
		if ( isset( $attr['valid_options'] ) ) :
		    $options = $attr['valid_options'];
		    foreach( $options as $option_key => $option_value ) : ?>
                <option value="<?php echo $option_value['name']; ?>" <?php selected( in_array($option_value['name'],$value) ); ?>><?php echo $option_value['title']; ?></option>
		<?php endforeach;
		endif;
        ?>
    </select>
<?php
}

function upfw_checkbox($value,$attr){

	if ( isset( $attr['valid_options'] ) ) :
	    $options = $attr['valid_options'];
	    foreach( $options as $option_key => $option_value ) : ?>
			<input type="checkbox" <?php checked($value[$option_value['name']]);?> name="theme_<?php echo upfw_get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>][<?php echo $option_value['name']; ?>]">
	        <label for="<?php echo $option_value['name']; ?>"><?php echo $option_value['title'];?></label><br>
	<?php endforeach;
	endif;

}

function upfw_color($value,$attr){ ?>

    <span class="colorPickerWrapper">
        <input type="text" class="popup-colorpicker" id="<?php echo $attr['name']; ?>" name="theme_<?php echo upfw_get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]" value="<?php echo $value; ?>" />
        <a href="#" class="clear"><img src="<?php echo upfw_get_theme_options_directory_uri(); ?>images/upfw_ico_delete.png" alt="Delete Text Field" /></a>
        <div class="popup-guy">
            <div class="popup-guy-inside">
                <div id="<?php echo $attr['name']; ?>picker" class="color-picker"></div>
            </div>
        </div>
    </span>

<?php
}

function upfw_image($value,$attr){ ?>
	
	<script language="JavaScript">
	jQuery(document).ready(function() {
	
		$container = jQuery("#<?php echo $attr['name']; ?>_container");
		$image_field = $container.find('.upload_image_field');
		$image_button = $container.find('.upload_image_button');

		if( $image_field.val() )
			$container.find('.image_preview').html("<img src='"+$image_field.val()+"'>");
	
		$image_button.click(function() {
			formfield = $image_field.attr('name');
			tb_show('', 'media-upload.php?type=image&TB_iframe=true');
			return false;
		});
		
		window.send_to_editor = function(html) {
			imgurl = jQuery('img',html).attr('src');
			$image_field.val(imgurl);
			$container.find('.image_preview').html("<img src='"+imgurl+"'>");
			tb_remove();
		}
	
	});
	</script>

	<div id="<?php echo $attr['name']; ?>_container">
		<input type="text" class="upload_image_field" id="<?php echo $attr['name']; ?>" name="theme_<?php echo upfw_get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]" value="<?php echo $value; ?>">
		<input class="upload_image_button" type="button" value="Upload or Select Image" />
		<div class="image_preview"></div>
	</div>

<?php
}

function upfw_category($value,$attr){
    global $wpdb;
?>

    <select name="theme_<?php echo upfw_get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]" id="theme_<?php echo upfw_get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]">
        <?php
		$categories = upfw_get_category_list();
		foreach ( $categories as $cat ) {
			echo '<option value="' . $cat['name'] . '"' . selected( $cat['name'] == $attr['name'] || $cat['name'] == $attr['value'] ) . '>' . $cat['title'] . '</option>';
		}
        ?>
    </select>

<?php
}

function upfw_categories($value,$attr){
    global $wpdb;
?>

<select name="theme_<?php echo upfw_get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]" id="theme_<?php echo upfw_get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]" multiple>
    <?php
    $i = $wpdb->get_results("SELECT $wpdb->terms.name, $wpdb->terms.slug, $wpdb->term_taxonomy.term_id FROM $wpdb->terms LEFT JOIN $wpdb->term_taxonomy ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id WHERE $wpdb->term_taxonomy.taxonomy = 'category' ORDER BY $wpdb->terms.name", ARRAY_A);
    foreach($i as $row):
            if($attr['name']):
                if($row['slug'] == $attr['name']):
                    $selected = " selected='selected'";
                endif;
            else:
                if($value['value'] == $row['slug']):
                    $selected = ' selected = "selected"';
                endif;
            endif;
        echo "<option value='".$row['slug']."'".$selected.">".$row['name']."</option>";
        $selected = '';
    endforeach;
    ?>
</select>
                
<?php
}

function upfw_page($value,$attr){
    global $wpdb;
?>

	<select name="theme_<?php echo upfw_get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]" id="theme_<?php echo upfw_get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]">
	    <?php
	    $i = $wpdb->get_results("SELECT post_title, ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type='page' ORDER BY post_title", ARRAY_A);
	    foreach($i as $row):
	        if($attr['name']):
	            if($row['ID'] == $attr['name']):
	                $selected = " selected='selected'";
	            endif;
	        else:
	            if($row['post_title'] == $value['value']):
	                $selected = " selected='selected'";
	            endif;
	        endif;
	        echo "<option value='".$row['ID']."'".$selected.">".$row['post_title']."</option>";
	        $selected = '';
	    endforeach;
	    ?>
	</select>
                
<?php
}

function upfw_pages($value,$attr){
    global $wpdb;
?>

	<select name="theme_<?php echo upfw_get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]" id="theme_<?php echo upfw_get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]" multiple>
	    <?php
	    $i = $wpdb->get_results("SELECT post_title, ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type='page' ORDER BY post_title", ARRAY_A);
	    foreach($i as $row):
	        if($attr['name']):
	            if($row['ID'] == $attr['name']):
	                $selected = " selected='selected'";
	            endif;
	        else:
	            if($row['post_title'] == $value['value']):
	                $selected = " selected='selected'";
	            endif;
	        endif;
	        echo "<option value='".$row['ID']."'".$selected.">".$row['post_title']."</option>";
	        $selected = '';
	    endforeach;
	    ?>
	</select>

<?php
}

function upfw_taxonomy($value,$attr){
    global $wpdb;
?>

 <select name="theme_<?php echo upfw_get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]" id="theme_<?php echo upfw_get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]">
	<?php $taxonomy = $value['taxonomy'];
	$i = $wpdb->get_results("SELECT $wpdb->terms.name, $wpdb->terms.slug, $wpdb->term_taxonomy.term_id FROM $wpdb->terms LEFT JOIN $wpdb->term_taxonomy ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id WHERE $wpdb->term_taxonomy.taxonomy = '$taxonomy' ORDER BY $wpdb->terms.name", ARRAY_A);
	foreach($i as $row):
	    if(!empty($attr['name'])):
	        foreach($attr['name'] as $std):
	            if($std == $row['slug']):
	                $selected = ' selected = "selected"';
	            endif;
	        endforeach;
	    else:
	        if($value['value']):
	            if(preg_match('/,/', $value['value'])):
	                $cats = explode(', ', $value['value']);
	                foreach($cats as $cat):
	                    if(preg_match('/\b'.$row['slug'].'\b/', $cat)):
	                        $selected = ' selected = "selected"';
	                    endif;
	                endforeach;
	            else:
	                if($value['value'] == $row['slug'] ):
	                    $selected = ' selected = "selected"';
	                endif;
	            endif;
	        else:
	            if($value['value'] == $row['post_title'] ):
	                $selected = ' selected = "selected"';
	            endif;
	        endif;
	    endif;
	    
	    echo "<option value='".$row['slug']."'".$selected.">".$row['name']."</option>";
	    $selected = '';
	endforeach;
 	?>
 </select>

<?php
}