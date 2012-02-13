<?php

function upfw_text_field($value,$attr){ ?>

	<input type="text" name="theme_<?php echo get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]" value="<?php echo $value; ?>">
                
<?php
}

function upfw_text_list($value,$attr){ ?>

    <div class="text_list">
        <?php
        if( isset( $value ) ) :
            if( is_array( $value ) ):
                foreach( $value as $text ):?>
                    <div class="entry">
                        <input class="text_list" type="text" name="theme_<?php echo get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>][]" value="<?php echo $text?>" />
                        <span class="delete_text_list"><a href="#"><img src="<?php echo THEME_DIR; ?>/admin/images/upfw_ico_delete.png" alt="Delete Text Field" /></a></span>
                        <div class="clear"></div>
                    </div>
                <?php endforeach;
            endif;
        else:
            if( isset( $value['value'] ) ) :
                if(preg_match('/,/', $value['value'])):
                    $list = explode(', ', $value['value']);
                    foreach($list as $text):?>
                            <div class="entry">
                                <input class="text_list" type="text" name="<?php echo $attr['name']; ?>[]" id="<?php echo $attr['name']; ?>" value="<?php echo $text?>" <?php echo $attr; ?> />
                                <span class="delete_text_list"><a href="#"><img src="<?php echo THEME_DIR; ?>/admin/images/upfw_ico_delete.png" alt="Delete Text Field" /></a></span>
                                <div class="clear"></div>
                            </div>
                    <?php endforeach;
                else:
                    if($value['value'] == $v ):
                        $selected = ' selected = "selected"';
                    endif;
                endif;
            endif;
        endif;?>
        
    </div>

	<?php $add_text = __('Add New Field', 'upfw');?>
	<p class="add_text_list"><a href="#"><?php echo $add_text;?></a></p>

<?php
}

function upfw_textarea($value,$attr){ ?>
	<textarea name="theme_<?php echo get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]" cols="48" rows="8"><?php echo $value; ?></textarea>
<?php
}

function upfw_select($value,$attr){ ?>
<select name="theme_<?php echo get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]">
    <?php
    if ( isset( $attr['valid_options'] ) ) :
        $options = $attr['valid_options'];
        foreach( $options as $option ) : 
        ?>
            <option value="<?php echo $option['name']; ?>" <?php selected($option['name'],$value); ?>><?php echo $option['title']; ?></option>
			<?php 
		endforeach;
	else:
		_e("This option has no valid options. Please create valid options as an array inside the UpThemes Framework.","upfw");
    endif;
    ?>
</select>
<?php
}

function upfw_multiple($value,$attr){ ?>

    <select name="theme_<?php echo get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>][]" multiple>
        <?php
		if ( isset( $attr['valid_options'] ) ) :
		    $options = $attr['valid_options'];
		    foreach( $options as $option_key => $option_value ) : ?>
                <option value="<?php echo $option_value['name']; ?>" <?php selected( in_array($option_value['name'],$value) ); ?>><?php echo $option_value['title']; ?></option>
		<?php endforeach;
		endif;
        ?>
    </select>
<?php
}

function upfw_checkbox($value,$attr){

	if ( isset( $attr['valid_options'] ) ) :
	    $options = $attr['valid_options'];
	    foreach( $options as $option_key => $option_value ) : ?>
			<input type="checkbox" <?php checked($value[$option_value['name']]);?> name="theme_<?php echo get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>][<?php echo $option_value['name']; ?>]">
	        <label for="<?php echo $option_value['name']; ?>"><?php echo $option_value['title'];?></label><br>
	<?php endforeach;
	endif;

}

function upfw_color($value,$attr){ ?>

    <span class="colorPickerWrapper">
        <input type="text" class="popup-colorpicker" id="<?php echo $attr['name']; ?>" name="theme_<?php echo get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]" value="<?php echo $value; ?>" />
        <a href="#" class="clear"><img src="<?php echo THEME_DIR; ?>/admin/images/upfw_ico_delete.png" alt="Delete Text Field" /></a>
        <div class="popup-guy">
            <div class="popup-guy-inside">
                <div id="<?php echo $attr['name']; ?>picker" class="color-picker"></div>
            </div>
        </div>
    </span>

<?php
}

/* @todo Rewrite the image option

function upfw_image($value,$attr){ ?>

	<input type="submit" class="image_picker" id="theme_<?php echo get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]" class="button-primary" value="Select Image">

	<!-- Hidden Input -->
	<input type="hidden" name="<?php echo $attr['name']; ?>" name="theme_<?php echo get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]" value="<?php echo $value; ?>" />

<?php
}*/

