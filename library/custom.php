<?php
/**
 * upfw Theme custom functions
 *
 * Contains all of the Theme's custom functions, which include
 * helper functions and various filters.
 * 
 * @package 	upfw
 * @copyright	Copyright (c) 2010, Chip Bennett
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		upfw 1.0
 */

/**
 * @todo	complete inline documentation
 * @todo	sort function definitions alphabetically
 */

/**
 * Get current page context
 * 
 * Returns a string containing the context of the
 * current page. This string is useful for several
 * purposes, including applying an ID to the HTML
 * body tag, and adding a contextual $name to calls
 * to get_header(), get_footer(), get_sidebar(), 
 * and get_template_part_file(), in order to 
 * facilitate Child Themes overriding default Theme
 * template part files.
 * 
 * @param	none
 * @return	string	current page context
 */
function upfw_get_context() {

	$context = 'index';
	
	if ( is_front_page() ) {
		// Front Page
		$context = 'front-page';
	} else if ( is_date() ) {
		// Date Archive Index
		$context = 'date';
	} else if ( is_author() ) {
		// Author Archive Index
		$context = 'author';
	} else if ( is_category() ) {
		// Category Archive Index
		$context = 'category';
	} else if ( is_tag() ) {
		// Tag Archive Index
		$context = 'tag';
	} else if ( is_tax() ) {
		// Taxonomy Archive Index
		$context = 'taxonomy';
	} else if ( is_archive() ) {
		// Archive Index
		$context = 'archive';
	} else if ( is_search() ) {
		// Search Results Page
		$context = 'search';
	} else if ( is_404() ) {
		// Error 404 Page
		$context = '404';
	} else if ( is_attachment() ) {
		// Attachment Page
		$context = 'attachment';
	} else if ( is_single() ) {
		// Single Blog Post
		$context = 'single';
	} else if ( is_page() ) {
		// Static Page
		$context = 'page';
	} else if ( is_home() ) {
		// Blog Posts Index
		$context = 'home';
	}
	
	return $context;
}

/**
 * Get current settings page tab
 */
function upfw_get_current_tab() {

	global $up_tabs;
	
	$first_tab = $up_tabs[0]['name'];
	
    if ( isset( $_GET['tab'] ) ) {
        $current = esc_attr( $_GET['tab'] );
    } else {
    	$current = $first_tab;
    }
    
	return $current;
}
	
/**
 * Determine Header Text Color Setting
 * 
 * Determine what color value to pass to the
 * HEADER_TEXTCOLOR constant, based on whether a 
 * dark or light color scheme is being displayed.
 */
function upfw_get_header_textcolor() {

	$headertextcolor = ( get_header_textcolor() ? get_header_textcolor() : false );
	if ( ! $headertextcolor ) {
		$colorscheme = upfw_get_color_scheme();
		
		if ( 'light' == $colorscheme ) {
			$headertextcolor = '666666';
		} elseif ( 'dark' == $colorscheme ) {
			$headertextcolor = 'dddddd';
		}
	}
	return $headertextcolor;
}

/*
 * Define supported Post Format types
 * 
 * Return an array containing the list of Post Format types
 * supported by the Theme.
 * 
 * @param	none
 * @return	array	Post format types supported by the Theme
 * @since	upfw 1.2
 */
function upfw_get_post_formats() {
	$postformats = array( 
		'aside' => array(
			'slug' => 'aside',
			'description' => __( 'An incidental remark; digression: a message that departs from the main subject.', 'upfw' )
		), 
		'audio' => array(
			'slug' => 'audio',
			'description' => __('A sound, or a sound signal; Of or relating to audible sound; Of or relating to the broadcasting or reproduction of sound, especially high-fidelity reproduction.', 'upfw' )
		), 
		'chat' => array(
			'slug' => 'chat',
			'description' => __('Any kind of communication over the Internet; primarily direct one-on-one chat or text-based group chat (formally also known as synchronous conferencing), using tools such as instant messengers and Internet Relay Chat.', 'upfw' )
		), 
		'gallery' => array(
			'slug' => 'gallery',
			'description' => __('A collection of art for exhibition.', 'upfw' )
		), 
		'image' => array(
			'slug' => 'image',
			'description' => __('picture: A visual representation (of an object or scene or person or abstraction) produced on a surface.', 'upfw' )
		), 
		'link' => array(
			'slug' => 'link',
			'description' => __('A reference to a document that the reader can directly follow, or that is followed automatically. The reference points to a whole document or to a specific element within a document.', 'upfw' )
		), 
		'quote' => array(
			'slug' => 'quote',
			'description' => __('A quotation, statement attributed to someone else; To refer to (part of) a statement that has been made by someone else.', 'upfw' )
		), 
		'status' => array(
			'slug' => 'status',
			'description' => __('state or condition of affairs', 'upfw' )
		), 
		'video'  => array(
			'slug' => 'video',
			'description' => __('A recording of both visual and audible components; Electronically capturing, recording, processing, storing, transmitting, and reconstructing a sequence of still images representing scenes in motion.', 'upfw' )
		)
	);
	return $postformats;
}

