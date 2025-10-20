<?php

/**
 * Register the blocks w/ metabox.io
 *
 * @since    1.1.0
 */

function add_custom_pagination_query_vars($vars) {
	$vars[] = 'paged_upcoming';
	$vars[] = 'paged_ended';
	return $vars;
}
add_filter('query_vars', 'add_custom_pagination_query_vars');

//**
// Allow
// */

// Allow custom blocks considering post_type 
function allow_blocks() {
	// Blocks 
	add_filter( 'allowed_block_types_all', 'post_type_allowed_block_types', 20, 2 );

}

function post_type_allowed_block_types( $allowed_blocks = array(), $editor_context ) {

	// error_log( "PLUGIN post_type_allowed_block_types :: allowed_blocks :: " . print_r($allowed_blocks, true)  );
	// error_log( "PLUGIN post_type_allowed_block_types :: editor_context :: " . print_r($editor_context, true)  );

	// competition
	if ( isset( $editor_context->post ) && $editor_context->post->post_type === 'competition' ) {
		return array(
			'core/image', 
			'core/heading', 
			'core/paragraph', 
			'core/list', 
			'core/quote', 
			'core/pullquote', 
			'core/block', 
			'core/button', 
			'core/buttons', 
			'core/column', 
			'core/columns', 
			'core/table', 
			'core/text-columns', 
			//
			'coblocks/accordion',
			'coblocks/accordion-item',
			'coblocks/alert',
			'coblocks/counter',
			'coblocks/column',
			'coblocks/row',
			'coblocks/dynamic-separator',
			'coblocks/logos',
			'coblocks/icon',
			'coblocks/buttons',			
			// Remplacez ceci par l'identifiant du bloc que vous souhaitez autoriser
			// Ajoutez d'autres identifiants de blocs au besoin
			// 'directory/wa-golfs-competition-block',
		);
	}
	
	// Because the theme restricts the blocks, add here custom blocks created in the plugin
	if ( isset( $editor_context->post ) && $editor_context->name !== 'core/edit-widgets' ) {
		// Add custom block
		$allowed_blocks[] = 'directory/wa-golfs-competition-block';
		// Add metabox.io testimony block
		$allowed_blocks[] = 'meta-box/wa-golfs-testimony';
		$allowed_blocks[] = 'meta-box/wa-golfs-courses';
		$allowed_blocks[] = 'meta-box/wa-golfs-competitions';
	}

	return $allowed_blocks;
}		

//**	
// Register
// */

// Register Theme Blocks >> need rwmb_meta_boxes
// Register via MetaBox.io block
function register_custom_blocks() {
	// Blocks
	add_filter( 'rwmb_meta_boxes', 'register_blocks');

}

