<?php if ( is_user_logged_in() ):?>

	<?php $upthemes =  THEME_DIR.'/admin/';?>
	
	<script type="text/javascript">
	    var upThemes = "<?php echo THEME_DIR; ?>";
	</script>

	<div id="upthemes_framework" class="wrap">
	    
		<?php upfw_admin_header(); ?>
		
		<?php
		
		
		/* ----------------------- Form Security Check -------------------------- */
		if(isset($_POST['_wpnonce'])):
		    //Check if submitted from this domain
		    check_admin_referer();
		    //Verify Form Nonce
		    if (!wp_verify_nonce($_POST['_wpnonce'], 'save_upthemes') ) 
				wp_die('Security exception detected, please try again.');
		endif;
		
		/* ------------------Import/Export Functions ----------------------- */
		//Restore Previous Options
		global $export_message;
		if(isset($_POST['up_restore'])):
		    $current = get_option('up_themes_'.UPTHEMES_SHORT_NAME);
		    $backup = get_option('up_themes_'.UPTHEMES_SHORT_NAME.'_backup');
		    update_option('up_themes_'.UPTHEMES_SHORT_NAME.'_backup', $current);
		    update_option('up_themes_'.UPTHEMES_SHORT_NAME, $backup);
		    $export_message = "<p class='import'>" . __("Everything's back to normal now!","upfw") . "</p>";
		endif;
		
		//Restore Defaults
		
		if(isset($_POST['up_defaults'])):
		    $current = get_option('up_themes_'.UPTHEMES_SHORT_NAME);
		    update_option('up_themes_'.UPTHEMES_SHORT_NAME.'_backup', $current);
		    delete_option('up_themes_'.UPTHEMES_SHORT_NAME);
		    $export_message = "<p class='import'>" . __("Default options restored!","upfw") . "<span><form method='post' action=''><input class='up_restore' type='submit' name='up_restore' value='" . __("Click Here to Undo","upfw") . "'></form></span></p>";
		endif;
		
		
		/* ------------------------- Import Options Code ------------------------------- */
		if(isset($_POST['up_import']) && isset($_POST['up_import_code'])):
		    $import = $_POST['up_import_code'];
		    $import = base64_decode($import);
		    $import = explode('~~', $import);
		    
		    //Check if code is legitimate
		    if(count($import) == 2):
			$option_name = $import[0];
			$options = explode('||', $import[1]);
		
			foreach ($options as $option):
			    $option = explode('|', $option);
			    global $new_options;
			    $new_options[$option[0]] = preg_replace('/"/', '\'', stripslashes($option[1]));
			endforeach;
			$current_option = get_option($option_name);
			update_option($option_name.'_backup', $current_option);
			update_option($option_name, $new_options);
			$export_message = "<p class='import'>" . __("Options Code Import Successful!","upfw") . "<span><form method='post' action=''><input class='up_restore' type='submit' name='up_restore' value='" . __("Click Here to Undo","upfw") . "'></form></span></p>";
		    else:
			$export_message = "<p class='import'>" . __("Oops! Something went wrong. <span>Try pasting your import code again.</span>","upfw") . "</p>";
		    endif;

		endif;
	    
		/* ------------------------- Save Theme Options ------------------------------- */
		if(isset($_POST['up_save'])):
		
			$posts = $_POST;
			foreach($posts as $k => $v):
			    //Check if option is array (mulitple selects)
			    if(is_array($v)):
				//Check if array is empty
				$check = 0;
				foreach($v as $key => $value):    
				    if($value != ''):
					$check++;
				    endif;
				endforeach;
				//If array is not empty
				if($check > 0  ):
				    //Remove empty array elements
				    $post[$k] = array_filter($v);
				else:
				    $post[$k] = '';
				endif;
				$check = 0;
			    else:
				//Remove slashes
				$post[$k] = preg_replace('/"/', '\'', stripslashes($v));
			    endif;
			endforeach;
			//Add options array to wp_options table
			update_option('up_themes_'.UPTHEMES_SHORT_NAME, $post);
		    endif;
		
		/* ---------------------- Default Options Functions  ----------------- */
		global $default_check;
		global $default_options;
		
		$option_check = get_option('up_themes_'.UPTHEMES_SHORT_NAME);	
		if($option_check):
		    $default_check = true;
		else:
		    $default_check = false;
		endif;
		

		//Get all options from the database
		$up_options_db = get_option('up_themes_'.UPTHEMES_SHORT_NAME);
		global $up_options;
		//Check if options are stored properly
		if(is_array($up_options_db)):
		    //Check array to an object
		    foreach ($up_options_db as $k => $v) {
			$up_options -> {$k} = $v;
		    }
		endif;
		?>
    
		<form method="post" enctype="multipart/form-data" action="" id="theme-options" name="theme-options">
		    
		<?php //Security Nonce For Cross Site Hacking
        wp_nonce_field('save_upthemes','upfw'); ?>
		    
		<div class="button-zone-wrapper zone-top">
		    <div class="button-zone">
			<span class="top">
			    <span class="formState"><?php _e("Theme options have changed. Make sure to save.","upfw"); ?></span>
			    <button class="save" id="up_save" name="up_save" type="submit"><?php _e("Save Changes","upfw"); ?></button>
			    <button class="button" type="reset"><?php _e("Discard Changes","upfw"); ?></button>
			</span>
		    </div><!-- .button-zone -->
		</div><!-- /.button-zone-wrapper -->
		
		<div id="up_main">

				<div id="up_sidebar">
				
					<div id="up_nav" class="box">
						
						<ul>
							<?php //Create dynamic tab links from array
							global $up_tabs;
							    foreach ($up_tabs as $tab):
								foreach($tab as $title => $shortname):?>
								    <li class="<?php echo $shortname?>"><a href="#<?php echo $shortname?>"><?php echo $title?></a></li>
								<?php endforeach;
							    endforeach;?>
							<li class="import-export"><a href="#import-export"><?php _e("Import/Export","upfw"); ?></a></li>
						</ul>
									
					</div><!-- /#up_nav -->
				
				</div><!-- /#up_sidebar -->
				
				<div id="up_content">
					<div id="tabber">
					    <?php //Create dynamic tabs from array
					    foreach ($up_tabs as $order => $tab):
						foreach($tab as $title => $shortname):?>
						    <div id="<?php echo $shortname?>">
							<h3><?php echo $title?></h3>
							<ul class="feature-set">
							    <?php require_once (THEME_PATH . '/theme-options/'.$shortname.'_'.$order.'.php'); ?>
							</ul>										
						    </div><!-- /#<?php echo $shortname?> -->
						<?php endforeach;
					    endforeach;?>
					    </form>
					    <div id="import-export">
						<h3><?php _e("Import/Export","upfw"); ?></h3>
						<ul class="feature-set">
						    <?php require_once (THEME_PATH . '/admin/import-export.php'); ?>
						</ul>										
					    </div><!-- /#import-export -->
				
					</div><!-- /#tabber -->

				</div><!-- /#up_content -->
			
				<div class="clear"></div>

			<div class="button-zone-wrapper zone-bottom">
			    <div class="button-zone">
				<span class="top">
				    <span class="formState"><?php _e("Theme options have changed. Make sure to save.","upfw"); ?></span>
				    <button class="save" id="up_save" name="up_save" type="submit"><?php _e("Save Changes","upfw"); ?></button>
				    <button class="button" type="reset"><?php _e("Discard Changes","upfw"); ?></button>
				</span>
			    </div><!-- .button-zone -->
			</div><!-- /.button-zone-wrapper -->
			
			</div><!-- /#up_container -->
		
		<div id="up_footer">
		
			<ul>
				<li><?php echo UPTHEMES_NAME?> <?php _e("Version","upfw"); ?> <?php echo UPTHEMES_THEME_VER; ?></li>
				<li><?php _e("UpThemes Framework Version","upfw"); ?> <?php echo UPTHEMES_VER; ?></li>
			</ul>
		
		</div><!-- /#up_footer -->
		
		</form>
	
	</div><!-- /#upthemes_framework -->

<?php else: _e("You must be logged in to view this page","upfw"); endif;?>