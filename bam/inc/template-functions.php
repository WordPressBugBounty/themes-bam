<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Bam
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function bam_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	// Site Layout.
	$bam_site_layout = get_theme_mod( 'bam_site_layout', 'boxed-layout' );
	$classes[] = $bam_site_layout;

	// Get sidebar alignment class.
	$classes[] = bam_get_layout();

	// Content Layout.
	$bam_content_layout = get_theme_mod( 'bam_content_layout', 'one-container' );
	$classes[] = $bam_content_layout;

	if ( bam_show_updated_date() ) {
		$classes[] = 'bam-show-updated';
	}

	return $classes;
}
add_filter( 'body_class', 'bam_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function bam_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'bam_pingback_header' );


/**
 * Add a custom excerpt length.
 */
function bam_excerpt_length( $length ) {
	if( is_admin() ) {
		return $length;
	}
	$custom_length = get_theme_mod( 'bam_excerpt_length', 25 );
	return absint( $custom_length );
}
add_filter( 'excerpt_length', 'bam_excerpt_length', 999 );

/**
 * Changes the excerpt more text.
 */
function bam_excerpt_more( $more ) {

	if ( is_admin() ) {
		return $more;
	}

	return ' &hellip; ';
}
add_filter( 'excerpt_more', 'bam_excerpt_more' );

/**
 * Changes tag font size.
 */
function bam_tag_cloud_sizes($args) {
	$args['smallest']	= 10;
	$args['largest'] 	= 10;
	return $args; 
}
add_filter('widget_tag_cloud_args','bam_tag_cloud_sizes');

/**
 * Change date to 'time ago' format if enabled in the Customizer.
 */
function bam_math_to_time_ago( $post_time, $format, $post, $updated ) {
	if ( is_single() ) {
		$use_time_ago = get_theme_mod( 'bam_single_time_ago', false );
	} else {
		$use_time_ago = get_theme_mod( 'bam_time_ago', false );
	}
	
	// Only filter time when $use_time_ago is enabled, and it's not using a machine-readable format (for datetime).
	if ( true === $use_time_ago && 'Y-m-d\TH:i:sP' !== $format ) {
		$current_time = current_time( 'timestamp' ); // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
		$cut_off      = get_theme_mod( 'bam_time_ago_date_count', '14' );
		$org_time     = strtotime( $post->post_date );

		if ( true === $updated ) {
			$org_time = strtotime( $post->post_modified );
		}

		// Transform cut off from days to seconds.
		$cut_off_seconds = $cut_off * 86400;

		if ( is_single() ) {
			$show_updated_date = get_theme_mod( 'bam_single_show_updated_date', false );
		} else {
			$show_updated_date = get_theme_mod( 'bam_show_updated_date', false );
		}

		if ( true === $show_updated_date ) {
			// Switch cut off to 24 hours.
			$cut_off_seconds = 86400;
		}

		if ( $cut_off_seconds >= ( $current_time - $org_time ) ) {
			$post_time = sprintf(
				/* translators: %s: Time ago date format */
				esc_html__( '%s ago', 'bam' ),
				human_time_diff( $org_time, $current_time )
			);
		}
	}

	return $post_time;
}


/**
 * Apply time ago format to publish dates if enabled.
 */
function bam_convert_to_time_ago( $post_time, $format, $post ) {
	// Don't override specifically requested formats.
	if ( empty( $format ) ) {
		$post_time = bam_math_to_time_ago( $post_time, $format, $post, false );
	}
	return $post_time;
}
add_filter( 'get_the_date', 'bam_convert_to_time_ago', 10, 3 );

/**
 * Apply time ago format to modified dates if enabled.
 */
function bam_convert_modified_to_time_ago( $post_time, $format, $post ) {
	return bam_math_to_time_ago( $post_time, $format, $post, true );
}

/**
 * Checks to see if it should display the updated date.
 */
function bam_show_updated_date() {
	if ( is_single() ) {
		$show_updated_date = get_theme_mod( 'bam_single_show_updated_date', false );
	} else {
		$show_updated_date = get_theme_mod( 'bam_show_updated_date', false );
	}
	return $show_updated_date;
}