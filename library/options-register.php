<?php
/**
 * Theme Options Settings API
 *
 * This file implements the WordPress Settings API for the 
 * Options for the UpThemes Framework.
 * 
 * @package 	UpThemes Framework
 * @copyright	Copyright (c) 2011, Chip Bennett
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		upfw 1.0
 */

/**
 * Register Theme Settings
 * 
 * Register theme options array to hold all theme options.
 * 
 * @link	http://codex.wordpress.org/Function_Reference/register_setting	Codex Reference: register_setting()
 * 
 * @param	string		$option_group		Unique Settings API identifier; passed to settings_fields() call
 * @param	string		$option_name		Name of the wp_options database table entry
 * @param	callback	$sanitize_callback	Name of the callback function in which user input data are sanitized
 */
register_setting( 
	// $option_group
	"theme_" . upfw_get_current_theme_id() . "_options", 
	// $option_name
	"theme_" . upfw_get_current_theme_id() . "_options", 
	// $sanitize_callback
	'upfw_options_validate'
);

/**
 * Register Global Admin Javascript Variables
 * 
 * Register JS variables used by theme options admin Javascript.
 * 
 * @global	array	Settings Page Tab definitions
 *
 */

function upfw_register_admin_js_globals(){

	global $up_tabs;
	
	$tab = '';
	$selected_tab = '';

	$selected_tab = $selected_tab ? $up_tabs[0]['name'] : $tab;
	$theme_name = strtolower(THEME_TITLE);
	$theme_url = get_template_directory_uri();

	echo "<script type=\"text/javascript\">\n";
	echo "var upfw = {\n";
	echo "    'theme' : '$theme_name',\n";

	if( isset( $_GET['page']) && esc_attr( $_GET['page'] ) == 'upfw-settings' && $selected_tab )
		echo "    'current_tab' : '$selected_tab',\n";
	
	echo "    'theme_url' : '$theme_url'\n";	
	echo "}" . "\n";
	echo "</script>" . "\n";

}

add_action('admin_enqueue_scripts','upfw_register_admin_js_globals',1);
//add_action('admin_head','do_awesome',1);

/**
 * Theme register_setting() sanitize callback
 * 
 * Validate and whitelist user-input data before updating Theme 
 * Options in the database. Only whitelisted options are passed
 * back to the database, and user-input data for all whitelisted
 * options are sanitized.
 * 
 * @link	http://codex.wordpress.org/Data_Validation	Codex Reference: Data Validation
 * 
 * @param	array	$input	Raw user-input data submitted via the Theme Settings page
 * @return	array	$input	Sanitized user-input data passed to the database
 *
 * @global	array	Settings Page Tab definitions
 *
 */
function upfw_options_validate( $input ) {

	global $up_tabs;

	// This is the "whitelist": current settings
	$valid_input = (array) upfw_get_options();
	// Get the array of Theme settings, by Settings Page tab
	$settingsbytab = upfw_get_settings_by_tab();
	// Get the array of option parameters
	$option_parameters = upfw_get_option_parameters();
	// Get the array of option defaults
	$option_defaults = upfw_get_option_defaults();
	// Get list of tabs
	
	// Determine what type of submit was input
	$submittype = 'submit';	
	foreach ( $up_tabs as $tab ) {
		$resetname = 'reset-' . $tab['name'];
		if ( ! empty( $input[$resetname] ) ) {
			$submittype = 'reset';
		}
	}
	
	// Determine what tab was input
	$submittab = '';	
	foreach ( $up_tabs as $tab ) {
		$submitname = 'submit-' . $tab['name'];
		$resetname = 'reset-' . $tab['name'];
		if ( ! empty( $input[$submitname] ) || ! empty($input[$resetname] ) ) {
			$submittab = $tab['name'];
		}
	}
	// Get settings by tab
	$tabsettings = $settingsbytab[$submittab];

	// Loop through each tab setting
	foreach ( $tabsettings as $setting ) {
					
		// If no option is selected, set the default
		$valid_input[$setting] = ( ! isset( $input[$setting] ) ? $option_defaults[$setting] : $input[$setting] );

		// If submit, validate/sanitize $input
		if ( 'submit' == $submittype ) {
		
			// Get the setting details from the defaults array
			$optiondetails = $option_parameters[$setting];
			// Get the array of valid options, if applicable
			$valid_options = ( isset( $optiondetails['valid_options'] ) ? $optiondetails['valid_options'] : false );
			
			// Validate checkbox fields
			if ( 'checkbox' == $optiondetails['type'] ) {
				// If input value is set and is true, return true; otherwise return false
				if( is_array($input[$setting]) ):
					foreach($input[$setting] as $key => $checkbox):
						$valid_input[$setting][$key] = ( ( isset( $checkbox ) && 'on' == $checkbox ) ? true : false );
					endforeach;
				else:
					$valid_input[$setting] = ( ( isset( $input[$setting] ) && true == $input[$setting] ) ? true : false );
				endif;
			}
			// Validate radio button fields
			else if ( 'radio' == $optiondetails['type'] ) {
				// Only update setting if input value is in the list of valid options
				$valid_input[$setting] = ( array_key_exists( $input[$setting], $valid_options ) ? $input[$setting] : $valid_input[$setting] );
			}
			// Validate select fields
			else if ( 'select' == $optiondetails['type'] ) {
				// Only update setting if input value is in the list of valid options
				$valid_input[$setting] = ( array_key_exists( $setting, $valid_options ) ? $input[$setting] : $valid_input[$setting] );
			}
			else if ( 'multiple' == $optiondetails['type'] ) {
				// Only update setting if input value is in the list of valid options
				$valid_input[$setting] = ( array_key_exists( $setting, $valid_options ) ? $input[$setting] : $valid_input[$setting] );
			}
			// Validate text input and textarea fields
			else if ( ( 'text' == $optiondetails['type'] || 'textarea' == $optiondetails['type'] ) ) {
				// Validate no-HTML content
				if ( 'nohtml' == $optiondetails['sanitize'] ) {
					// Pass input data through the wp_filter_nohtml_kses filter
					$valid_input[$setting] = wp_filter_nohtml_kses( $input[$setting] );
				}
				// Validate HTML content
				if ( 'html' == $optiondetails['sanitize'] ) {
					// Pass input data through the wp_filter_kses filter
					$valid_input[$setting] = wp_filter_kses( $input[$setting] );
				}
			}
		} 
		// If reset, reset defaults
		elseif ( 'reset' == $submittype ) {
			// Set $setting to the default value
			$valid_input[$setting] = $option_defaults[$setting];
		}
	}
	return $valid_input;

}

