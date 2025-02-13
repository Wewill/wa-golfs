<?php
/*
	Adds filters
	https://frankiejarrett.com/2011/09/create-a-dropdown-of-custom-taxonomies-in-wordpress-the-easy-way/
*/

//7
//http://thestizmedia.com/custom-post-type-filter-admin-custom-taxonomy/

// Ajoute des filtres sur les pages concernés 


/**
 * Display a custom taxonomy dropdown in admin
 * @author Mike Hemberger
 * @link http://thestizmedia.com/custom-post-type-filter-admin-custom-taxonomy/
 */
 
add_action('restrict_manage_posts', 'golfs_filter_post_type_by_taxonomy');
function golfs_filter_post_type_by_taxonomy() {
	global $typenow;
  	global $wp_query;

    // competition-category
	$taxonomy  = 'competition-category';
	if ($typenow == 'competitions') {
		$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
        if($info_taxonomy) :
        wp_dropdown_categories(array(
			'show_option_all' => sprintf( __('All %s','wa-golfs'), $info_taxonomy->label),
			// 'show_option_none'=> __("—",'wa-golfs'),
			'taxonomy'        => $taxonomy,
			'name'            => $taxonomy,
			'orderby'         => 'name',
			'selected'        => $selected,
			'show_count'      => true,
			'hide_empty'      => true,
			'hide_if_empty'   => true,
			'hierarchical' 		=> 1,
			'value_field' 		=> 'slug', // Permet de recuperer la query pour selectionner
		));
        endif;
    };

	// by custom field : c_state
	$meta_key = 'c_state';
	$stateLabels = array(
		'pending' => array(
			'label' => 'À venir',
		),
		'current' => array(
			'label' => 'En cours',
		),
		'ended' => array(
			'label' => 'Terminé',
		),
	);
	if ($typenow == 'competitions') {
		$selected = isset($_GET[$meta_key]) ? $_GET[$meta_key] : '';
		$states = get_posts(array(
			'post_type' => 'competitions',
			'meta_key' => $meta_key,
			'posts_per_page' => -1,
			'fields' => 'ids',
		));
		$states = array_unique(array_map(function($post_id) use ($meta_key) {
			return get_post_meta($post_id, $meta_key, true);
		}, $states));
		if (!empty($states)) {
			echo '<select name="' . esc_attr($meta_key) . '">';
			echo '<option value="">' . __('All States', 'wa-golfs') . '</option>';
			foreach ($states as $state) {
				printf(
					'<option value="%s"%s>%s</option>',
					esc_attr($state),
					selected($selected, $state, false),
					esc_html($stateLabels[$state]['label'])
				);
			}
			echo '</select>';
		}
	}
	
	// Authors 
	// if ($typenow == 'directory' || $typenow == 'farm' || $typenow == 'structure' || $typenow == 'operation' || $typenow == 'partner') {
	// 	wp_dropdown_users(array(
	// 		'name'              => 'author',
	// 		'show_option_all' 	=> __("All Authors",'wa-golfs'),
	// 		// 'show_option_none'	=> __("—",'wa-golfs'),
	// 		'multi'         		=> 1,
	// 		'orderby'         	=> 'display_name',
    //     	'role' 				=> array('administrator'), // Limt by role
	// 		'selected' 			=> !empty($_GET['author']) ? $_GET['author'] : 0, // Permet de recuperer la query pour selectionner
	//         'include_selected'  => false,
	// 	));
	// }

}

/**
 * Extend WordPress search to include custom fields
 *
 * https://adambalee.com
 */

/**
 * Join posts and postmeta tables
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_join
 */
add_filter( 'posts_join', 'competitions_search_join' );
add_filter( 'posts_join', 'course_search_join' );
add_filter( 'posts_join', 'testimony_search_join' );
function competitions_search_join ( $join ) {
    global $pagenow, $wpdb;

    // I want the filter only when performing a search on edit page of Custom Post Type named "competitions".
    if ( is_admin() && is_search() && 'edit.php' === $pagenow && 'competitions' === $_GET['post_type'] && ! empty( $_GET['s'] ) ) {
        $join .= 'LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }
    return $join;
}
function course_search_join ( $join ) {
    global $pagenow, $wpdb;

    // I want the filter only when performing a search on edit page of Custom Post Type named "course".
    if ( is_admin() && is_search() && 'edit.php' === $pagenow && 'course' === $_GET['post_type'] && ! empty( $_GET['s'] ) ) {
        $join .= 'LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }
    return $join;
}
function testimony_search_join ( $join ) {
    global $pagenow, $wpdb;

    // I want the filter only when performing a search on edit page of Custom Post Type named "testimony".
    if ( is_admin() && is_search() && 'edit.php' === $pagenow && 'testimony' === $_GET['post_type'] && ! empty( $_GET['s'] ) ) {
        $join .= 'LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }
    return $join;
}

/**
 * Modify the search query with posts_where
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
 */
add_filter( 'posts_where', 'competitions_search_where' );
function competitions_search_where( $where ) {
    global $pagenow, $wpdb;

    // I want the filter only when performing a search on edit page of Custom Post Type named "competitions".
    if ( is_admin() && is_search() && 'edit.php' === $pagenow && 'competitions' === $_GET['post_type'] && !empty( $_GET['c_state'] ) ) {

		$where = preg_replace(
			"/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
			"(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)",
			$where
		);

		if (isset($_GET['c_state']) && !empty($_GET['c_state'])) {
			$c_state = esc_sql($_GET['c_state']);
			$where .= " AND EXISTS (SELECT 1 FROM {$wpdb->postmeta} WHERE {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID AND {$wpdb->postmeta}.meta_key = 'c_state' AND {$wpdb->postmeta}.meta_value = '{$c_state}')";
		}

    }
    return $where;
}

/**
 * Prevent duplicates
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_distinct
 */
function cf_search_distinct( $where ) {
    global $wpdb;

    if ( is_search() ) {
        return "DISTINCT";
    }

    return $where;
}
add_filter( 'posts_distinct', 'cf_search_distinct' );
