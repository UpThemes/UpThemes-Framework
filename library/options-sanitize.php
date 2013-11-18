<?php

/**
 * Option sanitization functionality is from the Options Framework: https://github.com/devinsays/options-framework-theme
 *
 * Copyright 2013 Devin Price
 */

/* Text */

add_filter( 'upfw_sanitize_text', 'sanitize_text_field' );

/* Textarea */

function upfw_sanitize_textarea( $input ) {
	global $allowedposttags;
	$output = wp_kses( $input, $allowedposttags );
	return $output;
}

add_filter( 'upfw_sanitize_textarea', 'upfw_sanitize_textarea' );

/* Select */

add_filter( 'upfw_sanitize_select', 'upfw_sanitize_enum', 10, 2 );

/* Radio */

add_filter( 'upfw_sanitize_radio', 'upfw_sanitize_enum', 10, 2 );
add_filter( 'upfw_sanitize_radio_image', 'upfw_sanitize_enum', 10, 2 );

/* Images */

add_filter( 'upfw_sanitize_image', 'upfw_sanitize_enum', 10, 2 );

/* Checkbox */

function upfw_sanitize_checkbox( $input ) {
	if ( $input ) {
		$output = '1';
	} else {
		$output = false;
	}
	return $output;
}
add_filter( 'upfw_sanitize_checkbox', 'upfw_sanitize_checkbox' );

/* Multicheck */

function upfw_sanitize_multicheck( $input, $option ) {
	$output = '';
	if ( is_array( $input ) ) {
		foreach( $option['valid_options'] as $key => $value ) {
			$output[$key] = "0";
		}
		foreach( $input as $key => $value ) {
			if ( array_key_exists( $key, $option['valid_options'] ) && $value ) {
				$output[$key] = "1";
			}
		}
	}
	return $output;
}
add_filter( 'upfw_sanitize_multicheck', 'upfw_sanitize_multicheck', 10, 2 );

/* Color Picker */

add_filter( 'upfw_sanitize_color', 'upfw_sanitize_hex' );

/* Uploader */

function upfw_sanitize_upload( $input ) {
	$output = '';
	$filetype = wp_check_filetype($input);
	if ( $filetype["ext"] ) {
		$output = $input;
	}
	return $output;
}
add_filter( 'upfw_sanitize_upload', 'upfw_sanitize_upload' );

/* Editor */

function upfw_sanitize_editor($input) {
	if ( current_user_can( 'unfiltered_html' ) ) {
		$output = $input;
	}
	else {
		global $allowedtags;
		$output = wpautop(wp_kses( $input, $allowedtags));
	}
	return $output;
}
add_filter( 'upfw_sanitize_editor', 'upfw_sanitize_editor' );

/* Allowed Tags */

function upfw_sanitize_allowedtags($input) {
	global $allowedtags;
	$output = wpautop(wp_kses( $input, $allowedtags));
	return $output;
}

/* Allowed Post Tags */

function upfw_sanitize_allowedposttags($input) {
	global $allowedposttags;
	$output = wpautop(wp_kses( $input, $allowedposttags));
	return $output;
}

add_filter( 'upfw_sanitize_info', 'upfw_sanitize_allowedposttags' );


/* Check that the key value sent is valid */

function upfw_sanitize_enum( $input, $option ) {
	$output = '';
	if ( array_key_exists( $input, $option['valid_options'] ) ) {
		$output = $input;
	}
	return $output;
}

/**
 * Sanitize a color represented in hexidecimal notation.
 *
 * @param    string    Color in hexidecimal notation. "#" may or may not be prepended to the string.
 * @param    string    The value that this function should return if it cannot be recognized as a color.
 * @return   string
 *
 */

function upfw_sanitize_hex( $hex, $default = '' ) {
	if ( upfw_validate_hex( $hex ) ) {
		return $hex;
	}
	return $default;
}

/**
 * Is a given string a color formatted in hexidecimal notation?
 *
 * @param    string    Color in hexidecimal notation. "#" may or may not be prepended to the string.
 * @return   bool
 *
 */

function upfw_validate_hex( $hex ) {
	$hex = trim( $hex );
	/* Strip recognized prefixes. */
	if ( 0 === strpos( $hex, '#' ) ) {
		$hex = substr( $hex, 1 );
	}
	elseif ( 0 === strpos( $hex, '%23' ) ) {
		$hex = substr( $hex, 3 );
	}
	/* Regex match. */
	if ( 0 === preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) ) {
		return false;
	}
	else {
		return true;
	}
}