/**
 * Globalize the variable that holds 
 * the Settings Page tab definitions
 * 
 * @global	array	Settings Page Tab definitions
 */
global $up_tabs;

/**
 * Call add_settings_section() for each Settings 
 * 
 * Loop through each Theme Settings page tab, and add 
 * a new section to the Theme Settings page for each 
 * section specified for each tab.
 * 
 * @link	http://codex.wordpress.org/Function_Reference/add_settings_section	Codex Reference: add_settings_section()
 * 
 * @param	string		$sectionid	Unique Settings API identifier; passed to add_settings_field() call
 * @param	string		$title		Title of the Settings page section
 * @param	callback	$callback	Name of the callback function in which section text is output
 * @param	string		$pageid		Name of the Settings page to which to add the section; passed to do_settings_sections()
 */
foreach ( $up_tabs as $tab ) {
	$tabname = $tab['name'];
	$tabsections = $tab['sections'];
	foreach ( $tabsections as $section ) {
		$sectionname = $section['name'];
		$sectiontitle = $section['title'];
		
		// Add settings section
		add_settings_section(
			// $sectionid
			'upfw_' . $sectionname . '_section',
			// $title
			$sectiontitle,
			// $callback
			'upfw_sections_callback',
			// $pageid
			'upfw_' . $tabname . '_tab'
		);

	}
}

/**
 * Callback for add_settings_section()
 * 
 * Generic callback to output the section text
 * for each Plugin settings section. 
 * 
 * @param	array	$section_passed	Array passed from add_settings_section()
 */
function upfw_sections_callback( $section_passed ) {
	global $up_tabs;
	foreach ( $up_tabs as $tabname => $tab ) {
		$tabsections = $tab['sections'];
		foreach ( $tabsections as $sectionname => $section ) {
			if ( 'upfw_' . $sectionname . '_section' == $section_passed['id'] ) {
				?>
				<p><?php echo $section['description']; ?></p>
				<?php
			}
		}
	}
}

/**
 * Globalize the variable that holds 
 * all the Theme option parameters
 * 
 * @global	array	Theme options parameters
 */
global $option_parameters;
$option_parameters = upfw_get_option_parameters();

/**
 * Call add_settings_field() for each Setting Field
 * 
 * Loop through each Theme option, and add a new 
 * setting field to the Theme Settings page for each 
 * setting.
 * 
 * @link	http://codex.wordpress.org/Function_Reference/add_settings_field	Codex Reference: add_settings_field()
 * 
 * @param	string		$settingid	Unique Settings API identifier; passed to the callback function
 * @param	string		$title		Title of the setting field
 * @param	callback	$callback	Name of the callback function in which setting field markup is output
 * @param	string		$pageid		Name of the Settings page to which to add the setting field; passed from add_settings_section()
 * @param	string		$sectionid	ID of the Settings page section to which to add the setting field; passed from add_settings_section()
 * @param	array		$args		Array of arguments to pass to the callback function
 */
foreach ( $option_parameters as $option ) {
	$optionname = $option['name'];
	$optiontitle = $option['title'];
	$optiontab = $option['tab'];
	$optionsection = $option['section'];
	$optiontype = $option['type'];
	add_settings_field(
		// $settingid
		'upfw_setting_' . $optionname,
		// $title
		$optiontitle,
		// $callback
		'upfw_setting_callback',
		// $pageid
		'upfw_' . $optiontab . '_tab',
		// $sectionid
		'upfw_' . $optionsection . '_section',
		// $args
		$option
	);
}

/**
 * Callback for get_settings_field()
 */
function upfw_setting_callback( $option ) {
	$upfw_options = (array) upfw_get_options();

	$option_parameters = upfw_get_option_parameters();
	$optionname = $option['name'];
	$optiontitle = $option['title'];
	$optiondescription = $option['description'];
	$fieldtype = $option['type'];
	$fieldname = "theme_" . ( upfw_get_current_theme_id() ) . "_options[{$optionname}]";

	$attr = $option_parameters[$option['name']];
	$value = $upfw_options[$optionname];
	
    //Determine the type of input field
    switch ( $fieldtype ) {
        
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
        
        //Render radio image dropdowns
        case 'radio': upfw_radio($value,$attr);
        break;
                
        //Render radio image dropdowns
        case 'radio_image': upfw_radio_image($value,$attr);
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

        //Render taxonomy multiple select
        case 'taxonomy': upfw_taxonomy($value,$attr);
        break;

	    default:
	    break;
	    
	}

}
