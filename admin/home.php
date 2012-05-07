<?php if ( is_user_logged_in() ):?>

	<?php $upthemes =  THEME_DIR.'/admin/';?>
	
	<script type="text/javascript">
	    var upThemes = "<?php echo THEME_DIR; ?>";
	</script>

	<div id="upthemes_framework" class="wrap">
	    
		<?php upfw_admin_header(); ?>
    
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