<?php

/**
 * Register the blocks w/ metabox.io
 *
 * @since    1.1.0
 */

function register_blocks() {
	$prefix = 'waff_golfs_';

	/**
	 * Test block
	 */
	$meta_boxes[] = [
		'title'           => __( '(GOLFS) Testimony', 'wa-golfs' ),
		'description'     => esc_html__( 'Display random testimonies.', 'wa-golfs' ),
		'keywords'        => ['testimony', 'posts'],
		'id'              => 'wa-golfs-testimony',
		'category'        => 'layout',
		// 'icon'			  => 'format-standard',
		'icon'            => [
			'foreground' 	=> '#9500ff',
			'src' 			=> 'format-standard',
		],
		'supports'        => [
			'anchor'          => true,
			'customClassName' => true,
			'align' => ['wide', 'full'],
		],
		'type'            => 'block',
		'context'         => 'content',
		// Fields
		'fields'          => [
			[
				'id'   => $prefix . 't_background_class',
				'type' => 'text',
				'name' => esc_html__( 'CSS background color', 'wa-golfs' ),
				'std'  => 'bg-action-1',
			],
			[
				'id'   => $prefix . 't_text_class',
				'type' => 'text',
				'name' => esc_html__( 'CSS text color', 'waff' ),
				'std'  => 'text-dark',
			],

		],
		// Render
		'render_callback' => function( $attributes, $preview, $post_id ) {
			?>
			<div class="testimonial">
			<?= mb_get_block_field( 'waff_golfs_t_background_class' ) ?>
			<?= mb_get_block_field( 'waff_golfs_t_text_class' ) ?>
			<b>Default block page :</b>
				<div class="testimonial__text">
					Inner blocks : 
					<InnerBlocks />
				</div>
			</div>
			<?php
		},
	];

	return $meta_boxes;
}