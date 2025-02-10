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

function post_type_allowed_block_types( $allowed_blocks, $editor_context ) {

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

	// Because the thme restrict the blocks, add here custom blocks created in plugin
	// Add custom block
	$allowed_blocks[] = 'directory/wa-golfs-competition-block';
	// Add metabox.io testimony block
	$allowed_blocks[] = 'meta-box/wa-golfs-testimony';

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
		// 'icon'			  => 'format-standard',
		'icon'            => [
			'foreground' 	=> '#1d7c43',
			'src' 			=> 'format-standard',
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
			$t_background_class = mb_get_block_field( 'waff_golfs_t_background_class');
			$t_text_class = mb_get_block_field( 'waff_golfs_t_text_class');
			?>
			<div class="testimonial"> 
			<?= $t_background_class ? $t_background_class : 'bg-action-1 default' ?>
			<?= $t_text_class ? $t_text_class : 'text-dark default' ?>
			<b>Default block page :</b>
				<div class="testimonial__text">
					Inner blocks : 
					<InnerBlocks />
				</div>
			</div>
			<?php
		},
	];

	// wp_die( '<pre>' . print_r($meta_boxes, true) . '</pre>' );

	return $meta_boxes;
}