<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Bam
 */

if ( ! function_exists( 'bam_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function bam_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published sm-hu" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		add_filter( 'get_the_modified_date', 'bam_convert_modified_to_time_ago', 10, 3 );

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		remove_filter( 'get_the_modified_date', 'bam_convert_modified_to_time_ago', 10, 3 );

		printf(
			'<span class="posted-on"><i class="far fa-clock"></i><a href="%1$s" rel="bookmark">%2$s</a></span>',
			esc_url( get_permalink() ),
			$time_string
		); // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'bam_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function bam_posted_by() {

		$author_email	= get_the_author_meta( 'user_email' );
		$avatar_url		= get_avatar_url( $author_email );
		$avatar_markup  = '<img class="author-photo" alt="' . esc_attr( get_the_author() ) . '" src="' . esc_url( $avatar_url ) . '" />';
		$icon_markup 	= '<i class="fas fa-user"></i>';
		
		if ( is_single() ) {
			$display_avatar = get_theme_mod( 'bam_single_show_author_avatar', true );
		} else {
			$display_avatar = get_theme_mod( 'bam_show_author_avatar', true );
		}

		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'bam' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		$markup = '<span class="byline"> ';
		if( $display_avatar ) {
			$markup .= $avatar_markup;
		} else {
			$markup .= $icon_markup;
		}
		$markup .= $byline;
		$markup .= '</span>';

		echo $markup; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'bam_category_list' ) ) :
	/**
	 * Prints category list
	 */
	function bam_category_list() {

		if ( 'post' === get_post_type() ) {

			if ( is_single() ) {
				$show_category_list = get_theme_mod( 'bam_single_show_cat_list', true );
			} else {
				$show_category_list = get_theme_mod( 'bam_show_cat_list', true );
			}

			if ( $show_category_list == false ) {
				return;
			}

			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ' / ', 'bam' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">%1$s</span>', $categories_list ); // WPCS: XSS OK.
			}
		}
	}

endif;

if ( ! function_exists( 'bam_tags_list' ) ) :
	/**
	 * Prints category list
	 */
	function bam_tags_list() {
		
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list();
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<div class="tags-links"><span class="bam-tags-title">%1$s</span>%2$s</div>',
					esc_html__( 'Tagged', 'bam' ),
					$tags_list
				); // WPCS: XSS OK.
			}
		}
	}
endif;

if ( ! function_exists( 'bam_comments_link' ) ) :
	/**
	 * Prints comments link
	 */
	function bam_comments_link() {

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link"><i class="far fa-comments"></i>';
				comments_popup_link( '0', '1', '%' );
			echo '</span>';
		}
	}
endif;

