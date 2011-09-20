<?php

function up_seo_init(){
    /* Content Type, Description, Robots, Keywords */
    echo up_content_type();
    echo up_robots();
    echo up_description();
    echo up_keywords();
}
if( ! defined( 'DISABLE_UP_SEO' ) )
    add_action('up_seo', 'up_seo_init');


/* Check for SEO Plugins */
function up_seo_third_parties(){
	if ( ! defined( 'UP_SEO_THIRD_PARTY' ) ) define( 'UP_SEO_THIRD_PARTY', false );
    if( class_exists( 'All_in_One_SEO_Pack' ) || class_exists( 'Headspace_Plugin' ) ) return true;
}
add_action( 'init', 'up_seo_third_parties' );

/* Theme Options */
function up_seo_default_options() {
    
    if ( up_seo_third_parties() )echo "<kbd>".__('We detected that a third-party SEO plugin has been activated. UpThemes SEO has been automatically disabled to avoid any conflict.', 'upfw')."</kbd>";
    
    $options = array (
        
            array(	"name" => __('Disable the UpThemes SEO?','upfw'),
                            "desc" => __("Useful when using third-party plugins.",'upfw'),
                            "id" => "seo_disable",
                            "value" => "",
                            "type" => "select",
                            "default_text" => __('No', 'upfw'),
                            "options" => array(
                                __('Yes', 'upfw')=> '1'
                            )
            ),
            
            array(	"name" => __('Hide Custom Fields?','upfw'),
                            "desc" => __("If enabled, UpThemes SEO options will not be displayed on posts/pages.",'upfw'),
                            "id" => "seo_hide_custom_fields",
                            "value" => "",
                            "type" => "select",
                            "default_text" => __('No', 'upfw'),
                            "options" => array(
                                __('Yes', 'upfw')=> '1'
                            )
            ),
            
            array(	"name" => __('Homepage Title','upfw'),
                            "desc" => __("This will override your standard homepage title.",'upfw'),
                            "id" => "seo_homepage_title",
                            "value" => "",
                            "type" => "text"
            ),
            
            array(	   "name" => __('Homepage Title Layout','upfw'),
                            "desc" => __("Choose your desired title layout for the home page.",'upfw'),
                            "id" => "seo_home_title_layout",
                            "type" => "select",
                            "default_text" => __('Default', 'upfw'),
                            "options" => array(
                                __('Blog Title | Description', 'upfw')=> '%BLOG% %DESC%',
                                __('Blog Title', 'upfw')=> '%BLOG%',
                                __('Description', 'upfw')=> '%DESC%',
                                __('Homepage Title', 'upfw')=> '%TITLE%',
                                __('Homepage Title | Description', 'upfw')=> '%TITLE% %DESC%'
                            )
            ),
            
            array(	"name" => __('Homepage Meta Keywords','upfw'),
                            "desc" => __("Enter keywords or phrases separated by commas (ex. shoes, cars, dogs, world domination).",'upfw'),
                            "id" => "seo_homepage_keywords",
                            "value" => "",
                            "type" => "text"
            ),
            array(	"name" => __('Homepage Meta Description','upfw'),
                            "desc" => __("Enter the meta description you want to have appear on the homepage (ex. This is my shop where I sell things like shoes, cars, dogs and I am trying to achieve world domination).",'upfw'),
                            "id" => "seo_homepage_description",
                            "value" => "",
                            "type" => "textarea"
            ),
            
            array(	"name" => __('Singular Meta Keywords','upfw'),
                            "desc" => __("Enter keywords or phrases separated by commas (ex. shoes, cars, dogs, world domination).",'upfw'),
                            "id" => "seo_singular_keywords",
                            "value" => "",
                            "type" => "text"
            ),

            array(	"name" => __('Singular Meta Description','upfw'),
                            "desc" => __("Enter the meta description you want to have appear on the posts and pages (ex. This is my shop where I sell things like shoes, cars, dogs and I am trying to achieve world domination).",'upfw'),
                            "id" => "seo_singular_description",
                            "value" => "",
                            "type" => "textarea"
            ),


            
            array(	"name" => __('Page Title Layout','upfw'),
                            "desc" => __("Choose your desired title layout for pages.",'upfw'),
                            "id" => "seo_page_title_layout",
                            "type" => "select",
                            "default_text" => __('Default', 'upfw'),
                            "options" => array(
                                __('Title | Blog Title | Description', 'upfw')=> '%TITLE% %BLOG% %DESC%',
                                __('Blog Title | Title | Description', 'upfw')=> '%BLOG% %TITLE% %DESC%',
                                __('Title | Blog Title', 'upfw')=> '%TITLE% %BLOG%',
                                __('Title | Description', 'upfw')=> '%TITLE% %DESC%',
                                __('Title', 'upfw')=> '%TITLE%'
                            )
            ),
            
            array(	"name" => __('Single Post Title Layout','upfw'),
                            "desc" => __("Choose your desired title layout for single posts.",'upfw'),
                            "id" => "seo_single_title_layout",
                            "type" => "select",
                            "default_text" => __('Default', 'upfw'),
                            "options" => array(
                                __('Title | Blog Title | Description', 'upfw')=> '%TITLE% %BLOG% %DESC%',
                                __('Blog Title | Title | Description', 'upfw')=> '%BLOG% %TITLE% %DESC%',
                                __('Title | Blog Title', 'upfw')=> '%TITLE% %BLOG%',
                                __('Title | Description', 'upfw')=> '%TITLE% %DESC%',
                                __('Title', 'upfw')=> '%TITLE%'
                            )
            ),
            
            array(	"name" => __('Archive Title Layout','upfw'),
                            "desc" => __("Choose your desired title layout for archives (categories, tags, etc).",'upfw'),
                            "id" => "seo_archive_title_layout",
                            "type" => "select",
                            "default_text" => __('Default', 'upfw'),
                            "options" => array(
                                __('Archive Name | Page Number | Blog Title | Description', 'upfw')=> '%ARCHIVE% %PAGED% %BLOG% %DESC%',
                                __('Page Number | Archive Name | Blog Title | Description', 'upfw')=> '%PAGED% %ARCHIVE% %BLOG% %DESC%',
                                __('Page Number | Archive Name | Blog Title', 'upfw')=> '%PAGED% %ARCHIVE% %BLOG%',
                                __('Archive Name | Page Number | Blog Title', 'upfw')=> '%ARCHIVE% %PAGED% %BLOG%',
                                __('Archive Name | Blog Title | Description', 'upfw')=> '%ARCHIVE% %BLOG% %DESC%',
                                __('Blog Title | Archive Name | Description', 'upfw')=> '%BLOG% %ARCHIVE% %DESC%',
                                __('Archive Name | Blog Title', 'upfw')=> '%ARCHIVE% %BLOG%',
                                __('Archive Name | Description', 'upfw')=> '%ARCHIVE% %DESC%',
                                __('Archive Name', 'upfw')=> '%ARCHIVE%'
                            )
            ),
            
            array(	"name" => __('Search Results Title Layout','upfw'),
                            "desc" => __("Choose your desired title layout for search results.",'upfw'),
                            "id" => "seo_search_title_layout",
                            "type" => "select",
                            "default_text" => __('Default', 'upfw'),
                            "options" => array(
                                __('Search Results | Page Number | Blog Title | Description', 'upfw')=> '%SEARCH% %PAGED% %BLOG% %DESC%',
                                __('Page Number | Search Results | Blog Title | Description', 'upfw')=> '%PAGED% %SEARCH% %BLOG% %DESC%',
                                __('Page Number | Search Results | Blog Title', 'upfw')=> '%PAGED% %SEARCH% %BLOG%',
                                __('Search Results | Page Number | Blog Title', 'upfw')=> '%SEARCH% %PAGED% %BLOG%',
                                __('Search Results | Blog Title | Description', 'upfw')=> '%SEARCH% %BLOG% %DESC%',
                                __('Blog Title | Search Results | Description', 'upfw')=> '%BLOG% %SEARCH% %DESC%',
                                __('Search Results | Blog Title', 'upfw')=> '%SEARCH% %BLOG%',
                                __('Search Results | Description', 'upfw')=> '%SEARCH% %DESC%',
                                __('Search Results', 'upfw')=> '%SEARCH%'
                            )
            ),
            array(	"name" => __('Title Separator','upfw'),
                            "desc" => __("Enter a character or HTML entity.",'upfw'),
                            "id" => "seo_separator",
                            "value" => "",
                            "type" => "text"
            ),
            array(	"name" => __('Add a \'follow\' meta tag for all posts?','upfw'),
                            "desc" => __("By default, a 'nofollow' meta tag is placed on all pages and posts. You can override this setting on pages/posts.",'upfw'),
                            "id" => "seo_follow",
                            "value" => "",
                            "type" => "select",
                            "default_text" => __('No', 'upfw'),
                            "options" => array(
                                __('Yes', 'upfw')=> '1'
                            )
            ),
            array(	"name" => __('Indexing','upfw'),
                            "desc" => __("Select which contexts are not indexed by search engines",'upfw'),
                            "id" => "seo_index",
                            "value" => "",
                            "type" => "checkbox",
                            "options" => array(
                                __('Category', 'upfw')=> 'category',
                                __('Search', 'upfw')=> 'search',
                                __('Author', 'upfw')=> 'author',
                                __('Date', 'upfw')=> 'date',
                                __('Tag', 'upfw')=> 'tag',
                                __('Pages', 'upfw')=> 'page',
                                __('Posts', 'upfw')=> 'post',
                                __('Home', 'upfw')=> 'home'
                            )
            )

    );
    
    return $options;
}

