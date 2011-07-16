<?php

function upfw_text_field($value,$attr){
    global $up_options,$wpdb;
?>

                <li class="type-<?php echo $value['type'];?>" id="container-<?php echo $value['id'];?>">
                    <fieldset class="title">
                        <div class="inner">
                            <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                            <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                        </div>
                    </fieldset>
                    
                    <fieldset class="data">
                        <div class="inner">
                            <input type="text" name="<?php echo $value['id']; ?>" value="<?php if($up_options->$value['id']): echo $up_options->$value['id']; else: echo $value['value']; endif;?>" id="<?php echo $value['id']; ?>" <?php echo $attr; ?> />
                        </div>
                    </fieldset>
                    <div class="clear"></div>
                </li>
                
<?php
}

function upfw_text_list($value,$attr){
    global $up_options,$wpdb;
?>

                <li class="type-<?php echo $value['type'];?>" id="<?php echo $value['id']; ?>_list">
                    <fieldset class="title">
                        <div class="inner">
                            <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                            <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                        </div>
                    </fieldset>
                    <script type="text/javascript">
                        jQuery(function(){
                            jQuery('#<?php echo $value['id']; ?>_list p.add_text_list a').live('click', function(){
                                jQuery(this).parents('li').find('div.text_list').append('<div class="entry"><input class="text_list" type="text" name="<?php echo $value['id']; ?>[]" /><p class="delete_text_list"><a href="#"><img src="<?php echo THEME_DIR;?>/admin/images/upfw_ico_delete.png" alt="Delete Text Field" /></a></p></div><div class="clear"></div>');
                                jQuery(this).parents('li').find('input.hiddentext_list').remove();
                                return false;
                            });
                            jQuery('#<?php echo $value['id']; ?>_list p.delete_text_list a').live('click', function(){
                                var parent = jQuery(this).parent();
                                jQuery(parent).parent().fadeOut(function(){
                                    jQuery(this).remove();
                                });
                                var textList = jQuery(this).parents('li').find('div.text_list .entry').length;
                                if(textList == 1){jQuery(this).parents('li').find('div.text_list').append('<input class="hiddentext_list" type="hidden" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>[]" />');}
                                return false;
                            });
                        });
                    </script>
                    <fieldset class="data">
                        <div class="inner">
                            <div class="text_list">
                                <?php $add_text = $value['default_text'] ? $value['default_text'] : __('Add New Field', 'upfw');?>
                                <p class="add_text_list"><a href="#"><?php echo $add_text;?></a></p>
                                <?php
                                if($up_options->$value['id']):
                                    if(is_array($up_options->$value['id'])):
                                        foreach($up_options->$value['id'] as $text):?>
                                            <div class="entry">
                                                <input class="text_list" type="text" name="<?php echo $value['id']; ?>[]" id="<?php echo $value['id']; ?>" value="<?php echo $text?>" <?php echo $attr; ?> />
                                                <p class="delete_text_list"><a href="#"><img src="<?php echo THEME_DIR; ?>/admin/images/upfw_ico_delete.png" alt="Delete Text Field" /></a></p>
                                                <div class="clear"></div>
                                            </div>
                                        <?php endforeach;
                                    endif;
                                else:
                                    if($value['value']):
                                        if(preg_match('/,/', $value['value'])):
                                            $list = explode(', ', $value['value']);
                                            foreach($list as $text):?>
                                                    <div class="entry">
                                                        <input class="text_list" type="text" name="<?php echo $value['id']; ?>[]" id="<?php echo $value['id']; ?>" value="<?php echo $text?>" <?php echo $attr; ?> />
                                                        <p class="delete_text_list"><a href="#"><img src="<?php echo THEME_DIR; ?>/admin/images/upfw_ico_delete.png" alt="Delete Text Field" /></a></p>
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
                        </div>
                    </fieldset>
                    <div class="clear"></div>
                </li>
                
<?php
}

function upfw_textarea($value,$attr){
    global $up_options,$wpdb;
?>

                <li class="type-<?php echo $value['type'];?>" id="container-<?php echo $value['id'];?>">
                    <fieldset class="title">
                        <div class="inner">
                            <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                            <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                        </div>
                    </fieldset>
                    
                    <fieldset class="data">
                        <div class="inner">
                            <textarea name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" <?php echo $attr; ?>><?php if($up_options->$value['id']): echo $up_options->$value['id']; else: echo $value['value']; endif;?></textarea>
                        </div>
                    </fieldset>
                    <div class="clear"></div>
                </li>
                
<?php
}

function upfw_select($value,$attr){
    global $up_options,$wpdb;
?>

                <li class="type-<?php echo $value['type'];?>" id="container-<?php echo $value['id'];?>">
                    <fieldset class="title">
                        <div class="inner">
                            <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                            <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                        </div>
                    </fieldset>
                    
                    <fieldset class="data">
                        <div class="inner">
                            <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" <?php echo $attr; ?>>
                                <option value="<?php echo $value['default_value']; ?>"><?php if($value['default_text']): echo $value['default_text']; else: echo "None"; endif;?></option>
                                <?php
                                if(is_array($value['options'])):
                                    $i = $value['options'];
                                    foreach($i as $k => $v):
                                        if($up_options->$value['id']):
                                            if($up_options->$value['id'] == $v):
                                                $selected = ' selected = "selected"';
                                            endif;
                                        else:
                                            if($value['value'] == $v):
                                                $selected = ' selected = "selected"';
                                            endif;
                                        endif;?>
                                        <option value="<?php echo $v?>"<?php echo $selected?>><?php echo $k?></option>
                                        <?php $selected = '';?>
                                    <?php endforeach;
                                endif;
                                ?>
                            </select>
                        </div>
                    </fieldset>
                    <div class="clear"></div>
                </li>
                
<?php
}

