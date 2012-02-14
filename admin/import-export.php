<form method="post" action="#/import-export">
<?php //Security Nonce For Cross Site Hacking
wp_nonce_field('save_upthemes','upfw'); ?>


<?php
global $export_message;
echo $export_message;

//Create Export Code

$opts_to_export = get_option('up_themes_'.UPTHEMES_SHORT_NAME);
if(is_array($opts_to_export)):
    $encoded = 'up_themes_'.UPTHEMES_SHORT_NAME."~~";
    foreach($opts_to_export as $k => $v):
        if($v):
            $encoded .= $k.'|'.$v.'||';
        endif;
    endforeach;

    $encoded = base64_encode(substr($encoded, 0, -2));
    $encoded_check = true;
else:
    $encoded = __("No theme options found. Please refresh the theme options to generate an export code.","upfw");
    $encoded_check = false;
endif;

//Create Export Options

$options = array (
    array(  "name" => __("Theme Export Code","upfw"),
            "desc" => __("Copy and paste this code to somewhere safe.","upfw"),
            "id" => "up_export",
            "type" => "textarea",
            "value" => $encoded,
            "attr" => array("rows" => "12", "class" => "click-copy")
    )
);

//Create Download Link

if($encoded_check):
    $options[] = 
        array(  "name" => __("Download Theme Export Code","upfw"),
                "desc" => __("Download and save this file somewhere safe.","upfw"),
                "id" => "export_file",
                "type" => "button",
                "value" => __("Download File","upfw"),
                "attr" => array("ONCLICK" => "window.location.href='" . THEME_DIR . '/admin/export-options.php?f=upthemes_'.UPTHEMES_SHORT_NAME.'_'.date('mdy').'&e='.$encoded."'")
        );
endif;


//Create import options

$options[] =
    array(  "name" => __("Import Theme Options","upfw"),
            "desc" => __("Paste your options code here.","upfw"),
            "id" => "up_import_code",
            "type" => "textarea",
            "value" => '',
            "attr" => array("rows" => "12", "class" => "up_import_code")
    );
        
$options[] =
    array(  "name" => "",
            "desc" => __("Notice: This overwrites your current options.","upfw"),
            "id" => "up_import",
            "type" => "submit",
            "value" => __("Import Options Code","upfw")
    );


//Create Restore Defaults Option
$options[] = 
    array(  "name" => __("Restore Theme Defaults","upfw"),
            "desc" => __("Refresh all options to original defaults.","upfw"),
            "id" => "up_defaults",
            "type" => "submit",
            "value" => __("Restore Defaults","upfw"));
render_options($options);
?>

</form>