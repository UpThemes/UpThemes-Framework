<?php
/**
 * UpThemes Framework
 *
 * This file sets up and initializes the theme options framework.
 *
 * @package 	UpThemes Framework
 * @copyright	Copyright (c) 2013-2014, UpThemes
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		1.0
 */

/**
* UpThemes Framework Version
*/
define( 'UPTHEMES_VER', '2.5.2' );
define( 'THEME_OPTIONS_DIR', dirname( __FILE__ ) );

/**
 * Globalize the variable that holds the Theme Options
 *
 * @global	array	$up_theme_options	holds theme options
 */
global $up_theme_options;

$up_theme_options = array();

/**
 * Checks user role and adds functionality for admin users with certain capabilities.
 *
 * @uses 	current_user_can() 			http://codex.wordpress.org/Function_Reference/current_user_can			Codex Reference: current_user_can()
 * @uses 	add_action() 	 			http://codex.wordpress.org/Function_Reference/add_action				Codex Reference: add_action()
 */
function upfw_rolescheck() {
	if ( current_user_can( 'edit_theme_options' ) ) {

		// Load the functionality for theme options page
		add_action( 'admin_menu', 'upfw_add_theme_page' );

		// Enqueue scripts and styles
		add_action( 'admin_print_scripts-appearance_page_upfw-settings', 'upfw_enqueue_scripts_styles', 40 );

		// Load the framework in the admin.
		add_action( 'admin_init', 'upfw_init' );

		global $wp_customize;

		if ( isset( $wp_customize ) && $wp_customize->is_preview() ){
			require_once( THEME_OPTIONS_DIR . '/library/theme-customizer.php' );
		}

	}
}
// Hook
add_action( 'init', 'upfw_rolescheck', 1 );

/**
 * Initialize the UpThemes Framework.
 *
 * Loads the full framework for the theme options page.
 *
 * @uses 	register_setting() 				http://codex.wordpress.org/Function_Reference/register_setting	Codex Reference: register_setting()
 * @uses 	upfw_get_current_theme_id() 	defined in options.php
 */
function upfw_init() {

	require_once( THEME_OPTIONS_DIR . '/library/options-types.php' );
	require_once( THEME_OPTIONS_DIR . '/library/options-sanitize.php' );
	require_once( THEME_OPTIONS_DIR . '/library/options-register.php' );
	require_once( THEME_OPTIONS_DIR . '/library/custom.php' );

	register_setting(
		// $option_group
		'theme_' . upfw_get_current_theme_id() . '_options',
		// $option_name
		'theme_' . upfw_get_current_theme_id() . '_options',
		// $sanitize_callback
		'upfw_options_validate'
	);


	do_action( 'upfw_loaded' );
}

/**
 * Returns 'edit_theme_options' user capability to
 * allow proper usage of theme options page.
 *
 * @return 		string 	edit_theme_options 		Edit theme options user capability.
 */
function upfw_get_settings_page_cap() {
	return 'edit_theme_options';
}
// Hook into option_page_capability_{option_page}
add_action( 'option_page_capability_upfw-settings', 'upfw_get_settings_page_cap' );

/**
 * Places all theme options into global for usage by themes and plugins.
 *
 * @param 	array 	$options 		Current set of theme options
 */
function register_theme_options( $options ) {
	global $up_theme_options;

	$up_theme_options = array_merge( $up_theme_options, $options );
}

/**
 * Register a theme options tab inside the $up_tags global.
 *
 * @param 	array 	$args 		Theme options tab arguments.
 */
function register_theme_option_tab( $args ) {
	global $up_tabs;

	extract( $args );

	if( $name ):
		$up_tabs[] = $args;
		return true;
	endif;
}

/**
 * Define Theme Title Constant
 *
 * Creates THEME_TITLE constant
 *
 * @link	http://codex.wordpress.org/Function_Reference/register_setting	Codex Reference: register_setting()
 *
 * @uses	wp_get_theme()			http://codex.wordpress.org/Function_Reference/wp_get_theme	Codex Reference: wp_get_theme()
 * @param	array	$theme		    Holds the theme object
 * @param	string	$theme_title	Name of the current theme
 */
function upfw_define_theme_title() {
	$theme = wp_get_theme();

	if( !defined( 'THEME_TITLE' ) )
		define( 'THEME_TITLE', $theme->title );
}

add_action('after_setup_theme','upfw_define_theme_title',5);

/**
 * Return current theme ID
 *
 * Checks theme data for theme ID and returns it
 *
 * @uses	wp_get_theme()			http://codex.wordpress.org/Function_Reference/wp_get_theme	Codex Reference: wp_get_theme()
 *
 * @return	string $theme_shortname  Name of current theme
 */
function upfw_get_current_theme_id() {
	$theme = wp_get_theme();

	$theme_shortname = strtolower( preg_replace( '/ /', '_', $theme->title ) );

	return $theme_shortname;
}

/**
 * Return current theme options directory.
 *
 * Returns the location of the theme options directory.
 *
 * @return	string dirname 	Directory containing theme.
 */
