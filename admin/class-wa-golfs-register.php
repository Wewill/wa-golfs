<?php
/**
 * Register the post types.
 *
 * @since    1.1.0
 */
function register_post_types() {

	/**
	 * Competitions › Competition
	 */
	$labels = [
		'name'                     => esc_html__( 'Competitions', 'wa-golfs' ),
		'singular_name'            => esc_html__( 'Competition', 'wa-golfs' ),
		'add_new'                  => esc_html__( 'Add New', 'wa-golfs' ),
		'add_new_item'             => esc_html__( 'Add New Competition', 'wa-golfs' ),
		'edit_item'                => esc_html__( 'Edit Competition', 'wa-golfs' ),
		'new_item'                 => esc_html__( 'New Competition', 'wa-golfs' ),
		'view_item'                => esc_html__( 'View Competition', 'wa-golfs' ),
		'view_items'               => esc_html__( 'View Competitions', 'wa-golfs' ),
		'search_items'             => esc_html__( 'Search Competitions', 'wa-golfs' ),
		'not_found'                => esc_html__( 'No competitions found.', 'wa-golfs' ),
		'not_found_in_trash'       => esc_html__( 'No competitions found in Trash.', 'wa-golfs' ),
		'parent_item_colon'        => esc_html__( 'Parent Competitions:', 'wa-golfs' ),
		'all_items'                => esc_html__( 'All Competitions', 'wa-golfs' ),
		'archives'                 => esc_html__( 'Competition Archives', 'wa-golfs' ),
		'attributes'               => esc_html__( 'Competition Attributes', 'wa-golfs' ),
		'insert_into_item'         => esc_html__( 'Insert into competitions', 'wa-golfs' ),
		'uploaded_to_this_item'    => esc_html__( 'Uploaded to this competition', 'wa-golfs' ),
		'featured_image'           => esc_html__( 'Featured image', 'wa-golfs' ),
		'set_featured_image'       => esc_html__( 'Set featured image', 'wa-golfs' ),
		'remove_featured_image'    => esc_html__( 'Remove featured image', 'wa-golfs' ),
		'use_featured_image'       => esc_html__( 'Use as featured image', 'wa-golfs' ),
		'menu_name'                => esc_html__( 'Competitions', 'wa-golfs' ),
		'filter_items_list'        => esc_html__( 'Filter competitions list', 'wa-golfs' ),
		'filter_by_date'           => esc_html__( 'Filter competitions date', 'wa-golfs' ),
		'items_list_navigation'    => esc_html__( 'Competitions list navigation', 'wa-golfs' ),
		'items_list'               => esc_html__( 'Competitions list', 'wa-golfs' ),
		'item_published'           => esc_html__( 'Competition published.', 'wa-golfs' ),
		'item_published_privately' => esc_html__( 'Competition published privately.', 'wa-golfs' ),
		'item_reverted_to_draft'   => esc_html__( 'Competition reverted to draft.', 'wa-golfs' ),
		'item_scheduled'           => esc_html__( 'Competition scheduled.', 'wa-golfs' ),
		'item_updated'             => esc_html__( 'Competition updated.', 'wa-golfs' ),
		'text_domain'              => esc_html__( 'wa-golfs', 'wa-golfs' ),
	];
	$args = [
		'label'               => esc_html__( 'Competitions', 'wa-golfs' ),
		'labels'              => $labels,
		'description'         => 'A golf directory of competition listing, dates and results',
		'public'              => true,
		'hierarchical'        => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'show_in_rest'        => true,
		'query_var'           => true,
		'can_export'          => true,
		'delete_with_user'    => true,
		'order'               => true,
		'has_archive'         => true,
		'rest_base'           => '',
		'show_in_menu'        => true,
		//'menu_position'       => 10,
		'menu_icon'           => 'dashicons-awards',
		'capability_type'     => 'post',
		'supports'            => ['title', 'editor', 'thumbnail', 'custom-fields', 'revisions'], // https://stackoverflow.com/questions/77310854/wordpress-useentityprop-returns-undefined
		'taxonomies'          => ['competition-category'], //'category', 'post_tag'
		'rewrite'             => [
			'with_front' => false,
		],
		//https://stackoverflow.com/questions/72302604/wordpress-default-blocks-loaded-on-new-post
		'template' => array(
			// Add blocks to default content
			// Used to make the page template default and editable / removable ( instead of page model )
			array(
				'competitions/wa-golfs-competitions-block',
			),
			// array(
			// 	'core/paragraph',
			// 	array(
			// 		'align'   => 'center',
			// 		'content' => 'Place content you already in the block, even a link to a site like <a href="stackoverflow.com">stackoverflow</a>.',
			// 	),
			// ),
			// array(
			// 	'core/buttons',
			// 	array(
			// 		'layout' => array(
			// 			'type'           => 'flex',
			// 			'justifyContent' => 'center',
			// 		),
			// 	),
			// 	array(
			// 		array(
			// 			'core/button',
			// 			array(
			// 				'text'      => 'button text',
			// 				'url'       => 'https://the-url.com/',
			// 				'className' => 'a-custom-class-name',
			// 			),
			// 		),
			// 	),
			// ),
		)
	];
	
	register_post_type( 'competitions', $args );

	/**
	 * Courses › Course
	 */
	$labels = [
		'name'                     => esc_html__( 'Courses', 'wa-golfs' ),
		'singular_name'            => esc_html__( 'Course', 'wa-golfs' ),
		'add_new'                  => esc_html__( 'Add New', 'wa-golfs' ),
		'add_new_item'             => esc_html__( 'Add New Course', 'wa-golfs' ),
		'edit_item'                => esc_html__( 'Edit Course', 'wa-golfs' ),
		'new_item'                 => esc_html__( 'New Course', 'wa-golfs' ),
		'view_item'                => esc_html__( 'View Course', 'wa-golfs' ),
		'view_items'               => esc_html__( 'View Courses', 'wa-golfs' ),
		'search_items'             => esc_html__( 'Search Courses', 'wa-golfs' ),
		'not_found'                => esc_html__( 'No courses found.', 'wa-golfs' ),
		'not_found_in_trash'       => esc_html__( 'No courses found in Trash.', 'wa-golfs' ),
		'parent_item_colon'        => esc_html__( 'Parent Course:', 'wa-golfs' ),
		'all_items'                => esc_html__( 'All Courses', 'wa-golfs' ),
		'archives'                 => esc_html__( 'Course Archives', 'wa-golfs' ),
		'attributes'               => esc_html__( 'Course Attributes', 'wa-golfs' ),
		'insert_into_item'         => esc_html__( 'Insert into course', 'wa-golfs' ),
		'uploaded_to_this_item'    => esc_html__( 'Uploaded to this course', 'wa-golfs' ),
		'featured_image'           => esc_html__( 'Featured image', 'wa-golfs' ),
		'set_featured_image'       => esc_html__( 'Set featured image', 'wa-golfs' ),
		'remove_featured_image'    => esc_html__( 'Remove featured image', 'wa-golfs' ),
		'use_featured_image'       => esc_html__( 'Use as featured image', 'wa-golfs' ),
		'menu_name'                => esc_html__( 'Courses', 'wa-golfs' ),
		'filter_items_list'        => esc_html__( 'Filter courses list', 'wa-golfs' ),
		'filter_by_date'           => esc_html__( 'Filter courses date', 'wa-golfs' ),
		'items_list_navigation'    => esc_html__( 'Courses list navigation', 'wa-golfs' ),
		'items_list'               => esc_html__( 'Courses list', 'wa-golfs' ),
		'item_published'           => esc_html__( 'Course published.', 'wa-golfs' ),
		'item_published_privately' => esc_html__( 'Course published privately.', 'wa-golfs' ),
		'item_reverted_to_draft'   => esc_html__( 'Course reverted to draft.', 'wa-golfs' ),
		'item_scheduled'           => esc_html__( 'Course scheduled.', 'wa-golfs' ),
		'item_updated'             => esc_html__( 'Course updated.', 'wa-golfs' ),
	];
	$args = [
		'label'               => esc_html__( 'Courses', 'wa-golfs' ),
		'labels'              => $labels,
		'description'         => '',
		'public'              => true,
		'hierarchical'        => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'show_in_rest'        => true,
		'query_var'           => true,
		'can_export'          => true,
		'delete_with_user'    => true,
		'order'               => true,
		'has_archive'         => true,
		'rest_base'           => '',
		'show_in_menu'        => true,
		'menu_position'       => '',
		'menu_icon'           => 'dashicons-location-alt',
		'capability_type'     => 'post',
		'supports'            => ['title', 'editor', 'thumbnail', 'custom-fields', 'revisions'],
		'taxonomies'          => [],
		'rewrite'             => [
			'with_front' => false,
		],
	];

	register_post_type( 'course', $args );

	/**
	 * Testimonies › testimony
	 */
	$labels = [
		'name'                     => esc_html__( 'Testimonies', 'wa-golfs' ),
		'singular_name'            => esc_html__( 'Testimony', 'wa-golfs' ),
		'add_new'                  => esc_html__( 'Add New', 'wa-golfs' ),
		'add_new_item'             => esc_html__( 'Add New Testimony', 'wa-golfs' ),
		'edit_item'                => esc_html__( 'Edit Testimony', 'wa-golfs' ),
		'new_item'                 => esc_html__( 'New Testimony', 'wa-golfs' ),
		'view_item'                => esc_html__( 'View Testimony', 'wa-golfs' ),
		'view_items'               => esc_html__( 'View Testimonies', 'wa-golfs' ),
		'search_items'             => esc_html__( 'Search Testimonies', 'wa-golfs' ),
		'not_found'                => esc_html__( 'No testimonies found.', 'wa-golfs' ),
		'not_found_in_trash'       => esc_html__( 'No testimonies found in Trash.', 'wa-golfs' ),
		'parent_item_colon'        => esc_html__( 'Parent Testimony:', 'wa-golfs' ),
		'all_items'                => esc_html__( 'All Testimonies', 'wa-golfs' ),
		'archives'                 => esc_html__( 'Testimony Archives', 'wa-golfs' ),
		'attributes'               => esc_html__( 'Testimony Attributes', 'wa-golfs' ),
		'insert_into_item'         => esc_html__( 'Insert into testimony', 'wa-golfs' ),
		'uploaded_to_this_item'    => esc_html__( 'Uploaded to this testimony', 'wa-golfs' ),
		'featured_image'           => esc_html__( 'Featured image', 'wa-golfs' ),
		'set_featured_image'       => esc_html__( 'Set featured image', 'wa-golfs' ),
		'remove_featured_image'    => esc_html__( 'Remove featured image', 'wa-golfs' ),
		'use_featured_image'       => esc_html__( 'Use as featured image', 'wa-golfs' ),
		'menu_name'                => esc_html__( 'Testimonies', 'wa-golfs' ),
		'filter_items_list'        => esc_html__( 'Filter testimonies list', 'wa-golfs' ),
		'filter_by_date'           => esc_html__( 'Filter testimonies date', 'wa-golfs' ),
		'items_list_navigation'    => esc_html__( 'Testimonies list navigation', 'wa-golfs' ),
		'items_list'               => esc_html__( 'Testimonies list', 'wa-golfs' ),
		'item_published'           => esc_html__( 'Testimony published.', 'wa-golfs' ),
		'item_published_privately' => esc_html__( 'Testimony published privately.', 'wa-golfs' ),
		'item_reverted_to_draft'   => esc_html__( 'Testimony reverted to draft.', 'wa-golfs' ),
		'item_scheduled'           => esc_html__( 'Testimony scheduled.', 'wa-golfs' ),
		'item_updated'             => esc_html__( 'Testimony updated.', 'wa-golfs' ),
	];
	$args = [
		'label'               => esc_html__( 'Testimonies', 'wa-golfs' ),
		'labels'              => $labels,
		'description'         => '',
		'public'              => true,
		'hierarchical'        => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'show_in_rest'        => false,
		'query_var'           => true,
		'can_export'          => true,
		'delete_with_user'    => true,
		'has_archive'         => true,
		'rest_base'           => '',
		'show_in_menu'        => true,
		'menu_position'       => '',
		'menu_icon'           => 'dashicons-editor-quote',
		'capability_type'     => 'post',
		'supports'            => ['editor', 'thumbnail'],
		'taxonomies'          => [],
		'rewrite'             => [
			'with_front' => false,
		],
	];

	register_post_type( 'testimony', $args );
	
}
?>