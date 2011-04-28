<?php

default_theme_fonts();

function register_theme_font($args){
    global $up_fonts;
    extract($args);
    if($id && $name):
        if($font_family)$up_fonts['library'][$id] = $args;
        if($style)$up_fonts['styles'][$id] = $style;
        return true;
    endif;
    
}

function default_theme_fonts(){
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
            'id' => 'impact',
            'name' => 'Impact',
            'font_family' => "Impact"),
        array(
            'id' => 'palatino',
            'name' => 'Palatino',
            'font_family' => "Palatino"),
        array(
            'id' => 'courier_new',
            'name' => 'Courier New',
            'font_family' => "Courier New"),
        array(
            'id' => 'sue_ellen_francisco',
            'name' => 'Sue Ellen Francisco',
            'style' => "http://fonts.googleapis.com/css?family=Sue+Ellen+Francisco",
            'font_family' => "Sue Ellen Francisco")
    );
    
    foreach($args as $arg):
        register_theme_font($arg);
    endforeach;
}

function deregister_theme_font($id){
    global $up_fonts;
    if(is_array($up_fonts[$id])):
        unset($up_fonts[$id]);
        return true;
    endif;
}

/* Enqueue The Font */
function enqueue_theme_fonts(){
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
add_action('wp_print_styles', 'enqueue_theme_fonts');

/* Enqueue All Font Stylesheets for Admin */
function enqueue_admin_fonts(){
    global $up_fonts;
    
    /* Work in Context */
    
    if(is_array($up_fonts['styles'])):
        foreach($up_fonts['styles'] as $id => $style):
            wp_enqueue_style('up-font-'.$id, $style);
        endforeach;
    endif;
}
if(is_admin())add_action('init', 'enqueue_admin_fonts');

?>