function upfw_get_theme_options_directory() {
	return dirname(__FILE__);
}

/**
 * Return current theme directory.
 *
 * Checks the location of the theme directory to determine if
 * it is located in the parent or child theme.
 *
 * @uses	wp_get_theme()						http://codex.wordpress.org/Function_Reference/wp_get_theme	Codex Reference: wp_get_theme()
 * @uses	get_stylesheet_directory_uri()		http://codex.wordpress.org/Function_Reference/get_stylesheet_directory_uri	Codex Reference: get_stylesheet_directory_uri()
 * @uses	get_template_directory_uri()		http://codex.wordpress.org/Function_Reference/get_template_directory_uri	Codex Reference: get_template_directory_uri()
 * @return	string 								Stylesheet directory URI.
 */
function upfw_get_theme_dir(){
	$theme = wp_get_theme();

	$theme_template = $theme->get('Template');
	if( ! empty( $theme_template ) ){
		return get_stylesheet_directory_uri();
	}

	return get_template_directory_uri();
}

/**
 * Return current theme options directory URI.
 *
 * Checks the location of the theme options directory to determine if
 * it is located in the parent or child theme.
 *
 * @uses	upfw_get_theme_dir() 	Returns theme options theme directory.
 * @return	string 	$theme_dir		Stylesheet directory URI.
 */
function upfw_get_theme_options_directory_uri() {

	$theme_dir = upfw_get_theme_dir();

	return trailingslashit( trailingslashit( $theme_dir ) . basename( THEME_OPTIONS_DIR ) );
}

/**
 * Add CSS and Javascript includes for administration of options.
 *
 * Enqueues all the necessary styles and scripts for the admin,
 * only loading assets on the appropriate theme options page.
 *
 * @uses 	wp_enqueue_style() 						http://codex.wordpress.org/Function_Reference/wp_enqueue_style				Codex Reference: wp_enqueue_style()
 * @uses	wp_enqueue_script()						http://codex.wordpress.org/Function_Reference/wp_enqueue_script				Codex Reference: wp_enqueue_script()
 * @uses	wp_enqueue_media()						http://codex.wordpress.org/Function_Reference/wp_enqueue_media				Codex Reference: wp_enqueue_media()
 * @uses	wp_localize_script()					http://codex.wordpress.org/Function_Reference/wp_localize_script			Codex Reference: wp_localize_script()
 * @uses	wp_register_script()					http://codex.wordpress.org/Function_Reference/wp_register_script			Codex Reference: wp_register_script()
 * @uses	upfw_get_theme_options_directory_uri() 	defined in options.php
 */
function upfw_enqueue_scripts_styles() {

	// Loads styles and scripts to enable built-in colorpicker.
	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_style( 'wp-color-picker' );

	// Loads custom styles and scripts for the options page
	wp_enqueue_style('up_framework',upfw_get_theme_options_directory_uri() . "css/up_framework.css");
	wp_enqueue_script('up_framework', upfw_get_theme_options_directory_uri() . "js/up_framework.js", array('jquery'));

	// This function loads in the required media files for the media manager.
	wp_enqueue_media();

	// Register, localize and enqueue built-in media picker.
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
 * Set up the theme admin settings page
 *
 * Add "Theme Options" link to the "Appearance" menu
 *
 * @uses	add_theme_page() 				http://codex.wordpress.org/Function_Reference/add_theme_page 		Codex Reference: add_theme_page()
 * @uses	upfw_get_settings_page_cap()	defined in \library\options-types.php
 */
function upfw_add_theme_page() {

	// Globalize theme options page
	global $upfw_settings_page;

	// Add theme options page
	if( defined( 'UPFW_NO_THEME_OPTIONS_PAGE' ) && UPFW_NO_THEME_OPTIONS_PAGE == true )
		return;

	$upfw_settings_page = add_theme_page(
		// $page_title
		// Name displayed in HTML title tag
		apply_filters('options_page_menu_title', __( 'Theme Options', 'upfw' ) ),
		// $menu_title
		// Name displayed in the Admin Menu
		apply_filters('options_page_title', __( 'Theme Options', 'upfw' ) ),
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
 * Theme Settings Page Markup
 *
 * @uses	upfw_get_current_tab()		defined in \library\custom.php
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
 * Theme Option Defaults
 *
 * Returns an associative array that holds all of the default
 * values for all theme options.
 *
 * @uses	upfw_get_option_parameters()	defined in options.php
 *
 * @return	array	$option_defaults	associative array of option defaults
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
 * Theme Option Default
 *
 * Returns an associative array that holds
 * all of the default values for all Theme
 * options.
 *
 * @uses	upfw_get_option_parameters()	defined in options.php
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
 * Theme Option Parameters
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
 * Get Theme Options
 *
 * Array that holds all of the defined values
 * for upfw Theme options. If the user
 * has not specified a value for a given Theme
 * option, then the option's default value is
 * used instead.
 *
 * @uses	upfw_get_option_defaults()	defined in options.php
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
 * @uses	upfw_get_option_parameters()	defined in options.php
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
