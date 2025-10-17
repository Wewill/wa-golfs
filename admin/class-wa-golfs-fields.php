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
            // Ajouter une case à cocher "Fermeture des inscriptions" 
            [
                'name' => __( 'Close Registration', 'wa-golfs' ),
                'id'   => $prefix . 'close_registration',
                'type' => 'checkbox',
                'desc' => __( 'Close registration for some competition. e.q. : Coupe HDF, ...', 'wa-golfs' ),
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
                'mime_type' => 'text/csv',
            ],
            [
                'name' => __( 'Preview', 'wa-golfs' ),
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
                'mime_type' => 'text/csv',
            ],
            [
                'name' => __( 'Preview', 'wa-golfs' ),
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
                'mime_type' => 'text/csv',
            ],
            [
                'name' => __( 'Preview', 'wa-golfs' ),
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
                'name'              => __( 'Number', 'wa-golfs' ),
                'id'                => $prefix . 'number',
                'type'              => 'text',
                'label_description' => '<span class="label">INFO</span> ' . __( 'Fill in the course number.', 'wa-golfs' ),
            ],
            [
                'name'              => __( 'Distances', 'wa-golfs' ),
                'id'                => $prefix . 'distances',
                'type'              => 'text_list',
                'label_description' => '<span class="label">INFO</span> ' . __( 'Fill in the specific distances for the course here.', 'wa-golfs' ),
                'options'           => [
                    'Color'    => 'color',
                    'Distance' => 'distance',
                ],
				'clone'             => true,
                'sort_clone'        => true,
                'max_clone'         => 99,
                'desc'              => '<span class="label">INFO</span> ' . __( 'Exemple : Blancs: 103 m - Jaunes: 101 m - Bleus: 93 m - Rouges: 90 m', 'wa-golfs' ),
            ],
            [
                'name'              => __( 'Number of strokes', 'wa-golfs' ),
                'id'                => $prefix . 'number_of_strokes',
                'type'              => 'text',
                'label_description' => '<span class="label">INFO</span> ' . __( 'Fill in the number of stroke for the course here.', 'wa-golfs' ),
                'desc'              => __( 'PAR', 'wa-golfs' ),
                // 'placeholder'       => __( 'PAR', 'wa-golfs' ),
            ],
            [
                'name'              => __( 'Handicap', 'wa-golfs' ),
                'id'                => $prefix . 'handicap',
                'type'              => 'text',
                'label_description' => '<span class="label">INFO</span> ' . __( 'Fill in the handicap for the course here.', 'wa-golfs' ),
                'desc'              => __( 'HCP', 'wa-golfs' ),
                // 'placeholder'       => __( 'HCP', 'wa-golfs' ),
            ],
            [
                'name'              => __( 'Green depth', 'wa-golfs' ),
                'id'                => $prefix . 'green',
                'type'              => 'text',
                'label_description' => '<span class="label">INFO</span> ' . __( 'Fill in the green depth for the course here.', 'wa-golfs' ),
                'desc'              => __( 'GREEN', 'wa-golfs' ),
                // 'placeholder'       => __( 'GREEN', 'wa-golfs' ),
            ],
            [
                'name'              => __( 'Altitude', 'wa-golfs' ),
                'id'                => $prefix . 'altitude',
                'type'              => 'text',
                'label_description' => '<span class="label">INFO</span> ' . __( 'Fill in the altitude in meters for the course here.', 'wa-golfs' ),
            ],
            [
                'name'              => __( 'Course map', 'wa-golfs' ),
                'id'                => $prefix . 'course_map',
                'type'              => 'image_advanced',
                'force_delete'      => false,
                'required'          => false,
                'clone'             => false,
                'clone_empty_start' => false,
                'hide_from_rest'    => false,
            ],
            [
                'name'       => __( 'Introduction', 'wa-golfs' ),
                'id'         => $prefix . 'introduction',
                'type'       => 'textarea',
                // 'required'   => true,
                'limit'      => 350,
                'rows'       => 5,
                'class' => 'enable-markdown',
				'label_description' => '<span class="label">INFO</span> ' . __( 'Fill with simple text', 'wa-golfs' ),
				'desc' => '<span class="label">TIPS</span> ' . __( 'Lead content will be showed after title', 'wa-golfs' ) . '<br/>' .   __( '<span class="label">TIPS</span> Markdown is available : *italic* (Command + b) **bold** (Command + i) ***label*** (Command + Shift + L) #small# (Command + Shift + S) ##huge## (Command + Shift + H)', 'wa-golfs' ),
            ],

        ],
    ];

    // Medias 
	$meta_boxes[] = [
		'title'      => __( 'Course › Medias', 'wa-golfs' ),
		'id'         => 'course-medias',
		'post_types' => ['course'],
		'fields'     => [
            [
                'type'       => 'divider',
                'before'      => '<span class="label">INFO</span> ' . __( '<b>Featured image</b> please choose an image below on side panel.', 'wa-golfs' ),
                'save_field' => false,
            ],
            [
                'name'             => __( 'Gallery files', 'wa-golfs' ),
                'id'               => $prefix . 'medias_gallery',
                'type'             => 'image_advanced',
                'desc'             => __( '<span class="important">Maximum 20 images.</span>', 'wa-golfs' ),
                'max_file_uploads' => 20,
				'label_description' => '<span class="label">INFO</span>' .__( 'Choose one or many image.s', 'wa-golfs' ),
            ],
            [
                'name' => __( 'Vimeo & YouTube video link', 'wa-golfs' ),
                'id'   => $prefix . 'medias_video_link',
                'type' => 'url',
                'clone'      => true,
                'sort_clone' => true,
                'max_clone'  => 20,
                'label_description' => '<span class="label">INFO</span> ' . __( 'Recommanded : choose an external video link from online platform', 'wa-golfs' ),
            ],
            [
                'name' => __( 'Video file', 'wa-golfs' ),
                'id'   => $prefix . 'medias_video',
                'type' => 'video',
				'label_description' => '<span class="label">INFO</span> ' . __( 'Upload a video file directly to the media library', 'wa-golfs' ),
				'desc' => '<span class="label">TIPS</span> ' . __( 'Video has to be *.mp4 well compressed format.', 'wa-golfs' ),
            ],
			[
                'name'             => __( 'Files', 'wa-golfs' ),
                'id'               => $prefix . 'medias_files',
                'type'             => 'file_upload',
                'desc'             => __( '<span class="important">Maximum 10 files.</span>', 'wa-golfs' ),
                'max_file_uploads' => 10,
				'label_description' => '<span class="label">INFO</span> ' . __( 'Upload one or many file.s', 'wa-golfs' ),
            ],
		],
	];

    return $meta_boxes;
}

// Taxonomies 
