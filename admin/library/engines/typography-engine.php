<?php

/* Initialize Font Libraries */
function upfw_typography_init(){
    
    /* Google Fonts */
    if(!defined('DISABLE_GOOGLE_FONTS') )
        upfw_google_fonts();
        
    /* Universal Fonts */
    if( !defined('DISABLE_UNIVERSAL_FONTS') )
        upfw_universal_fonts();
    
    /* Sort The Fonts Alphabetically */
    global $up_fonts;
    ksort($up_fonts);
    
    if( isset( $_REQUEST['up_defaults'] ) ):
        delete_option('up_themes_'.UPTHEMES_SHORT_NAME.'_fonts');
        delete_option('up_themes_'.UPTHEMES_SHORT_NAME.'_fonts_queue');
        delete_option('up_themes_'.UPTHEMES_SHORT_NAME.'_custom_fonts');
    endif;

}

add_action('init', 'upfw_typography_init', 10);

/* Enqueue The Font CSS */
function upfw_enqueue_font_css(){
    global $up_fonts;
    $fonts = get_option('up_themes_'.UPTHEMES_SHORT_NAME.'_fonts');

    /* Current Custom Fonts - Since we have no way of knowing when a user deletes a font */
    $current_custom = get_option('up_themes_'.UPTHEMES_SHORT_NAME.'_custom_fonts_queue');

    /* Stored Custom Fonts */
    $custom_fonts = get_option('up_themes_'.UPTHEMES_SHORT_NAME.'_custom_fonts');
    
    /* Check stored against current to make sure we don't display deleted css */
    if(is_array($custom_fonts)):
        foreach($custom_fonts as $id => $font):
            if(!$current_custom[$id])unset($custom_fonts[$id]);
        endforeach;
    endif;
    
    /* Merge custom fonts into main font array */
    if(is_array($custom_fonts))$fonts = array_merge($fonts, $custom_fonts);
        
    if( is_array( $fonts ) ) :
		$css = '';
        foreach($fonts as $option):
            foreach ( $option as $font => $property ):
                $lineheight = ( isset( $property['lineheight'] ) ? $property['lineheight'] : false );
                $lineheight = $lineheight ? "line-height:{$lineheight};" : '';
                $textshadow = ( isset( $property['textshadow'] ) ? $property['textshadow'] : false );
                $textshadow_normal = $textshadow ? "text-shadow:{$textshadow};" : '';
                $textshadow_moz = $textshadow ? "-moz-text-shadow:{$textshadow};" : '';
                $textshadow_webkit = $textshadow ? "-webkit-text-shadow:{$textshadow};" : '';
                $fontweight = ( isset( $property['fontweight_faux'] ) ? $property['fontweight_faux'] : false );
                $fontweight = $fontweight ? "font-weight:{$fontweight};" : '';
				$fontweight_stylesheet = !defined('DISABLE_GOOGLE_FONTS') ? ':'.$property['fontweight'] : '';
                $fontstyle = ( isset( $property['fontstyle'] ) ? $property['fontstyle'] : false );
                $fontstyle = $fontstyle ? "font-style:{$fontstyle};" : '';
                $texttransform = ( isset( $property['texttransform'] ) ? $property['texttransform'] : false );
                $texttransform = $texttransform ? "text-transform:{$texttransform};" : '';
                $textdecoration = ( isset( $property['textdecoration'] ) ? $property['textdecoration'] : false );
                $textdecoration = $textdecoration ? "text-decoration:{$textdecoration};" : '';
                $letterspacing = ( isset( $property['letterspacing'] ) ? $property['letterspacing'] : false );
                $letterspacing = $letterspacing ? "letter-spacing:{$letterspacing};" : '';
                $fontsize = ( isset( $property['fontsize'] ) ? $property['fontsize'] : false );
                $fontsize = $fontsize ? "font-size:{$fontsize};" : '';
                $selector = ( isset( $property['selector'] ) ? $property['selector'] : false );
                $font_family = ( isset( $up_fonts[$font]['font_family'] ) ? $up_fonts[$font]['font_family'] : false );
                $font_family = $font_family ? "font-family:\"{$font_family}\";" : '';
                
                $stylesheet = $up_fonts[$font]['style'];
                
                preg_match("/\.css/i", $stylesheet, $matches);

                if( $matches )
	                $stylesheet = $stylesheet.$fontweight_stylesheet;
	                
                if( $stylesheet ) wp_enqueue_style( $font, $stylesheet, null, null );
                
                if( $selector ) $css .= $selector."\n{\n  {$font_family}\n  {$fontsize}\n  {$lineheight}\n  {$fontstyle}\n  {$letterspacing}\n  {$fontweight}\n  {$texttransform}\n  {$textdecoration}\n  {$textshadow_normal}\n  {$textshadow_moz}\n  {$textshadow_webkit}\n}\n\n";
                
            endforeach;
        endforeach;
    endif;
    
    global $up_fonts_css;
    $up_fonts_css = $css;
}
add_action('init', 'upfw_enqueue_font_css');

/* Print the font CSS */
add_action( 'wp_ajax_upfw_css', 'upfw_css',1 );
add_action( 'wp_ajax_nopriv_upfw_css', 'upfw_css',1 );

