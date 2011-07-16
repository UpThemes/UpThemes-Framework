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
    
    if($_REQUEST['up_defaults']):
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
        
    if(is_array($fonts)):
        foreach($fonts as $option):
            foreach ($option as $font => $property):
                $lineheight = $property['lineheight'];
                $lineheight = $lineheight ? "line-height:{$lineheight};" : '';
                $textshadow = $property['textshadow'];
                $textshadow_normal = $textshadow ? "text-shadow:{$textshadow};" : '';
                $textshadow_moz = $textshadow ? "-moz-text-shadow:{$textshadow};" : '';
                $textshadow_webkit = $textshadow ? "-webkit-text-shadow:{$textshadow};" : '';
                $fontweight = $property['fontweight'];
                $fontweight = $fontweight ? "font-weight:{$fontweight};" : '';
				$fontweight_stylesheet = ':'.$property['fontweight'];
                $fontstyle = $property['fontstyle'];
                $fontstyle = $fontstyle ? "font-style:{$fontstyle};" : '';
                $texttransform = $property['texttransform'];
                $texttransform = $texttransform ? "text-transform:{$texttransform};" : '';
                $textdecoration = $property['textdecoration'];
                $textdecoration = $textdecoration ? "text-decoration:{$textdecoration};" : '';
                $letterspacing = $property['letterspacing'];
                $letterspacing = $letterspacing ? "letter-spacing:{$letterspacing};" : '';
                $fontsize = $property['fontsize'];
                $fontsize = $fontsize ? "font-size:{$fontsize};" : '';
                $selector = $property['selector'];
                $font_family = $up_fonts[$font]['font_family'];
                $font_family = $font_family ? "font-family:\"{$font_family}\";" : '';
                $stylesheet = $up_fonts[$font]['style'].$fontweight_stylesheet;
                if($stylesheet)wp_enqueue_style($font, $stylesheet);
                if($selector)$css .= $selector."\n{\n  {$font_family}\n  {$fontsize}\n  {$lineheight}\n  {$fontstyle}\n  {$letterspacing}\n  {$fontweight}\n  {$texttransform}\n  {$textdecoration}\n  {$textshadow_normal}\n  {$textshadow_moz}\n  {$textshadow_webkit}\n}\n\n";
            endforeach;
        endforeach;
    endif;
    
    global $up_fonts_css;
    $up_fonts_css = $css;
}
add_action('init', 'upfw_enqueue_font_css');

/* Print the font CSS */
add_action( 'wp_ajax_upfw_css', 'upfw_css' );
add_action( 'wp_ajax_nopriv_upfw_css', 'upfw_css' );

function upfw_css(){
    global $up_fonts_css;
	header( 'Content-Type: text/css' );
	echo $up_fonts_css;
    exit;
}

