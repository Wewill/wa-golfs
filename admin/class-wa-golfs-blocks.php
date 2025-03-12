<?php

/**
 * Register the blocks w/ metabox.io
 *
 * @since    1.1.0
 */

//**
// Allow
// */

// Allow custom blocks considering post_type 
function allow_blocks() {
	// Blocks 
	add_filter( 'allowed_block_types_all', 'post_type_allowed_block_types', 10, 2 );

}

function post_type_allowed_block_types( $allowed_blocks = array(), $editor_context ) {

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
			$themeClass = 'testimony mt-0 --mb-10 contrast--light';
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
			if ( $attributes['align'] !== 'full' && current_user_can('administrator') ) {
				echo '<div class="alert alert-dismissible alert-warning fade show" role="alert">
					<strong>Oh snap!</strong> '
					. esc_html__( 'For a better experience, this block renders better in full size.', 'wa-golfs' ) .
					'<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
				</div>';
			}
			?>
			<section id="<?= $id ?>" class="<?= $t_background_class ? $t_background_class : 'bg-action-1 default' ?> <?= $class ?> <?= $animation_class ?>" <?= $data ?>>

				<div class="container-fluid p-8">
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
			// if ( $attributes['align'] !== 'full' && current_user_can('administrator') ) {
			// 	echo '<div class="alert alert-dismissible alert-warning fade show" role="alert">
			// 		<strong>Oh snap!</strong> '
			// 		. esc_html__( 'For a better experience, this block renders better in full size.', 'wa-golfs' ) .
			// 		'<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
			// 	</div>';
			// }
			?>
			<section id="<?= $id ?>" class="<?= $class ?> <?= $animation_class ?>" <?= $data ?> style="background-color: var(--waff-action-3-lighten-3); color: var(--waff-action-3-inverse);">

				<div class="container-fluid p-8">

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
			// if ( $attributes['align'] !== 'full' && current_user_can('administrator') ) {
			// 	echo '<div class="alert alert-dismissible alert-warning fade show" role="alert">
			// 		<strong>Oh snap!</strong> '
			// 		. esc_html__( 'For a better experience, this block renders better in full size.', 'wa-golfs' ) .
			// 		'<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
			// 	</div>';
			// }
			?>
			<section id="<?= $id ?>" class="<?= $class ?> <?= $animation_class ?>" <?= $data ?> style="--background-color: var(--waff-action-3-lighten-3); --color: var(--waff-action-3-inverse);">
				<div class="container-fluid" <?= $preview ? 'style="padding:3rem;"' : ''; ?>>
					<div class="row row-cols-1 row-cols-md-2 mt-4 mb-4" <?= $preview ? 'style="display:flex;"' : ''; ?>>

						<div class="col"  <?= $preview ? 'style="flex:1;margin-right:1rem;"' : ''; ?>> 
							<hgroup class="d-flex flex-row align-items-center justify-content-between mb-5" <?= $preview ? 'style="display:flex;justify-content: space-between;"' : ''; ?>>
								<h6 class="headflat text-color-accent-1">Les compétitions à venir</h6>
								<a href="#" class="headflat fw-light ps-4" <?= $preview ? 'style="display:none;"' : ''; ?>>Toutes <i class="bi bi-arrow-right-short"></i></a>
								<h6 class="headflat text-color-accent-1 fw-light ms-auto"><?= date_i18n('F Y'); ?></h6>
							</hgroup>

							<div class="row">
								<div class="col">

									<div class="row row-cols-1 row-cols-md-1 g-4">
										<?php
										$args = array(
											'post_type' => 'competitions',
											'posts_per_page' => 3,
											'meta_query' => array(
												array(
													'key' => 'c_state',
													'value' => array('pending', 'current'),
													'compare' => 'IN',
												),
											),
											'date_query' => array(
												array(
													'column' => 'post_date_gmt',
													'after' => '1 year ago',
												),
												// array(
												// 	'column' => 'post_modified_gmt',
												// 	'after' => '1 month ago',
												// ),
											),		
											'orderby' => 'meta_value',
											'meta_key' => 'c_date',
											'order' => 'DESC',
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
								<a href="#" class="headflat fw-light ps-4 me-auto" <?= $preview ? 'style="display:none;"' : ''; ?>>Tous <i class="bi bi-arrow-right-short"></i></a>
								<h6 class="headflat text-color-accent-1 fw-light ms-auto d-none"><?= date_i18n('F Y'); ?></h6>
							</hgroup>

							<div class="row row-cols-1 row-cols-md-2 g-4">

								<?php
								$args = array(
									'post_type' => 'competitions',
									'posts_per_page' => 6,
									'meta_query' => array(
										array(
											'key' => 'c_state',
											'value' => 'ended',
											'compare' => '=',
										),
									),
									'date_query' => array(
										array(
											'column' => 'post_date_gmt',
											'after' => '1 year ago',
										),
										// array(
										// 	'column' => 'post_modified_gmt',
										// 	'after' => '1 month ago',
										// ),
									),		
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
									wp_reset_postdata();
								endif;
								?>
								

							</div>
						</div>

					</div>
				</div>				
			</section>
			<script>
			// Convert PHP array to JS object
			const stateColors = <?= json_encode(STATE_COLORS); ?>;
			const stateLabels = <?= json_encode(STATE_LABELS); ?>;		
			// Initialize FullCalendar	
			document.addEventListener('DOMContentLoaded', function() {
				var calendarEl = document.getElementById('competitions-calendar');
				var calendar = new FullCalendar.Calendar(calendarEl, {
					initialView: 'dayGridMonth',
					locale: "fr", // Set the locale to French
					headerToolbar: false, // Hide the toolbar
					height: 'auto', // Adjust the height to display the full calendar
					contentHeight: 'auto', // Ensure the content height is auto
					dayHeaders: false, // Hide days
					fixedWeekCount: false, // Determines the number of weeks displayed in a month view
					events: [
						<?php
						$args = array(
							'post_type' => 'competitions',
							'posts_per_page' => -1,
							'date_query' => array(
								array(
									'column' => 'post_date_gmt',
									'after' => '1 year ago',
								),
							),
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