function up_privacy_check(){
    // Under SETTIGNS > PRIVACY in the WordPress backend
    if (get_option('blog_public') == 0 )return true;
}

function up_content_type(){
    return "\n".'<meta http-equiv="Content-Type" content="'. get_bloginfo('html_type') .'; charset='. get_bloginfo('charset') .'" />' . "\n";
}

function up_robots(){
    /* Robots */
    if(up_privacy_check())return;
    if(up_seo_third_parties())return;
    global $up_options;
    $index = 'index';
    $follow = 'nofollow';
    $context = $up_options->seo_index ? $up_options->seo_index : false;
    if ( is_category() && $context && in_array('category', $context) ) { $index = 'noindex'; }  
    elseif ( is_tag() && $context && in_array('tag', $context)) { $index = 'noindex'; }
    elseif ( is_search() && $context && in_array('search', $context) ) { $index = 'noindex'; }  
    elseif ( is_author() && $context && in_array('author', $context)) { $index = 'noindex'; }  
    elseif ( is_date() && $context && in_array('date', $context)) { $index = 'noindex'; }
    elseif ( is_page() && $context && in_array('page', $context)) { $index = 'noindex'; }
    elseif ( is_single() && $context && in_array('post', $context)) { $index = 'noindex'; }
    elseif ( (is_home() || is_front_page()) && $context && in_array('home', $context)) { $index = 'noindex'; }
    
    /* Global Settings */
    if($up_options->seo_follow)$follow = 'follow';
    
    /* Page / Single Post */
    $single_follow = get_post_meta(get_the_ID(),'_up_seo_follow',true);
    if(is_singular() && $single_follow == 'no')$follow = 'nofollow';
    if(is_singular() && $single_follow == 'yes')$follow = 'follow';
    
    return '<meta name="robots" content="'. $index .', '. $follow .'" />' . "\n";
}

