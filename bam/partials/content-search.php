<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bam
 */

?>

<?php $bam_article_classes = bam_blog_article_classes(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $bam_article_classes ); ?>>

	<?php $bam_thumb_size = bam_thumbnail_size(); ?>

	<div class="blog-entry-inner clearfix">

		<?php
			/**
			 * Before article content hook
			 * @since 1.3.3
			 **/
			do_action( 'bam_before_article_content' );
		?>

		<?php bam_post_thumbnail( $bam_thumb_size ); ?>

		<div class="blog-entry-content">

			<?php
				/**
				 * Before entry header hook
				 * @since 1.3.3
				 **/
				do_action( 'bam_before_entry_header' );
			?>

			<div class="category-list">
				<?php bam_category_list(); ?>
			</div><!-- .category-list -->

			<header class="entry-header">
				<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

				<?php if ( 'post' === get_post_type() ) : ?>
					<div class="entry-meta">
						<?php bam_entry_meta(); ?>
					</div><!-- .entry-meta -->
				<?php endif; ?>
			</header><!-- .entry-header -->

			<?php
				/**
				 * After entry header hook
				 * @since 1.3.3
				 **/
				do_action( 'bam_after_entry_header' );
			?>

			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->

			<footer class="entry-footer">
				<?php bam_entry_footer(); ?>
			</footer><!-- .entry-footer -->
		</div><!-- .blog-entry-content -->

		<?php
			/**
			 * After article content hook
			 * @since 1.3.3
			 **/
			do_action( 'bam_after_article_content' );
		?>

	</div><!-- .blog-entry-inner -->

</article><!-- #post-<?php the_ID(); ?> -->
