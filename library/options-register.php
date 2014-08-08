<?php
/**
 * Settings API registration.
 *
 * This file implements the WordPress Settings API for the
 * options within the UpThemes Framework.
 *
 * @package 	UpThemes Framework
 * @copyright	Copyright (c) 2013, UpThemes
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		1.0
 */

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
	$theme_url = upfw_get_theme_dir();

	echo "<script type=\"text/javascript\">\n";
	echo "var upfw = {\n";
	echo "		'theme' : '$theme_name',\n";

	if( isset( $_GET['page']) && esc_attr( $_GET['page'] ) == 'upfw-settings' && $selected_tab )
		echo "		'current_tab' : '$selected_tab',\n";

	echo "		'theme_url' : '$theme_url'\n";
	echo "}" . "\n";
	echo "</script>" . "\n";

}

add_action('admin_print_scripts','upfw_register_admin_js_globals',1);

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

function upfw_init_page(){
	/**
	 * Globalize the variable that holds
	 * the Settings Page tab definitions
	 *
	 * @global	array	Settings Page Tab definitions
	 */
	global $up_tabs;

	/**
	 * Call add_settings_section() for each settings page tab
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
	if ($up_tabs) {
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
}

add_action('current_screen','upfw_init_page');

/**
 * Callback for get_settings_field()
 */
function upfw_setting_callback( $option ) {
	global $upfw_custom_callbacks;

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
		case 'text': upfw_text($value,$attr);
		break;

		//Render textarea options
		case 'textarea': upfw_textarea($value,$attr);
		break;

		//Render wordpress editor options
		case 'editor': upfw_editor($value,$attr);
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

		//Render checkboxes
		case 'multicheck': upfw_multicheck($value,$attr);
		break;

		//Render color picker
		case 'color': upfw_color($value,$attr);
		break;

		//Render upload image
		case 'image': upfw_upload($value,$attr);
		break;

		//Render upload
		case 'upload': upfw_upload($value,$attr);
		break;

		default:
		break;
	}

	// Check if there is a callback to envoke for custom fields
	if (isset($upfw_custom_callbacks[$fieldtype])) {
		$custom_field_name = 'theme_' . upfw_get_current_theme_id() . '_options[' . $attr['name'] . ']';

		call_user_func($upfw_custom_callbacks[$fieldtype], $value, $attr, $custom_field_name);
	}
}
