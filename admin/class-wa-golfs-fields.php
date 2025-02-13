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
                'type' => 'datetime',
                'desc' => __( 'Fill in the date & time of competition here.', 'wa-golfs' ),
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
                'name' => __( 'Competition departures', 'wa-golfs' ),
                'id'   => $prefix . 'competition_departures',
                'type' => 'file',
                'force_delete' => true,
                'max_file_uploads' => 1,
                'upload_dir' => ABSPATH . '/wp-content/uploads/competitions/',
                'mime_type' => ['text/csv'],
            ],
            [
                'name' => __( 'Preview', 'wa-rsfp' ),
                'id'   =>  $prefix . 'preview_competition_departures',
                'type' => 'previewcsv',
                'meta_value' => $prefix . 'competition_departures'
            ],

            [
                'name' => __( 'Competition results (brut)', 'wa-golfs' ),
                'id'   => $prefix . 'competition_results_brut',
                'type' => 'file',
                'force_delete' => true,
                'max_file_uploads'  => 1,
                'upload_dir' => ABSPATH . '/wp-content/uploads/competitions/',
                'mime_type' => ['text/csv'],
            ],
            [
                'name' => __( 'Preview', 'wa-rsfp' ),
                'id'   =>  $prefix . 'preview_competition_results_brut',
                'type' => 'previewcsv',
                'meta_value' => $prefix . 'competition_results_brut'
            ],
            [
                'name' => __( 'Competition results (net)', 'wa-golfs' ),
                'id'   => $prefix . 'competition_results_net',
                'type' => 'file',
                'force_delete' => true,
                'max_file_uploads'  => 1,
                'upload_dir' => ABSPATH . '/wp-content/uploads/competitions/',
                'mime_type' => ['text/csv'],
            ],
            [
                'name' => __( 'Preview', 'wa-rsfp' ),
                'id'   =>  $prefix . 'preview_competition_results_net',
                'type' => 'previewcsv',
                'meta_value' => $prefix . 'competition_results_net'
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
                'desc'              => __( 'Exemple : Blancs: 103 m - Jaunes: 101 m - Bleus: 93 m - Rouges: 90 m', 'wa-golfs' ),
            ],
            [
                'name'              => __( 'Number of strokes', 'wa-golfs' ),
                'id'                => $prefix . 'number_of_strokes',
                'type'              => 'text',
                'label_description' => __( 'Fill in the number of stroke for the course here.', 'wa-golfs' ),
                'desc'              => __( 'PAR', 'wa-golfs' ),
                // 'placeholder'       => __( 'PAR', 'wa-golfs' ),
            ],
            [
                'name'              => __( 'Handicap', 'wa-golfs' ),
                'id'                => $prefix . 'handicap',
                'type'              => 'text',
                'label_description' => __( 'Fill in the handicap for the course here.', 'wa-golfs' ),
                'desc'              => __( 'HCP', 'wa-golfs' ),
                // 'placeholder'       => __( 'HCP', 'wa-golfs' ),
            ],
            [
                'name'              => __( 'Green depth', 'wa-golfs' ),
                'id'                => $prefix . 'green',
                'type'              => 'text',
                'label_description' => __( 'Fill in the green depth for the course here.', 'wa-golfs' ),
                'desc'              => __( 'GREEN', 'wa-golfs' ),
                // 'placeholder'       => __( 'GREEN', 'wa-golfs' ),
            ],
            [
                'name'              => __( 'Altitude', 'wa-golfs' ),
                'id'                => $prefix . 'altitude',
                'type'              => 'text',
                'label_description' => __( 'Fill in the altitude in meters for the course here.', 'wa-golfs' ),
            ],

            [
                'name'              => __( 'Video', 'wa-golfs' ),
                'id'                => $prefix . 'c_video',
                'type'              => 'video',
                'max_file_uploads'  => 1,
                'force_delete'      => false,
                'required'          => false,
                'clone'             => false,
                'clone_empty_start' => false,
                'hide_from_rest'    => false,
            ],
            [
                'name'              => __( 'Course map', 'wa-golfs' ),
                'id'                => $prefix . 'c_course_map',
                'type'              => 'image_advanced',
                'force_delete'      => false,
                'required'          => false,
                'clone'             => false,
                'clone_empty_start' => false,
                'hide_from_rest'    => false,
            ],
        ],
    ];

    return $meta_boxes;
}

// Taxonomies 
