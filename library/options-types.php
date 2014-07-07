<?php
/**
 * Defines the default option types.
 *
 * This file implements the UI for theme options.
 *
 * @package 	UpThemes Framework
 * @copyright	Copyright (c) 2013-2014, UpThemes
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		1.0
 */

/**
 * Hook for adding custom option types
 *
 * @global 		array 	$upfw_custom_callbacks 		Allows for custom option types to be added to framework.
 */
global $upfw_custom_callbacks;

/**
 * Adds custom field option types to the $upfw_custom_callbacks global.
 *
 * @param 		string 	$type  			The identifier for this custom type, must be lowercase, underscores instead of spaces.
 * @param 		string 	$callback 		The name of the function that will output the controls for this option field type.
 */
function upfw_add_custom_field( $type = null, $callback = null ) {
	// don't do anything if they don't input the correct args
	if ( is_null( $type ) or is_null( $callback ) ) {
		return false;
	}

	// check to see if $callback is an actual function
	// we only want to add the callback if the function exists
	if ( function_exists( $callback) ) {
		global $upfw_custom_callbacks;

		// for right now we will override any previous callbacks added
		$upfw_custom_callbacks[ $type ] = $callback;
	}
}

/**
 * Outputs a text field option type.
 *
 * @param 	string 	$value 	The current value for this option.
 * @param 	array 	$attr 	An array of attributes for this theme option defined by the theme creator in the options array.
 */
function upfw_text( $value, $attr ) { ?>

	<input type="text" name="theme_<?php echo esc_attr( upfw_get_current_theme_id() ); ?>_options[<?php echo esc_attr( $attr['name'] ); ?>]" value="<?php echo esc_attr( $value ); ?>">

<?php
	if( $attr['description'] ){
		echo '<div><em>' . $attr['description'] . '</em></div>';
	}
}

/**
 * Outputs a textarea field option type.
 *
 * @param 	string 	$value 	The current value for this option.
 * @param 	array 	$attr 	An array of attributes for this theme option defined by the theme creator in the options array.
 */
function upfw_textarea( $value, $attr ) { ?>

	<textarea name="theme_<?php echo esc_attr( upfw_get_current_theme_id() ); ?>_options[<?php echo esc_attr( $attr['name'] ); ?>]" cols="48" rows="8"><?php echo esc_attr( $value ); ?></textarea>

<?php
	if( $attr['description'] ){
		echo '<div><em>' . $attr['description'] . '</em></div>';
	}
}

/**
 * Outputs a WYSIWYG editor field option type.
 *
 * @param 	string 	$value 	The current value for this option.
 * @param 	array 	$attr 	An array of attributes for this theme option defined by the theme creator in the options array.
 */
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

	if( $attr['description'] ){
		echo '<div><em>' . $attr['description'] . '</em></div>';
	}
}

/**
 * Outputs a select field option type.
 *
 * @param 	string 	$value 	The current value for this option.
 * @param 	array 	$attr 	An array of attributes for this theme option defined by the theme creator in the options array.
 */
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
	if( $attr['description'] ){
		echo '<div><em>' . $attr['description'] . '</em></div>';
	}
}

/**
 * Outputs image radio select field option type.
 *
 * @param 	string 	$value 	The current value for this option.
 * @param 	array 	$attr 	An array of attributes for this theme option defined by the theme creator in the options array.
 */
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

		if( $attr['description'] ):
			echo '<div><em>' . $attr['description'] . '</em></div>';
		endif;
	else:
		_e("This option has no valid options. Please create valid options as an array inside the UpThemes Framework.","upfw");
	endif;

}

/**
 * Outputs a radio button field option type.
 *
 * @param 	string 	$value 	The current value for this option.
 * @param 	array 	$attr 	An array of attributes for this theme option defined by the theme creator in the options array.
 */
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
		if( $attr['description'] ):
			echo '<div><em>' . $attr['description'] . '</em></div>';
		endif;
	else:
		_e("This option has no valid options. Please create valid options as an array inside the UpThemes Framework.","upfw");
	endif;

}

/**
 * Outputs a multicheck field option type.
 *
 * @param 	string 	$value 	The current value for this option.
 * @param 	array 	$attr 	An array of attributes for this theme option defined by the theme creator in the options array.
 */
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

	if( $attr['description'] ):
		echo '<div><em>' . $attr['description'] . '</em></div>';
	endif;

}

/**
 * Outputs a colorpicker field option type.
 *
 * @param 	string 	$value 	The current value for this option.
 * @param 	array 	$attr 	An array of attributes for this theme option defined by the theme creator in the options array.
 */
function upfw_color($value,$attr) { ?>

	<span class="colorPickerWrapper">
		<input type="text" class="popup-colorpicker" id="<?php echo esc_attr( $attr['name'] ); ?>" name="theme_<?php echo esc_attr( upfw_get_current_theme_id() ); ?>_options[<?php echo esc_attr( $attr['name'] ); ?>]" value="<?php echo esc_attr( $value ); ?>" />
	</span>
<?php
	if( $attr['description'] ):
		echo '<div><em>' . $attr['description'] . '</em></div>';
	endif;
}

/**
 * Outputs a media upload field option type.
 *
 * @param 	string 	$value 	The current value for this option.
 * @param 	array 	$attr 	An array of attributes for this theme option defined by the theme creator in the options array.
 */
function upfw_upload($value,$attr) { ?>

	<div id="<?php echo esc_html( $attr['name'] ); ?>_container" class="imageWrapper">
		<input type="text" class="upfw-open-media" id="<?php echo esc_attr( $attr['name'] ); ?>" name="theme_<?php echo esc_attr( upfw_get_current_theme_id() ); ?>_options[<?php echo esc_attr( $attr['name'] ); ?>]" value="<?php echo esc_attr( $value ); ?>">
		<input class="upfw-open-media button button-primary" type="submit" value="<?php esc_attr_e('Upload or Select a File','upfw'); ?>" />
		<div class="image_preview"></div>
	</div>
<?php

	if( $attr['description'] ):
		echo '<div><em>' . $attr['description'] . '</em></div>';
	endif;
}