<?php

/**
 *
 * This file exists to implement support for the Theme Customizer,
 * introduced in WordPress 3.4.
 *
 **/

add_action( 'customize_register', 'upfw_customize_register' );

function upfw_customize_register($wp_customize) {
	/**
	 * Globalize the variable that holds 
	 * the Settings Page tab definitions
	 * 
	 * @global	array	Settings Page Tab definitions
	 */
	global $up_tabs;
	
	/**
	 * Register each tab section in the Theme Customizer
	 * 
	 * @todo Add description.
	 * 
	 */
	foreach ( $up_tabs as $tab ) {
		$tabname = $tab['name'];
		$tabsections = $tab['sections'];
		foreach ( $tabsections as $section ) {
			$sectionname = $section['name'];
			$sectiontitle = $section['title'];

			$wp_customize->add_section($sectionname, array(
				'title' => $sectiontitle,
				'description' => $sectiondescription
			) );

		}
	}

	$upfw_options = upfw_get_options();
	
	$upfw_option_parameters = upfw_get_option_parameters();
	
	foreach( $upfw_option_parameters as $option ){
	
		$optionname = $option['name'];
		$optiondb = "theme_" . ( upfw_get_current_theme_id() ) . "_options[$optionname]";
		$option_section_name =  $option['section'];

		if( $option['type'] == 'text' || $option['type'] == 'textarea' ){

			$wp_customize->add_setting( $optiondb, array(
				'default'				=> $option['default'],
				'type'					=> 'option',
				'capabilities'	=> 'manage_theme_options'
			) );

			$wp_customize->add_control( $option['name'], array(
				'label'   => $option['title'],
				'section' => $option_section_name,
				'settings' => $optiondb,
				'type'    => 'text',
			) );

		}

		if( $option['type'] == 'color' ){

			$wp_customize->add_setting( $optiondb, array(
				'default'				=> $option['default'],
				'type'					=> 'option',
				'capabilities'	=> 'manage_theme_options'
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $option['name'], array(
				'label'   => $option['title'],
				'section' => $option_section_name,
				'settings'=> $optiondb,
			) ) );

		}

		if( $option['type'] == 'radio' || $option['type'] == 'select' ){

			$wp_customize->add_setting( $optiondb, array(
				'default'				=> $option['default'],
				'type'					=> 'option',
				'capabilities'	=> 'manage_theme_options'
			) );

			$wp_customize->add_control( $option['name'], array(
				'label'   => $option['title'],
				'section' => $option_section_name,
				'settings'=> $optiondb,
				'type'    => $option['type'],
				'choices' => upfw_extract_valid_options($option['valid_options'])
			) );

		}

	}

}

function upfw_extract_valid_options($options){
	$new_options = array();
	foreach($options as $option){
		$new_options[$option['name']] = $option['title'];
	}
	return $new_options;
}