<?php

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
 * @copyright	Copyright (c) 2013, UpThemes
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

$up_theme_options = array();

add_action( 'init', 'upfw_rolescheck', 20 );

function upfw_rolescheck() {
	if ( current_user_can( 'edit_theme_options' ) ) {
		// Load the functionality for theme options page
		add_action( 'admin_menu', 'upfw_add_theme_page' );
		add_action( 'admin_print_scripts-appearance_page_upfw-settings', 'upfw_enqueue_scripts_styles', 40 );
		add_action( 'admin_init', 'upfw_init' );
		//add_action( 'wp_before_admin_bar_render', 'optionsframework_adminbar' );
		require_once( dirname( __FILE__ ) . '/library/theme-customizer.php' );
	}
}

function upfw_init() {

	require_once( dirname( __FILE__ ) . '/library/options-sanitize.php' );
	require_once( dirname( __FILE__ ) . '/library/options-register.php' );
	require_once( dirname( __FILE__ ) . '/library/custom.php' );

	register_setting(
		// $option_group
		"theme_" . upfw_get_current_theme_id() . "_options",
		// $option_name
		"theme_" . upfw_get_current_theme_id() . "_options",
		// $sanitize_callback
		'upfw_options_validate'
	);

}

/**
* UpThemes Framework Version
*/
define('UPTHEMES_VER', '2.4');

function register_theme_options( $options ) {
	global $up_theme_options;
	$up_theme_options = array_merge( $up_theme_options, $options );
}

