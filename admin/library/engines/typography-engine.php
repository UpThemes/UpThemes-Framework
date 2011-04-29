<?php
upfw_google_fonts();
upfw_universal_fonts();

function upfw_register_theme_font($args){
    global $up_fonts;
    extract($args);
    if($id && $name):
        if($font_family)$up_fonts['library'][$id] = $args;
        if($style)$up_fonts['styles'][$id] = $style;
        return true;
    endif;
    
}

function upfw_universal_fonts(){
    $args = array(
        array(
            'id' => 'georgia',
            'name' => 'Georgia',
            'font_family' => "Georgia"),
        array(
            'id' => 'helvetica',
            'name' => 'Helvetica',
            'font_family' => "Helvetica"),
        array(
            'id' => 'times_new_roman',
            'name' => 'Times New Roman',
            'font_family' => "Times New Roman"),
        array(
            'id' => 'arial',
            'name' => 'Arial',
            'font_family' => "Arial"),
        array(
            'id' => 'arial_narrow',
            'name' => 'Arial Narrow',
            'font_family' => "Arial Narrow"),
        array(
            'id' => 'impact',
            'name' => 'Impact',
            'font_family' => "Impact"),
        array(
            'id' => 'palatino',
            'name' => 'Palatino',
            'font_family' => "Palatino Linotype"),
        array(
            'id' => 'courier_new',
            'name' => 'Courier New',
            'font_family' => "Courier New"),
        array(
            'id' => 'century_gothic',
            'name' => 'Century Gothic',
            'font_family' => "Century Gothic")
    );
    
    foreach($args as $arg):
        upfw_register_theme_font($arg);
    endforeach;
}

function upfw_deregister_theme_font($id){
    global $up_fonts;
    if(is_array($up_fonts[$id])):
        unset($up_fonts[$id]);
        return true;
    endif;
}

/* Enqueue The Font */
function upfw_enqueue_theme_fonts(){
    global $up_fonts;
    $contexts = get_option('up_themes_'.UPTHEMES_SHORT_NAME.'_fonts');
    if(is_array($contexts)):
        $queued = FALSE;
        $global = FALSE;
        foreach($contexts as $context => $style):
            if($context != 'global'):
                if(function_exists('is_'.$context)):
                    if(call_user_func('is_'.$context)):
                        wp_enqueue_style('up-layout-'.$context, $up_fonts[$style['id']]['style']);
                        $queued = TRUE;
                    endif;
                endif;
            else:
                $global = TRUE;
            endif;
        endforeach;
        if(!$queued && $global)wp_enqueue_style('up-font-global', $up_fonts[$contexts['global']['id']]['style']);
    endif;
}
add_action('wp_print_styles', 'upfw_enqueue_theme_fonts');

