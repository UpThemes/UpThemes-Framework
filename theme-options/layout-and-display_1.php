<?php

$options = array (

    array(  "name" => "Theme Layout",
        "desc" => "Please select the layout for your site.",
        "id" => "layout_global",
        "type" => "layouts",
        "value" => get_bloginfo('template_directory').'/library/layouts/right-column.css'),
        
    array(  "name" => "Theme Layout for Archives",
        "desc" => "Please select the layout for your archives.",
        "id" => "layout_archive",
        "type" => "layouts",
        "context" => "archive",
        "value" => get_bloginfo('template_directory').'/library/layouts/right-column.css'),
    
    array(  "name" => "Theme Layout for Categories",
        "desc" => "Please select the layout for your categories.",
        "id" => "layout_category",
        "type" => "layouts",
        "context" => "category",
        "value" => get_bloginfo('template_directory').'/library/layouts/right-column.css'),
    
    
    array(  "name" => "Theme Layout for Pages",
        "desc" => "Please select the layout for your pages.",
        "id" => "layout_page",
        "type" => "layouts",
        "context" => "page",
        "value" => get_bloginfo('template_directory').'/library/layouts/right-column.css'),

    array(  "name" => "Theme Layout for Posts",
        "desc" => "Please select the layout for your posts.",
        "id" => "layout_post",
        "type" => "layouts",
        "context" => "single",
        "value" => get_bloginfo('template_directory').'/library/layouts/right-column.css'),
        
    array(  "name" => "Theme Layout for Author Archives",
        "desc" => "Please select the layout for your author archives.",
        "id" => "layout_author",
        "type" => "layouts",
        "context" => "author",
        "value" => get_bloginfo('template_directory').'/library/layouts/right-column.css'),
        
    array(  "name" => "Theme Layout for Search",
        "desc" => "Please select the layout for your search page.",
        "id" => "layout_search",
        "type" => "layouts",
        "context" => "search",
        "value" => get_bloginfo('template_directory').'/library/layouts/right-column.css'),
        
    array(  "name" => "Theme Layout for Tag Archives",
        "desc" => "Please select the layout for your tag archives.",
        "id" => "layout_tag",
        "type" => "layouts",
        "context" => "tag",
        "value" => get_bloginfo('template_directory').'/library/layouts/right-column.css')
        

);

render_options($options);
?>