function register_theme_option_tab( $args ) {
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
 * @uses	wp_get_theme()			http://codex.wordpress.org/Function_Reference/wp_get_theme	Codex Reference: wp_get_theme()
 * @param	array	$theme		    Holds the theme object
 * @param	string	$theme_title	Name of the current theme
 */
function upfw_define_theme_title() {
	$theme = wp_get_theme();

	if( !defined('THEME_TITLE') )
		define('THEME_TITLE',$theme->title);
}

add_action('after_setup_theme','upfw_define_theme_title',5);

/**
 * Return current theme ID
 *
 * Checks theme data for theme ID and returns it
 *
 * @uses	wp_get_theme()			http://codex.wordpress.org/Function_Reference/wp_get_theme	Codex Reference: wp_get_theme()
 * @param	array	$theme		    Holds the theme object
 * @param	string	$theme_title	Name of the current theme
 *
 * @return	string $theme_shortname  Name of current theme
 */
function upfw_get_current_theme_id() {
	$theme = wp_get_theme();

	$theme_shortname = strtolower(preg_replace('/ /', '_', $theme->title));

	return $theme_shortname;
}

function upfw_get_theme_options_directory() {
	return dirname(__FILE__);
}

function upfw_get_theme_options_directory_uri() {
	return trailingslashit( trailingslashit( get_template_directory_uri() ) . basename( dirname(__FILE__) ) );
}

/**
* Add CSS and Javascript includes
*/
function upfw_enqueue_scripts_styles() {

	wp_enqueue_style('up_framework',upfw_get_theme_options_directory_uri() . "css/up_framework.css");

	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_style( 'wp-color-picker' );

	wp_enqueue_script('up_framework', upfw_get_theme_options_directory_uri() . "js/up_framework.js", array('jquery'));

	// This function loads in the required media files for the media manager.
	wp_enqueue_media();

	// Register, localize and enqueue our custom JS.
	wp_register_script( 'upfw-nmp-media', upfw_get_theme_options_directory_uri() . 'js/media.js', array( 'jquery' ), '1.0.0', true );
	wp_localize_script( 'upfw-nmp-media', 'upfw_nmp_media',
		array(
			'title'     => __( 'Upload or Choose Your Custom Image File', 'upfw' ), // This will be used as the default title
			'button'    => __( 'Insert Image into Input Field', 'upfw' )            // This will be used as the default button text
		)
	);
	wp_enqueue_script( 'upfw-nmp-media' );

}

/**
 * Setup the Theme Admin Settings Page
 *
 * Add "Theme Options" link to the "Appearance" menu
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
		$optiontab = $option_parameter['tab'];
		$optionname = $option_parameter['name'];
		// Add an indexed array key to the
		// settings-by-tab array for each
		// setting associated with each tab
		$settingsbytab[$optiontab][] = $optionname;
		$settingsbytab['all'][] = $optionname;
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

/**
 * Hook for adding custom fields
 */

global $upfw_custom_callbacks;

function upfw_add_custom_field($type = null, $callback = null) {
	// don't do anything if they don't input the correct args
	if (is_null($type) or is_null($callback)) {
		return false;
	}

	// check to see if $callback is an actual function
	// we only want to add the callback if the function exists
	if (function_exists($callback)) {
		global $upfw_custom_callbacks;

		// for right now we will override any previous callbacks added
		$upfw_custom_callbacks[$type] = $callback;
	}
}


function upfw_text($value,$attr) { ?>

	<input type="text" name="theme_<?php echo esc_attr( upfw_get_current_theme_id() ); ?>_options[<?php echo esc_attr( $attr['name'] ); ?>]" value="<?php echo esc_attr( $value ); ?>">

<?php
}

function upfw_textarea($value,$attr) { ?>

	<textarea name="theme_<?php echo esc_attr( upfw_get_current_theme_id() ); ?>_options[<?php echo esc_attr( $attr['name'] ); ?>]" cols="48" rows="8"><?php echo esc_attr( $value ); ?></textarea>

<?php
}

function upfw_editor($value, $attr) {
	// setup some basic variables to help
	$theme_id = upfw_get_current_theme_id();
	$name = $attr['name'];

	// remap some of the $attr keys to wp_editor keys
	// more settings can be remapped once they are needed
	$editor_settings = array(
		'textarea_name' => "theme_{$theme_id}_options[{$name}]"
	);

	// WordPress Editor generator
	wp_editor($value, $attr['name'], $editor_settings);
}

function upfw_select($value,$attr) { ?>
<select name="theme_<?php echo esc_attr( upfw_get_current_theme_id() ); ?>_options[<?php echo esc_attr( $attr['name'] ); ?>]">
	<?php
	if ( isset( $attr['valid_options'] ) ) :
		$options = $attr['valid_options'];
		foreach( $options as $option ) :
		?>
			<option value="<?php echo esc_attr( $option['name'] ); ?>" <?php selected($option['name'],$value); ?>><?php echo esc_html( $option['title'] ); ?></option>
			<?php
		endforeach;
	else:
		_e("This option has no valid options. Please create valid options as an array inside the UpThemes Framework.","upfw");
	endif;
	?>
</select>
<?php
}

function upfw_radio_image($value,$attr) {

	if ( isset( $attr['valid_options'] ) ) :
		$options = $attr['valid_options'];
		foreach( $options as $option ) :

?>

	<label class="radio_image">
		<input type="radio" name="theme_<?php echo esc_attr( upfw_get_current_theme_id() ); ?>_options[<?php echo esc_attr( $attr['name'] ); ?>]" value="<?php echo esc_attr( $option['name'] ); ?>" <?php checked( $option['name'], $value ); ?>>
		<?php if( $option['image'] ) echo '<img src="' . esc_url( $option['image'] ) . '">'; ?>
	</label>
<?php
		endforeach;
	else:
		_e("This option has no valid options. Please create valid options as an array inside the UpThemes Framework.","upfw");
	endif;

}

function upfw_radio($value,$attr) {

	if ( isset( $attr['valid_options'] ) ) :
		$options = $attr['valid_options'];
		foreach( $options as $option ) :

?>

	<label class="radio">
	  <input type="radio" name="theme_<?php echo esc_attr( upfw_get_current_theme_id() ); ?>_options[<?php echo esc_attr( $attr['name'] ); ?>]" value="<?php echo esc_attr( $option['name'] ); ?>" <?php checked( esc_attr( $option['name'] ), $value ); ?>> <?php echo esc_attr( $option['title'] ); ?>
	</label>

<?php
		endforeach;
	else:
		_e("This option has no valid options. Please create valid options as an array inside the UpThemes Framework.","upfw");
	endif;

}

function upfw_multicheck($value,$attr) {

	if ( isset( $attr['valid_options'] ) ) :
		$options = $attr['valid_options'];

		foreach( $options as $option_key => $option_value ) :
			$checked;
			if( isset( $value[$option_value['name']] ) ){
				$checked = checked( $value[$option_value['name']], true, false );
			} else {
				$checked = checked( false, true, false );
			}
		?>
			<input type="checkbox" <?php echo $checked; ?> name="theme_<?php echo esc_attr( upfw_get_current_theme_id() ); ?>_options[<?php echo esc_attr( $attr['name'] ); ?>][<?php echo esc_attr( $option_value['name'] ) ?>]">
			<label for="<?php echo esc_html( $option_value['name'] ) ?>"><?php echo esc_html( $option_value['title'] ); ?></label><br>
	<?php endforeach;
	endif;

}

function upfw_color($value,$attr) { ?>

	<span class="colorPickerWrapper">
		<input type="text" class="popup-colorpicker" id="<?php echo esc_attr( $attr['name'] ); ?>" name="theme_<?php echo esc_attr( upfw_get_current_theme_id() ); ?>_options[<?php echo esc_attr( $attr['name'] ); ?>]" value="<?php echo esc_attr( $value ); ?>" />
	</span>

<?php
}

function upfw_upload($value,$attr) { ?>

	<div id="<?php echo esc_html( $attr['name'] ); ?>_container" class="imageWrapper">
		<input type="text" class="upfw-open-media" id="<?php echo esc_attr( $attr['name'] ); ?>" name="theme_<?php echo esc_attr( upfw_get_current_theme_id() ); ?>_options[<?php echo esc_attr( $attr['name'] ); ?>]" value="<?php echo esc_attr( $value ); ?>">
		<input class="upfw-open-media button button-primary" type="submit" value="<?php esc_attr_e('Upload or Select a File','upfw'); ?>" />
		<div class="image_preview"></div>
	</div>

<?php
}