/**
 * Define upfw Admin Page Tab Markup
 * 
 * @uses	upfw_get_current_tab()	defined in \functions\options.php
 * @uses	upfw_get_settings_page_tabs()	defined in \functions\options.php
 * 
 * @link	http://www.onedesigns.com/tutorials/separate-multiple-theme-options-pages-using-tabs	Daniel Tara
 */
function upfw_get_page_tab_markup() {

	global $up_tabs;

	$page = 'upfw-settings';	
	
	if ( isset( $_GET['page'] ) && 'upfw-reference' == $_GET['page'] ) {
		$page = 'upfw-reference';
	} else {
		
	}

    $current = upfw_get_current_tab();
		
	if ( 'upfw-settings' == $page ) {
        $tabs = $up_tabs;
	} else if ( 'upfw-reference' == $page ) {
        $tabs = upfw_get_reference_page_tabs();
	}
    
    $links = array();
    
    foreach( $tabs as $tab ) {
		if( isset($tab['name']) )
			$tabname = $tab['name'];
		if( isset($tab['title']) )
			$tabtitle = $tab['title'];
        if ( $tabname == $current ) {
            $links[] = "<a class='nav-tab nav-tab-active' href='?page=$page&tab=$tabname'>$tabtitle</a>";
        } else {
            $links[] = "<a class='nav-tab' href='?page=$page&tab=$tabname'>$tabtitle</a>";
        }
    }
    
    echo '<div id="icon-themes" class="icon32"><br /></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach ( $links as $link )
        echo $link;
    echo '</h2>';
    
}

/**
 * Image Handling for gallery previous/next links
 * 
 * function needed because WP gives no easy way to 
 * display both image and text in prev/next links.
 */
function upfw_gallery_links() {
	global $post;
	$post = get_post($post);
	$attachments = array_values(get_children("post_parent=$post->post_parent&post_type=attachment&post_mime_type=image&orderby=menu_order ASC, ID ASC"));

	$k = 0;
	
	foreach ( $attachments as $k => $attachment )
		if ( $attachment->ID == $post->ID )
			break;

	$links = array( 'prevlink' => '', 'prevthumb' => '', 'nextlink' => '', 'nextthumb' => '' );

	if ( isset($attachments[$k+1]) ) {
		$links['prevlink'] = get_permalink($attachments[$k+1]->ID);
		$links['prevthumb'] = wp_get_attachment_link($attachments[$k+1]->ID, 'attachment-nav-thumbnail', true);
	}

	if ( isset($attachments[$k-1]) ) {
		$links['nextlink'] = get_permalink($attachments[$k-1]->ID);
		$links['nextthumb'] = wp_get_attachment_link($attachments[$k-1]->ID, 'attachment-nav-thumbnail', true);
	}

	return $links;
}

/**
 * Image Handling for gallery image metadata
 */
