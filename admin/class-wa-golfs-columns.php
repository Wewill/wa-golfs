<?php
/*
	Customize Columns 
	http://code.tutsplus.com/articles/add-a-custom-column-in-posts-and-custom-post-types-admin-screen--wp-24934
*/

/*
--------------
USER
*/

/*
  Adds Post Counts by Post Type per User in the User List withing WordPress' Admin console (URL path => /wp-admin/users.php)
  Written for: http://wordpress.stackexchange.com/questions/3233/showing-users-post-counts-by-custom-post-type-in-the-admins-user-list
  By: Mike Schinkel (http://mikeschinkel.com)
  Date: 24 October 2010
*/

add_filter('manage_users_columns' , 'add_extra_user_column', 20);
function add_extra_user_column($columns) {
    unset($columns['posts']);
    return array_merge( $columns, 
		array(
				'posted' => __( 'Posted', 'wa-golfs'),
				)
		);
}


add_filter('manage_users_sortable_columns' , 'add_extra_user_sortable_column', 20);
function add_extra_user_sortable_column($columns) {
    return array_merge( $columns, 
		array(
			'role' => 'role',
			)
		);
}

add_action('manage_users_custom_column',  'users_manage_columns', 20, 3);
function users_manage_columns($value, $column_name, $user_id) {
    //$user = get_userdata( $user_id );
    switch ($column_name) {
		case 'posted' :
			$counts = get_author_post_type_counts();
		    $custom_column = array();
		    if (isset($counts[$user_id]) && is_array($counts[$user_id]))
		      foreach($counts[$user_id] as $count) {
		      	$link_to = 'href="'.admin_url('edit.php?post_type='.$count['post_type'].'&author='.$user_id.'&edition=0').'"';
		        $custom_column[] = "\t<tr><th>{$count['label']}</th>" .
		                             "<td><a {$link_to}>{$count['count']}</a></td></tr>";
		      }
		    $custom_column = implode("\n",$custom_column);
		    if (empty($custom_column))
		      $custom_column = "<th> — </th>";
		    $custom_column = "<table>\n{$custom_column}\n</table>";
		    $value = $custom_column;
        default:
            break;
    }
    return $value;
}

/*add_action('pre_user_query','manage_pre_user_query');
function manage_pre_user_query($user_search) {
    global $wpdb,$current_screen;

    if ( 'users' != $current_screen->id ) 
        return;

    $vars = $user_search->query_vars;

    switch ($vars['orderby']) {
		case 'role' :
		        $user_search->query_from .= " INNER JOIN {$wpdb->usermeta} m1 ON {$wpdb->users}.ID=m1.user_id AND (m1.meta_key='role')"; 
		        $user_search->query_orderby = ' ORDER BY UPPER(m1.meta_value) '. $vars['order'];
			break;
        default:
            break;
    }
}*/

function get_author_post_type_counts() {
  static $counts;
  if (!isset($counts)) {
    global $wpdb;
    global $wp_post_types;
    $sql = <<<SQL
	SELECT
	post_type,
	post_author,
	COUNT(*) AS post_count
	FROM
	{$wpdb->posts}
	WHERE 1=1
	AND post_type NOT IN ('revision','nav_menu_item', 'oembed_cache')
	AND post_status IN ('publish','pending')
	GROUP BY
	post_type,
	post_author
	SQL;
    $posts = $wpdb->get_results($sql);
    foreach($posts as $post) {
	    $post_type_object = $wp_post_types[$post_type = $post->post_type];
	     
	    if (!empty($post_type_object->label))
	    	$label = $post_type_object->label;
	    else if (!empty($post_type_object->labels->name))
	    	$label = $post_type_object->labels->name;
	    else
	    	$label = ucfirst(str_replace(array('-','_'),' ',$post_type));
	      	if (!isset($counts[$post_author = $post->post_author]))
	      		$counts[$post_author] = array();
	      	$counts[$post_author][] = array(
	        	'post_type' => $post_type,
	        	'label' => $label,
	        	'count' => $post->post_count,
	        );
    }
  }
  return $counts;
}

/*
--------------
* Post : competitions
*/


// manage_edit-{$post_type}_columns
add_filter( 'manage_edit-competitions_columns', 'competitions_columns' ) ;
function competitions_columns( $columns ) {
	$columns['c_state'] = __( 'State', 'wa-golfs');
	$columns['c_date'] = __( 'Competition date', 'wa-golfs');
	$columns['_c_data'] = __( 'Data', 'wa-golfs');
	$columns['c_external_link'] = __( 'External link', 'wa-golfs');
	return $columns;
}