function upfw_multiple($value,$attr){
    global $up_options,$wpdb;
?>

                <li class="type-<?php echo $value['type'];?>" id="container-<?php echo $value['id'];?>">
                    <fieldset class="title">
                        <div class="inner">
                            <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                            <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                        </div>
                    </fieldset>
                    
                    <fieldset class="data">
                        <div class="inner">
                            <select MULTIPLE name="<?php echo $value['id']; ?>[]" id="<?php echo $value['id']; ?>" <?php echo $attr; ?>>
                                <option value=""><?php if($value['default_text']): echo $value['default_text']; else: echo "None"; endif;?></option>
                                <?php
                                if(is_array($value['options'])):
                                    $i = $value['options'];
                                    foreach($i as $k => $v):
                                        if($up_options->$value['id']):
                                            if(is_array($up_options->$value['id'])):
                                                foreach($up_options->$value['id'] as $std):
                                                    if($v == $std):
                                                        $selected = ' selected = "selected"';
                                                    endif;
                                                endforeach;
                                            endif;
                                        else:
                                            
                                            if($value['value']):
                                                if(preg_match('/,/', $value['value'])):
                                                    $cats = explode(', ', $value['value']);
                                                    foreach($cats as $cat):
                                                        if(preg_match('/\b'.$v.'\b/', $cat)):
                                                            $selected = ' selected = "selected"';
                                                        endif;
                                                    endforeach;
                                                else:
                                                    if($value['value'] == $v ):
                                                        $selected = ' selected = "selected"';
                                                    endif;
                                                endif;
                                            else:
                                                if($value['value'] == $v ):
                                                    $selected = ' selected = "selected"';
                                                endif;
                                            endif;
                                            
                                        endif;?>
                                        <option value="<?php echo $v?>"<?php echo $selected?>><?php echo $k?></option>
                                        <?php $selected = '';?>
                                    <?php endforeach;
                                endif;
                                ?>
                            </select>
                        </div>
                    </fieldset>
                    <div class="clear"></div>
                </li>
                
<?php
}

function upfw_checkbox($value,$attr){
    global $up_options,$wpdb;
?>

                <li class="type-<?php echo $value['type'];?>" id="container-<?php echo $value['id'];?>">
                    <fieldset class="title">
                        <div class="inner">
                            <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                            <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                        </div>
                    </fieldset>
                    
                    <fieldset class="data">
                        <div class="inner">
                            <div class="checkbox-container">
                                <?php $selected = '';
                                $count = 0;
                                if(is_array($value['options'])): $count++;
                                    $i = $value['options'];
                                    //This gets the latest options
                                    $up_options_db = get_option('up_themes_'.UPTHEMES_SHORT_NAME);
                                    foreach($i as $k => $v):
                                        if($up_options_db[$value['id']]):
                                            if(is_array($up_options->$value['id'])):
                                                foreach($up_options->$value['id'] as $std):
                                                    if($v == $std):
                                                        $selected = ' checked="yes"';
                                                    endif;
                                                endforeach;
                                            endif;
                                        else:
                                            $selected = '';
                                        endif;?>
                                        <div class="checkbox">
                                            <input type="checkbox" <?php echo $selected;?> value="<?php echo $v;?>" name="<?php echo $value['id']; ?>[]" id="<?php echo $value['id']; ?>">
                                            <label><?php echo $k;?></label>
                                        </div>
                                        
                                        <?php $selected = '';?>
                                    <?php endforeach;
                                endif;
                                ?>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </fieldset>
                    <div class="clear"></div>
                </li>
                
<?php
}

function upfw_color($value,$attr){
    global $up_options,$wpdb;
?>

                <li class="type-<?php echo $value['type'];?>" id="container-<?php echo $value['id'];?>">
                    <fieldset class="title">
                        <div class="inner">
                            <label for="awesome3"><?php echo $value['name']; ?></label>
                            <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                        </div>
                    </fieldset>
                    <fieldset class="data">
                        <div class="inner">
                            <span class="colorPickerWrapper">
                                <a href="#" class="clear"><img src="<?php echo THEME_DIR; ?>/admin/images/upfw_ico_delete.png" alt="Delete Text Field" /></a>
                                <input type="text" class="popup-colorpicker" id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>" value="<?php if($up_options->$value['id']): echo $up_options->$value['id']; else: echo $value['value']; endif;?>" <?php echo $attr; ?> />
                                <div class="popup-guy">
                                    <div class="popup-guy-inside">
                                        <div id="<?php echo $value['id']; ?>picker" class="color-picker"></div>
                                    </div>
                                </div>
                            </span>
                        </div>
                    </fieldset>
                    <script type="text/javascript">
                    jQuery("#container-<?php echo $value['id']; ?>").find('.clear').click(function(e){
                    	e.preventDefault();
                    	jQuery(this).parent().find('input').val('').css('backgroundColor','#f9f9f9');
                    });
                    </script>
                    <div class="clear"></div>
                </li>
                
<?php
}