function register_blocks( $meta_boxes ) {
	$prefix = 'waff_golfs_';

	/**
	 * Testimony block
	 */
	$meta_boxes[] = [
		'title'           => __( '(GOLF) Testimony', 'wa-golfs' ),
		'description'     => esc_html__( 'Display random testimonies.', 'wa-golfs' ),
		'keywords'        => ['testimony', 'posts'],
		'id'              => 'wa-golfs-testimony',
		'category'        => 'layout',
		// 'icon'			  => 'format-quote',
		'icon'            => [
			'foreground' 	=> '#1d7c43',
			'src' 			=> 'format-quote',
		],
		'supports'        => [
			'anchor'          => true,
			'customClassName' => true,
			'align' => ['wide', 'full'],
		],
		'type'            => 'block',
		'context'         => 'side',
		// Fields
		'fields'          => [
			[
				'id'   => $prefix . 't_title',
				'type' => 'text',
				'name' => esc_html__( 'Title', 'wa-golfs' ),
				'placeholder' => esc_html__( 'Testimony', 'wa-golfs' ),
			],
			[
				'id'   => $prefix . 't_background_class',
				'type' => 'text',
				'name' => esc_html__( 'CSS background color', 'wa-golfs' ),
				'std'  => 'bg-action-1',
			],
			[
				'id'   => $prefix . 't_text_class',
				'type' => 'text',
				'name' => esc_html__( 'CSS text color', 'wa-golfs' ),
				'std'  => 'text-dark',
			],

		],
		// Render
		'render_callback' => function( $attributes ) {
			$preview = defined( 'REST_REQUEST' ) && REST_REQUEST ?? true;

			$prefix = 'waff_golfs_';

			// Fields data.
			// if ( empty( $attributes['data'] ) ) {
			// return;
			// }

			// Unique HTML ID if available.
			$id = '';
			if ( $attributes['name'] ) {
				$id = $attributes['name'] . '-';
			} elseif (  $attributes['data']['name'] ) {
				$id = $attributes['data']['name'] . '-';
			}
			$id .= ( $attributes['id'] && $attributes['id'] !== $attributes['name']) ? $attributes['id'] : wp_generate_uuid4();
			if ( $attributes['anchor'] ) {
				$id = $attributes['anchor'];
			}
			// Block class
			$themeClass = 'testimony --mt-10 --mb-10 contrast--light';
			$class = $themeClass . ' ' . ( $attributes['className'] ?? '' );
			if ( ! empty( $attributes['align'] ) ) {
				$class .= " align{$attributes['align']}";
			}
			// Animation 
			$data = '';
			$animation_class = '';
			if ( ! empty( $attributes['animation'] ) ) {
				$animation_class .= " coblocks-animate";
				$data .= " data-coblocks-animation='{$attributes['animation']}'";
			}
			
			$t_background_class = mb_get_block_field( $prefix . 't_background_class');
			$t_text_class = mb_get_block_field( $prefix . 't_text_class');
			$t_title = mb_get_block_field( $prefix . 't_title');

			// Display notice if block isn't full
			if ( $attributes['align'] !== 'full' && current_user_can('administrator') && !is_front_page() ) {
				echo '<div class="alert alert-dismissible alert-warning fade show" role="alert">
					<strong>Oh snap!</strong> '
					. esc_html__( 'For a better experience, this block renders better in full size.', 'wa-golfs' ) .
					'<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
				</div>';
			}
			?>
			<section id="<?= $id ?>" class="<?= $t_background_class ? $t_background_class : 'bg-action-1 default' ?> <?= $class ?> <?= $animation_class ?>" <?= $data ?>>

				<div class="container-fluid p-3 p-sm-4 p-md-8">
					<div class="row">
						<div class="col-md-4">
							<h6 class="subline <?= $t_text_class ? $t_text_class : 'text-dark default' ?>"><?= $t_title ? $t_title : 'Témoignage' ?></h6>
							<InnerBlocks />
						</div>
						<div class="col">
							<?php
							$args = array(
								'post_type' => 'testimony',
								'posts_per_page' => 1,
								'orderby' => 'rand'
							);
							$testimony_query = new WP_Query( $args );
							if ( $testimony_query->have_posts() ) :
								while ( $testimony_query->have_posts() ) : $testimony_query->the_post();
									$t_name = get_post_meta( get_the_ID(), 't_name', true );
									$t_position = get_post_meta( get_the_ID(), 't_position', true );
									$content = get_the_content();
									$content = wp_trim_words( $content, 40, '...' ); // Limit content to 200 characters
							?>
									<h2 class="<?= $t_text_class ? $t_text_class : 'text-dark default' ?> subline-3 lh-base">"<?= $content ?>"</h2>
									<div class="d-flex align-items-center mt-4">
										<?php if ( has_post_thumbnail() ) : ?>
											<?php the_post_thumbnail( 'thumbnail', ['class' => 'w-50-px h-50-px bg-cover img-fluid rounded-circle me-3', 'alt' => esc_attr( $t_name ), 'style' => 'filter: grayscale() contrast(1.3) brightness(0.8)'] ); ?>
										<?php else : ?>
											<div class="w-50-px h-50-px bg-black rounded-circle me-3"></div>
										<?php endif; ?>
										<div>
											<p class="fw-bold mb-0"><?= esc_html( $t_name ); ?></p>
											<p class="muted mb-0"><?= esc_html( $t_position ); ?></p>
										</div>
									</div>
							<?php
								endwhile;
								wp_reset_postdata();
							endif;
							?>
						</div>
					</div>
				</div>

			</section>
			<?php
		},
	];

	/**
	 * Courses block
	 */
	$meta_boxes[] = [
		'title'           => __( '(GOLF) Courses', 'wa-golfs' ),
		'description'     => esc_html__( 'Display featured random courses or all courses.', 'wa-golfs' ),
		'keywords'        => ['course', 'posts'],
		'id'              => 'wa-golfs-courses',
		'category'        => 'layout',
		// 'icon'			  => 'location-alt',
		'icon'            => [
			'foreground' 	=> '#1d7c43',
			'src' 			=> 'location-alt',
		],
		'supports'        => [
			'anchor'          => true,
			'customClassName' => true,
			'align' => ['wide', 'full'],
		],
		'type'            => 'block',
		'context'         => 'side',
		// Fields
		'fields'          => [
			[
				'id'   => $prefix . 'c_title',
				'type' => 'text',
				'name' => esc_html__( 'Title', 'wa-golfs' ),
				'placeholder' => esc_html__( 'Featured courses', 'wa-golfs' ),
			],
			[
				'id'   => $prefix . 'c_subtitle',
				'type' => 'text',
				'name' => esc_html__( 'Subtitle (first)', 'wa-golfs' ),
				// 'std'  => esc_html__( 'Edito', 'wa-golfs' ),
				'placeholder' => esc_html__( 'An awesome subtitle', 'wa-golfs' ),
			],
			// [
			// 	'id'   => $prefix . 'c_content',
			// 	'type' => 'wysiwyg', //textarea
			// 	'name' => esc_html__( 'Content', 'wa-golfs' ),
			// 	'desc' => esc_html__( 'Content will be displayed as cols. Markdown is available.', 'wa-golfs' ),
			// ], >> Innerblocks
			// Switch display between featured or all courses 
			[
				'id'   => $prefix . 'c_display',
				'type' => 'select',
				'name' => esc_html__( 'Display', 'wa-golfs' ),
				'options' => [ 	
					'featured' => esc_html__( 'Featured', 'wa-golfs' ),
					'all' => esc_html__( 'All', 'wa-golfs' ),
				],
				'std'  => 'featured',
			],
		],
		// Render
		'render_callback' => function( $attributes ) {
			$preview = defined( 'REST_REQUEST' ) && REST_REQUEST ?? true;

			$prefix = 'waff_golfs_';
			// Fields data.
			// if ( empty( $attributes['data'] ) ) {
			// return;
			// }

			// Unique HTML ID if available.
			$id = '';
			if ( $attributes['name'] ) {
				$id = $attributes['name'] . '-';
			} elseif (  $attributes['data']['name'] ) {
				$id = $attributes['data']['name'] . '-';
			}
			$id .= ( $attributes['id'] && $attributes['id'] !== $attributes['name']) ? $attributes['id'] : wp_generate_uuid4();
			if ( $attributes['anchor'] ) {
				$id = $attributes['anchor'];
			}
			// Block class
			$themeClass = 'course --mt-10 --mb-10 contrast--light bg-action-3';
			$class = $themeClass . ' ' . ( $attributes['className'] ?? '' );
			if ( ! empty( $attributes['align'] ) ) {
				$class .= " align{$attributes['align']}";
			}
			// Animation 
			$data = '';
			$animation_class = '';
			if ( ! empty( $attributes['animation'] ) ) {
				$animation_class .= " coblocks-animate";
				$data .= " data-coblocks-animation='{$attributes['animation']}'";
			}
			
			$c_title = mb_get_block_field( $prefix . 'c_title');
			$c_subtitle = mb_get_block_field( $prefix . 'c_subtitle');
			$c_display = mb_get_block_field( $prefix . 'c_display');

			// Display notice if block isn't full
			// if ( $attributes['align'] !== 'full' && current_user_can('administrator') && !is_front_page() ) {
			// 	echo '<div class="alert alert-dismissible alert-warning fade show" role="alert">
			// 		<strong>Oh snap!</strong> '
			// 		. esc_html__( 'For a better experience, this block renders better in full size.', 'wa-golfs' ) .
			// 		'<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
			// 	</div>';
			// }
			?>
			<section id="<?= $id ?>" class="<?= $class ?> <?= $animation_class ?>" <?= $data ?> style="background-color: var(--waff-action-3-lighten-3); color: var(--waff-action-3-inverse);">

				<div class="container-fluid p-3 p-sm-4 p-md-8">

					<div class="d-flex justify-content-between align-items-center mb-6">
						<hgroup>
							<h6 class="subline text-action-3"><?= $c_subtitle ?></h6>
							<h3 class="text-dark"><?= $c_title ?></h3>
						</hgroup>
						<div class="max-w-50"><InnerBlocks /></div>	
					</div>

					<div class="row" <?= $preview ? 'style="display:flex;"' : ''; ?>>

						<style scoped>
							div.framefilter {
								-webkit-backdrop-filter: blur(6px); /* Add this line first, it fixes blur for Safari*/ 
								backdrop-filter: blur (6px);
								background-color: rgba(0, 0, 0, 0.05);
							}

							#<?= $id ?> .card.c-card {
								/* height: 500px; */
								min-height: <?= $c_display === 'featured' ? '350px' : '250px' ?>;
							}

							@media (min-width: 768px) {
								#<?= $id ?> .card.c-card {
									height: <?= $c_display === 'featured' ? '32vw' : '24vw' ?> !important;
								}
							}

						</style>

						<?php
						$args = array(
							'post_type' => 'course',
							'posts_per_page' => $c_display === 'featured' ? 2 : -1,
							'orderby' => $c_display === 'featured' ? 'rand' : 'menu_order',
						);
						$course_query = new WP_Query( $args );
						if ( $course_query->have_posts() ) :
							while ( $course_query->have_posts() ) : $course_query->the_post();
								$c_number_of_strokes = get_post_meta( get_the_ID(), 'c_number_of_strokes', true );
								$c_handicap = get_post_meta( get_the_ID(), 'c_handicap', true );
								$c_green = get_post_meta( get_the_ID(), 'c_green', true );
								$c_altitude = get_post_meta( get_the_ID(), 'c_altitude', true );
								$content = get_the_content();
								$content = wp_trim_words( $content, $c_display === 'featured' ? 40: 10, '...' ); // Limit content to 200 characters
						?>
								<div class="<?= $c_display === 'featured' ? 'col-md-6' : 'col-sm-4 col-md-3' ?> mb-4" <?= $preview ? 'style="flex:1;"' : ''; ?>>
									<div class="card c-card overflow-hidden rounded-4 shadow-lg border-0 mb-4 ---- bg-cover bg-position-center-center" style="background-image: url('<?php the_post_thumbnail_url('large'); ?>');">
										<div class="d-flex flex-column justify-content-between h-100 p-4 pb-3 text-white text-shadow-1">
											<div class="d-flex justify-content-between p-4 rounded-4 fw-bold framefilter" <?= $preview ? 'style="display:flex;"' : ''; ?>>
												<?php if ( $c_number_of_strokes ) : ?>
													<p class="mb-0">Par | <?= esc_html( $c_number_of_strokes ); ?></p>
												<?php endif; ?>
												<?php if ( $c_handicap ) : ?>
													<p class="mb-0">Handicap | <?= esc_html( $c_handicap ); ?></p>
												<?php endif; ?>
												<?php if ( $c_green ) : ?>
													<p class="mb-0">Green | <?= esc_html( $c_green ); ?>m</p>
												<?php endif; ?>
												<?php if ( $c_altitude ) : ?>
													<p class="mb-0">Altitude | <?= esc_html( $c_altitude ); ?>m</p>
												<?php endif; ?>
											</div>
										</div>
										<a href="<?php the_permalink(); ?>" class="stretched-link"></a>
									</div>
									<h4 class="text-dark fw-normal mb-3"><a href="<?php the_permalink(); ?>"><?= get_the_title(); ?></a></h4>
									<p><?= esc_html( $content ); ?></p>
								</div>
						<?php
							endwhile;
							wp_reset_postdata();
						endif;
						?>

					</div>
					</div>

			</section>
			<?php
		},
	];

	/**
	 * Competitions block
	 */
	$meta_boxes[] = [
		'title'           => __( '(GOLF) Competitions', 'wa-golfs' ),
		'description'     => esc_html__( 'Display featured month competions and results.', 'wa-golfs' ),
		'keywords'        => ['competitions', 'posts'],
		'id'              => 'wa-golfs-competitions',
		'category'        => 'layout',
		// 'icon'			  => 'awards',
		'icon'            => [
			'foreground' 	=> '#1d7c43',
			'src' 			=> 'awards',
		],
		'supports'        => [
			'anchor'          => true,
			'customClassName' => true,
			'align' => ['wide', 'full'],
		],
		'type'            => 'block',
		'context'         => 'side',
		// Fields
		'fields'          => [
			[
				'id'   => $prefix . 'co_display',
				'type' => 'select',
				'name' => esc_html__( 'Display', 'wa-golfs' ),
				'options' => [ 	
					'featured' => esc_html__( 'Featured', 'wa-golfs' ),
					'all' => esc_html__( 'All', 'wa-golfs' ),
				],
				'std'  => 'featured',
			],
		],
		// Render
		'render_callback' => function( $attributes ) {
			$preview = defined( 'REST_REQUEST' ) && REST_REQUEST ?? true;

			// Use constants
			$stateColors = STATE_COLORS;
			$stateLabels = STATE_LABELS;

			$prefix = 'waff_golfs_';
			// Fields data.
			// if ( empty( $attributes['data'] ) ) {
			// return;
			// }

			 // Enqueue the script only when this block is present
			wp_enqueue_script(
				'fullcalendar',
				'https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js',
				array(),
				'6.1.15',
				false
			);

			// Unique HTML ID if available.
			$id = '';
			if ( $attributes['name'] ) {
				$id = $attributes['name'] . '-';
			} elseif (  $attributes['data']['name'] ) {
				$id = $attributes['data']['name'] . '-';
			}
			$id .= ( $attributes['id'] && $attributes['id'] !== $attributes['name']) ? $attributes['id'] : wp_generate_uuid4();
			if ( $attributes['anchor'] ) {
				$id = $attributes['anchor'];
			}
			// Block class
			$themeClass = 'competitions --mt-10 --mb-10 contrast--light';
			$class = $themeClass . ' ' . ( $attributes['className'] ?? '' );
			if ( ! empty( $attributes['align'] ) ) {
				$class .= " align{$attributes['align']}";
			}
			// Animation 
			$data = '';
			$animation_class = '';
			if ( ! empty( $attributes['animation'] ) ) {
				$animation_class .= " coblocks-animate";
				$data .= " data-coblocks-animation='{$attributes['animation']}'";
			}
			
			$co_display = mb_get_block_field( $prefix . 'co_display');

			// Display notice if block isn't full
			// if ( $attributes['align'] !== 'full' && current_user_can('administrator') && !is_front_page() ) {
			// 	echo '<div class="alert alert-dismissible alert-warning fade show" role="alert">
			// 		<strong>Oh snap!</strong> '
			// 		. esc_html__( 'For a better experience, this block renders better in full size.', 'wa-golfs' ) .
			// 		'<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
			// 	</div>';
			// }

			if ($co_display === 'featured' ) : ?>
			<section id="<?= $id ?>" class="<?= $class ?> <?= $animation_class ?>" <?= $data ?> style="--background-color: var(--waff-action-3-lighten-3); --color: var(--waff-action-3-inverse);">
				<div class="container-fluid" <?= $preview ? 'style="padding:3rem;"' : ''; ?>>
					<div class="row row-cols-1 row-cols-md-2 mt-4 mb-4" <?= $preview ? 'style="display:flex;"' : ''; ?>>

						<div class="col" <?= $preview ? 'style="flex:1;margin-right:1rem;"' : ''; ?>> 
							<hgroup class="d-flex flex-row align-items-center justify-content-between mb-5" <?= $preview ? 'style="display:flex;justify-content: space-between;"' : ''; ?>>
								<h6 class="headflat text-color-accent-1">Les compétitions à venir</h6>
								<a href="/competitions" class="headflat fw-light ps-4" <?= $preview ? 'style="display:none;"' : ''; ?>>Toutes <i class="bi bi-arrow-right-short"></i></a>
								<h6 class="headflat text-color-accent-1 fw-light ms-auto d-none d-sm-block"><?= date_i18n('F Y'); ?></h6>
							</hgroup>

							<div class="row row-cols-1 row-cols-md-2 g-4">
								<div class="col">
									<div class="row row-cols-1 row-cols-md-1 g-4">
										<?php
										$paged_upcoming = (get_query_var('paged_upcoming')) ? get_query_var('paged_upcoming') : 1;
										$args = array(
											'post_type'      => 'competitions',
											'posts_per_page' => 3,
											'meta_query'     => array(
												'relation' => 'AND',
												array(
													'key'     => 'c_state',
													'value'   => array('pending', 'current'),
													'compare' => 'IN',
												),
												array(
													'key'     => 'c_date',
													'value'   => array(
														date('Y-m-d'), // aujourd’hui
														date('Y-m-d', strtotime('+1 month')) // +1 mois
													),
													'compare' => 'BETWEEN',
													'type'    => 'DATE',
												),
											),
											'orderby'  => 'meta_value',
											'meta_key' => 'c_date',
											'order'    => 'ASC',
										);
										$competition_query = new WP_Query( $args );
										if ( $competition_query->have_posts() ) :
											while ( $competition_query->have_posts() ) : $competition_query->the_post();
												$c_date = get_post_meta( get_the_ID(), 'c_date', true );
												?>
												<div class="col mt-0">
												<h5 class="mb-2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> 
													<span class="dot" style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; vertical-align: 2px; margin-left: 2px; background-color: <?php echo esc_attr( $stateColors[get_post_meta(get_the_ID(), 'c_state', true)]['textColor'] ); ?>;"></span>
												</h5>
													<p class="text-color-accent-1"><span class="fw-bold text-action-3"><?php echo date_i18n( 'j F Y', strtotime( $c_date ) ); ?></span> <?= wp_trim_words( get_the_excerpt(), 17, '...' ); ?></p>
												</div>
												<?php
											endwhile;
											// Pagination
											$big = 999999999; // need an unlikely integer
											$display_pagination_links_upcoming = false;
											$pagination_links_upcoming = paginate_links( array(
												'base'    => str_replace( $big, '%#%', esc_url( add_query_arg( 'paged_upcoming', $big ) ) ),
												'format'  => '',
												'current' => max( 1, $paged_upcoming ),
												'total'   => $competition_query->max_num_pages,
												'add_args' => array('paged_ended' => $paged_ended), // preserve other pagination in URL
												'type'    => 'list',
												'prev_text' => '&laquo;',
												'next_text' => '&raquo;',
											) );
											if ( $pagination_links_upcoming && $display_pagination_links_upcoming ) {
												// Add classes to <ul>
												$pagination_links_upcoming = str_replace(
													'<ul class=\'page-numbers\'>',
													'<ul class="list-group list-group list-group-horizontal">',
													$pagination_links_upcoming
												);
												echo '<nav class="mt-1 px-1" aria-label="Navigation des competitions à venir">' . $pagination_links_upcoming . '</nav>';
											}
											wp_reset_postdata();
										endif;
										?>
									</div>
								</div>
								<div class="col">
									<div id="competitions-calendar"></div>
								</div>
							</div>
						</div>

						<div class="col" <?= $preview ? 'style="flex:1;margin-left:1rem;"' : ''; ?>>
							<hgroup class="d-flex flex-row align-items-center justify-content-between mb-5" <?= $preview ? 'style="display:flex;justify-content: space-between;"' : ''; ?>>
								<h6 class="headflat text-color-accent-1">Les résultats</h6>
								<a href="/competitions" class="headflat fw-light ps-4 me-auto" <?= $preview ? 'style="display:none;"' : ''; ?>>Tous <i class="bi bi-arrow-right-short"></i></a>
								<h6 class="headflat text-color-accent-1 fw-light ms-auto d-none"><?= date_i18n('F Y'); ?></h6>
							</hgroup>

							<div class="row row-cols-1 row-cols-md-2 g-4">

								<?php

								$paged_ended = (get_query_var('paged_ended')) ? get_query_var('paged_ended') : 1;
								$args = array(
									'post_type' => 'competitions',
									'posts_per_page' => 6,
									'paged' => $paged_ended,
									'meta_query' => array(
										'relation' => 'AND',
										array(
											'key' => 'c_state',
											'value' => 'ended',
											'compare' => '=',
										),
										array(
											'relation' => 'OR',
											array(
												'key'     => 'c_competition_results_brut',
												'value'   => '',
												'compare' => '!=',
											),
											array(
												'key'     => 'c_competition_results_net',
												'value'   => '',
												'compare' => '!=',
											),
										),
										array(
											'key'     => 'c_date',
											'value'   => array(
												date('Y-m-d', strtotime('-1 year')), // il y a 1 an
												date('Y-m-d') // aujourd'hui
											),
											'compare' => 'BETWEEN',
											'type'    => 'DATE',
										),
									),
									// 'date_query' => array(
									// 	array(
									// 		'column' => 'post_date_gmt',
									// 		'after' => '1 year ago',
									// 	),
									// ),		
									'orderby' => 'meta_value',
									'meta_key' => 'c_date',
									'order' => 'DESC',
								);
								$ended_competition_query = new WP_Query( $args );
								if ( $ended_competition_query->have_posts() ) :
									while ( $ended_competition_query->have_posts() ) : $ended_competition_query->the_post();
										$c_date = get_post_meta( get_the_ID(), 'c_date', true );
										?>
										<div class="col mt-0">
											<h5 class="mb-2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> 
												<span class="dot" style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; vertical-align: 2px; margin-left: 2px; background-color: <?php echo esc_attr( $stateColors[get_post_meta(get_the_ID(), 'c_state', true)]['textColor'] ); ?>;"></span>
											</h5>
											<p class="text-color-accent-1"><span class="fw-bold text-action-3"><?php echo date_i18n( 'j F Y', strtotime( $c_date ) ); ?></span> <?= wp_trim_words( get_the_excerpt(), 17, '...' ); ?></p>
										</div>
										<?php
									endwhile;
									// Pagination
									$big = 999999999; // need an unlikely integer
									$display_pagination_links_ended = false;
									$pagination_links_ended = paginate_links( array(
										'base' => add_query_arg('paged_ended', '%#%'),
										'format'  => '',
										'current' => max( 1, $paged_ended ),
										'total'   => $ended_competition_query->max_num_pages,
										'add_args' => array('paged_upcoming' => $paged_upcoming), // preserve other pagination in URL
										'type'    => 'list',
										'prev_text' => '&laquo;',
										'next_text' => '&raquo;'
									) );
									if ( $pagination_links_ended && $display_pagination_links_ended ) {
										// Add classes to <ul>
										$pagination_links_ended = str_replace(
											'<ul class=\'page-numbers\'>',
											'<ul class="list-group list-group list-group-horizontal">',
											$pagination_links_ended
										);
										echo '<nav class="mt-1 px-1" aria-label="Navigation des résultats de compétitions">' . $pagination_links_ended . '</nav>';
									}
									wp_reset_postdata();
								endif;
								?>
								
								

							</div>
						</div>

					</div>
				</div>				
			</section>
			<?php else : ?>
			<section id="<?= $id ?>" class="<?= $class ?> <?= $animation_class ?>" <?= $data ?> style="--background-color: var(--waff-action-3-lighten-3); --color: var(--waff-action-3-inverse);">
				<div class="container-fluid" <?= $preview ? 'style="padding:3rem;"' : ''; ?>>

					<div class="row mt-4 mb-0" <?= $preview ? 'style="display:flex;"' : ''; ?>>

						<div class="col-12 col-md-8" <?= $preview ? 'style="flex:1;margin-right:1rem;"' : ''; ?>>
							<h2><?= esc_html__( 'Toutes les compétitions', 'wa-golfs' ); ?></h2>
							<p class="text-muted"><?= esc_html__( 'Découvrez l\'ensemble des compétitions de notre club, des tournois emblématiques d’hier aux rendez-vous à venir. Revivez les moments forts en consultant les résultats des compétitions précédentes et préparez-vous, en vous inscrivant aux compétitions à venir, à vivre de nouvelles émotions sur nos greens..', 'wa-golfs' ); ?></p>

							<!-- Anchor menu -->
							<nav class="mt-4">
								<ul class="nav nav-pills gap-2">
									<li class="nav-item">
										<a class="nav-link btn btn-color-accent-1" href="#competitions-upcoming"><?= esc_html__('Les compétitions à venir', 'wa-golfs'); ?></a>
									</li>
									<li class="nav-item">
										<a class="nav-link btn btn-action-3" href="#competitions-results"><?= esc_html__('Les résultats', 'wa-golfs'); ?></a>
									</li>
								</ul>
							</nav>
						</div>

						<div class="col-12 col-md-4">
							<div id="competitions-calendar"></div>
						</div>		

					</div>

					<div class="mb-4" <?= $preview ? 'style="display:flex;"' : ''; ?>>

						<div class="mb-4" <?= $preview ? 'style="flex:1;margin-right:1rem;"' : ''; ?>> 
							<hgroup class="d-flex flex-row align-items-center justify-content-between mb-5" <?= $preview ? 'style="display:flex;justify-content: space-between;"' : ''; ?>>
								<h6 class="headflat text-color-accent-1" id="competitions-upcoming">Les compétitions à venir</h6>
							</hgroup>

							<div class="row row-cols-1 row-cols-md-3 g-4">
								<?php
								$paged_upcoming = (get_query_var('paged_upcoming')) ? get_query_var('paged_upcoming') : 1;
								$args = array(
									'post_type'      => 'competitions',
									'posts_per_page' => 20,
									'paged' => $paged_upcoming,
									'meta_query'     => array(
										'relation' => 'AND',
										array(
											'key'     => 'c_state',
											'value'   => array('pending', 'current'),
											'compare' => 'IN',
										),
										array(
											'key'     => 'c_date',
											'value'   => array(
												date('Y-m-d'), // aujourd’hui
												date('Y-m-d', strtotime('+1 year')) // +1 an
											),
											'compare' => 'BETWEEN',
											'type'    => 'DATE',
										),
									),
									'orderby'  => 'meta_value',
									'meta_key' => 'c_date',
									'order'    => 'ASC',

								);
								$competition_query = new WP_Query( $args );
								if ( $competition_query->have_posts() ) :
									while ( $competition_query->have_posts() ) : $competition_query->the_post();
										$c_introduction 			= get_post_meta( get_the_ID(), 'c_introduction', true );
										$c_media_url 				= get_the_post_thumbnail_url( get_the_ID(), 'medium' );
										$c_media_thumbnail_url		= get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
										$c_image = $c_media_thumbnail_url ? '<div class="d-flex flex-center rounded-4 bg-color-layout overflow-hidden"><img decoding="async" src="'.$c_media_thumbnail_url.'" class="img-fluid fit-image rounded-4 img-transition-scale --h-100-px w-150-px h-auto"></div>' : '<div class="d-flex flex-center rounded-4 bg-color-layout"><img decoding="async" src="https://placehold.co/300x300/white/white" class="img-fluid fit-image rounded-4 img-transition-scale --h-100-px --w-100-px op-0"><i class="position-absolute bi bi-image text-action-3"></i></div>';
										$c_last_updated 			=  __('Last update', 'waff') . " " . human_time_diff(get_post_time('U'), current_time('timestamp')) . " " . __('ago', 'waff');
										$c_date 					= get_post_meta( get_the_ID(), 'c_date', true );
										$c_state 					= get_post_meta( get_the_ID(), 'c_state', true );

										$c_competition_departures 	= get_post_meta( get_the_ID(), 'c_competition_departures', true );
										$c_competition_results_brut = get_post_meta( get_the_ID(), 'c_competition_results_brut', true );
										$c_competition_results_net 	= get_post_meta( get_the_ID(), 'c_competition_results_net', true );

										$competition_date = get_post_meta(get_the_ID(), 'c_date', true); 
										$competition_date_string = wp_kses(
											sprintf(
												'<time datetime="%1$s">%2$s</time>',
												esc_attr($competition_date),
												sprintf(
													__('<strong>Le %1$s</strong>, à %2$s', 'waff'),
													date_i18n(get_option('date_format'), strtotime($competition_date)),
													date_i18n(get_option('time_format'), strtotime($competition_date))
												)
											),
											array_merge(
												wp_kses_allowed_html('post'),
												array(
													'time' => array(
														'datetime' => true,
													),
												)
											)
										);

									?>
									<div class="col mt-0 mb-4">
										<div class="card border-0 p-4 h-100" style="background-color:var(--waff-action-3-lighten-3);">
											<div class="d-flex g-0 align-items-center">
												<div class="w-150-px order-first">
													<?= $c_image ?>
												</div>
												<div class="w-100">
													<div class="card-body">
														<span class="fs-xs">
															<span class="state-label" style="color:<?= esc_attr($stateColors[$c_state]['textColor']); ?>;">
																<span class="dot" style="display:inline-block;width:8px;height:8px;border-radius:50%;vertical-align:2px;margin-left:2px;background-color:<?= esc_attr($stateColors[$c_state]['textColor']); ?>;"></span>
																<?= esc_html($stateLabels[$c_state]['label']); ?>
															</span>
															<?= $c_competition_departures ? '<i class="bi bi-person-lines-fill ms-1"></i> Départs' : '' ?>
															<?= ($c_competition_results_brut || $c_competition_results_net) ? '<i class="bi bi-check-circle-fill ms-1"></i> Résultats' : '' ?>
														</span>
														<?= the_title('<h4 class="post__title entry-title m-0 lh-1 mt-2 mb-1 fw-bold" style="margin-left: -2px !important;"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h4>', false); ?>
														<p class="competition-date --muted mb-0"><i class="bi bi-calendar-event"></i> <?= $competition_date_string ?></p>
														<p class="card-text fs-sm mb-0"><?= esc_html(wp_trim_words(get_the_excerpt() ?: $c_introduction, 15, ' &hellip;')) ?></p>
														<p class="card-text --mt-n2 mt-3"><small class="text-body-secondary"><?= $c_last_updated ?></small></p>
													</div>
												</div>
											</div>
										</div>
									</div>
									<?php

									endwhile;
									// Pagination
									$big = 999999999; // need an unlikely integer
									$display_pagination_links_upcoming = true;
									$pagination_links_upcoming = paginate_links( array(
										'base'    => str_replace( $big, '%#%', esc_url( add_query_arg( 'paged_upcoming', $big ) ) ),
										'format'  => '',
										'current' => max( 1, $paged_upcoming ),
										'total'   => $competition_query->max_num_pages,
										'add_args' => array('paged_ended' => $paged_ended), // preserve other pagination in URL
										'type'    => 'list',
										'prev_text' => '&laquo;',
										'next_text' => '&raquo;',
									) );
									if ( $pagination_links_upcoming && $display_pagination_links_upcoming ) {
										// Add classes to <ul>
										$pagination_links_upcoming = str_replace(
											'<ul class=\'page-numbers\'>',
											'<ul class="list-group list-group list-group-horizontal">',
											$pagination_links_upcoming
										);
										echo '<nav class="mt-1 px-1" aria-label="Navigation des competitions à venir">' . $pagination_links_upcoming . '</nav>';
									}
									wp_reset_postdata();
								endif;
								?>
							</div>
						</div>

						<div class="bg-color-layout --f-w-gutter pt-4 pb-4" <?= $preview ? 'style="flex:1;margin-left:1rem;"' : ''; ?>>
							<hgroup class="d-flex flex-row align-items-center justify-content-between mb-5" <?= $preview ? 'style="display:flex;justify-content: space-between;"' : ''; ?>>
								<h6 class="headflat text-action-2" id="competitions-results">Les résultats</h6>
							</hgroup>

							<div class="row row-cols-1 row-cols-md-3 g-4">
								<?php
								$paged_ended = (get_query_var('paged_ended')) ? get_query_var('paged_ended') : 1;
								$args = array(
									'post_type' => 'competitions',
									'posts_per_page' => 9, //
									'paged' => $paged_ended,
									'meta_query' => array(
										'relation' => 'AND',
										array(
											'key' => 'c_state',
											'value' => 'ended',
											'compare' => '=',
										),
										array(
											'relation' => 'OR',
											array(
												'key'     => 'c_competition_results_brut',
												'value'   => '',
												'compare' => '!=',
											),
											array(
												'key'     => 'c_competition_results_net',
												'value'   => '',
												'compare' => '!=',
											),
										),
										array(
											'key'     => 'c_date',
											'value'   => array(
												date('Y-m-d', strtotime('-1 year')), // il y a 1 an
												date('Y-m-d') // aujourd'hui
											),
											'compare' => 'BETWEEN',
											'type'    => 'DATE',
										),
									),
									// 'date_query' => array(
									// 	array(
									// 		'column' => 'post_date_gmt',
									// 		'after' => '1 year ago',
									// 	),
									// ),		
									'orderby' => 'meta_value',
									'meta_key' => 'c_date',
									'order' => 'DESC',
								);
								$ended_competition_query = new WP_Query( $args );
								if ( $ended_competition_query->have_posts() ) :
									while ( $ended_competition_query->have_posts() ) : $ended_competition_query->the_post();
										$c_date = get_post_meta( get_the_ID(), 'c_date', true );
										?>
										<div class="col mt-0">
											<h5 class="mb-2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> 
												<span class="dot" style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; vertical-align: 2px; margin-left: 2px; background-color: <?php echo esc_attr( $stateColors[get_post_meta(get_the_ID(), 'c_state', true)]['textColor'] ); ?>;"></span>
											</h5>
											<p class="text-color-accent-1"><span class="fw-bold text-action-3"><?php echo date_i18n( 'j F Y', strtotime( $c_date ) ); ?></span> <?= wp_trim_words( get_the_excerpt(), 17, '...' ); ?></p>
										</div>
										<?php
									endwhile;
									// Pagination
									$big = 999999999; // need an unlikely integer
									$display_pagination_links_ended = true;
									$pagination_links_ended = paginate_links( array(
										'base' => add_query_arg('paged_ended', '%#%'),
										'format'  => '',
										'current' => max( 1, $paged_ended ),
										'total'   => $ended_competition_query->max_num_pages,
										'add_args' => array('paged_upcoming' => $paged_upcoming), // preserve other pagination in URL
										'type'    => 'list',
										'prev_text' => '&laquo;',
										'next_text' => '&raquo;'
									) );
									if ( $pagination_links_ended && $display_pagination_links_ended ) {
										// Add classes to <ul>
										$pagination_links_ended = str_replace(
											'<ul class=\'page-numbers\'>',
											'<ul class="list-group list-group list-group-horizontal">',
											$pagination_links_ended
										);
										echo '<nav class="mt-1 px-1" aria-label="Navigation des résultats de compétitions">' . $pagination_links_ended . '</nav>';
									}
									wp_reset_postdata();
								endif;
								?>
							</div>
						</div>

					</div>
				</div>				
			</section>
			<?php endif; ?>
			<style>
				.page-numbers {
					display: inline-block;
					padding: 0.25rem 0.65rem;
					margin: 0 0.25rem;
					border-radius: 0.25rem;
					background-color: var(--waff-action-3-inverse-trans-4);
					color: var(--waff-action-3-inverse);
				}
				a.page-numbers {
					background-color: var(--waff-action-3-inverse-trans-3);
				}
			</style>
			<script>
			// Convert PHP array to JS object
			const stateColors = <?= json_encode(STATE_COLORS); ?>;
			const stateLabels = <?= json_encode(STATE_LABELS); ?>;		
			const coDisplay = <?= json_encode($co_display); ?>;		
			// Initialize FullCalendar	
			document.addEventListener('DOMContentLoaded', function() {
				var calendarEl = document.getElementById('competitions-calendar');
				var calendar = new FullCalendar.Calendar(calendarEl, {
					initialView: 'dayGridMonth',
					locale: "fr", // Set the locale to French
					// Weeks starting on Monday
					firstDay: 1,
					headerToolbar: coDisplay === 'featured' ? false : {
						left: "prev,next today",
						center: "",
						right: "title",
					}, // Hide the toolbar
					buttonText: {
						today: 'Aujourd\'hui' // Traduction du bouton today
					},
					height: 'auto', // Adjust the height to display the full calendar
					contentHeight: 'auto', // Ensure the content height is auto
					dayHeaders: false, // Hide days
					fixedWeekCount: false, // Determines the number of weeks displayed in a month view
					events: [
						<?php
						$args = array(
							'post_type' => 'competitions',
							'posts_per_page' => -1,
							'meta_query' => array(
								array(
									'key'     => 'c_date',
									'value'   => array(
										date('Y-m-d', strtotime('-1 year')), // il y a 1 an
										date('Y-m-d') // aujourd'hui
									),
									'compare' => 'BETWEEN',
									'type'    => 'DATE',
								),
							),
							// 'date_query' => array(
							// 	array(
							// 		'column' => 'post_date_gmt',
							// 		'after' => '1 year ago',
							// 	),
							// ),
						);
						$competition_query = new WP_Query( $args );
						if ( $competition_query->have_posts() ) :
							while ( $competition_query->have_posts() ) : $competition_query->the_post();
								$state = get_post_meta(get_the_ID(), 'c_state', true);
								?>
								{
									title: '<?php echo esc_js( get_the_title() ); ?>',
									start: '<?php echo esc_js( get_post_meta(get_the_ID(), 'c_date', true) ); ?>',
									state: '<?php echo esc_js( get_post_meta(get_the_ID(), 'c_state', true) ); ?>',
									permalink: '<?php echo esc_js(get_permalink()); ?>',
									state: '<?php echo esc_js($state); ?>',
								},
								<?php
							endwhile;
							wp_reset_postdata();
						endif;
						?>
					],
					eventDidMount: function(info) {
						var state = info.event.extendedProps.state;
						if (state && stateColors[state]) {
							info.el.style.color = stateColors[state].textColor;
							var dotEl = info.el.getElementsByClassName('fc-daygrid-event-dot')[0];
							if (dotEl) {
								dotEl.style.borderColor = stateColors[state].textColor;
							}
							var timeEl = info.el.getElementsByClassName('fc-event-time')[0];
							if (timeEl) {
								timeEl.classList.add('visually-hidden');
							}
							var titleEl = info.el.getElementsByClassName('fc-event-title')[0];
							if (titleEl) {
								titleEl.classList.add('visually-hidden');
							}
						}
					},
					dayCellDidMount: function (info) {
						if (info.date.getTime() === new Date().setHours(0, 0, 0, 0)) {
							info.el.style.backgroundColor = "rgb(228, 235, 242)"; // Change the background color of the current day
						}
					},
				});
				calendar.render();

				// Add custom styles to override FullCalendar styles
				var style = document.createElement('style');
				style.innerHTML = '.fc-scrollgrid { border:0 !important; } .fc-daygrid-day-events { display:flex; margin-bottom: 0 !important; justify-content: end;} .fc-scrollgrid tr { border-top:1px solid silver; border-color:var(--waff-color-dark-trans-4); }';
				document.head.appendChild(style);
			});
			</script>
			<?php
		},
	];

	return $meta_boxes;
}