function up_is_front_page(){
	global $wp_query;
	if (get_query_var('name') || !is_page())return;
	$page_on_front = get_option('page_on_front');
	if( is_page($page_on_front) ):
            $wp_query->is_front_page = true;
            return;
	endif;
} 
add_action( 'parse_query', 'up_is_front_page', 100, 1 );

function up_description(){
    global $post, $up_options;
    
    /* Description */
    if(up_privacy_check())return;
    if(up_seo_third_parties())return;
    
    /* Description */
    $description = '';
    $home_desc = $up_options->seo_homepage_description ? $up_options->seo_homepage_description : get_bloginfo('description');
    $singular_desc = $up_options->seo_singular_description ? $up_options->seo_singular_description : substr(preg_replace('/'.$pattern.'/s', '$1$6', $post->post_content), 0, 160);
    $customfield_desc = get_post_meta($post->ID,'_up_seo_description',true);
    
    if(is_home() || is_front_page()) $description = $home_desc;
    if(is_singular() && !is_home() && !is_front_page()) $description = $singular_desc;
    if($customfield_desc) $description = $customfield_desc;
    
    $description = html_entity_decode($description, ENT_QUOTES, 'UTF-8');
    $description = strip_tags(trim(stripslashes($description)));
    
    if(!empty($description)){
            echo '<meta name="description" content="'.$description.'" />' . "\n";
    }
}

