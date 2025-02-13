<?php
/**
 * Render particular custom post type pages in admin_menu 
 *
 * @since    1.7.0
 */

	/**
	 * Once competitions custom post type is registered, create a page in competitions sub menu called Calendar with a calendar as a month vue with all events ( competition )
	 */
	add_action('admin_menu', function() {
		add_submenu_page(
			'edit.php?post_type=competitions',
			esc_html__('Calendar', 'wa-golfs'),
			esc_html__('Calendar', 'wa-golfs'),
			'manage_options',
			'competitions-calendar',
			'competitions_calendar_page_callback'
		);
	});

	function competitions_calendar_page_callback() {
		echo '<div class="wrap">';
		echo '<h1>' . esc_html__('Competitions Calendar', 'wa-golfs') . '</h1>';
		echo '<div id="competitions-calendar"></div>';
		echo '</div>';
	}

	// Adds ajax events
	function get_competitions_events() {
		$events_query = new WP_Query(array(
		   'post_type' => 'competitions',
		   'posts_per_page' => -1, // fetch all events
		   // Additional query parameters if needed
		));
	 
		$events = array();
		if ($events_query->have_posts()) {
		   while ($events_query->have_posts()) {
				$events_query->the_post();
				// Add single event to the $events array
				$events[] = array(
					'title' => get_the_title(),
					'start' => get_post_meta(get_the_ID(), 'c_date', true),
					//'end' => get_post_meta(get_the_ID(), 'c_end_date', true)
					'state' => get_post_meta(get_the_ID(), 'c_state', true),
					'external_link' => get_post_meta(get_the_ID(), 'c_external_link', true),
					'permalink' => get_permalink(),
					'edit_link' => get_edit_post_link(),
					'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'medium')
				);			  
		   }
		   wp_reset_postdata();
		}
	 
		wp_send_json($events);
		wp_die();
	 }

	 add_action('wp_ajax_nopriv_get_competitions_events', 'get_competitions_events');
	 add_action('wp_ajax_get_competitions_events', 'get_competitions_events');

?>