<?php
/**
 * Register the post types custom meta fields.
 *
 * @since    1.1.0
 */
function register_custom_meta_fields() {
	// Posts
	add_filter( 'rwmb_meta_boxes', 'competitions_fields', 10);
	add_filter( 'rwmb_meta_boxes', 'testimony_fields', 10);
	add_filter( 'rwmb_meta_boxes', 'course_fields', 10);

	// Taxonomies 

}

// Posts 
function competitions_fields( $meta_boxes ) {
    $prefix = 'c_';

    $meta_boxes[] = [
        'title'      => __( 'Competitions > General', 'wa-golfs' ),
        'id'         => 'competitions-general',
        'post_types' => ['competitions'],
        'fields'     => [
            [
                'name'    => __( 'State', 'wa-golfs' ),
                'id'      => $prefix . 'state',
                'type'    => 'select',
                'options' => [
                    'pending' => __( 'À venir', 'wa-golfs' ),
                    'current' => __( 'En cours', 'wa-golfs' ),
                    'ended'   => __( 'Terminée', 'wa-golfs' ),
                ],
            ],
            [
                'name' => __( 'Date', 'wa-golfs' ),
                'id'   => $prefix . 'date',
                'type' => 'date',
                'desc' => __( 'Fill in the date of competition here.', 'wa-golfs' ),
            ],
            [
                'type' => 'heading',
                'name' => __( 'FF Golf', 'wa-golfs' ),
            ],
            [
                'name' => __( 'External link', 'wa-golfs' ),
                'id'   => $prefix . 'external_link',
                'type' => 'url',
                'desc' => __( 'Fill in ffgolf external link to competition here.', 'wa-golfs' ),
            ],
            [
                'name' => __( 'Competition brut', 'wa-golfs' ),
                'id'   => $prefix . 'competition_brut',
                'type' => 'file',
            ],
            [
                'name' => __( 'Competition net', 'wa-golfs' ),
                'id'   => $prefix . 'competition_net',
                'type' => 'file',
            ],
            [
                'name' => __( 'Competition results', 'wa-golfs' ),
                'id'   => $prefix . 'competition_results',
                'type' => 'file',
            ],
        ],
    ];

    return $meta_boxes;
}

function testimony_fields( $meta_boxes ) {
    $prefix = 't_';

    $meta_boxes[] = [
        'title'      => __( 'Testimony › General', 'wa-golfs' ),
        'id'         => 'testimony-general',
        'post_types' => ['testimony'],
        'fields'     => [
            [
                'name'        => __( 'Name', 'wa-golfs' ),
                'id'          => $prefix . 'name',
                'type'        => 'text',
                'desc'        => __( 'Fill in the name of the person who wrote the testimony here.', 'wa-golfs' ),
                'placeholder' => __( 'Claude Dupont', 'wa-golfs' ),
            ],
            [
                'name'              => __( 'Position', 'wa-golfs' ),
                'id'                => $prefix . 'position',
                'type'              => 'text',
                'desc' 				=> __( 'Fill in the position or function of the person who wrote the testimony here.', 'wa-golfs' ),
                'placeholder' 		=> __( 'Responsable de golf', 'wa-golfs' ),
            ],
        ],
    ];

    return $meta_boxes;
}

function course_fields( $meta_boxes ) {
    $prefix = 'c_';

    $meta_boxes[] = [
        'title'      => __( 'Course › General', 'wa-golfs' ),
        'id'         => 'course-general',
        'post_types' => ['course'],
        'fields'     => [
            [
                'name'              => __( 'Distances', 'wa-golfs' ),
                'id'                => $prefix . 'distances',
                'type'              => 'text_list',
                'label_description' => __( 'Fill in the specific distances for the course here.', 'wa-golfs' ),
                'options'           => [
                    'Color'    => 'color',
                    'Distance' => 'distance',
                ],
				'clone'             => true,
                'sort_clone'        => true,
                'max_clone'         => 99,
            ],
            [
                'name'              => __( 'Number of strokes', 'wa-golfs' ),
                'id'                => $prefix . 'number_of_strokes',
                'type'              => 'text',
                'label_description' => __( 'Fill in the number of stroke for the course here.', 'wa-golfs' ),
            ],
        ],
    ];

    return $meta_boxes;
}

// Taxonomies 