function up_keywords(){
	
    /* Keywords */
    if(up_privacy_check())return;
    if(up_seo_third_parties())return;
    
    global $post, $wpdb, $up_options;    

    $keywords = '';
    
    $home_keywords = $up_options->seo_homepage_keywords;
    $singular_keywords = $up_options->seo_singular_keywords;
    $customfield_keywords = get_post_meta($post->ID,'_up_seo_keywords',true);

    if(is_home() || is_front_page()) $keywords = $home_keywords;
    if(is_single() && !$singular_keywords && !$customfield_keywords){
        $the_keywords = array(); 
        //Tags
        if(get_the_tags($post->ID)){ 
            foreach(get_the_tags($post->ID) as $tag) {
                $tag_name = $tag->name; 
                $the_keywords[] = strtolower($tag_name);
            }
        }
        //Cats
        if(get_the_category($post->ID)){ 
            foreach(get_the_category($post->ID) as $cat) {
                $cat_name = $cat->name; 
                $the_keywords[] = strtolower($cat_name);
            }
        }
        //Other Taxonomies
        $all_taxonomies = get_taxonomies();
        $addon_taxonomies = array();
        if(!empty($all_taxonomies)){
            foreach($all_taxonomies as $key => $taxonomies){
                if($taxonomies != 'category' AND 
                    $taxonomies != 'post_tag' AND 
                    $taxonomies != 'nav_menu' AND
                    $taxonomies != 'link_category'){
                    $addon_taxonomies[] = $taxonomies;
                }
            }
        }
        $addon_terms = array();
        if(!empty($addon_taxonomies)){
            foreach($addon_taxonomies as $taxonomies){
                $addon_terms[] = get_the_terms($post->ID, $taxonomies);
            }
        }
        if(!empty($addon_terms)){
            foreach($addon_terms as $addon){
                if(!empty($addon)){
                    foreach($addon as $term){
                        $the_keywords[] = strtolower($term->name);
                    }
                }
            }
        }
        $keywords = implode(",",$the_keywords);
    }
    if(is_singular() && !is_home() && !is_front_page() && $singular_keywords) $keywords = $singular_keywords;
    if($customfield_keywords) $keywords = $customfield_keywords;
    $keywords = htmlspecialchars($keywords, ENT_QUOTES, 'UTF-8');
    $keywords = trim(stripslashes($keywords), ',');

    if(!empty($keywords)){
            echo '<meta name="keywords" content="'.$keywords.'" />' . "\n";
    }

}

/* Title Function */
function up_title(){

	global $post, $up_options, $wp_query;
        $separator = $up_options->seo_separator ? $up_options->seo_separator : '|';
        
        /* Check for SEO Plugins */
	if ( true == UP_SEO_THIRD_PARTY || $up_options->seo_disable) { return get_bloginfo('name').wp_title($separator, false); }
        
        /* SEO Settings and Default Settings */
	$disable = $up_options->seo_disable;
	$home_layout = $up_options->seo_home_title_layout ? $up_options->seo_home_title_layout : "%BLOG% %DESC%";
    $page_layout = $up_options->seo_page_title_layout ? $up_options->seo_page_title_layout : "%TITLE% %BLOG% %DESC%";
	$single_layout = $up_options->seo_single_title_layout ? $up_options->seo_single_title_layout : "%TITLE% %BLOG% %DESC%";
	$archive_layout = $up_options->seo_archive_title_layout ? $up_options->seo_archive_title_layout : "%ARCHIVE% %TITLE% %DESC%";
    $search_layout = $up_options->seo_search_title_layout ? $up_options->seo_search_title_layout : "%SEARCH% %TITLE% %DESC%";
        
    
    /* If Not Disabled */
	if(!$disable){
        /* Set Layout Context */
        if(is_single()) $layout = $single_layout;
        if(is_page()) $layout = $page_layout;
        if(is_archive()) $layout = $archive_layout;
        if(is_category()) $layout = $archive_layout;
        if(is_tax()) $layout = $archive_layout;
        if(is_search()) $layout = $search_layout;
        if(is_404()) $layout = $home_layout;
        if(is_home() || is_front_page()) $layout = $home_layout;
        if(get_post_type() && !is_singular()) $layout = $page_layout;
        
        /* Override Title with Custom Field */
        $custom_title = ( isset( $post ) ? get_post_meta( $post->ID, '_up_seo_title', true ) : false );
        if( $custom_title && ! is_home() && ! is_front_page() ) $layout = preg_replace( '/%TITLE%/', '%CUSTOMFIELD%', $layout );
        
        $title = up_seo_title_layout($layout);
        return $title;
	}
}


