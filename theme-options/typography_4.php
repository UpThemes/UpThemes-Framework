<?php

$options = array (

    array(
        "name" => "Product Titles",
        "desc" => "Select the font and style for product titles.",
        "id" => "product_title",
        "selector" => "h2.prodtitle",
        "type" => "typography",
        "default" => "Buda"),
    
    array(
        "name" => "Widget Titles",
        "desc" => "Select the font and style for product titles.",
        "id" => "widget_title",
        "selector" => "h3.widgettitle",
        "type" => "typography",
        "default" => "Century Gothic")
);

/* Add Multple Selector Support */
$options = upfw_multiple_typography($options);


/* ------------ Do not edit below this line ----------- */

//Check if theme options set
global $default_check;
global $default_options;

if(!$default_check):
    foreach($options as $option):
        if($option['type'] != 'image'):
            $default_options[$option['id']] = $option['value'];
        else:
            $default_options[$option['id']] = $option['url'];
        endif;
    endforeach;
    $update_option = get_option('up_themes_'.UPTHEMES_SHORT_NAME);
    if(is_array($update_option)):
        $update_option = array_merge($update_option, $default_options);
        update_option('up_themes_'.UPTHEMES_SHORT_NAME, $update_option);
    else:
        update_option('up_themes_'.UPTHEMES_SHORT_NAME, $default_options);
    endif;
endif;

render_options($options);

?>