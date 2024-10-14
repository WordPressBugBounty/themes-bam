<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bam
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
		/**
		 * Before article content hook
		 * @since 1.3.3
		 **/
		do_action( 'bam_before_article_content' );
	?>

	<?php
		/**
		 * Before entry header hook
		 * @since 1.3.3
		 **/
		do_action( 'bam_before_entry_header' );
	?>

	<header class="entry-header">
		<?php the_title( '<h1 class="page-entry-title entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<?php
		/**
		 * After entry header hook
		 * @since 1.3.3
		 **/
		do_action( 'bam_after_entry_header' );
	?>

	<?php bam_post_thumbnail(); ?>

	<?php do_action( 'bam_before_page_entry_content' ); ?>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'bam' ),
			'after'  => '</div>',
		) );
		?>
	</div><!-- .entry-content -->

	<?php do_action( 'bam_after_page_entry_content' ); ?>

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Edit <span class="screen-reader-text">%s</span>', 'bam' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>

	<?php
		/**
		 * After article content hook
		 * @since 1.3.3
		 **/
		do_action( 'bam_after_article_content' );
	?>
	
</article><!-- #post-<?php the_ID(); ?> -->