// manage_edit-{$post_type}_sortable_columns
add_filter( 'manage_edit-competitions_sortable_columns', 'competitions_sortable_columns' );
function competitions_sortable_columns( $columns ) {
	$columns['c_state'] = 'c_state';
	$columns['c_date'] = 'c_date';
	return $columns;
}

// manage_{$post_type}_posts_custom_column
add_action("manage_competitions_posts_custom_column", 'competitions_manage_columns', 10, 2);
function competitions_manage_columns($column_name, $post_id) {
    switch ($column_name) {
		case 'c_state' :
			echo get_state($column_name, $post_id);
            break;
		case 'c_date' :
			echo get_meta($column_name, $post_id);
            break;
		case 'c_external_link' :
			echo get_meta_link($column_name, $post_id);
            break;
		case '_c_data' :
			$fields = [
				'c_competition_results_brut' => 'Résultats bruts',
				'c_competition_results_net' => 'Résultats nets',
				'c_competition_departures' => 'Départs'
			];

			foreach ($fields as $field => $label) {
				$file_url = get_post_meta($post_id, $field, true);
				if ($file_url) {
					$file_info = pathinfo($file_url);
					if (isset($file_info['extension']) && strtolower($file_info['extension']) === 'csv') {
						echo '<div style="color:rgb(66, 149, 66);">' . $label . ' <span class="dashicons dashicons-yes-alt" style="color:rgb(66, 149, 66);"></span></div>';
					} else {
						echo '<div style="color:silver;">' . $label . ' <span class="dashicons dashicons-no-alt"></span></div>';
					}
				} else {
					echo '<div style="color:silver;">' . $label . ' <span class="dashicons dashicons-no-alt"></span></div>';
				}
			}
			break;	
   		default:
            break;
    }

}

/*
--------------
* Post : course
*/


// manage_edit-{$post_type}_columns
add_filter( 'manage_edit-course_columns', 'course_columns' ) ;
function course_columns( $columns ) {
	$columns['c_number_of_strokes'] = __( 'Number of strokes', 'wa-golfs');
	$columns['c_handicap'] = __( 'Handicap', 'wa-golfs');
	$columns['c_green'] = __( 'Green depth', 'wa-golfs');
	return $columns;
}

// manage_edit-{$post_type}_sortable_columns
add_filter( 'manage_edit-course_sortable_columns', 'course_sortable_columns' );
function course_sortable_columns( $columns ) {
	$columns['c_number_of_strokes'] = 'c_number_of_strokes';
	$columns['c_handicap'] = 'c_handicap';
	$columns['c_green'] = 'c_green';
	return $columns;
}

// manage_{$post_type}_posts_custom_column
add_action("manage_course_posts_custom_column", 'course_manage_columns', 10, 2);
function course_manage_columns($column_name, $post_id) {
    switch ($column_name) {
		case 'c_number_of_strokes' :
			echo get_meta($column_name, $post_id);
            break;
		case 'c_handicap' :
			echo get_meta($column_name, $post_id);
			break;
		case 'c_green' :
			echo get_meta($column_name, $post_id);
			break;
   		default:
            break;
    }

}


/*
--------------
* Post : testimony
*/


// manage_edit-{$post_type}_columns
add_filter( 'manage_edit-testimony_columns', 'testimony_columns' ) ;
function testimony_columns( $columns ) {
	//Here remove title column in $columns array
	$columns['t_name'] = __( 'Name', 'wa-golfs');
	$columns['_t_excerpt'] = __( 'Testimony excerpt', 'wa-golfs');
	return $columns;
}

// manage_edit-{$post_type}_sortable_columns
add_filter( 'manage_edit-testimony_sortable_columns', 'testimony_sortable_columns' );
function testimony_sortable_columns( $columns ) {
	$columns['t_name'] = 't_name';
	return $columns;
}

// manage_{$post_type}_posts_custom_column
add_action("manage_testimony_posts_custom_column", 'testimony_manage_columns', 10, 2);
function testimony_manage_columns($column_name, $post_id) {
    switch ($column_name) {
		case 't_name' :
			echo get_meta($column_name, $post_id);
            break;
		case '_t_excerpt' :
			echo get_content_excerpt($post_id);
            break;
   		default:
            break;
    }

}

