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
	if ($typenow == 'competition') {
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