function upfw_google_fonts(){
    global $upfw_google_fonts;
    $upfw_google_fonts = array(
        'Sue Ellen Francisco',
        'Aclonica',
        'Damion',
        'News Cycle',
        'Swanky and Moo Moo',
        'Wallpoet',
        'Over the Rainbow',
        'Special Elite',
        'Quattrocento Sans',
        'Smythe',
        'The Girl Next Door',
        'Sue Ellen Francisco',
        'Dawning of a New Day',
        'Waiting for the Sunrise',
        'Annie Use Your Telescope',
        'Bangers',
        'VT323',
        'Six Caps',
        'EB Garamond',
        'Miltonian',
        'Miltonian Tattoo',
        'Sunshiney',
        'Indie Flower',
        'Sniglet:800',
        'Terminal Dosis Light',
        'Anonymous Pro',
        'Bevan',
        'Nova Square',
        'Nova Oval',
        'Nova Slim',
        'Nova Mono',
        'Nova Round',
        'Nova Cut',
        'Nova Flat',
        'Nova Script',
        'Lekton',
        'MedievalSharp',
        'Michroma',
        'Philosopher',
        'Kenia',
        'Maiden Orange',
        'Kristi',
        'Astloch',
        'Architects Daughter',
        'Cuprum',
        'Crimson Text',
        'Cabin',
        'Quattrocento',
        'Expletus Sans',
        'PT Serif',
        'PT Serif Caption',
        'Josefin Slab',
        'UnifrakturMaguntia',
        'Radley',
        'Crafty Girls',
        'Vibur',
        'Geo',
        'Luckiest Guy',
        'Anton',
        'IM Fell Double Pica SC',
        'IM Fell Great Primer SC',
        'IM Fell DW Pica',
        'IM Fell Double Pica',
        'IM Fell French Canon',
        'IM Fell English SC',
        'IM Fell Great Primer',
        'IM Fell DW Pica SC',
        'IM Fell English',
        'IM Fell French Canon SC',
        'Cousine',
        'Just Another Hand',
        'Molengo',
        'Raleway:100',
        'Old Standard TT',
        'Mountains of Christmas',
        'Homemade Apple',
        'Coda',
        'Neucha',
        'League Script',
        'Unkempt',
        'Walter Turncoat',
        'Cherry Cream Soda',
        'Calligraffitti',
        'Permanent Marker',
        'Josefin Sans',
        'Lato',
        'Meddon',
        'Kranky',
        'Rock Salt',
        'Arimo',
        'Covered By Your Grace',
        'Just Me Again Down Here',
        'Neuton',
        'Schoolbell',
        'OFL Sorts Mill Goudy TT',
        'Syncopate',
        'Droid Sans',
        'Inconsolata',
        'Tinos',
        'Droid Serif',
        'Vollkorn',
        'Reenie Beanie',
        'Cardo',
        'Arvo',
        'Droid Sans Mono',
        'Merriweather',
        'Yanone Kaffeesatz',
        'Candal',
        'Cantarell',
        'Gruppo',
        'Lobster',
        'PT Sans',
        'PT Sans Narrow',
        'PT Sans Caption',
        'Chewy',
        'Coming Soon',
        'Pacifico',
        'Orbitron',
        'Tangerine',
        'Allerta Stencil',
        'Allerta',
        'Fontdiner Swanky',
        'Ubuntu',
        'Nobile',
        'Slackey',
        'Bentham',
        'Crushed',
        'Puritan',
        'Corben:bold',
        'Dancing Script',
        'Kreon',
        'Amaranth',
        'Irish Grover',
        'Cabin Sketch:bold',
        'UnifrakturCook:bold',
        'Buda:light',
        'Coda:800',
        'Coda Caption:800',
    );
    $font_list = '';
    foreach($upfw_google_fonts as $font):
        if(preg_match('/:/', $font)):
            $string = explode(':', $font);
            $font = $string[0];
            $style = ':'.$string[1];
        else:
            $style = '';
        endif;
        $args = array(
            'name' => $font,
            'id' => strtolower(str_replace(' ', '_', $font)),
            'style' => 'http://fonts.googleapis.com/css?family='.str_replace(' ', '+', $font).$style,
            'font_family' => $font
        );
        upfw_register_theme_font($args);
        $font_list .= $font_list ? ', "'.$font.'"' : $font;
    endforeach;
}

function upfw_multiple_typography($options){
    global $up_options;
    
    $multiple = array(
        array(
        'name' => __('Multiple Selectors', 'upfw'),
        'desc' => __('Add a new selector category to generate a font style option below. You must save and refresh to see the new font option.', 'upfw'),
        'id' => 'upfw_user_selectors',
        'type' => 'text_list',
        'default_text' => __('Add New Font Category', 'upfw'))
    );
    
    if(is_array($up_options->upfw_user_selectors)):
        foreach($up_options->upfw_user_selectors as $name):
            $multiple[] = array(
                'name' => $name,
                'desc' => '',
                'show_selector' => true,
                'type' => 'typography',
                'id' => preg_replace('/[^a-z\sA-Z\s0-9\s]/', '', strtolower(str_replace(' ', '_', $name)))
            );
        endforeach;
    endif;
    if(is_array($multiple)) $options = array_merge($options, $multiple);
    
    return $options;
}

?>