function upfw_image($value,$attr){
    global $up_options,$wpdb;
?>

                <script type="text/javascript">
					jQuery(document).ready(function($){
                        
                        //View UpThemes Gallery
                        jQuery('a#<?php echo $value['id']; ?>viewgallery').toggle(
                            function(){
                                jQuery(this).text('Hide Gallery');
                                jQuery('#<?php echo $value['id']; ?>allimages').slideDown();
                                
								var data = {
									action: 'show_gallery_images',
									id: '<?php echo $value['id']; ?>'
								};
							
								// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
								jQuery.post(ajaxurl, data, function(response) {
									jQuery('#<?php echo $value['id']; ?>allimages .thumbs').html(response);
								});
								
								return false;
                            },
                            function(){
                                jQuery(this).text('Select from the UpThemes Gallery');
                                jQuery('#<?php echo $value['id']; ?>allimages').slideUp();
                                								                                    
                                return false;
                            }
                        );
                        
                        jQuery("#container-<?php echo $value['id']; ?>").find('a.clear').live('click', function(e){
                    		
                    		e.preventDefault();
							jQuery('#<?php echo $value['id']; ?>preview').html('<img src="<?php echo THEME_DIR; ?>/admin/images/upfw_noimage.gif" alt="No Image" />');
							jQuery('input#<?php echo $value['id']; ?>').attr('value', '');
							
							jQuery(this).hide();
                        
                        });
                        
                        //Select and image from the gallery
                        jQuery('#<?php echo $value['id']; ?>allimages a').live('click', function(e){
                        
                            e.preventDefault();

                            activate_save_animation(e);

							// hide the clear button
                            jQuery("#container-<?php echo $value['id']; ?>").find('a.clear').css('display','inline-block');

                            //Add image source to hidden input
                            jQuery('input#<?php echo $value['id']; ?>').attr('value', jQuery(this).attr('href'));
                            
                            //Send image to preview
                            jQuery('#<?php echo $value['id']; ?>preview').html('<img src="'+jQuery(this).attr('href')+'" alt="<?php echo $value['id']; ?> Image" />');

                            return false;
                            
                        });

                        <?php //Upload Security
		    			$upload_security = md5($_SERVER['SERVER_ADDR']); ?>
                        //Upload an Image
                        var <?php echo $value['id']; ?>=jQuery('div.uploadify button#<?php echo $value['id']; ?>');
                        var status=jQuery('#<?php echo $value['id']; ?>status');
                        new AjaxUpload(<?php echo $value['id']; ?>, {
                            action: '<?php echo THEME_DIR; ?>/admin/upload-file.php',
                            name: '<?php echo $upload_security?>',
                            data: {
                            	upload_path : '<?php echo base64_encode(UPFW_UPLOADS_DIR); ?>'
                            },
                            onSubmit: function(file, ext){
                                //Check if file is an image
                                if (! (ext && /^(JPG|PNG|GIF|jpg|png|jpeg|gif)$/.test(ext))){ 
                                   // extension is not allowed 
                                   status.text('Only JPG, PNG or GIF files are allowed');
                                   return false;
                                }
                                jQuery('#<?php echo $value['id']; ?>loader').addClass('activeload');
                            },
                            onComplete: function(file, response){
                                //On completion clear the status
                                status.text('');
                                //Successful upload
                                if(response==="success"){
                                	$file = file.toLowerCase().replace(/ /g,'_').replace(/(_)\1+/g, '_').replace(/[^\w\(\).-]/gi,'_').replace(/__/g,'_').replace(/#/g, '_');
                                    //Preview uploaded file
									jQuery('#<?php echo $value['id']; ?>preview').removeClass('uploaderror');
                                    jQuery('#<?php echo $value['id']; ?>preview').html('<img class="preview" src="<?php echo UPFW_UPLOADS_URL; ?>/'+$file+'" alt="<?php echo $value['id']; ?> Image" />').addClass('success');
                                    //Add image source to hidden input
                                    jQuery('input#<?php echo $value['id']; ?>').attr('value', '<?php echo UPFW_UPLOADS_URL; ?>/'+$file);
                                    //Append thumbnail to gallery
                                    jQuery('.thumbs').append('<a class="preview" href="<?php echo UPFW_UPLOADS_URL; ?>/'+$file+'"><img src="<?php echo UPFW_UPLOADS_URL; ?>/'+$file+'" /></a>');
                                    //Save Me Fool
                                    activate_save_animation();
                                } else{
                                    //Something went wrong
                                    jQuery('#<?php echo $value['id']; ?>preview').text(file+' did not upload. Please try again.').addClass('uploaderror');
                                }
                              	jQuery('#<?php echo $value['id']; ?>loader').removeClass('activeload');
                            }
                        });
                    });
                </script>
                <li class="type-<?php echo $value['type'];?>" id="container-<?php echo $value['id'];?>">
                    <fieldset class="title">
                        <div class="inner">
                            <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                            <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                        </div>
                        <!-- Image Preview Input -->
                        <div class="preview" id="<?php echo $value['id']; ?>preview"><?php
                        
                        if($up_options->$value['id']):
                            echo "<img src='".$up_options->$value['id']."' alt='Preview Image' />";
                        	echo "</div>";
	                        echo '<a href="#" class="clear"><img src="' . THEME_DIR . '/admin/images/upfw_ico_delete.png" alt="' . __("Delete Text Field","upfw") . '" /></a>';
                        else:
                        	echo "<img src='".THEME_DIR."/admin/images/upfw_noimage.gif' alt='No Image Available' />";
                        	echo "</div>";
	                        echo '<a href="#" class="clear" style="display: none;"><img src="' . THEME_DIR . '/admin/images/upfw_ico_delete.png" alt="' . __("Delete Text Field","upfw") . '" /></a>';
	                    endif;?>

                    </fieldset>

                    <fieldset class="data">
                            <div class="inner">
                                <div class="uploadify">
                                    <button type="button" id="<?php echo $value['id']; ?>" class="secondary" <?php echo $attr; ?>><?php echo $value['value']; ?></button>
                                    <span id="<?php echo $value['id']; ?>loader" class="loader"></span>
                                </div>

                                <!-- Hidden Input -->
                                <input type="hidden" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>" value="<?php if($up_options->$value['id']): echo $up_options->$value['id']; endif;?>" />

                                <!-- Upload Status Input -->
                                <div class="status hide" id="<?php echo $value['id']; ?>status"></div>

                                <!-- Divider -->
                                <div class="divider">
                                    <span><?php _e("OR","upfw"); ?></span>
                                </div>

                                <!-- View Gallery -->
                                <div class="viewgallery">
                                    <a id="<?php echo $value['id']; ?>viewgallery" href="#"><?php _e("Select from the UpThemes Gallery","upfw"); ?></a>
                                </div>
                                
                                <!-- All Images -->
                                <div id="<?php echo $value['id']; ?>allimages" class="allimages">
                                    <div class="thumbs">
                                        <?php $path = UPFW_UPLOADS_DIR;
                                        $directory = @opendir($path) or die("Unable to open folder. Please make sure your /wp-content/uploads/upfw/ folder exists and has permissions of 777.");
                                        while (false !== ($file = readdir($directory))) {
                                            if($file == "index.php") continue;
                                            if($file == ".") continue;
                                            if($file == "..") continue;
                                            if($file == "list.php") continue;
                                            if($file == "Thumbs.db") continue;?>
                                            <a class="preview" href="<?php echo UPFW_UPLOADS_URL; ?>/<?php echo $file?>"><img src="<?php echo UPFW_UPLOADS_URL; ?>/<?php echo $file?>" /></a>
                                        <?php }
                                        closedir($directory);?>
                                        <div class="clear"></div>
                                    </div>
                                    <?php if($value['url']):?>
                                        <div class="default">
                                            <p><em><?php _e("Default Image","upfw"); ?></em></p>
                                            <a href="<?php echo $value['url']; ?>"><img src="<?php echo $value['url']; ?>" /></a>
                                        </div>
                                    <?php endif;?>
                                </div>

                            </div>
                    </fieldset>
                    <div class="clear"></div>
                </li>
<?php
}

function upfw_category($value,$attr){
    global $up_options,$wpdb;
?>

                <li class="type-<?php echo $value['type'];?>" id="container-<?php echo $value['id'];?>">
                    <fieldset class="title">
                        <div class="inner">
                            <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                            <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                        </div>
                    </fieldset>
                    
                    <fieldset class="data">
                        <div class="inner">
                            
                            <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" <?php echo $attr; ?>>
                                <option value=""><?php if($value['default_text']): echo $value['default_text']; else: echo "None"; endif;?></option>
                                <?php
                                $i = $wpdb->get_results("SELECT $wpdb->terms.name, $wpdb->terms.slug, $wpdb->term_taxonomy.term_id FROM $wpdb->terms LEFT JOIN $wpdb->term_taxonomy ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id WHERE $wpdb->term_taxonomy.taxonomy = 'category' ORDER BY $wpdb->terms.name", ARRAY_A);
                                foreach($i as $row):
                                        if($up_options->$value['id']):
                                            if($row['slug'] == $up_options->$value['id']):
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
                        </div>
                    </fieldset>
                    <div class="clear"></div>
                </li>
                
<?php
}

function upfw_categories($value,$attr){
    global $up_options,$wpdb;
?>

                <li class="type-<?php echo $value['type'];?>" id="container-<?php echo $value['id'];?>">
                    <fieldset class="title">
                        <div class="inner">
                            <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                            <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                        </div>
                    </fieldset>
                    
                    <fieldset class="data">
                        <div class="inner">
                            <select MULTIPLE name="<?php echo $value['id']; ?>[]" id="<?php echo $value['id']; ?>" <?php echo $attr; ?>>
                                <option value=""><?php if($value['default_text']): echo $value['default_text']; else: echo "None"; endif;?></option>
                                <?php
                                $i = $wpdb->get_results("SELECT $wpdb->terms.name, $wpdb->terms.slug, $wpdb->term_taxonomy.term_id FROM $wpdb->terms LEFT JOIN $wpdb->term_taxonomy ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id WHERE $wpdb->term_taxonomy.taxonomy = 'category' ORDER BY $wpdb->terms.name", ARRAY_A);
                                foreach($i as $row):
                                    if(!empty($up_options->$value['id'])):
                                        foreach($up_options->$value['id'] as $std):
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
                        </div>
                    </fieldset>
                    <div class="clear"></div>
                </li>
                
<?php
}

function upfw_page($value,$attr){
    global $up_options,$wpdb;
?>

                <li class="type-<?php echo $value['type'];?>" id="container-<?php echo $value['id'];?>">
                    <fieldset class="title">
                        <div class="inner">
                            <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                            <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                        </div>
                    </fieldset>
                    
                    <fieldset class="data">
                        <div class="inner">
                            <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" <?php echo $attr; ?>>
                                <option value=""><?php if($value['default_text']): echo $value['default_text']; else: echo "None"; endif;?></option>
                                <?php
                                $i = $wpdb->get_results("SELECT post_title, ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type='page' ORDER BY post_title", ARRAY_A);
                                foreach($i as $row):
                                    if($up_options->$value['id']):
                                        if($row['ID'] == $up_options->$value['id']):
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
                        </div>
                    </fieldset>
                    <div class="clear"></div>
                </li>
                
<?php
}

function upfw_pages($value,$attr){
    global $up_options,$wpdb;
?>

                <li class="type-<?php echo $value['type'];?>" id="container-<?php echo $value['id'];?>">
                    <fieldset class="title">
                        <div class="inner">
                            <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                            <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                        </div>
                    </fieldset>
                    
                    <fieldset class="data">
                        <div class="inner">
                            <select multiple="multiple" name="<?php echo $value['id']; ?>[]" id="<?php echo $value['id']; ?>" <?php echo $attr; ?>>
                                <option value=""><?php if($value['default_text']): echo $value['default_text']; else: echo "None"; endif;?></option>
                                <?php
                                $i = $wpdb->get_results("SELECT post_title, ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type='page' ORDER BY post_title", ARRAY_A);
                                foreach($i as $row):
                                    if(!empty($up_options->$value['id'])):
                                        if($up_options->$value['id']):
                                            foreach($up_options->$value['id'] as $std):
                                                if($std == $row['ID']):
                                                    $selected = ' selected = "selected"';
                                                endif;
                                            endforeach;
                                        endif;
                                    else:
                                        if($value['value']):
                                            if(preg_match('/,/', $value['value'])):
                                                $pages = explode(', ', $value['value']);
                                                foreach($pages as $page):
                                                    if(preg_match('/\b'.$row['post_title'].'\b/', $page)):
                                                        $selected = ' selected = "selected"';
                                                    endif;
                                                endforeach;
                                            else:
                                                if($value['value'] == $row['post_title'] ):
                                                    $selected = ' selected = "selected"';
                                                endif;
                                                
                                            endif;
                                        else:
                                            if($value['value'] == $row['post_title'] ):
                                                $selected = ' selected = "selected"';
                                            endif;
                                        endif;
                                    endif;
                                    echo "<option value='".$row['ID']."'".$selected.">".$row['post_title']."</option>";
                                    $selected = '';
                                endforeach
                                ?>
                            </select>
                        </div>
                    </fieldset>
                    <div class="clear"></div>
                </li>

<?php
}

function upfw_submit($value,$attr){
    global $up_options,$wpdb;
?>

                <li class="type-<?php echo $value['type'];?>" id="container-<?php echo $value['id'];?>">
                    <fieldset class="title">
                        <div class="inner">
                            <label><?php echo $value['name']; ?></label>
                            <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                        </div>
                    </fieldset>
                    
                    <fieldset class="data">
                        <div class="inner">
                            <div class="uploadify">
                            <button type="submit" id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>" class="secondary" <?php echo $attr; ?>><?php echo $value['value']; ?></button>
                            </div>
                        </div>
                    </fieldset>
                    <div class="clear"></div>
                </li>
                
<?php
}

function upfw_taxonomy($value,$attr){
    global $up_options,$wpdb;
?>

                <li class="type-<?php echo $value['type'];?>" id="container-<?php echo $value['id'];?>">
                    <fieldset class="title">
                        <div class="inner">
                            <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                            <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                        </div>
                    </fieldset>
                    
                    <fieldset class="data">
                        <div class="inner">
                            <select MULTIPLE name="<?php echo $value['id']; ?>[]" id="<?php echo $value['id']; ?>" <?php echo $attr; ?>>
                                <option value=""><?php if($value['default_text']): echo $value['default_text']; else: echo "None"; endif;?></option>
                                <?php
                                $taxonomy = $value['taxonomy'];
                                $i = $wpdb->get_results("SELECT $wpdb->terms.name, $wpdb->terms.slug, $wpdb->term_taxonomy.term_id FROM $wpdb->terms LEFT JOIN $wpdb->term_taxonomy ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id WHERE $wpdb->term_taxonomy.taxonomy = '$taxonomy' ORDER BY $wpdb->terms.name", ARRAY_A);
                                foreach($i as $row):
                                    if(!empty($up_options->$value['id'])):
                                        foreach($up_options->$value['id'] as $std):
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
                        </div>
                    </fieldset>
                    <div class="clear"></div>
                </li>
                
<?php
}

function upfw_button($value,$attr){
    global $up_options,$wpdb;
?>

                <li class="type-<?php echo $value['type'];?>" id="container-<?php echo $value['id'];?>">
                    <fieldset class="title">
                        <div class="inner">
                            <label><?php echo $value['name']; ?></label>
                            <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                        </div>
                    </fieldset>
                    
                    <fieldset class="data">
                        <div class="inner">
                            <div class="uploadify">
                            <button type="button" id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>" class="secondary" <?php echo $attr; ?>><?php echo $value['value']; ?></button>
                            </div>
                        </div>
                    </fieldset>
                    <div class="clear"></div>
                </li>
                
<?php
}

function upfw_divider($value,$attr){
    global $up_options,$wpdb;
?>

                <li class="type-<?php echo $value['type'];?>" id="container-<?php echo $value['id'];?>">
                    <h2 class="tight"><?php echo $value['name']; ?></h2>
                    <div class="clear"></div>
                </li>

<?php
}

function upfw_typography($value,$attr){
    global $up_options,$up_fonts,$wpdb;

    $option = $up_options->$value['id'];
	if(!$option):
    	global $default_options;
    	$option = $value['value'];
    endif;

    $family  = $option['font'] ? $option['font'] : $value['default'];
    $fontsize  = $option['fontsize'] ? $option['fontsize']: '12px';
    $fontstyle  = $option['fontstyle'] ? $option['fontstyle']: 'normal';
    $lineheight = $option['lineheight'] ? $option['lineheight'] : '16px';
    $texttransform = $option['texttransform'] ? $option['texttransform'] : 'none';
    $fontweight = $option['fontweight'] ? $option['fontweight'] : 'normal';
    $textdecoration = $option['textdecoration'] ? $option['textdecoration'] : 'none';
    $textshadow = $option['textshadow'] ? $option['textshadow'] : 'none';
    $letterspacing = $option['letterspacing'] ? $option['letterspacing'] : '0px';
    $show_selector = $value['show_selector'];
    $selector = $option['selector'] ? $option['selector'] : $value['selector'];
    $fonts = $up_fonts; ?>
                    
    <li class="type-<?php echo $value['type'];?> typography" id="<?php echo $value['id']; ?> container-<?php echo $value['id']; ?>">
        <fieldset class="title">
            <div class="inner">
                <label><?php echo $value['name']; ?></label>
                <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
            </div>
        </fieldset>
        
        <fieldset class="data">
            <div class="inner">
                <div class="type_fields">

                    <div class="field">
                        <?php if($show_selector):?>
                            <label class="font-label" for="<?php echo $value['id']; ?>_selector"><?php _e('CSS Selector','upfw'); ?></label>
                            <input class="font-selector" type="text" id="<?php echo $value['id']; ?>_selector" name="<?php echo $value['id']; ?>[selector]" value="<?php echo $selector;?>">
                        <?php else:?>
                            <input class="font-selector" type="hidden" id="<?php echo $value['id']; ?>_selector" name="<?php echo $value['id']; ?>[selector]" value="<?php echo $selector;?>">
                        <?php endif;?>
                    </div>
                
                	<fieldset>
	                	
	                    <div class="field">
	                        <label class="font-label" for="<?php echo $value['id']; ?>_font"><?php _e('Font Family','upfw'); ?></label>
	                        <?php if(is_array($fonts)): ksort($fonts);?>
	                            <select id="<?php echo $value['id']; ?>_font" name="<?php echo $value['id']; ?>[font]">
	                                <?php foreach($fonts as $font):
	                                    $selected = ($font['id'] == strtolower($family)) ? 'selected="selected"' : ''; ?>
	                                    <option title="<?php echo $font['style'];?>" id="<?php echo $font['font_family'];?>" value="<?php echo $font['id'];?>" <?php echo $selected;?>><?php echo $font['name'];?></option>
	                                <?php endforeach;?>
	                            </select>
	                        
	                            <?php /* Update WP Options */
	                            $fonts_option = get_option('up_themes_'.UPTHEMES_SHORT_NAME.'_fonts');
	                            $custom_fonts_option = get_option('up_themes_'.UPTHEMES_SHORT_NAME.'_custom_fonts');
	                            $new_font[$value['id']][$option['font']] = array(
	                                'selector' => $option['selector'],
	                                'lineheight' => $option['lineheight'],
	                                'fontstyle' => $option['fontstyle'],
	                                'fontweight' => $option['fontweight'],
	                                'textshadow' => $option['textshadow'],
	                                'textdecoration' => $option['textdecoration'],
	                                'texttransform' => $option['texttransform'],
	                                'letterspacing' => $option['letterspacing'],
	                                'fontsize' => $option['fontsize'],
	                                'id' => $value['id']
	                            );
	                            /* Check For Hardcoded Fonts */
	                            if(!$value['custom']):
	                                if(is_array($fonts_option)):
	                                    $fonts_option = array_merge($fonts_option, $new_font);
	                                    delete_option('up_themes_'.UPTHEMES_SHORT_NAME.'_fonts');
	                                    update_option('up_themes_'.UPTHEMES_SHORT_NAME.'_fonts', $fonts_option);
	                                else:
	                                    update_option('up_themes_'.UPTHEMES_SHORT_NAME.'_fonts', $new_font);
	                                endif;

	                            /* Check For Dynamic User Fonts */
	                            else:
	                                if(is_array($custom_fonts_option)):
	                                    $custom_fonts_option = array_merge($custom_fonts_option, $new_font);
	                                    delete_option('up_themes_'.UPTHEMES_SHORT_NAME.'_custom_fonts');
	                                    update_option('up_themes_'.UPTHEMES_SHORT_NAME.'_custom_fonts', $custom_fonts_option);
	                                else:
	                                    update_option('up_themes_'.UPTHEMES_SHORT_NAME.'_custom_fonts', $new_font);
	                                endif;
	                            endif;
	                        else:?>
	                            <p><?php _e('No fonts registered yet', 'upfw');?></p>
	                        <?php endif;?>
						</div>
	
						<div class="field">
                            <label class="font-label" for="<?php echo $value['id']; ?>_fontweight"><?php _e('Font Weight','upfw'); ?></label>
	                        <select id="<?php echo $value['id']; ?>_fontweight" name="<?php echo $value['id']; ?>[fontweight]">
                                <?php $weights = $fonts[$family]['weights'];
                                if(!$weights):?>
                                    <option value="normal" <?php if($fontweight=='normal') echo "selected";?>><?php _e("Normal","upfw"); ?></option>
                                    <!--<option value="bold" <?php //if($fontweight=='bold') echo "selected";?>><?php //_e("Bold","upfw"); ?></option>-->
                                <?php elseif(is_array($weights)): ?>
                                    <?php foreach($weights as $name => $weight):?>
                                        <option value="<?php echo $weight;?>" <?php if($fontweight==$weight) echo "selected";?>><?php echo $name;?></option>
                                    <?php endforeach;?>
                                <?php endif;?>
	                        </select>
	                        <kbd><?php _e("Please Note: Some fonts do not have additional weights. In many cases, the below preview will not accurately reflect font weight."); ?></kbd>
						</div>
					
						<div class="field">
	                        <label class="font-label" for="<?php echo $value['id']; ?>_textshadow"><?php _e('Text Shadow','upfw'); ?></label>
	                        <select id="<?php echo $value['id']; ?>_textshadow" name="<?php echo $value['id']; ?>[textshadow]">
	                            <option value="none" <?php if($textshadow=='none') echo "selected";?>><?php _e("No Shadow","upfw"); ?></option>
	                            <option value="3px 3px 0 rgba(0,0,0,0.1)" <?php if($textshadow=='3px 3px 0 rgba(0,0,0,0.1)') echo "selected";?>><?php _e("Style #1","upfw"); ?></option>
	                            <option value="1px 1px 4px rgba(0,0,0,0.3)" <?php if($textshadow=='1px 1px 4px rgba(0,0,0,0.3)') echo "selected";?>><?php _e("Style #2","upfw"); ?></option>
	                            <option value="0 1px 0 rgba(255,255,255,1)" <?php if($textshadow=='0 1px 0 rgba(255,255,255,1)') echo "selected";?>><?php _e("Style #3","upfw"); ?></option>
	                            <option value="2px -2px 0 rgba(0,0,0,0.2)" <?php if($textshadow=='2px -2px 0 rgba(0,0,0,0.2)') echo "selected";?>><?php _e("Style #4","upfw"); ?></option>
	                            <option value="-2px 2px 0 rgba(0,0,0,0.2)" <?php if($textshadow=='-2px 2px 0 rgba(0,0,0,0.2)') echo "selected";?>><?php _e("Style #5","upfw"); ?></option>
	                            <option value="3px 0 0 rgba(0,0,0,0.2)" <?php if($textshadow=='3px 0 0 rgba(0,0,0,0.2)') echo "selected";?>><?php _e("Style #6","upfw"); ?></option>
	                            <option value="0 2px 0 rgba(0,0,0,0.2)" <?php if($textshadow=='0 2px 0 rgba(0,0,0,0.2)') echo "selected";?>><?php _e("Style #7","upfw"); ?></option>
	                        </select>
	                        <kbd><?php _e("Please Note: Text shadow is an advanced CSS property. Not all browsers will see it."); ?></kbd>
						</div>

					</fieldset>
					
					<fieldset>
											
						<div class="field">
	                        <label class="font-label" for="<?php echo $value['id']; ?>_texttransform"><?php _e('Text Transform','upfw'); ?></label>
	                        <select id="<?php echo $value['id']; ?>_texttransform" name="<?php echo $value['id']; ?>[texttransform]">
	                            <option value="none"><?php _e("None","upfw"); ?></option>
	                            <option value="uppercase" <?php if($texttransform=='uppercase') echo "selected";?>><?php _e("uppercase","upfw"); ?></option>
	                            <option value="lowercase" <?php if($texttransform=='lowercase') echo "selected";?>><?php _e("lowercase","upfw"); ?></option>
	                            <option value="capitalize" <?php if($texttransform=='capitalize') echo "selected";?>><?php _e("capitalize","upfw"); ?></option>
	                        </select>
						</div>
						
						<div class="field">
	                        <label class="font-label" for="<?php echo $value['id']; ?>_fontstyle"><?php _e('Font Style','upfw'); ?></label>
	                        <select id="<?php echo $value['id']; ?>_fontstyle" name="<?php echo $value['id']; ?>[fontstyle]">
	                            <option value="normal"><?php _e("Normal","upfw"); ?></option>
	                            <option value="italic" <?php if($fontstyle=='italic') echo "selected";?>><?php _e("Italic","upfw"); ?></option>
	                            <option value="oblique" <?php if($fontstyle=='oblique') echo "selected";?>><?php _e("Oblique","upfw"); ?></option>
	                        </select>
						</div>
						
	                   <div class="field">
	                        <label class="font-label" for="<?php echo $value['id']; ?>_textdecoration"><?php _e('Text Decoration','upfw'); ?></label>
	                        <select id="<?php echo $value['id']; ?>_textdecoration" name="<?php echo $value['id']; ?>[textdecoration]">
	                            <option value="none"><?php _e("None","upfw"); ?></option>
	                            <option value="underline"<?php if($textdecoration=='underline') echo "selected";?>><?php _e("underline","upfw"); ?></option>
	                            <option value="line-through"<?php if($textdecoration=='line-through') echo "selected";?>><?php _e("line-through","upfw"); ?></option>
	                            <option value="overline" <?php if($textdecoration=='overline') echo "selected";?>><?php _e("overline","upfw"); ?></option>
	                        </select>
						</div>
						
	                    <div class="field">
	                        <label class="font-label" for="<?php echo $value['id']; ?>_fontsize"><?php _e('Font Size','upfw'); ?>: <span class="<?php echo $value['id']; ?>_font_size_preview"><?php echo $fontsize;?></span></label>
	                        <div id="<?php echo $value['id'];?>-font-size" class="font-slider"></div>
	                        <input type="hidden" id="<?php echo $value['id']; ?>_fontsize" name="<?php echo $value['id']; ?>[fontsize]" value="<?php echo $fontsize;?>">
	                    </div>
	
	                    <div class="field">
	                        <label class="font-label" for="<?php echo $value['id']; ?>_lineheight"><?php _e('Line Height','upfw'); ?>: <span class="<?php echo $value['id']; ?>_line_height_preview"><?php echo $lineheight;?></span></label>
	                        <div id="<?php echo $value['id'];?>-line-height" class="font-slider"></div>
	                        <input type="hidden" id="<?php echo $value['id']; ?>_lineheight" name="<?php echo $value['id']; ?>[lineheight]" value="<?php echo $lineheight;?>">
	                    </div>
	                    
	                    <div class="field">
	                        <label class="font-label" for="<?php echo $value['id']; ?>_letterspacing"><?php _e('Letter Spacing','upfw'); ?>: <span class="<?php echo $value['id']; ?>_letter_spacing_preview"><?php echo $letterspacing;?></span></label>
	                        <div id="<?php echo $value['id'];?>-letter-spacing" class="font-slider"></div>
	                        <input type="hidden" id="<?php echo $value['id']; ?>_letterspacing" name="<?php echo $value['id']; ?>[letterspacing]" value="<?php echo $letterspacing;?>">
	                    </div>

					</fieldset>

                </div>
            </div>
        </fieldset>

        <div class="font-preview">
            <label class="font-preview-label"><?php _e('Preview', 'upfw');?></label>
            <div id="font-preview" class="<?php echo $value['id']; ?>_type_preview" style="font-family:<?php echo $up_fonts['library'][$family]['font_family']; ?>; font-size: <?php echo $fontsize; ?>; font-style: <?php echo $fontstyle; ?>; letter-spacing: <?php echo $letterspacing;?>; line-height:<?php echo $lineheight;?>; text-transform:<?php echo $texttransform;?>; text-decoration:<?php echo $textdecoration;?>; font-weight:<?php echo $fontweight;?>; text-shadow:<?php echo $textshadow; ?>; -moz-text-shadow:<?php echo $textshadow; ?>; -webkit-text-shadow:<?php echo $textshadow; ?>;  "><?php _e('Pack my box with five dozen liquor jugs.', 'upfw');?><br /><?php _e('The quick brown fox jumps over the lazy dog. ', 'upfw');?></div>
        </div>
        
        <script type="text/javascript">
            jQuery(function($){
                
                /* Font Size Slider */
                $( "#<?php echo $value['id'];?>-font-size" ).slider({
                        range: "min",
                        value: <?php echo str_replace('px', '', $fontsize);?>,
                        min: 7,
                        max: 59,
                        slide: function( event, ui ) {
                            $("#<?php echo $value['id']; ?>_fontsize").val(ui.value+"px");
                            $(".<?php echo $value['id']; ?>_type_preview").css('font-size',ui.value+"px");
                            $(".<?php echo $value['id']; ?>_font_size_preview").text(ui.value+"px");
                            $('.button-zone').addClass('formChanged');
                            $('.button-zone button').addClass('save-me-fool');
                            $('.formState').fadeIn( 400 );
                        }
                });
                
                /* Line Height Slider */
                $( "#<?php echo $value['id'];?>-line-height" ).slider({
                        range: "min",
                        value: <?php echo str_replace('px', '', $lineheight);?>,
                        min: 7,
                        max: 79,
                        slide: function( event, ui ) {
                            $("#<?php echo $value['id']; ?>_lineheight").val(ui.value+"px");
                            $(".<?php echo $value['id']; ?>_type_preview").css('line-height',ui.value+"px");
                            $(".<?php echo $value['id']; ?>_line_height_preview").text(ui.value+"px");
                            $('.button-zone').addClass('formChanged');
                            $('.button-zone button').addClass('save-me-fool');
                            $('.formState').fadeIn( 400 );
                        }
                });
                
                /* Letter Spacing Slider */
                $( "#<?php echo $value['id'];?>-letter-spacing" ).slider({
                        range: "min",
                        value: <?php echo str_replace('px', '', $letterspacing);?>,
                        min: -5,
                        max: 20,
                        slide: function( event, ui ) {
                            $("#<?php echo $value['id']; ?>_letterspacing").val(ui.value+"px");
                            $(".<?php echo $value['id']; ?>_type_preview").css('letter-spacing',ui.value+"px");
                            $(".<?php echo $value['id']; ?>_letter_spacing_preview").text(ui.value+"px");
                            $('.button-zone').addClass('formChanged');
                            $('.button-zone button').addClass('save-me-fool');
                            $('.formState').fadeIn( 400 );
                        }
                });

                /* Font Family */
                $("#<?php echo $value['id']; ?>_font").live('change', function(e){
                	
                	_$this = $(this);
                	family = _$this.val();
                	
                	$.getJSON(ajaxurl+"?action=get_font_weight_options&font="+family, function(data){
	                	
						if(data.success && data.html){
							$("#<?php echo $value['id']; ?>_fontweight").html(data.html);

		                    var stylesheet = _$this.find(':selected')[0].title;
		                    var selector = _$this.find(':selected')[0].id;
		                    var link = $('.<?php echo $value['id']; ?>-import-style');
		                    if(link){
		                        link.attr('href', stylesheet+':'+$("#<?php echo $value['id']; ?>_fontweight").val());
		                    }else{
		                        $('head').append('<link class="<?php echo $value['id']; ?>-import-style" rel="stylesheet" type="text/css" href="'+stylesheet+'" />');
		                    }
		                    $(".<?php echo $value['id']; ?>_type_preview").css('font-family', selector);
                		}

                	});
                
					
                });
                
                /* Font Weight Change */
                $("#<?php echo $value['id']; ?>_fontweight").live('change', function(e){
                    var stylesheet = $("#<?php echo $value['id']; ?>_font").find(':selected')[0].title;
                    stylesheet = stylesheet+':'+$(this).val();
                    var selector = $("#<?php echo $value['id']; ?>_font").find(':selected')[0].id;
                    var link = $('.<?php echo $value['id']; ?>-import-style');
                    if(link){
                        link.attr('href', stylesheet);
                    }else{
                        $('head').append('<link class="<?php echo $value['id']; ?>-import-style" rel="stylesheet" type="text/css" href="'+stylesheet+'" />');
                    }
                    $(".<?php echo $value['id']; ?>_type_preview").css('font-family', selector);
                });                

                /* Font Weight */
                $("#<?php echo $value['id']; ?>_fontweight").live('change', function(e){
                    var selector = $(this).find(':selected')[0].value;
                    $(".<?php echo $value['id']; ?>_type_preview").css('font-weight', selector);
                });

                /* Font Style */
                $("#<?php echo $value['id']; ?>_fontstyle").live('change', function(e){
                    var selector = $(this).find(':selected')[0].value;
                    $(".<?php echo $value['id']; ?>_type_preview").css('font-style', selector);
                });

                /* Text Shadow */
                $("#<?php echo $value['id']; ?>_textshadow").live('change', function(e){
                    var selector = $(this).find(':selected')[0].value;
                    $(".<?php echo $value['id']; ?>_type_preview").css('text-shadow', selector);
                    $(".<?php echo $value['id']; ?>_type_preview").css('-moz-text-shadow', selector);
                    $(".<?php echo $value['id']; ?>_type_preview").css('-webkit-text-shadow', selector);
                });

                /* Text Decoration */
                $("#<?php echo $value['id']; ?>_textdecoration").live('change', function(e){
                    var selector = $(this).find(':selected')[0].value;
                    $(".<?php echo $value['id']; ?>_type_preview").css('text-decoration', selector);
                });

                /* Text Transform */
                $("#<?php echo $value['id']; ?>_texttransform").live('change', function(e){
                    var selector = $(this).find(':selected')[0].value;
                    $(".<?php echo $value['id']; ?>_type_preview").css('text-transform', selector);
                });
                
                /* Queue Up Stylesheet */
                $('head').append('<link class="<?php echo $value['id']; ?>-import-style" rel="stylesheet" type="text/css" href="'+$("#<?php echo $value['id']; ?>_font").find(':selected')[0].title+'" />');
                
                /* Queue Up Font Preview */
                $(".<?php echo $value['id']; ?>_type_preview").css('font-family', $("#<?php echo $value['id']; ?>_font").find(':selected')[0].id);
            });
        </script>
        <div class="clear"></div>
    </li>

<?php
}

function upfw_style($value,$attr){
    global $up_options,$wpdb; ?>

                        <li class="type-<?php echo $value['type'];?>" id="container-<?php echo $value['id'];?>">
                            <fieldset class="title">
                                <div class="inner">
                                    <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                                    <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                                </div>
                            </fieldset>
                            
                            <fieldset class="data">
                                <div class="inner">
                                    <script type="text/javascript">
                                        jQuery(function($){
                                            $('.up-style-<?php echo $value['id'];?>').click(function(){
                                                $('#style-<?php echo $value['id'];?>').val($(this).attr('rel'));
                                                $('.up-style-<?php echo $value['id'];?>').removeClass('up-style-active');
                                                $(this).addClass('up-style-active');
                                                return false;
                                            });
                                        });
                                    </script>
                                    <input type="hidden" id="style-<?php echo $value['id'];?>" name="<?php echo $value['id'];?>" value="<?php echo $up_options->$value['id'];?>" />
                                    <div class="up-style-container">
                                        <?php global $up_styles;
                                        if(is_array($up_styles)):
                                            delete_option('up_themes_'.UPTHEMES_SHORT_NAME.'_styles');
                                            foreach($up_styles as $up_style):
                                                global $up_options;
                                                $selected = ($up_options->$value['id'] == $up_style['style']) ? ' up-style-active' : '';
                                                if($selected):
                                                    //Add layout to enqueue_theme_layout()
                                                    $context = $value['context'] ? $value['context'] : 'global';
                                                    $styles = get_option('up_themes_'.UPTHEMES_SHORT_NAME.'_styles');
                                                    $style[$context] = array('id' => $up_style['id']);
                                                    if(is_array($styles)):
                                                        $styles = array_merge($styles, $style);
                                                        update_option('up_themes_'.UPTHEMES_SHORT_NAME.'_styles', $styles);
                                                    else:
                                                        update_option('up_themes_'.UPTHEMES_SHORT_NAME.'_styles', $style);
                                                    endif;
                                                endif;?>
                                                <a class="up-style up-style-<?php echo $value['id'].$selected;?>" href="<?php echo $up_style['style'];?>" rel="<?php echo $up_style['style'];?>"><span><em><?php echo $up_style['name'];?></em></span><img src="<?php echo $up_style['image'];?>" alt="<?php echo $up_style['name'];?>" id="<?php echo $up_style['id'];?>" /></a>
                                            <?php endforeach;
                                        else:
                                            echo "<p>".__('No styles are registered yet for this theme.', 'upfw')."</p>";
                                        endif;?>
                                    </div>
                                </div>
                            </fieldset>
		                    <div class="clear"></div>
                        </li>
                        
<?php
}

function upfw_layouts($value,$attr){
    global $up_options,$wpdb, $up_layouts; 
?>

                <li class="type-<?php echo $value['type'];?>" id="container-<?php echo $value['id'];?>">
                    <fieldset class="title">
                        <div class="inner">
                            <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
                            <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                        </div>
                    </fieldset>
                    
                    <fieldset class="data">
                        <div class="inner">
                            <script type="text/javascript">
                                jQuery(function($){
                                    $('.up-layout-<?php echo $value['id'];?>').click(function(){
                                        $('#layout-<?php echo $value['id'];?>').val($(this).attr('rel'));
                                        $('.up-layout-<?php echo $value['id'];?>').removeClass('up-layout-active');
                                        $(this).addClass('up-layout-active');
                                        return false;
                                    });
                                });
                            </script>
                            <input type="hidden" id="layout-<?php echo $value['id'];?>" name="<?php echo $value['id'];?>" value="<?php echo $up_options->$value['id'];?>" />
                            <div class="up-layout-container">
                                <?php global $up_layouts;
                                //delete_option('up_themes_'.UPTHEMES_SHORT_NAME.'_layouts');

                                if( $up_layouts ):
                                foreach($up_layouts as $up_layout):
                                    $selected = ($up_options->$value['id'] == $up_layout['style']) ? ' up-layout-active' : '';
                                    if($selected):
                                        //Add layout to enqueue_theme_layout()
                                        $context = $value['context'] ? $value['context'] : 'global';
                                        $layouts = get_option('up_themes_'.UPTHEMES_SHORT_NAME.'_layouts');
                                        $layout[$context] = array('id' => $up_layout['id']);
                                        if(is_array($layouts)):
                                            $layouts = array_merge($layouts, $layout);
                                            update_option('up_themes_'.UPTHEMES_SHORT_NAME.'_layouts', $layouts);
                                        else:
                                            add_option('up_themes_'.UPTHEMES_SHORT_NAME.'_layouts', $layout);
                                        endif;
                                    endif;?>
                                    <a class="up-layout up-layout-<?php echo $value['id'].$selected;?>" href="<?php echo $up_layout['style'];?>" rel="<?php echo $up_layout['style'];?>"><span><em><?php echo $up_layout['name'];?></em></span><img src="<?php echo $up_layout['image'];?>" alt="<?php echo $up_layout['name'];?>" id="<?php echo $up_layout['id'];?>" /></a>
                                <?php endforeach;?>
                                <?php else: ?>
                                <?php _e('It appears there are no layouts available. Please check your theme options for a valid layout option.','upfw'); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </fieldset>
                    <div class="clear"></div>
                </li>
                
<?php
} ?>