function upfw_css(){
    global $up_fonts_css;
	header( 'Content-Type: text/css' );
	echo $up_fonts_css;
    exit;
}

if( !is_admin() ) add_action('wp_head','upfw_inject_theme_option_css',900);

function upfw_inject_theme_option_css(){

?>
<link rel="stylesheet" type="text/css" href="<?php echo admin_url('admin-ajax.php'); ?>?action=upfw_css" />
<?php
}

/* Register A Font*/
function upfw_register_font($args){
    global $up_fonts;
    extract($args);
    if($id && $name):
        if($font_family)$up_fonts[$id] = $args;
        return true;
    endif;
}

/* Deregister A Font */
function upfw_deregister_font($id){
    global $up_fonts;
    if(is_array($up_fonts[$id])):
        unset($up_fonts[$id]);
        return true;
    endif;
}

/* Register Universal Fonts */
function upfw_universal_fonts(){
    global $up_universal_fonts;
    $up_universal_fonts = array(
        'Georgia',
        'Helvetica',
        'Times New Roman',
        'Arial',
        'Arial Narrow',
        'Impact',
        'Palatino Linotype',
        'Courier New',
        'Century Gothic',
        'Lucida Sans Unicode'
    );
    
    foreach($up_universal_fonts as $font):
        $arg = array(
            'name' => $font,
            'id' => strtolower(str_replace(' ', '_', $font)),
            'font_family' => $font
        );
        upfw_register_font($arg);
    endforeach;
}

/* Register Google Webfonts */
function upfw_google_fonts(){

	$font_list = "";
	
	//if( false === ( $upfw_google_fonts = get_transient('google_webfont_list') )  ):

		$upfw_google_fonts = wp_remote_get('https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyDJYYVPLT9JaoMPF8G5cFm1YjTZMjknizE');
		
		$upfw_google_fonts = json_decode( $upfw_google_fonts['body'] )->items;
	
		if( $upfw_google_fonts ):
		
			set_transient('google_webfont_list',$upfw_google_fonts,( 60 * 60 * 48 ));
			$upfw_google_fonts = get_transient('google_webfont_list');

		endif;
	
	//endif;

	if( $upfw_google_fonts ):
					    
	    foreach( $upfw_google_fonts as $key => $font ):

			$font_family = $font->family;
			$font_variants = $font->variants;

	        $args = array(
	            'name' => $font->family,
	            'id' => strtolower(str_replace(' ', '_', $font->family)),
	            'style' => 'http://fonts.googleapis.com/css?family='.str_replace(' ', '+', $font->family),
	            'font_family' => $font->family,
	            'variants' => $font->variants,
	        );

	        upfw_register_font($args);

	        $font_list .= $font_list ? ', "' . $font->family . '"' : $font->family;
			$font_weights = '';

	    endforeach;

	endif;    

}

/* Render Multiple Options */
function upfw_multiple_typography($options){
    global $up_options;
    
    $multiple = array(
        array(
        'name' => __('Selector Groups', 'upfw'),
        'desc' => __('Add a new selector group (comma delimited) to generate a font style option below. You must save the options first.', 'upfw'),
        'id' => 'upfw_user_selectors',
        'type' => 'text_list',
        'default_text' => __('Add New Selector Group', 'upfw'))
    );
    
	$custom = array();
    if( isset( $up_options->upfw_user_selectors ) ) :		
        foreach( $up_options->upfw_user_selectors as $name ):
            $multiple[] = array(
                'name' => $name,
                'desc' => __( 'Custom Selectors', 'upfw' ),
                'type' => 'typography',
                'id' => preg_replace( '/[^a-z\sA-Z\s0-9\s]/', '', strtolower( str_replace( ' ', '_', $name ) ) ),
                'selector' => $name,
                'custom' => true
            );
            $custom[preg_replace( '/[^a-z\sA-Z\s0-9\s]/', '', strtolower( str_replace( ' ', '_', $name ) ) )] = true;
        endforeach;
    endif;
    
    if ( is_array( $multiple ) ) $options = array_merge( $options, $multiple );
    update_option( 'up_themes_' . UPTHEMES_SHORT_NAME . '_custom_fonts_queue', $custom );
    return $options;
}

function get_font_weights($font){
	
	global $up_fonts;

	$font = $_REQUEST['font'];

	$font = $up_fonts[$font];	
	$font_weights = array('Normal' => 'normal');
	// Need to make sure each font has bold....this breaks most fonts that don't have weights assigned - $font_weights = array('Normal' => 'normal', 'Bold' => 'Bold');

	if( $font['variants'] )
		$font_weights = $font['variants'];

	return $font_weights;

}

function ajx_get_font_weight_options($font){
	
	$font_weights = get_font_weights($font);
	
	foreach($font_weights as $key => $value):
	
		$html .= "<option value='{$value}'>{$key}</option>";
	
	endforeach;
	
	$response = json_encode( array( 'html' => $html, 'success' => true ) );
	
	// response output
    header( "Content-Type: application/json" );
    echo $response;
 
    exit;

}

add_action( 'wp_ajax_get_font_weight_options', 'ajx_get_font_weight_options' );

?>