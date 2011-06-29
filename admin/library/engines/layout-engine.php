<?php
function register_theme_layout($args){
    global $up_layouts;
    extract($args);
    $context = $context ? $context : 'global';
    if($id && $name && $style && $image):
        $up_layouts[$id] = $args;
        return true;
    endif;
}

function default_theme_layouts(){
    $args = array(
        array(
            'id' => 'left_column',
            'name' => 'Left Column',
            'style' => get_bloginfo("template_directory")."/layouts/left-column.css",
            'image' => get_bloginfo("template_directory")."/layouts/left-column.jpg"),
        array(
            'id' => 'left_column_grid',
            'name' => 'Left Column w/ Grid',
            'style' => get_bloginfo("template_directory")."/layouts/left-column-grid.css",
            'image' => get_bloginfo("template_directory")."/layouts/left-column-grid.jpg"),
        array(
            'id' => 'right_column',
            'name' => 'Right Column',
            'style' => get_bloginfo("template_directory")."/layouts/right-column.css",
            'image' => get_bloginfo("template_directory")."/layouts/right-column.jpg"),
        array(
            'id' => 'right_column_grid',
            'name' => 'Right Column w/ Grid',
            'style' => get_bloginfo("template_directory")."/layouts/right-column-grid.css",
            'image' => get_bloginfo("template_directory")."/layouts/right-column-grid.jpg")
    );
    
    foreach($args as $arg):
        register_theme_layout($arg);
    endforeach;
}

function deregister_theme_layout($id){
    global $up_layouts;
    if(is_array($up_layouts[$id])):
        unset($up_layouts[$id]);
        return true;
    endif;
}

/* Enqueue The Layout */
function enqueue_theme_layout(){
    global $up_layouts;
    $contexts = get_option('up_themes_'.UPTHEMES_SHORT_NAME.'_layouts');
    if(is_array($contexts)):
        $queued = FALSE;
        $global = FALSE;
        foreach($contexts as $context => $layout):
            if($context != 'global'):
                if(function_exists('is_'.$context)):
                    if(call_user_func('is_'.$context)):
                        wp_enqueue_style('up-layout-'.$context, $up_layouts[$layout['id']]['style']);
                        $queued = TRUE;
                    endif;
                endif;
            else:
                $global = TRUE;
            endif;
        endforeach;
        if(!$queued && $global)wp_enqueue_style('up-layout-global', $up_layouts[$contexts['global']['id']]['style']);
    endif;
}
add_action('wp_print_styles', 'enqueue_theme_layout');
?>