/**
 * Helpers
 */

 

//Get state 
function get_state($column_name, $post_id) {
	$stateColors = array(
		'pending' => array(
			'textColor' => 'rgb(236, 173, 39)',
			'backgroundColor' => 'rgb(249, 235, 204)',
		),
		'current' => array(
			'textColor' => 'rgb(66, 149, 66)',
			'backgroundColor' => 'rgb(182, 222, 182)',
		),
		'ended' => array(
			'textColor' => 'rgb(171, 171, 171)',
			'backgroundColor' => 'rgb(226,226,226)',
		),
	);
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

	$state = get_post_meta($post_id, $column_name, true );
	if ( empty( $state ) ) {
		echo __( '<span style="color:silver;">—</span>' );
	} else {
		$color = $stateColors[$state];
		$label = $stateLabels[$state]['label'];
		printf(
			__( '<span class="label" style="color:%s; background-color:%s;">%s</span>' ),
			$color['textColor'],
			$color['backgroundColor'],
			$label
		);
	}
}



// Get content 
function get_content_excerpt($post_id) {
	$post = get_post($post_id);
	$content = $post->post_content;
	$excerpt = wp_trim_words( $content, 10, '...' );
	return $excerpt;
}

// Get attachment_id form an image url  
function get_attachment_id_from_url($url) {
	global $wpdb;
	$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$url'";
	return $wpdb->get_var($query);
}

// Get h2 
function get_h2($fd) {
	if ( empty( $fd ) )
		echo __( '<span style="color:silver;">—</span>' ); // marker.png
	else
		printf( __( '<h2>%s</h2>' ), $fd );
}

// Get price 
function get_price($fd) {
	if ( empty( $fd ) )
		echo __( '<span style="color:silver;">—</span>' ); // marker.png
	else
		printf( __( '<h2>%s €</h2>' ), $fd );
}

// Get percentage 
function get_percentage($fd) {
	if ( empty( $fd ) )
		echo __( '<span style="color:silver;">—</span>' ); // marker.png
	else
		printf( __( '<h2>%s &#37;</h2>' ), $fd );
}

// Get code 
function get_code($fd) {
	if ( empty( $fd ) )
		echo __( '<span style="color:silver;">—</span>' ); // marker.png
	else
		printf( __( '<code>%s</code>' ), $fd );
}

// Get code 
function get_color($fd) {
	if ( empty( $fd ) )
		echo __( '<span style="color:silver;">—</span>' ); // marker.png
	else
		printf( __( '<div style="background-color:%s;border:1px solid #bababa;width:20px;height:20px;"></span>' ), $fd );
}

// Get Image 
function get_image($fd) {
	if ( function_exists( 'wpcf_fields_image_resize_image' ) ) 
		$attachment_id = get_attachment_id_from_url($fd);
		$image = wp_get_attachment_image_src( $attachment_id, 'thumbnail');

	if ( empty( $image ) )
		echo __( '<span class="empty">∅</span>' ); // marker.png
	else
		printf( __( '<div class="tax-img-holder"><img class="thumbnail" src="%s" alt="Image" width="50" /></div>' ), $image[0] );
}

// Get Image 
function get_image_fromid($attachment_id) {
	$image = wp_get_attachment_image_src( $attachment_id, 'thumbnail');
	if ( empty( $image ) )
		echo __( '<span class="empty">—</span>' ); // marker.png
	else
		printf( __( '<div class="tax-img-holder"><img class="thumbnail" src="%s" alt="Image" width="50" /></div>' ), $image[0] );
}

//Get meta
function get_meta_usermeta($column_name, $user_id) {
	$meta = get_user_meta($user_id, $column_name, true );
	if ( empty( $meta ) )
		return __( '<span style="color:silver;">—</span>' );
	else
		return '<h3>' . $meta . '</h3>';
}

//Get meta
function get_meta_usercb($column_name, $user_id) {
	$meta = get_user_meta($user_id, $column_name, true );
	if ( empty( $meta ) )
		return __( '<span style="color:silver;">—</span>' );
	else
		return '<span class="dashicons-before dashicons-yes"></span>';
}

//Get meta
function get_meta($column_name, $post_id) {
	$meta = get_post_meta($post_id, $column_name, true );
	if ( empty( $meta ) )
		echo __( '<span style="color:silver;">—</span>' );
	else
		printf( __( '<h3>%s</h3>' ), $meta);
}