function upfw_gallery_image_meta() {
	global $post;
	$post = get_post($post);
	$is_parent = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC' ) );
	$attachmentimage = ( $is_parent ? array_shift( $is_parent ) : false );
	$imagepost = ( $attachmentimage ? $attachmentimage->ID : $post->ID );
	$m = get_post_meta( $imagepost, '_wp_attachment_metadata' , true );
	$image = wp_get_attachment_image( $imagepost, 'full' );
	$url = wp_get_attachment_url( $imagepost );
	$uploaddir = wp_upload_dir();
	$imagesize = size_format( filesize( $uploaddir['basedir'] . '/' . $m['file'] ) );
	$image_meta = array (
		'image' => $image,
		'url' => $url,		
		'width' => $m['width'],
		'height' => $m['height'],
		'dimensions' => false,
		'filesize' => $imagesize,
		'created_timestamp' => $m['image_meta']['created_timestamp'],
		'copyright' => $m['image_meta']['copyright'],
		'credit' => $m['image_meta']['credit'],
		'aperture' => $m['image_meta']['aperture'],
		'focal_length' => $m['image_meta']['focal_length'],
		'iso' => $m['image_meta']['iso'],
		'shutter_speed' => $m['image_meta']['shutter_speed'],
		'camera' => $m['image_meta']['camera'],
		'caption' => '(No caption provided.)'
	);
	// image dimensions handler
	if ( $m['width'] && $m['height'] ) {
		$image_meta['dimensions'] = $m['width'] . '&#215;' . $m['height'] . ' px';
	}
	// image created_timestamp handler
	if ( $m['image_meta']['created_timestamp'] ) {
		$image_meta['created_timestamp'] = date( 'm M Y', $m['image_meta']['created_timestamp'] );
	}
	// image aperture handler
	if ( $m['image_meta']['aperture'] ) {
		$image_meta['aperture'] = 'f/' . $m['image_meta']['aperture'];
	}
	// shutter speed handler
	if ( ( $m['image_meta']['shutter_speed'] != '0' ) && ( ( 1 / $m['image_meta']['shutter_speed'] ) > 1 ) ) {
	$image_meta['shutter_speed'] =  "1/";
		if (number_format((1 / $m['image_meta']['shutter_speed']), 1) ==  number_format((1 / $m['image_meta']['shutter_speed']), 0)) {
			$image_meta['shutter_speed'] = $image_meta['shutter_speed'] . number_format((1 / $m['image_meta']['shutter_speed']), 0, '.', '') . ' sec';
		} else {
			$image_meta['shutter_speed'] = $image_meta['shutter_speed'] .  number_format((1 / $m['image_meta']['shutter_speed']), 1, '.', '') . ' sec';
		}
	} else {
		$image_meta['shutter_speed'] = $m['image_meta']['shutter_speed'].' sec';
	}
	// image caption handler
	if ( ! empty( $post->post_excerpt ) ) {
		$image_meta['caption'] = get_the_excerpt(); // this is the "caption"
	} else if ( is_object( $attachmentimage ) && $attachmentimage->post_excerpt ) {
		$image_meta['caption'] = $attachmentimage->post_excerpt;
	}
	return $image_meta;
}

/**
 * Paginate Archive Index Page Links
 */
function upfw_get_paginate_archive_page_links( $type = 'plain', $endsize = 1, $midsize = 1 ) {
	global $wp_query, $wp_rewrite;	
	$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
	
	// Sanitize input argument values
	if ( ! in_array( $type, array( 'plain', 'list', 'array' ) ) ) $type = 'plain';
	$endsize = (int) $endsize;
	$midsize = (int) $midsize;
	
	// Setup argument array for paginate_links()
	$pagination = array(
		'base' => @add_query_arg('paged','%#%'),
		'format' => '',
		'total' => $wp_query->max_num_pages,
		'current' => $current,
		'show_all' => false,
		'end_size' => $endsize,
		'mid_size' => $midsize,
		'type' => $type,
		'prev_text' => '&lt;&lt;',
		'next_text' => '&gt;&gt;'
	);

	if( $wp_rewrite->using_permalinks() )
		$pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );

	if( !empty($wp_query->query_vars['s']) )
		$pagination['add_args'] = array( 's' => get_query_var( 's' ) );

	return paginate_links( $pagination );
}

/**
 * Display copyright notice customized according to date of first post
 */
function upfw_copyright() {
	// check for cached values for copyright dates
	$copyright_cache = wp_cache_get( 'copyright_dates', 'upfw' );
	// query the database for first/last copyright dates, if no cache exists
	if ( false === $copyright_cache ) {	
		global $wpdb;
		$copyright_dates = $wpdb->get_results("
			SELECT
			YEAR(min(post_date_gmt)) AS firstdate,
			YEAR(max(post_date_gmt)) AS lastdate
			FROM
			$wpdb->posts
			WHERE
			post_status = 'publish'
		");
		$copyright_cache = $copyright_dates;
		// add the first/last copyright dates to the cache
		wp_cache_set( 'copyright_dates', $copyright_cache, 'upfw', '604800' );
	}
	// Build the copyright notice, based on cached date values
	$output = '&copy; ';
	if( $copyright_cache ) {
		$copyright = $copyright_cache[0]->firstdate;
		if( $copyright_cache[0]->firstdate != $copyright_cache[0]->lastdate ) {
			$copyright .= '-' . $copyright_cache[0]->lastdate;
		}
		$output .= $copyright;
	} else {
		$output .= date( 'Y' );
	}
	return $output;
}