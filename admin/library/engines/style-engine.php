<?php
function register_theme_style($args){
    global $up_styles;
    extract($args);
    $context = $context ? $context : 'global';
    if($id && $name && $style && $image):
        $up_styles[$id] = $args;
        return true;
    endif;
}

function default_theme_styles(){
    $args = array(
        array(
            'id' => 'light',
            'name' => 'Light',
            'style' => get_bloginfo("template_directory")."/library/styles/light.css",
            'image' => get_bloginfo("template_directory")."/library/styles/light.jpg"),
        array(
            'id' => 'dark',
            'name' => 'Dark',
            'style' => get_bloginfo("template_directory")."/library/styles/dark.css",
            'image' => get_bloginfo("template_directory")."/library/styles/dark.jpg")
    );
    
    foreach($args as $arg):
        register_theme_style($arg);
    endforeach;
}

function deregister_theme_style($id){
    global $up_styles;
    if(is_array($up_styles[$id])):
        unset($up_styles[$id]);
        return true;
    endif;
}

/* Enqueue The Style */
function enqueue_theme_style(){
    global $up_styles;
    $contexts = get_option('up_themes_'.UPTHEMES_SHORT_NAME.'_styles');
    if(is_array($contexts)):
        $queued = FALSE;
        $global = FALSE;
        foreach($contexts as $context => $style):
            if($context != 'global'):
                if(function_exists('is_'.$context)):
                    if(call_user_func('is_'.$context)):
                        wp_enqueue_style('up-layout-'.$context, $up_styles[$style['id']]['style']);
                        $queued = TRUE;
                    endif;
                endif;
            else:
                $global = TRUE;
            endif;
        endforeach;
        if(!$queued && $global)wp_enqueue_style('up-style-global', $up_styles[$contexts['global']['id']]['style']);
    endif;
}
add_action('wp_print_styles', 'enqueue_theme_style');
?>