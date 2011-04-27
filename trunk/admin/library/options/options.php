<?php

function upfw_text_field($value,$attr){ ?>

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
                </li>
                
                <?php $attr = '';

}

function upfw_text_list($value,$attr){ ?>

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
                                <p class="add_text_list"><a href="#">Add New Field</a></p>
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
                </li>
                
                <?php $attr = '';
}

function upfw_textarea($value,$attr){ ?>

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
                </li>
                
                <?php $attr = '';
}

function upfw_select($value,$attr){ ?>

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
                </li>
                
                <?php $attr = '';

}

function upfw_multiple($value,$attr){ ?>

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
                </li>
                
                <?php $attr = '';

}

function upfw_checkbox($value,$attr){ ?>

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
                </li>
                
                <?php $attr = '';

}

function upfw_color($value,$attr){ ?>

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
                </li>
                
                <?php

}

function upfw_image($value,$attr){ ?>

                <script type="text/javascript">
                    jQuery(function(){
                        
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
                        
                        //Select and image from the gallery
                        jQuery('#<?php echo $value['id']; ?>allimages a').live('click', function(){
                            //Add image source to hidden input
                            jQuery('input#<?php echo $value['id']; ?>').attr('value', jQuery(this).attr('href'));
                            //Send image to preview
                            jQuery('#<?php echo $value['id']; ?>preview').html('<img src="'+jQuery(this).attr('href')+'" alt="<?php echo $value['id']; ?> Image" />');
                            activate_save_animation();
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
                                    jQuery('.thumbs').append('<a class="preview" href="<?php echo UPFW_UPLOADS_URL; ?>/'+$file+'"><img src="<?php echo UPFW_UPLOADS_URL; ?>/'+$file+'" /></a>')
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
                        elseif($value['url']):
                            echo "<img src='".$value['url']."' alt='Preview Image' />";
                        else:
                        	echo "<img src='".THEME_DIR."/admin/images/upfw_noimage.gif' alt='No Image Available' />";
                        endif;?></div>	
                        
                    </fieldset>

                    <fieldset class="data">
                            <div class="inner">
                                <div class="uploadify">
                                    <button type="button" id="<?php echo $value['id']; ?>" class="secondary" <?php echo $attr; ?>><?php echo $value['value']; ?></button>
                                    <span id="<?php echo $value['id']; ?>loader" class="loader"></span>
                                </div>

                                <!-- Hidden Input -->
                                <input type="hidden" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>" value="<?php if($up_options->$value['id']): echo $up_options->$value['id']; else: echo $value['url']; endif;?>" />

                                <!-- Upload Status Input -->
                                <div class="status hide" id="<?php echo $value['id']; ?>status"></div>

                                <!-- Divider -->
                                <div class="divider">
                                    <span>OR</span>
                                </div>

                                <!-- View Gallery -->
                                <div class="viewgallery">
                                    <a id="<?php echo $value['id']; ?>viewgallery" href="#">Select from the UpThemes Gallery</a>
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
                                            <p><em>Default Image</em></p>
                                            <a href="<?php echo $value['url']; ?>"><img src="<?php echo $value['url']; ?>" /></a>
                                        </div>
                                    <?php endif;?>
                                </div>

                            </div>
                    </fieldset>
                </li>
            <?php

}

function upfw_category($value,$attr){  

	global $wpdb; ?>

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
                </li>
                
                <?php $attr = '';

}

function upfw_categories($value,$attr){ 

	global $wpdb; ?>

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
                </li>
                
                <?php $attr = '';

}

function upfw_page($value,$attr){  

	global $wpdb; ?>

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
                </li>
                
                <?php $attr = '';
}

function upfw_pages($value,$attr){  

	global $wpdb; ?>

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
                </li>
                <?php $attr = '';

}

function upfw_submit($value,$attr){ ?>

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
                </li>
                
                <?php $attr = '';

}

function upfw_taxonomy($value,$attr){  

	global $wpdb; ?>

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
                </li>
                
                <?php $attr = '';

}

function upfw_button($value,$attr){ ?>

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
                </li>
                
                <?php $attr = '';
}

function upfw_divider($value,$attr){ ?>

                <li class="type-<?php echo $value['type'];?>" id="container-<?php echo $value['id'];?>">
                    <h2 class="tight"><?php echo $value['name']; ?></h2>
                </li>

                <?php $attr = '';

}

function upfw_typography($value,$attr){ ?>

                <li class="type-<?php echo $value['type'];?> typography" id="<?php echo $value['id']; ?> container-<?php echo $value['id']; ?>">
                    <fieldset class="title">
                        <div class="inner">
                            <label><?php echo $value['name']; ?></label>
                            <?php if($value['desc']): ?><kbd><?php echo $value['desc']; ?></kbd><?php endif;?>
                        </div>
                    </fieldset>
                    
                    <fieldset class="data">
                        <div class="inner">
                        
                        	<fieldset class="type_fields">
                        	
                            	<?php 
                            	
                            	$font_family = $value['id']."_font";
                            	$font_size   = $value['id']."_fontsize";
                            	$font_lineheight  = $value['id']."_lineheight";
                            	
                            	$font  = $up_options->$font_family;
                            	$size  = $up_options->$font_size;
                            	$lineheight = $up_options->$font_lineheight;
                            	
                            	?>

                                <label for="<?php echo $value['id']; ?>_font"><?php _e('Font Family','upfw'); ?></label>
                                <select id="<?php echo $value['id']; ?>_font" name="<?php echo $value['id']; ?>_font">
                                	<option value="Arial" <?php if( $font == 'Arial' ) echo 'selected'; ?>>Arial</option>
                                	<option value="Helvetica" <?php if( $font == 'Helvetica' ) echo 'selected'; ?>>Helvetica</option>
                                	<option value="Georgia" <?php if( $font == 'Georgia' ) echo 'selected'; ?>>Georgia</option>
                                	<option value="Times New Roman" <?php if( $font == 'Times New Roman' ) echo 'selected'; ?>>Times New Roman</option>
                                	<option value="Impact" <?php if( $font == 'Impact' ) echo 'selected'; ?>>Impact</option>
                                	<option value="Palatino" <?php if( $font == 'Palatino' ) echo 'selected'; ?>>Palatino</option>
                                	<option value="Courier New" <?php if( $font == 'Courier New' ) echo 'selected'; ?>>Courier New</option>
                                </select>

                                <label for="<?php echo $value['id']; ?>_fontsize"><?php _e('Font Size','upfw'); ?></label>
                                <select id="<?php echo $value['id']; ?>_fontsize" name="<?php echo $value['id']; ?>_fontsize">
                                	<?php
                                	$firstnumber = 7;
                                	$secondnumber = 60;
                                	while($firstnumber < $secondnumber):
                                		echo '<option value="'. $firstnumber .'px"';
                                		if( $firstnumber."px" == $size )
	                                		echo 'selected';
	                                	echo '>'. $firstnumber .'px</option>';
                                		$firstnumber++;
                                	endwhile;
                                	?>
                                </select>
                                
                                <label for="<?php echo $value['id']; ?>_lineheight"><?php _e('Line Height','upfw'); ?></label>
                                <select id="<?php echo $value['id']; ?>_lineheight" name="<?php echo $value['id']; ?>_lineheight">
                                	<?php
                                	$firstnumber = 7;
                                	$secondnumber = 80;
                                	while($firstnumber < $secondnumber):
                                		echo '<option value="'. $firstnumber .'px"';
                                		if( $firstnumber."px" == $lineheight )
	                                		echo 'selected';
	                                	echo '>'. $firstnumber .'px</option>';
                                		$firstnumber++;
                                	endwhile;
                                	?>
                                </select>
                                
                            </fieldset>
                            
                        </div>

                    </fieldset>

                <div class="type_preview" style="font-family:<?php echo $font; ?>; font-size: <?php echo $size; ?>; font-family:<?php echo $font; ?>; ">AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz</div>

                    <script type="text/javascript">
                    
                        jQuery("#<?php echo $value['id']; ?>_size").change(function(e){
                            jQuery("#<?php echo $value['id']; ?> .type_preview").css('font-size',jQuery(this).val());
                        });
                        jQuery("#<?php echo $value['id']; ?>_font").change(function(e){
                            jQuery("#<?php echo $value['id']; ?> .type_preview").css('font-family','"'+jQuery(this).val()+'"');
                        });

                        jQuery("#<?php echo $value['id']; ?>_lineheight").change(function(e){
                            jQuery("#<?php echo $value['id']; ?> .type_preview").css('line-height','"'+jQuery(this).val()+'"');
                        });

                    </script>

                </li>
                
                <?php $attr = '';
}

function upfw_layouts($value,$attr){ ?>

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
                                delete_option('up_themes_'.UPTHEMES_SHORT_NAME.'_layouts');

                                if( $up_layouts ):

                                foreach($up_layouts as $up_layout):
                                    global $up_options;
                                    $selected = ($up_options->$value['id'] == $up_layout['style']) ? ' up-layout-active' : '';
                                    if($selected):
                                        //Add layout to enqueue_theme_layout()
                                        $context = $value['context'] ? $value['context'] : 'global';
                                        $layouts = get_option('up_themes_'.UPTHEMES_SHORT_NAME.'_layout');
                                        $layout[$context] = array('id' => $up_layout['id']);
                                        if(is_array($layouts)):
                                            $layouts = array_merge($layouts, $layout);
                                            update_option('up_themes_'.UPTHEMES_SHORT_NAME.'_layouts', $layouts);
                                        else:
                                            update_option('up_themes_'.UPTHEMES_SHORT_NAME.'_layouts', $layout);
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
                </li>
                
                <?php $attr = '';

}