if( !is_admin() ) add_action('wp_head','upfw_inject_theme_option_css',1);

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
		'Aclonica',
		'Allan',
		'Allerta',
		'Allerta Stencil',
		'Amaranth' => array('weights' => array('Normal 400' => '400',
											   'Bold 700' => '700') ),
		'Annie Use Your Telescope',
		'Anonymous Pro' => array('weights' => array('Normal 400' => '400',
													'Bold 700' => '700') ),
		'Anton',
		'Architects Daughter',
		'Arimo' => array('weights' => array('Normal 400' => '400',
											'Bold 700' => '700',) ),
		'Artifika',
		'Arvo' => array('weights' => array('Normal 400' => '400',
										   'Bold 700' => '700',) ),
		'Asset',
		'Astloch' => array('weights' => array('Normal 400' => '400',
											  'Bold 700' => '700') ),
		'Bangers',
		'Bentham',
		'Bevan',
		'Bigshot One',
		'Brawler',
		'Buda',
		'Cabin' => array('weights' => array('Normal 400' => '400',
											'Medium 500' => '500',
											'Semi-Bold 600' => '600',
											'Bold 700' => '700',) ),
		'Cabin Sketch',
		'Calligraffitti',
		'Candal',
		'Cantarell' => array('weights' => array('Normal 400' => '400',
												'Bold 700' => '700',) ),
		'Cardo',
		'Carter One',
		'Caudex' => array('weights' => array('Normal 400' => '400',
											 'Bold 700' => '700',) ),
		'Cedarville Cursive',
		'Cherry Cream Soda',
		'Chewy',
		'Coda',
		'Coda Caption',
		'Coming Soon',
		'Copse',
		'Corben',
		'Cousine' => array('weights' => array('Normal 400' => '400',
											  'Bold 700' => '700',) ),
		'Covered By Your Grace',
		'Crafty Girls',
		'Crimson Text' => array('weights' => array('Normal 400' => '400',
												   'Semi-Bold 600' => '600',
												   'Bold 700' => '700',) ),
		'Crushed',
		'Cuprum',
		'Damion',
		'Dancing Script' => array('weights' => array('Normal 400' => '400',
													 'Bold 700' => '700') ),
		'Dawning of a New Day',
		'Didact Gothic',
		'Droid Sans' => array('weights' => array('Normal 400' => '400',
												 'Bold 700' => '700') ),
		'Droid Sans Mono',
		'Droid Serif',
		'EB Garamond',
		'Expletus Sans' => array('weights' => array('Normal 400' => '400',
												   'Medium 500' => '500',
												   'Semi-Bold 600' => '600',
												   'Bold 700' => '700') ),
		'Fontdiner Swanky',
		'Francois One',
		'Geo',
		'Goblin One',
		'Goudy Bookletter 1911',
		'Gravitas One',
		'Gruppo',
		'Hammersmith One',
		'Holtwood One SC',
		'Homemade Apple',
		'IM Fell DW Pica' => array('weights' => array('Normal 400' => '400') ),
		'IM Fell DW Pica SC',
		'IM Fell Double Pica' => array('weights' => array('Normal 400' => '400') ),
		'IM Fell Double Pica SC',
		'IM Fell English',
		'IM Fell English SC',
		'IM Fell French Canon' => array('weights' => array('Normal 400' => '400') ),
		'IM Fell French Canon SC',
		'IM Fell Great Primer' => array('weights' => array('Normal 400' => '400') ),
		'IM Fell Great Primer SC',
		'Josefin Sans' => array('weights' => array('Ultra-Light 100' => '100',
												   'Book 300' => '300',
												   'Normal 400' => '400',
												   'Semi-Bold 600' => '600',
												   'Bold 700' => '700',) ),
		'Josefin Slab' => array('weights' => array('Ultra-Light 100' => '100',
												   'Book 300' => '300',
												   'Normal 400' => '400',
												   'Semi-Bold 600' => '600',
												   'Bold 700' => '700',) ),
		'Judson' => array('weights' => array('Normal 400' => '400',
										 'Bold 700' => '700') ),
		'Jura' => array('weights' => array('Book 300' => '300',
									   'Normal 400' => '400',
									   'Medium 500' => '500',
									   'Semi-Bold 600' => '600') ),
		'Just Another Hand',
		'Just Me Again Down Here',
		'Kameron' => array('weights' => array('Normal 400' => '400',
											  'Bold 700' => '700') ),
		'Kenia',
		'Kranky',
		'Kreon' => array('weights' => array('Book 300' => '300',
											'Normal 400' => '400',
											'Bold 700' => '700') ),
		'Kristi',
		'La Belle Aurore',
		'Lato' => array('weights' => array('Ultra-Light 100' => '100',
										   'Book 300' => '300',
										   'Normal 400' => '400',
										   'Bold 700' => '700',
										   'Ultra-Bold 900' => '900') ),
		'League Script' => array('weights' => array('Normal 400' => '400') ),
		'Lekton' => array('weights' => array('Normal 400' => '400',
										     'Bold 700' => '700') ),
		'Limelight',
		'Lobster',
		'Lobster Two' => array('weights' => array('Normal 400' => '400',
												  'Bold 700' => '700') ),
		'Lora',
		'Luckiest Guy',
		'Maiden Orange',
		'Mako',
		'Maven Pro' => array('weights' => array('Normal 400' => '400',
												'Medium 500' => '500',
												'Bold 700' => '700',
												'Ultra-Bold 900' => '900') ),
		'Meddon',
		'MedievalSharp',
		'Megrim',
		'Merriweather',
		'Metrophobic',
		'Michroma',
		'Miltonian',
		'Miltonian Tattoo',
		'Molengo',
		'Monofett',
		'Mountains of Christmas',
		'Muli' => array('weights' => array('Book 300' => '300',
										   'Normal 400' => '400') ),
		'Neucha',
		'Neuton' => array('weights' => array('Normal 400' => '400')),
		'News Cycle',
		'Nobile' => array('weights' => array('Normal 400' => '400',
											 'Bold 700' => '700') ),
		'Nova Cut',
		'Nova Flat',
		'Nova Mono',
		'Nova Oval',
		'Nova Round',
		'Nova Script',
		'Nova Slim',
		'Nova Square',
		'Nunito' => array('weights' => array('Book 300' => '300',
											 'Normal 400' => '400',
											 'Bold 700' => '700') ),
		'OFL Sorts Mill Goudy TT' => array('weights' => array('Normal 400' => '400') ),
		'Old Standard TT' => array('weights' => array('Normal 400' => '400',
											 'Bold 700' => '700') ),
		'Open Sans' => array('weights' => array('Book 300' => '300',
										   	    'Normal 400' => '400',
										   	    'Semi-Bold 600' => '600',
										   		'Bold 700' => '700',
										   		'Extra-Bold 800' => '800') ),
		'Open Sans Condensed' => array('weights' => array('Book 300' => '300')),
		'Orbitron' => array('weights' => array('Normal 400' => '400',
										   	   'Medium 500' => '500',
										   	   'Bold 700' => '700',
										   	   'Ultra-Bold 900' => '900') ),
		'Oswald',
		'Over the Rainbow',
		'PT Sans' => array('weights' => array('Normal 400' => '400',
										   	  'Bold 700' => '700') ),
		'PT Sans Caption' => array('weights' => array('Normal 400' => '400') ),
		'PT Sans Narrow' => array('weights' => array('Normal 400' => '400',
										   			 'Bold 700' => '700') ),
		'PT Serif' => array('weights' => array('Normal 400' => '400',
										   	   'Bold 700' => '700') ),
		'PT Serif Caption' => array('weights' => array('Normal 400' => '400') ),
		'Pacifico',
		'Paytone One',
		'Permanent Marker',
		'Philosopher',
		'Play' => array('weights' => array('Normal 400' => '400',
										   'Bold 700' => '700') ),
		'Playfair Display',
		'Podvoka',
		'Puritan',
		'Quattrocento',
		'Quattrocento Sans',
		'Radley',
		'Raleway',
		'Redressed',
		'Reenie Beanie',
		'Rock Salt',
		'Rokkitt',
		'Ruslan Display',
		'Schoolbell',
		'Shadows Into Light',
		'Shanti',
		'Sigmar One',
		'Six Caps',
		'Slackey',
		'Smythe',
		'Sniglet',
		'Special Elite',
		'Stardos Stencil' => array('weights' => array('Normal 400' => '400',
										   			  'Bold 700' => '700') ),
		'Sue Ellen Fransisco',
		'Sunshiney',
		'Swanky and Moo Moo',
		'Syncopate' => array('weights' => array('Normal 400' => '400',
										   		'Bold 700' => '700') ),
		'Tangerine' => array('weights' => array('Normal 400' => '400',
										   		'Bold 700' => '700') ),
		'Tenor Sans',
		'Terminal Dosis Light',
		'The Girl Next Door',
		'Tinos' => array('weights' => array('Normal 400' => '400',
										   	'Bold 700' => '700') ),
		'Ubuntu' => array('weights' => array('Book 300' => '300',
											 'Normal 400' => '400',
											 'Medium 500' => '500',
											 'Bold 700' => '700') ),
		'Ultra',
		'UnifrakturCook',
		'UnifrakturMaguntia',
		'Unkempt',
		'VT323',
		'Varela',
		'Vibur',
		'Vollkorn' => array('weights' => array('Normal 400' => '400',
											   'Bold 700' => '700') ),
		'Waiting for the Sunrise',
		'Wallpoet',
		'Walter Turncoat',
		'Wire One',
		'Yanone Kaffeesatz' => array('weights' => array('Light 200' => '200',
														'Book 300' => '300',
														'Normal 400' => '400',
														'Bold 700' => '700') ),
		'Zeyada'
    );
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
    
    if(is_array($up_options->upfw_user_selectors)):
        foreach($up_options->upfw_user_selectors as $name):
            $multiple[] = array(
                'name' => $name,
                'desc' => __('Custom Selectors', 'upfw'),
                'type' => 'typography',
                'id' => preg_replace('/[^a-z\sA-Z\s0-9\s]/', '', strtolower(str_replace(' ', '_', $name))),
                'selector' => $name,
                'custom' => true
            );
            $custom[preg_replace('/[^a-z\sA-Z\s0-9\s]/', '', strtolower(str_replace(' ', '_', $name)))] = true;
        endforeach;
    endif;
    
    if(is_array($multiple)) $options = array_merge($options, $multiple);
    update_option('up_themes_'.UPTHEMES_SHORT_NAME.'_custom_fonts_queue', $custom);
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