function up_seo_title_layout( $layout = "%ARCHIVE% %TITLE% %BLOG% %DESC%" ){
    global $up_options, $post, $wp_query;

    $sep = $up_options->seo_separator ? ' '.$up_options->seo_separator.' ' : ' | ';
    $paged = get_query_var('paged') ? __('Page ', 'upfw').get_query_var('paged') : '';
    $search = get_query_var('s') ? __('Search Results For ', 'upfw').get_query_var('s') : '';
    $title = ( isset( $post ) ? $post->post_title : '' );
    $home_title = ( is_front_page() || is_home() && isset( $up_options->seo_homepage_title ) ) ? $up_options->seo_homepage_title : '';
    $home_description = ( is_front_page() || is_home() ) ? $up_options->seo_homepage_description : '';
    
    $customfield = ( isset( $post ) ? get_post_meta(get_the_ID(), '_up_seo_title', true) : false );
    
    /* Insert Separators */
    $layout = preg_replace('/ /', $sep, $layout);
    
    /* Home Page Title */
    if($home_title)$layout = preg_replace('/%TITLE%/', $home_title, $layout);
   
    /* Insert Blog Title */
    $layout = preg_replace('/%BLOG%/', get_bloginfo('name'), $layout);
    
    /* Post Type Archive */
    $post_type = get_post_type();
    
    if($post_type):
        $post_type = get_post_type_object($post_type);
        $labels = $post_type->labels;
        $name = $labels->name;
        $post_type = $name;
    endif;

    if( $post_type && ! is_singular() ) $layout = preg_replace( '/%TITLE%/', $post_type, $layout );
    
    /* Insert Blog Description */
    $layout = preg_replace( '/%DESC%/', get_bloginfo( 'description' ), $layout );
        
    /* Insert Page/Post Title */
    if ( $title && is_singular( ) ) $layout = preg_replace('/%TITLE%/', $title, $layout );
    
    /* Insert Custom Page/Post Title */
    if ( $customfield ) $layout = preg_replace('/%CUSTOMFIELD%/', $customfield, $layout );
    
    /* Insert Paged Results */
    if ( $paged ) : $layout = preg_replace('/%PAGED%/', $paged, $layout ); else : $layout = preg_replace( '/%PAGED%/', '', $layout ); endif;
    
    /* Insert Search Results */
    if ( $search ) $layout = preg_replace('/%SEARCH%/', $search, $layout );
    
    /* Insert Category Title */
    if ( is_category() ) $layout = preg_replace('/%ARCHIVE%/', single_cat_title( '', false ), $layout );
    
    /* Insert Tag Title */
    if (function_exists( 'is_tag' ) ) :
        if( is_tag() ) $layout = preg_replace('/%ARCHIVE%/', single_tag_title( '', false ), $layout );
    endif;

    /* Insert Author Archives */
    if( is_author() ) $layout = preg_replace( '/%ARCHIVE%/', __( 'Author Archives' ), $layout );
    
    /* Insert Taxonomy Name and Term */
    if( is_tax() ) :
        if ( function_exists('get_taxonomies') ) :
            $taxonomy_obj = $wp_query->get_queried_object();
            if(!empty($taxonomy_obj->name)) :
                    $taxonomy_nice_name = $taxonomy_obj->name;
                    $term_id = $taxonomy_obj->term_taxonomy_id;
                    $taxonomy_short_name = $taxonomy_obj->taxonomy;
                    $taxonomy_top_level_items = get_taxonomies(array('name' => $taxonomy_short_name), 'objects');
                    $taxonomy_top_level_item = $taxonomy_top_level_items[$taxonomy_short_name]->label;
            endif;
            $layout = preg_replace('/%ARCHIVE%/', $taxonomy_top_level_item.' '.__('Archives').': '.$taxonomy_nice_name, $layout);
        endif;
    endif;
    
    /* Insert Month */
    if(is_month()) $layout = preg_replace('/%ARCHIVE%/', get_the_time('F - Y', false), $layout);
    
    /* Insert Day */
    if(is_day()) $layout = preg_replace('/%ARCHIVE%/', get_the_time('F j, Y', false), $layout);
    
    /* Insert Year */
    if(is_year()) $layout = preg_replace('/%ARCHIVE%/', get_the_time('Y', false), $layout);
    
    /* Insert Post Format */
    if(get_query_var('post_format')):
        $post_type = ucwords(str_replace('post-format-', '', get_query_var('post_format')));
        $layout = preg_replace('/%ARCHIVE%/', $post_type, $layout);
    endif;

    /* Remove Double Separators */
    $layout = preg_replace("/ *$sep /", " ", $layout);
    
    /* Trim Orphan Separators */
    $layout = trim($layout, $sep);
    
    return $layout;
}

