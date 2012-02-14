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
	require( get_template_directory() . '/admin/options-register.php' );
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
 * @uses	upfw_get_current_tab()	defined in \functions\custom.php
 * @uses	upfw_get_page_tab_markup()	defined in \functions\custom.php
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
			settings_fields("theme_" . ( get_current_theme_id() ) . "_options");
			// Output each settings section, and each
			// Settings field in each section
			do_settings_sections( $settings_section );
		?>
			<?php submit_button( __( 'Save Settings', 'upfw' ), 'primary', "theme_" . ( get_current_theme_id() ) . "_options[submit-{$currenttab}]", false ); ?>
			<?php submit_button( __( 'Reset Defaults', 'upfw' ), 'secondary', "theme_" . ( get_current_theme_id() ) . "_options[reset-{$currenttab}]", false ); ?>
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
		$option_defaults[$name] = $option_parameter['default'];
	}
	// Return the defaults array
	return $option_defaults;
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
	
	$options = $up_theme_options;
	
    return $options;
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
	$up_options = wp_parse_args( get_option( "theme_" . ( get_current_theme_id() ) . "_options", array() ), $option_defaults );
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

?>