function upfw_category($value,$attr){
    global $wpdb;
?>

    <select name="theme_<?php echo get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]" id="theme_<?php echo get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]">
        <?php
        $i = $wpdb->get_results("SELECT $wpdb->terms.name, $wpdb->terms.slug, $wpdb->term_taxonomy.term_id FROM $wpdb->terms LEFT JOIN $wpdb->term_taxonomy ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id WHERE $wpdb->term_taxonomy.taxonomy = 'category' ORDER BY $wpdb->terms.name", ARRAY_A);
        foreach($i as $row):
                if($attr['name']):
                    if($row['slug'] == $attr['name']):
                        $selected = " selected='selected'";
                    endif;
                else:
                    if($value['value'] == $row['slug']):
                        $selected = ' selected = "selected"';
                    endif;
                endif;
            echo "<option value='".$row['slug']."'".$selected.">".$row['name']."</option>";
            $selected = '';
        endforeach;
        ?>
    </select>

<?php
}

function upfw_categories($value,$attr){
    global $wpdb;
?>

<select name="theme_<?php echo get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]" id="theme_<?php echo get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]" multiple>
    <?php
    $i = $wpdb->get_results("SELECT $wpdb->terms.name, $wpdb->terms.slug, $wpdb->term_taxonomy.term_id FROM $wpdb->terms LEFT JOIN $wpdb->term_taxonomy ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id WHERE $wpdb->term_taxonomy.taxonomy = 'category' ORDER BY $wpdb->terms.name", ARRAY_A);
    foreach($i as $row):
            if($attr['name']):
                if($row['slug'] == $attr['name']):
                    $selected = " selected='selected'";
                endif;
            else:
                if($value['value'] == $row['slug']):
                    $selected = ' selected = "selected"';
                endif;
            endif;
        echo "<option value='".$row['slug']."'".$selected.">".$row['name']."</option>";
        $selected = '';
    endforeach;
    ?>
</select>
                
<?php
}

function upfw_page($value,$attr){
    global $wpdb;
?>

	<select name="theme_<?php echo get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]" id="theme_<?php echo get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]">
	    <?php
	    $i = $wpdb->get_results("SELECT post_title, ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type='page' ORDER BY post_title", ARRAY_A);
	    foreach($i as $row):
	        if($attr['name']):
	            if($row['ID'] == $attr['name']):
	                $selected = " selected='selected'";
	            endif;
	        else:
	            if($row['post_title'] == $value['value']):
	                $selected = " selected='selected'";
	            endif;
	        endif;
	        echo "<option value='".$row['ID']."'".$selected.">".$row['post_title']."</option>";
	        $selected = '';
	    endforeach;
	    ?>
	</select>
                
<?php
}

function upfw_pages($value,$attr){
    global $wpdb;
?>

	<select name="theme_<?php echo get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]" id="theme_<?php echo get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]" multiple>
	    <?php
	    $i = $wpdb->get_results("SELECT post_title, ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type='page' ORDER BY post_title", ARRAY_A);
	    foreach($i as $row):
	        if($attr['name']):
	            if($row['ID'] == $attr['name']):
	                $selected = " selected='selected'";
	            endif;
	        else:
	            if($row['post_title'] == $value['value']):
	                $selected = " selected='selected'";
	            endif;
	        endif;
	        echo "<option value='".$row['ID']."'".$selected.">".$row['post_title']."</option>";
	        $selected = '';
	    endforeach;
	    ?>
	</select>

<?php
}

function upfw_taxonomy($value,$attr){
    global $wpdb;
?>

 <select name="theme_<?php echo get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]" id="theme_<?php echo get_current_theme_id(); ?>_options[<?php echo $attr['name']; ?>]">
	<?php $taxonomy = $value['taxonomy'];
	$i = $wpdb->get_results("SELECT $wpdb->terms.name, $wpdb->terms.slug, $wpdb->term_taxonomy.term_id FROM $wpdb->terms LEFT JOIN $wpdb->term_taxonomy ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id WHERE $wpdb->term_taxonomy.taxonomy = '$taxonomy' ORDER BY $wpdb->terms.name", ARRAY_A);
	foreach($i as $row):
	    if(!empty($attr['name'])):
	        foreach($attr['name'] as $std):
	            if($std == $row['slug']):
	                $selected = ' selected = "selected"';
	            endif;
	        endforeach;
	    else:
	        if($value['value']):
	            if(preg_match('/,/', $value['value'])):
	                $cats = explode(', ', $value['value']);
	                foreach($cats as $cat):
	                    if(preg_match('/\b'.$row['slug'].'\b/', $cat)):
	                        $selected = ' selected = "selected"';
	                    endif;
	                endforeach;
	            else:
	                if($value['value'] == $row['slug'] ):
	                    $selected = ' selected = "selected"';
	                endif;
	            endif;
	        else:
	            if($value['value'] == $row['post_title'] ):
	                $selected = ' selected = "selected"';
	            endif;
	        endif;
	    endif;
	    
	    echo "<option value='".$row['slug']."'".$selected.">".$row['name']."</option>";
	    $selected = '';
	endforeach;
 	?>
 </select>

<?php
}