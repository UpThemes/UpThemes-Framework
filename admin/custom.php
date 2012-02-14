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
 * Get Current Page Layout
 */
function upfw_get_current_page_layout() {
	global $post;
	global $upfw_options;
	$custom = ( get_post_custom( $post->ID ) ? get_post_custom( $post->ID ) : false );
	$custom_layout = ( isset( $custom['_upfw_layout'][0] ) ? $custom['_upfw_layout'][0] : 'default' );	
	$layout = '';
	if ( ! is_admin() ) {
		if ( is_attachment() ) {
			$layout .= 'attachment';
		} 
		else if ( is_page() ) {
			if ( 'default' == $custom_layout ) {
				$layout .= $upfw_options['default_static_page_layout'];
			} else {
				$layout .= $custom_layout;
			}
		} 
		else if ( is_single() ) {
			if ( 'gallery' == get_post_format() || 'image' == get_post_format() || 'video' == get_post_format() ) {
				$layout .= 'full';
			} 
			else if ( 'default' == $custom_layout ) {
				$layout .= $upfw_options['default_single_post_layout'];
			} 
			else {
				$layout .= $custom_layout;
			}
		} 
		else if ( is_home() || is_archive() || is_search() || is_404() ) {
			$layout .= $upfw_options['post_index_layout'];
		}
	} 
	else if ( is_admin() ) {
		if ( 'attachment' == $post->post_type ) {
			$layout .= 'attachment';
		} 
		else if ( 'page' == $post->post_type ) {
			if ( 'default' == $custom_layout ) {
				$layout .= $upfw_options['default_static_page_layout'];
			} 
			else {
				$layout .= $custom_layout;
			}
		} 
		else if ( 'post' == $post->post_type ) {
			if ( 'gallery' == get_post_format( $post->ID ) || 'image' == get_post_format( $post->ID ) || 'video' == get_post_format( $post->ID ) ) {
				$layout .= 'full';
			} 
			if ( 'default' == $custom_layout ) {
				$layout .= $upfw_options['default_single_post_layout'];
			} 
			else {
				$layout .= $custom_layout;
			}
		}
	}
	return $layout;
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
 * Get GitHub API Data
 * 
 * Uses the GitHub API (v3) to get information
 * regarding open or closed issues (bug reports)
 * or commits, then outputs them in a table.
 *
 * Derived from code originally developed by
 * Michael Fields (@_mfields):
 * @link	https://gist.github.com/1061846 Simple Github commit API shortcode for WordPress
 * 
 * @param	string	$context		(required) API data context. Currently supports 'commits' and 'issues'. Default: 'commits'
 * @param	string	$status			(optional) Issue state, either 'open' or 'closed'. Only used for 'commits' context. Default: 'open'
 * @param	string	$releasedate	(optional) Date, in YYYY-MM-DD format, used to return commits/issues since last release.
 * @param	string	$user			(optional) GitHub user who owns repository.
 * @param	string	$repo			(optional) GitHub repository for which to return API data
 * 
 * @return	string	table of formatted API data
 */
function upfw_get_github_api_data( $context = 'commits', $status = 'open', $milestone = '7', $roadmap = false, $currentrelease = '2.6', $releasedate = '2011-12-16', $user = 'chipbennett', $repo = 'upfw' ) {

	$capability = 'read';

	// $branch is user/repository string.
	// Used variously throughout the function
	$branch = $user . '/' . $repo;

	// Create transient key string. Used to ensure API data are 
	// pinged only periodically. Different transient keys are
	// created for commits, open issues, and closed issues.
	$transient_key = 'upfw_' . $currentrelease . '_github_';
	if ( 'commits' == $context ) {
		$transient_key .= 'commits' . md5( $branch );
	} else if ( 'issues' == $context ) {
		$transient_key .= 'issues_' . $status . md5( $branch . $milestone );
	}

	// If cached (transient) data are used, output an HTML
	// comment indicating such
	$cached = get_transient( $transient_key );

	if ( false !== $cached ) {
		return $cached .= "\n" . '<!--Returned from transient cache.-->';
	}
	
	// Construct the API request URL, based on $branch and
	// $context, for issues, $status, and $milestone
	$apiurl = 'https://api.github.com/repos/' . $branch . '/' . $context;
	if ( 'commits' == $context ) {
		$apiurl .= '';
	} else if ( 'issues' == $context ) {
		$apiurl .= '?state=' . $status;
		$apiurl .= '&milestone=' . $milestone;
		$apiurl .= '&sort=created&direction=asc';
	}	
	
	// Request the API data, using the constructed URL
	$remote = wp_remote_get( esc_url( $apiurl ) );

	// If the API data request results in an error, return
	// an appropriate comment
	if ( is_wp_error( $remote ) ) {
		if ( current_user_can( $capability ) ) {
			return '<p>Github API: Github is unavailable.</p>';
		}
		return;
	}

	// If the API returns a server error in response, output
	// an error message indicating the server response.
	if ( '200' != $remote['response']['code'] ) {
		if ( current_user_can( $capability ) ) {
			return '<p>Github API: Github responded with an HTTP status code of ' . esc_html( $remote['response']['code'] ) . '.</p>';
		}
		return;
	}

	// If the API returns a valid response, the data will be
	// json-encoded; so decode it.
	$data = json_decode( $remote['body'] );	
	if ( 'issues' == $context ) {
		// Test	
	}
	usort( $data, 'upfw_sort_github_data' );

	// If the decoded json data is null, return a message
	// indicating that no data were returned.
	if ( ! isset( $data ) || empty( $data ) ) {
		$apidata = $context;
		if ( 'issues' == $context ) {
			$apidata = $status . ' ' . $context;
		}
		if ( current_user_can( $capability ) ) {
			return '<p>No ' . $apidata . ' could be found.</p>';
			return '<p>Github API: No ' . $apidata . ' could be found for this repository.</p>';
		}
		return;
	}

	// If the decoded json data has content, prepare the data
	// to be output.
	if ( 'issues' == $context ) {
		// $reportdate is used as a table column header
		$reportdate = ( 'open' == $status ? 'Reported' : 'Closed' );
		// $reportobject is used to return the appropriate timestamp
		$reportobject = ( 'open' == $status ? 'created_at' : 'closed_at' );
	} else if ( 'commits' == $context ) {
		// $reportdate is used as a table column header
		$reportdate = 'Date';
	}
	// $reportidlabel is used as a table column header
	$reportidlabel = ( 'issues' == $context ? '#' : 'Commit' );
	// $datelastrelease is the PHP date of last released, based
	// on the $releasedate parameter passed to the function
	$datelastrelease = get_date_from_gmt( date( 'Y-m-d H:i:s', strtotime( $releasedate ) ), 'U' );

	// Begin constructing the table
	$output = '';
	$output .= "\n" . '<table class="github-api github-' . $context . '">';
	$output .= "\n" . '<thead>';
	$output .= "\n\t" . '<tr><th>' . $reportidlabel . '</th><th>' . $reportdate . '</th>';
	if ( 'issues' == $context ) {
		$output .= '<th>Milestone</th><th>Label</th>';
	}
	$output .= '<th>Issue</th></tr>';
	$output .= "\n" . '</thead>';
	$output .= "\n" . '<tbody>';

	// Step through each object in the $data array
	foreach( $data as $object ) {
		if ( 'issues' == $context ) {
			$url = 'https://github.com/' . $branch . '/' . $context .'/' . $object->number;
			$reportid = $object->number;
			$message = $object->title;
			$label = $object->labels;
			$label = $label[0];
				$labelname = $label->name;
				$labelcolor = $label->color;
			$objecttime = $object->$reportobject;
			$milestoneobj = $object->milestone;
			$milestonetitle = $milestoneobj->title;
			$milestonenumber = $milestoneobj->number;
		} else if ( 'commits' == $context ) {				
			$url = 'https://github.com/' . $branch . '/commit/' . $object->sha;
			$reportid = substr( $object->sha, 0, 6 );
			$commit = $object->commit;
				$message = $commit->message;
				$author = $commit->author;
			$objecttime = $author->date;
		}
		$time = get_date_from_gmt( date( 'Y-m-d H:i:s', strtotime( $objecttime ) ), 'U' );
		$timestamp = date( 'dMy', $time );
		$time_human = 'About ' . human_time_diff( $time, get_date_from_gmt( date( 'Y-m-d H:i:s' ), 'U' ) ) . ' ago';
		$time_machine = date( 'Y-m-d\TH:i:s\Z', $time );
		$time_title_attr = date( get_option( 'date_format' ) . ' at ' . get_option( 'time_format' ), $time );
		
		// Only output $data reported/created/closed since 
		// the last release
		if ( ( 'issues' == $context && ( $milestone == $milestonenumber || ( true == $roadmap && $milestonetitle > $currentrelease ) ) ) || ( 'commits' == $context && $time > $datelastrelease ) ) {
			$output .= "\n\t" . '<tr>';
			$output .= '<td style="padding:3px 5px;text-align:center;font-weight:bold;"><a href="' . esc_url( $url ) . '">' . $reportid . '</a></td>';
			$output .= '<td style="padding:3px 5px;text-align:center;color:#999;font-size:12px;"><time title="' . esc_attr( $time_title_attr ) . '" datetime="' . esc_attr( $time_machine ) . '">' . esc_html( $timestamp ) . '</time></td>';
			if ( 'issues' == $context ) {
				$output .= '<td style="padding:3px 5px;text-align:center;color:#999;">' . $milestonetitle . '</td>';
				$output .= '<td style="padding-left:5px;text-align:center;"><div style="text-shadow:#555 1px 1px 0px;border:1px solid #bbb;-webkit-border-radius:5px;-moz-border-radius:5px;border-radius:5px;padding:3px;padding-bottom:5px;padding-top:1px;font-weight:bold;background-color:#ffffff;color:#' . $labelcolor . ';">' . $labelname . '</div></td>';
			}
			$output .= '<td style="padding:3px 5px;font-size:12px;">' . esc_html( $message ) . '</td>';
			$output .= '</tr>';
		}
	}

	// Complete construction of the table
	$output .= "\n" . '</tbody>';
	$output .= "\n" . '</table>';

	// Set the transient (cache) for the API data
	set_transient( $transient_key, $output, 600 );

	// Return the output
	return $output;
}

/**
 * Sort GitHub API Data
 * 
 * Callback function for usort() to sort the GitHub 
 * API (v3) issues data by issue number or commit date
 * 
 * @return	object	object of GitHub API data sorted by issue number or commit date
 */
function upfw_sort_github_data( $a, $b ) {
	$sort = 0;
	$param_a = '';
	$param_b = '';
	if ( isset( $a->number ) ) {
		$param_a = $a->number;
		$param_b = $b->number;
	} else if ( isset( $a->committer ) ) {
		$commit_a = $a->commit;
		$commit_b = $b->commit;
		$committer_a = $commit_a->committer;
		$committer_b = $commit_b->committer;
		$param_a = get_date_from_gmt( date( 'Y-m-d H:i:s', strtotime( $committer_a->date ) ), 'U' );
		$param_b = get_date_from_gmt( date( 'Y-m-d H:i:s', strtotime( $committer_b->date ) ), 'U' );
	}
	if (  $param_a ==  $param_b ) { 
		$sort = 0; 
	} else {
		$sort = ( $param_a < $param_b ? -1 : 1 );
	}
	return $sort;
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