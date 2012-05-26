<?php
/**
 * UpThemes Framework custom functions
 *
 * Contains all of the Theme's custom functions, which include
 * helper functions and various filters.
 * 
 * @package 	upfw
 * @copyright	Copyright (c) 2010 Chip Bennett
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		upfw 1.0
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
 * Get list of categories
 */
function upfw_get_category_list() {
	$cat_list = get_categories();
	$category_list = array();
	foreach ( $cat_list as $cat ) {
		$slug = $cat->slug;
		$name = $cat->name;
		$category_list[$slug] = array(
			'name' => $slug,
			'title' => $name
		);
	}
	return $category_list;
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