/* Add SEO Meta Box */
function up_seo_meta_box() {
    global $up_options;
    
    if(!$up_options->seo_hide_custom_fields):
        if( function_exists( 'add_meta_box' ) ) {
            add_meta_box( 'new-post-meta-boxes', __('UpThemes SEO Options'), 'up_seo_post_meta_display', 'post', 'normal', 'core' );
            add_meta_box( 'new-post-meta-boxes', __('UpThemes SEO Options'), 'up_seo_post_meta_display', 'page', 'normal', 'core' );
        }
    endif;
}
if(is_admin()) add_action( 'admin_menu', 'up_seo_meta_box' );


/* Display SEO Options */
function up_seo_post_meta_display() {
    global $up_options;?>
    <style type="text/css">
        .form-field textarea{overflow: scroll; overflow-y: scroll; overflow-x: hidden; overflow:-moz-scrollbars-vertical;}
        .form-field input, .form-field textarea{margin:0 0 10px; color:#555; width:100%;}
        .form-field label{margin:0 0 5px;}
    </style>
    <!--Start Form Wrapper-->
    <div class="form-wrap">
            <?php /* Get GLOBALS */
            
        /* Call Current Custom Fields */
        $meta = get_post_custom($post->ID);
        $follow = $meta['_up_seo_follow'][0];
        $title = $meta['_up_seo_title'][0];
        $desc = $meta['_up_seo_description'][0];
        $keywords = $meta['_up_seo_keywords'][0];
        ?>
      <!-- Start Form Styles -->
      <div class="form-field">
     
        <!-- Enable Nofollow -->
        <label><?php echo __("SEO - Custom Post Title");?></label>
        <input type="hidden" name="meta[999][key]" id="meta[999][key]" value="_up_seo_title" />
        <input type="text" name="meta[999][value]" id="meta[999][value]" value="<?php echo $title;?>">
        
        <label><?php echo __("SEO - Custom Post Description");?></label>
        <input type="hidden" name="meta[998][key]" id="meta[998][key]" value="_up_seo_description" />
        <textarea name="meta[998][value]" id="meta[998][value]" rows='3'><?php echo $desc;?></textarea>        
        
        <label><?php echo __("SEO - Keywords (comma-separated)");?></label>
        <input type="hidden" name="meta[997][key]" id="meta[997][key]" value="_up_seo_keywords" />
        <input type="text" name="meta[997][value]" id="meta[997][value]" value="<?php echo $keywords;?>">
        
        <label><?php echo __("Add a 'follow' meta tag for this post?");?></label>
        <input type="hidden" name="meta[996][key]" id="meta[996][key]" value="_up_seo_follow" />
        <select name="meta[996][value]" id="meta[996][value]">
            <option <?php if($follow == 'default')echo "selected='selected'";?> value="default"><?php _e('Default', 'upfw');?></option>
            <option <?php if($follow == 'no')echo "selected='selected'";?> value="no"><?php _e('No', 'upfw');?></option>
            <option <?php if($follow == 'yes')echo "selected='selected'";?> value="yes"><?php _e('Yes', 'upfw');?></option>
        </select>
        <?php if($up_options->seo_follow):?>
            <p style="color:#4AC25B;"><em>
                <?php _e("'follow' meta tags are enabled globally");?>
            </em></p>
        <?php endif;?>
        
          
        <!-- End Customization -->
        <?php /* XSS Protection */ wp_nonce_field('µ˛ÜhÍmÍß£rÂmÍwˆrk','up_seo_options'); ?>
        
      </div>
      <!-- End Form Styles -->
      
    </div>
    <!--End Form Wrapper-->

<?php } //End Options Panel

/* Save SEO Options */
function up_seo_meta_save( $post_id ) {
    /* WP Capability Protection */
    if ( !current_user_can( 'edit_post', $post_id )) return $post_id;
    
    /* XSS Protection */
    if (empty($_POST['meta']) || !wp_verify_nonce($_POST['up_seo_options'],'µ˛ÜhÍmÍß£rÂmÍwˆrk')) return;
    
    /* POSTed Options */
    $options = $_POST['meta'];
    update_post_meta($post_id, $options[999]['key'], $options[999]['value']);
    update_post_meta($post_id, $options[998]['key'], $options[998]['value']);
    update_post_meta($post_id, $options[997]['key'], trim($options[997]['value'], ','));
    update_post_meta($post_id, $options[996]['key'], $options[996]['value']);
}
if(is_admin()) add_action( 'save_post', 'up_seo_meta_save' );

?>