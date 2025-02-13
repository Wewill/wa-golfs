<?php
/**
 * Register the custom taxonomies.
 *
 * @since    1.1.0
 */
function register_taxonomies() {

    /**
     * Competition categories
     */
	$labels = [
		'name'                       => esc_html__( 'Competition categories', 'wa-golfs' ),
		'singular_name'              => esc_html__( 'Competition category', 'wa-golfs' ),
		'menu_name'                  => esc_html__( 'Competition categories', 'wa-golfs' ),
		'search_items'               => esc_html__( 'Search Competition categories', 'wa-golfs' ),
		'popular_items'              => esc_html__( 'Popular Competition categories', 'wa-golfs' ),
		'all_items'                  => esc_html__( 'All Competition categories', 'wa-golfs' ),
		'parent_item'                => esc_html__( 'Parent Competition category', 'wa-golfs' ),
		'parent_item_colon'          => esc_html__( 'Parent Competition category:', 'wa-golfs' ),
		'edit_item'                  => esc_html__( 'Edit Competition category', 'wa-golfs' ),
		'view_item'                  => esc_html__( 'View Competition category', 'wa-golfs' ),
		'update_item'                => esc_html__( 'Update Competition category', 'wa-golfs' ),
		'add_new_item'               => esc_html__( 'Add New Competition category', 'wa-golfs' ),
		'new_item_name'              => esc_html__( 'New Competition category Name', 'wa-golfs' ),
		'separate_items_with_commas' => esc_html__( 'Separate competition categorys with commas', 'wa-golfs' ),
		'add_or_remove_items'        => esc_html__( 'Add or remove competition categorys', 'wa-golfs' ),
		'choose_from_most_used'      => esc_html__( 'Choose most used competition categorys', 'wa-golfs' ),
		'not_found'                  => esc_html__( 'No competition categories found.', 'wa-golfs' ),
		'no_terms'                   => esc_html__( 'No competition categories', 'wa-golfs' ),
		'filter_by_item'             => esc_html__( 'Filter by competition category', 'wa-golfs' ),
		'items_list_navigation'      => esc_html__( 'Competition categories list pagination', 'wa-golfs' ),
		'items_list'                 => esc_html__( 'Competition categories list', 'wa-golfs' ),
		'most_used'                  => esc_html__( 'Most Used', 'wa-golfs' ),
		'back_to_items'              => esc_html__( '&larr; Go to Competition categories', 'wa-golfs' ),
	];
	$args = [
		'label'              => esc_html__( 'Competition categories', 'wa-golfs' ),
		'labels'             => $labels,
		'description'        => esc_html__( 'List competition category of a competition', 'wa-golfs' ),
		'public'             => true,
		'publicly_queryable' => true,
		'hierarchical'       => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_nav_menus'  => true,
		'show_in_rest'       => true,
		'show_tagcloud'      => true,
		'show_in_quick_edit' => true,
		'show_admin_column'  => true,
		'query_var'          => true,
		'sort'               => true,
		'meta_box_cb'        => 'post_tags_meta_box',
		'rest_base'          => '',
		'rewrite'            => [
			'with_front'   => false,
			'hierarchical' => false,
		],
	];
	register_taxonomy( 'competition-category', ['competitions'], $args );
    
}
?>