if ( ! function_exists( 'bam_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function bam_entry_footer() {

		if ( is_single() ) {
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
		}
		
	}
endif;

if ( ! function_exists( 'bam_entry_meta' ) ) :
	/**
	 * Displays posts meta data on blog posts.
	 */
	function bam_entry_meta() {

		if ( is_single() ) {
			$show_author = get_theme_mod( 'bam_single_show_author', true );
			$show_date = get_theme_mod( 'bam_single_show_date', true );
			$show_comments = get_theme_mod( 'bam_single_show_comments', true );
		} else {
			$show_author = get_theme_mod( 'bam_show_author', true );
			$show_date = get_theme_mod( 'bam_show_date', true );
			$show_comments = get_theme_mod( 'bam_show_comments', true );
		}

		if ( true == $show_author ) {
			bam_posted_by();
		}
		if ( true == $show_date ) {
			bam_posted_on();
		}
		if ( true == $show_comments ) {
			bam_comments_link();
		}
		
	}

endif;

if ( ! function_exists( 'bam_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function bam_post_thumbnail( $size = "" ) {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			
			if( true == get_theme_mod( 'bam_show_post_thumbnail', true ) ) { ?>
				<div class="post-thumbnail">
					<?php the_post_thumbnail( $size ); ?>
				</div><!-- .post-thumbnail -->
			<?php } ?>

		<?php else : ?>
		
		<div class="post-thumbnail">
			<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
				the_post_thumbnail( $size, array(
					'alt' => the_title_attribute( array(
						'echo' => false,
					) ),
				) );
				?>
			</a>
		</div>

		<?php
		endif; // End is_singular().
	}
endif;


/**
 * Displays header image.
 */
function bam_header_image() {

	$header_image = get_header_image();

	if ( ! empty ( $header_image ) ) : 

		$custom_header = get_custom_header();

		$alt = "";

		// Use alternative text assigned to the image, if available. Otherwise, leave it empty.
		if ( ! empty( $custom_header->attachment_id ) ) {
			$image_alt = get_post_meta( $custom_header->attachment_id, '_wp_attachment_image_alt', true );
		
			if ( is_string( $image_alt ) ) {
				$alt = $image_alt;
			}
		}
	
		$bam_link_header_image = get_theme_mod( 'bam_link_header_image', false );
		echo '<div class="th-header-image clearfix">';
			if ( $bam_link_header_image == true ) { echo '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" rel="home">'; }
				echo '<img src="' . esc_url ( $header_image ) . '" height="' . esc_attr( $custom_header->height ) . '" width="' . esc_attr( $custom_header->width ) . '" alt="' . esc_attr( $alt ) . '" />';
			if ( $bam_link_header_image == true ) { echo '</a>'; }
		echo '</div>';

	endif;

}


/**
 * Posts pagination.
 */
if ( ! function_exists( 'bam_posts_pagination' ) ) {

	function bam_posts_pagination() {

		$pagination_type = get_theme_mod( 'bam_pagination_type', 'page-numbers' );

		if ( $pagination_type == 'page-numbers' ) {
			the_posts_pagination();
		} else {
			the_posts_navigation();
		}

	}

}

/**
 * Adds a Sub Nav Toggle to the Mobile Menu.
 *
 * @param stdClass $args  An object of wp_nav_menu() arguments.
 * @param WP_Post  $item  Menu item data object.
 * @param int      $depth Depth of menu item. Used for padding.
 * @return stdClass An object of wp_nav_menu() arguments.
 * @since Bam 1.1.5
 */
function bam_add_dropdown_toggle_to_menu( $args, $item, $depth ) {
	
	// Add sub menu toggles to the menu.
	if ( isset( $args->show_toggles ) && $args->show_toggles ) {

		$args->after = '';

		// Add a toggle to items with children.
		if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {

			$toggle_target_string = '.mobile-navigation .menu-item-' . $item->ID . ' > .sub-menu';

			// Add the sub menu toggle.
			$args->after = '<button class="dropdown-toggle" data-toggle-target="' . $toggle_target_string . '" aria-expanded="false"><span class="screen-reader-text">' . esc_html__( 'Show sub menu', 'bam' ) . '</span></button>';

		}

	} 

	return $args;

}

add_filter( 'nav_menu_item_args', 'bam_add_dropdown_toggle_to_menu', 10, 3 );

if ( ! function_exists( 'bam_get_selected_breadcrumb' ) ) {
	/**
	 * Get the user selected breadcrumb
	 * 
	 * @since 1.3.3
	 */
	function bam_get_selected_breadcrumb() {
		$breadcrumb_source = get_theme_mod( 'bam_breadcrumb_source', 'none' );

		if ( 'yoast' === $breadcrumb_source ) {
			if ( function_exists( 'yoast_breadcrumb' ) ) {
				yoast_breadcrumb( '<div id="bam-yoast-breadcrumbs">', '</div>' );
			}
		} elseif ( 'navxt' === $breadcrumb_source ) {
			if ( function_exists( 'bcn_display' ) ) {
				bcn_display();
			}
		} elseif ( 'rankmath' === $breadcrumb_source ) {
			if ( function_exists( 'rank_math_the_breadcrumbs') ) {
				rank_math_the_breadcrumbs();
			}
		}
	}

}

if ( ! function_exists( 'bam_locate_breadcrumbs' ) ) {
	/**
	 * Adds the breadcrumb to the selected location
	 * 
	 * @since 1.3.3
	 */
	function bam_locate_breadcrumbs() {

		if ( 'none' === get_theme_mod( 'bam_breadcrumb_source', 'none' ) ) {
			return;
		}

		if ( 'before-entry-header' === get_theme_mod( 'bam_breadcrumb_location', 'before-entry-header' ) ) {
			if ( is_archive() || is_search() ) {
				add_action( 'bam_before_main', 'bam_breadcrumb_template' );
			} elseif ( is_singular() ) {
				add_action( 'bam_before_entry_header', 'bam_breadcrumb_template' );
			}
		} else {
			add_action( 'bam_after_header', 'bam_breadcrumb_template' );
		}

	}
	add_action( 'wp', 'bam_locate_breadcrumbs' );
}

if ( ! function_exists( 'bam_breadcrumb_template' ) ) {
	/**
	 * Breadcrumb template markup.
	 * 
	 * @since 1.3.3
	 */
	function bam_breadcrumb_template() {
		$breadcrumb_location = get_theme_mod( 'bam_breadcrumb_location', 'before-entry-header' );

		if ( 'after-site-header' === $breadcrumb_location ) {
			echo '<div class="bam-header-breadcrumb-bar">
					<div class="container">';
		}

		echo '<div class="bam-breadcrumb-wrap">';
		
		bam_get_selected_breadcrumb();

		echo '</div>';

		if ( 'after-site-header' === $breadcrumb_location ) {
			echo '</div>
				</div>';
		}

	}

}