<?php
/**
 * Block Styles
 *
 * @link https://developer.wordpress.org/reference/functions/register_block_style/
 *
 * @package WordPress
 * @subpackage Bam
 * @since Bam 1.1.7
 */

if ( function_exists( 'register_block_style' ) ) {

	/**
	 * Register block styles.
	 *
	 * @since Bam 1.1.7
	 *
	 * @return void
	 */
    function bam_register_block_styles() {
        register_block_style(
			'core/heading',
			array(
				'name'  => 'bam-widget-title',
				'label' => esc_html__( 'Widget title style', 'bam' ),
			)
		);
    }
    add_action( 'init', 'bam_register_block_styles' );

}