//Get link
function get_meta_link($column_name, $post_id) {
	$meta = get_post_meta($post_id, $column_name, true );
	if ( empty( $meta ) )
		echo __( '<span style="color:silver;">—</span>' );
	else
		printf( __( '<a href="%s"><span class="new"><span class="dashicons-before dashicons-admin-links"></span> Lien FFGOLF</span></a>' ), $meta);
}

//Get recursive meta
function get_recursive_meta($column_name, $post_id) {
	$meta = get_post_meta($post_id, $column_name, true );
	if ( empty( $meta ) )
		echo __( '<span style="color:silver;">—</span>' );
	else
		printf( __( '<h3>%s</h3>' ), implode(',', $meta));
}

//Get time
function get_time_bymeta($column_name, $post_id) {
	$meta = get_post_meta($post_id, $column_name, true );
	if ( empty( $meta ) )
		echo __( '<span style="color:silver;">—</span>' );
	else
		printf( __( '<h5><span class="dashicons-before dashicons-clock"></span> %s min.</h6>' ), $meta);
}

// Get post
function get_post_bymeta($column_name, $post_id) {
	$meta = get_post_meta($post_id, $column_name, true );
	$post_title = get_the_title($meta);
	if ( empty( $meta ) )
		echo __( '<span style="color:silver;">—</span>' );
	else
		printf( __( '<h3>%s</h3>' ), $post_title);
}

//Get post w/ link
function get_postlink_bymeta($column_name, $post_id) {
	$meta = get_post_meta($post_id, $column_name, true );
	$post_title = get_the_title($meta);
	$post_link = get_edit_post_link($meta);
	if ( empty( $meta ) )
		echo __( '<span style="color:silver;">—</span>' );
	else
		printf( __( '<h3><a href="%s">%s</a></h3>' ), $post_link, $post_title);
}

//Get post w/ link in a btn 
function get_postlinkbtn_bymeta($column_name, $post_id) {
	$meta = get_post_meta($post_id, $column_name, true );
	$post_title = get_the_title($meta);
	$post_link = get_edit_post_link($meta);
	if ( empty( $meta ) )
		echo __( '<span style="color:silver;">—</span>' );
	else
		printf( __( '<br/><a href="%s" title="%s" class="page-title-action">View</a>' ), $post_link, $post_title);
}


//Display Post Thumbnail Also In Edit Post and Page Overview
//http://www.hongkiat.com/blog/wordpress-tweaks-for-post-management/

if ( !function_exists('wa_addthumbcolumn') && function_exists('add_theme_support') ) {

	add_theme_support('post-thumbnails', array( 'competitions', 'course') );
	
	function wa_addthumbcolumn($cols) {

		$newcols = array();
		foreach($cols as $key => $title) {
			if ($key=='title') // Put the Thumbnail column before the Title column
				//$newcols['thumbnail'] = __('Thumbnail');
				$newcols['thumbnail'] = __('<span class="dashicons-before dashicons-visibility" style="color:silver;"></span>');
			$newcols[$key] = $title;
		}
		return $newcols;

	    //$cols['thumbnail'] = __('Thumbnail');
	    //return $cols;
	}
	 
	function wa_addthumbvalue($column_name, $post_id) {
	    $width = (int) 45;
	    $height = (int) 45;
	    if ( 'thumbnail' == $column_name ) {
	        // thumbnail of WP 2.9
	        $thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
	         
	        // image from gallery
	        $attachments = get_children( array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image') );
	         
	        if ($thumbnail_id)
	            $thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
	        elseif ($attachments) {
	            foreach ( $attachments as $attachment_id => $attachment ) {
	            $thumb = wp_get_attachment_image( $attachment_id, array($width, $height), true );
	        }
	    }
	    if ( isset($thumb) && $thumb ) { echo $thumb; }
	    else { echo __('—'); }
	    }
	}
	 
	// for posts only
	add_filter( 'manage_competitions_posts_columns', 'wa_addthumbcolumn' , 99);
	add_action( 'manage_competitions_posts_custom_column', 'wa_addthumbvalue', 10, 2 ); // NE PAS AFFICHER SI PRESENT DANS LE THEME

	add_filter( 'manage_course_posts_columns', 'wa_addthumbcolumn' , 99);
	add_action( 'manage_course_posts_custom_column', 'wa_addthumbvalue', 10, 2 ); // NE PAS AFFICHER SI PRESENT DANS LE THEME
}