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
    global $upfw_google_fonts;
    $upfw_google_fonts = array(
		'Aclonica' => array('weights' => array('Normal' =>'400') ),
		'Allan' => array('weights' => array('Bold' => '700') ),
		'Allerta' => array('weights' => array('Normal' =>'400') ),
		'Allerta Stencil' => array('weights' => array('Normal' =>'400') ),
		'Amaranth' => array('weights' => array('Normal' => '400',
											   'Bold' => '700') ),
		'Annie Use Your Telescope' => array('weights' => array('Normal' =>'400') ),
		'Anonymous Pro' => array('weights' => array('Normal' => '400',
													'Bold' => '700') ),
		'Anton' => array('weights' => array('Normal' =>'400') ),
		'Architects Daughter',
		'Arimo' => array('weights' => array('Normal' => '400',
											'Bold' => '700',) ),
		'Artifika' => array('weights' => array('Normal' =>'400') ),
		'Arvo' => array('weights' => array('Normal' => '400',
										   'Bold' => '700',) ),
		'Asset' => array('weights' => array('Normal' =>'400') ),
		'Astloch' => array('weights' => array('Normal' => '400',
											  'Bold' => '700') ),
		'Bangers' => array('weights' => array('Normal' =>'400') ),
		'Bentham' => array('weights' => array('Normal' =>'400') ),
		'Bevan' => array('weights' => array('Normal' =>'400') ),
		'Bigshot One' => array('weights' => array('Normal' =>'400') ),
		'Brawler' => array('weights' => array('Normal' =>'400') ),
		'Buda' => array('weights' => array('Book' => '300') ),
		'Cabin' => array('weights' => array('Normal' => '400',
											'Medium' => '500',
											'Semi-Bold' => '600',
											'Bold' => '700',) ),
		'Cabin Sketch' => array('weights' => array('Bold' => '700') ),
		'Calligraffitti' => array('weights' => array('Normal' =>'400') ),
		'Candal' => array('weights' => array('Normal' =>'400') ),
		'Cantarell' => array('weights' => array('Normal' => '400',
												'Bold' => '700',) ),
		'Cardo' => array('weights' => array('Normal' =>'400') ),
		'Carter One' => array('weights' => array('Normal' =>'400') ),
		'Caudex' => array('weights' => array('Normal' => '400',
											 'Bold' => '700',) ),
		'Cedarville Cursive' => array('weights' => array('Normal' =>'400') ),
		'Cherry Cream Soda' => array('weights' => array('Normal' =>'400') ),
		'Chewy' => array('weights' => array('Normal' =>'400') ),
		'Coda' => array('weights' => array('Extra-Bold' => '800') ),
		'Coda Caption' => array('weights' => array('Extra-Bold' => '800') ),
		'Coming Soon' => array('weights' => array('Normal' =>'400') ),
		'Copse' => array('weights' => array('Normal' =>'400') ),
		'Corben' => array('weights' => array('Bold' =>'700') ),
		'Cousine' => array('weights' => array('Normal' => '400',
											  'Bold' => '700',) ),
		'Covered By Your Grace' => array('weights' => array('Normal' =>'400') ),
		'Crafty Girls' => array('weights' => array('Normal' =>'400') ),
		'Crimson Text' => array('weights' => array('Normal' => '400',
												   'Semi-Bold' => '600',
												   'Bold' => '700',) ),
		'Crushed' => array('weights' => array('Normal' =>'400') ),
		'Cuprum' => array('weights' => array('Normal' =>'400') ),
		'Damion' => array('weights' => array('Normal' =>'400') ),
		'Dancing Script' => array('weights' => array('Normal' => '400',
													 'Bold' => '700') ),
		'Dawning of a New Day' => array('weights' => array('Normal' =>'400') ),
		'Didact Gothic' => array('weights' => array('Normal' =>'400') ),
		'Droid Sans' => array('weights' => array('Normal' => '400',
												 'Bold' => '700') ),
		'Droid Sans Mono' => array('weights' => array('Normal' =>'400') ),
		'Droid Serif' => array('weights' => array('Normal' =>'400') ),
		'EB Garamond' => array('weights' => array('Normal' =>'400') ),
		'Expletus Sans' => array('weights' => array('Normal' => '400',
												   'Medium' => '500',
												   'Semi-Bold' => '600',
												   'Bold' => '700') ),
		'Fontdiner Swanky' => array('weights' => array('Normal' =>'400') ),
		'Francois One' => array('weights' => array('Normal' =>'400') ),
		'Geo' => array('weights' => array('Normal' =>'400') ),
		'Goblin One' => array('weights' => array('Normal' =>'400') ),
		'Goudy Bookletter 1911' => array('weights' => array('Normal' =>'400') ),
		'Gravitas One' => array('weights' => array('Normal' =>'400') ),
		'Gruppo' => array('weights' => array('Normal' =>'400') ),
		'Hammersmith One' => array('weights' => array('Normal' =>'400') ),
		'Holtwood One SC' => array('weights' => array('Normal' =>'400') ),
		'Homemade Apple' => array('weights' => array('Normal' =>'400') ),
		'IM Fell DW Pica' => array('weights' => array('Normal' => '400') ),
		'IM Fell DW Pica SC' => array('weights' => array('Normal' =>'400') ),
		'IM Fell Double Pica' => array('weights' => array('Normal' => '400') ),
		'IM Fell Double Pica SC' => array('weights' => array('Normal' =>'400') ),
		'IM Fell English' => array('weights' => array('Normal' =>'400') ),
		'IM Fell English SC' => array('weights' => array('Normal' =>'400') ),
		'IM Fell French Canon' => array('weights' => array('Normal' => '400') ),
		'IM Fell French Canon SC' => array('weights' => array('Normal' =>'400') ),
		'IM Fell Great Primer' => array('weights' => array('Normal' => '400') ),
		'IM Fell Great Primer SC' => array('weights' => array('Normal' =>'400') ),
		'Josefin Sans' => array('weights' => array('Ultra-Light' => '100',
												   'Book' => '300',
												   'Normal' => '400',
												   'Semi-Bold' => '600',
												   'Bold' => '700',) ),
		'Josefin Slab' => array('weights' => array('Ultra-Light' => '100',
												   'Book' => '300',
												   'Normal' => '400',
												   'Semi-Bold' => '600',
												   'Bold' => '700',) ),
		'Judson' => array('weights' => array('Normal' => '400',
										 'Bold' => '700') ),
		'Jura' => array('weights' => array('Book' => '300',
									   'Normal' => '400',
									   'Medium' => '500',
									   'Semi-Bold' => '600') ),
		'Just Another Hand' => array('weights' => array('Normal' =>'400') ),
		'Just Me Again Down Here' => array('weights' => array('Normal' =>'400') ),
		'Kameron' => array('weights' => array('Normal' => '400',
											  'Bold' => '700') ),
		'Kenia' => array('weights' => array('Normal' =>'400') ),
		'Kranky' => array('weights' => array('Normal' =>'400') ),
		'Kreon' => array('weights' => array('Book' => '300',
											'Normal' => '400',
											'Bold' => '700') ),
		'Kristi' => array('weights' => array('Normal' =>'400') ),
		'La Belle Aurore' => array('weights' => array('Normal' =>'400') ),
		'Lato' => array('weights' => array('Ultra-Light' => '100',
										   'Book' => '300',
										   'Normal' => '400',
										   'Bold' => '700',
										   'Ultra-Bold' => '900') ),
		'League Script' => array('weights' => array('Normal' =>'400') ),
		'Lekton' => array('weights' => array('Normal' => '400',
										     'Bold' => '700') ),
		'Limelight' => array('weights' => array('Normal' =>'400') ),
		'Lobster' => array('weights' => array('Normal' =>'400') ),
		'Lobster Two' => array('weights' => array('Normal' => '400',
												  'Bold' => '700') ),
		'Lora' => array('weights' => array('Normal' =>'400') ),
		'Luckiest Guy' => array('weights' => array('Normal' =>'400') ),
		'Maiden Orange' => array('weights' => array('Normal' =>'400') ),
		'Mako' => array('weights' => array('Normal' =>'400') ),
		'Maven Pro' => array('weights' => array('Normal' => '400',
												'Medium' => '500',
												'Bold' => '700',
												'Ultra-Bold' => '900') ),
		'Meddon' => array('weights' => array('Normal' =>'400') ),
		'MedievalSharp' => array('weights' => array('Normal' =>'400') ),
		'Megrim' => array('weights' => array('Normal' =>'400') ),
		'Merriweather' => array('weights' => array('Normal' =>'400') ),
		'Metrophobic' => array('weights' => array('Normal' =>'400') ),
		'Michroma' => array('weights' => array('Normal' =>'400') ),
		'Miltonian' => array('weights' => array('Normal' =>'400') ),
		'Miltonian Tattoo' => array('weights' => array('Normal' =>'400') ),
		'Molengo' => array('weights' => array('Normal' =>'400') ),
		'Monofett' => array('weights' => array('Normal' =>'400') ),
		'Mountains of Christmas' => array('weights' => array('Normal' =>'400') ),
		'Muli' => array('weights' => array('Book' => '300',
										   'Normal' => '400') ),
		'Neucha' => array('weights' => array('Normal' =>'400') ),
		'Neuton' => array('weights' => array('Normal' =>'400') ),
		'News Cycle' => array('weights' => array('Normal' =>'400') ),
		'Nobile' => array('weights' => array('Normal' => '400',
											 'Bold' => '700') ),
		'Nova Cut' => array('weights' => array('Normal' =>'400') ),
		'Nova Flat' => array('weights' => array('Normal' =>'400') ),
		'Nova Mono' => array('weights' => array('Normal' =>'400') ),
		'Nova Oval' => array('weights' => array('Normal' =>'400') ),
		'Nova Round' => array('weights' => array('Normal' =>'400') ),
		'Nova Script' => array('weights' => array('Normal' =>'400') ),
		'Nova Slim' => array('weights' => array('Normal' =>'400') ),
		'Nova Square' => array('weights' => array('Normal' =>'400') ),
		'Nunito' => array('weights' => array('Book' => '300',
											 'Normal' => '400',
											 'Bold' => '700') ),
		'OFL Sorts Mill Goudy TT' => array('weights' => array('Normal' => '400') ),
		'Old Standard TT' => array('weights' => array('Normal' => '400',
											 'Bold' => '700') ),
		'Open Sans' => array('weights' => array('Book' => '300',
										   	    'Normal' => '400',
										   	    'Semi-Bold' => '600',
										   		'Bold' => '700',
										   		'Extra-Bold' => '800') ),
		'Open Sans Condensed' => array('weights' => array('Book' => '300')),
		'Orbitron' => array('weights' => array('Normal' => '400',
										   	   'Medium' => '500',
										   	   'Bold' => '700',
										   	   'Ultra-Bold' => '900') ),
		'Oswald' => array('weights' => array('Normal' =>'400') ),
		'Over the Rainbow' => array('weights' => array('Normal' =>'400') ),
		'PT Sans' => array('weights' => array('Normal' => '400',
										   	  'Bold' => '700') ),
		'PT Sans Caption' => array('weights' => array('Normal' => '400') ),
		'PT Sans Narrow' => array('weights' => array('Normal' => '400',
										   			 'Bold' => '700') ),
		'PT Serif' => array('weights' => array('Normal' => '400',
										   	   'Bold' => '700') ),
		'PT Serif Caption' => array('weights' => array('Normal' => '400') ),
		'Pacifico' => array('weights' => array('Normal' =>'400') ),
		'Paytone One' => array('weights' => array('Normal' =>'400') ),
		'Permanent Marker' => array('weights' => array('Normal' =>'400') ),
		'Philosopher' => array('weights' => array('Normal' =>'400') ),
		'Play' => array('weights' => array('Normal' => '400',
										   'Bold' => '700') ),
		'Playfair Display' => array('weights' => array('Normal' =>'400') ),
		'Podvoka' => array('weights' => array('Normal' =>'400') ),
		'Puritan' => array('weights' => array('Normal' =>'400') ),
		'Quattrocento' => array('weights' => array('Normal' =>'400') ),
		'Quattrocento Sans' => array('weights' => array('Normal' =>'400') ),
		'Radley' => array('weights' => array('Normal' =>'400') ),
		'Raleway' => array('weights' => array('Ultra-Light' => '100') ),
		'Redressed' => array('weights' => array('Normal' =>'400') ),
		'Reenie Beanie' => array('weights' => array('Normal' =>'400') ),
		'Rock Salt' => array('weights' => array('Normal' =>'400') ),
		'Rokkitt' => array('weights' => array('Normal' =>'400') ),
		'Ruslan Display' => array('weights' => array('Normal' =>'400') ),
		'Schoolbell' => array('weights' => array('Normal' =>'400') ),
		'Shadows Into Light' => array('weights' => array('Normal' =>'400') ),
		'Shanti' => array('weights' => array('Normal' =>'400') ),
		'Sigmar One' => array('weights' => array('Normal' =>'400') ),
		'Six Caps' => array('weights' => array('Normal' =>'400') ),
		'Slackey' => array('weights' => array('Normal' =>'400') ),
		'Smythe' => array('weights' => array('Normal' =>'400') ),
		'Sniglet' => array('weights' => array('Extra-Bold' => '800') ),
		'Special Elite' => array('weights' => array('Normal' =>'400') ),
		'Stardos Stencil' => array('weights' => array('Normal' => '400',
										   			  'Bold' => '700') ),
		'Sue Ellen Fransisco' => array('weights' => array('Normal' =>'400') ),
		'Sunshiney' => array('weights' => array('Normal' =>'400') ),
		'Swanky and Moo Moo' => array('weights' => array('Normal' =>'400') ),
		'Syncopate' => array('weights' => array('Normal' => '400',
										   		'Bold' => '700') ),
		'Tangerine' => array('weights' => array('Normal' => '400',
										   		'Bold' => '700') ),
		'Tenor Sans' => array('weights' => array('Normal' =>'400') ),
		'Terminal Dosis Light' => array('weights' => array('Normal' =>'400') ),
		'The Girl Next Door' => array('weights' => array('Normal' =>'400') ),
		'Tinos' => array('weights' => array('Normal' => '400',
										   	'Bold' => '700') ),
		'Ubuntu' => array('weights' => array('Book' => '300',
											 'Normal' => '400',
											 'Medium' => '500',
											 'Bold' => '700') ),
		'Ultra' => array('weights' => array('Normal' =>'400') ),
		'UnifrakturCook' => array('weights' => array('Bold' => '700') ),
		'UnifrakturMaguntia' => array('weights' => array('Normal' => '800') ),
		'Unkempt' => array('weights' => array('Normal' =>'400') ),
		'VT323' => array('weights' => array('Normal' =>'400') ),
		'Varela' => array('weights' => array('Normal' =>'400') ),
		'Vibur' => array('weights' => array('Normal' =>'400') ),
		'Vollkorn' => array('weights' => array('Normal' => '400',
											   'Bold' => '700') ),
		'Waiting for the Sunrise' => array('weights' => array('Normal' =>'400') ),
		'Wallpoet' => array('weights' => array('Normal' =>'400') ),
		'Walter Turncoat' => array('weights' => array('Normal' =>'400') ),
		'Wire One' => array('weights' => array('Normal' =>'400') ),
		'Yanone Kaffeesatz' => array('weights' => array('Light' => '200',
														'Book' => '300',
														'Normal' => '400',
														'Bold' => '700') ),
		'Zeyada' => array('weights' => array('Normal' => '400')
    ));
    $font_list = '';
    foreach($upfw_google_fonts as $key => $font):
        
        if( $key ):

			if( !is_numeric($key) )
	           $font_family = $key;
	        else
	        	$font_family = $font;
	                
	        if( isset($font['weights']) && is_array( $font['weights'] ) ):
	            $font_weights = $font['weights'];
	        endif;
			        
	        $args = array(
	            'name' => $font_family,
	            'id' => strtolower(str_replace(' ', '_', $font_family)),
	            'style' => 'http://fonts.googleapis.com/css?family='.str_replace(' ', '+', $font_family),
	            'font_family' => $font_family,
	            'weights' => $font_weights,
	        );
	        upfw_register_font($args);

	        $font_list .= $font_list ? ', "'.$font.'"' : $font;
			$font_weights = '';

        endif;
    endforeach;
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

	if( $font['weights'] )
		$font_weights = $font['weights'];

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