<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin\' uh?' );
}

/*------------------------------------------------------------------------------------------------*/
/* !MENU ITEMS CLASSES ========================================================================== */
/*------------------------------------------------------------------------------------------------*/

// !Add a "current" class to the post type archive items if we display the corresponding single.

add_filter( 'wp_get_nav_menu_items', 'sfar_cpt_archive_menu_filter', 5 );

function sfar_cpt_archive_menu_filter( $items ) {

	if ( ! empty( $items ) ) {
		foreach ( $items as &$item ) {
			if ( 'post_type_archive' === $item->type && post_type_exists( $item->object ) && is_singular( $item->object ) ) {
				$item->classes[] = 'current_page_parent';
			}
		}
	}

	return $items;
}


// !WordPress adds a CSS class to the blog item, but our archive items are wrongly detected as it.

add_filter( 'nav_menu_css_class', 'sfar_remove_current_blog_css_class', 1, 2 );

function sfar_remove_current_blog_css_class( $classes, $item ) {
	static $home_page_id;
	static $is_not_blog;

	if ( ! isset( $home_page_id ) ) {
		$home_page_id = (int) get_option( 'page_for_posts' );

		if ( ! $home_page_id ) {
			remove_filter( 'nav_menu_css_class', 'sfar_remove_current_blog_css_class', 1 );
			return $classes;
		}

		$is_not_blog = is_post_type_archive() || ( is_singular() && ! is_singular( 'post' ) );
	}

	if ( 'post_type' === $item->type && (int) $item->object_id === $home_page_id && $is_not_blog ) {
		$classes = array_diff( $classes, array( 'current_page_parent' ) );
	}

	return $classes;
}


/*------------------------------------------------------------------------------------------------*/
/* !POSTS PER ARCHIVE PAGE ====================================================================== */
/*------------------------------------------------------------------------------------------------*/

// !Set a limit to the number of posts per page.

add_action( 'pre_get_posts', 'sfar_pre_get_posts' );

function sfar_pre_get_posts( $query ) {

	if ( ! $query->is_main_query() ) {
		return;
	}

	remove_action( 'pre_get_posts', 'sfar_pre_get_posts' );

	$post_type = array_filter( (array) $query->get( 'post_type' ) );

	if ( 1 === count( $post_type ) && $query->is_post_type_archive() && ! $query->get( 'posts_per_archive_page' ) ) {

		$post_type = reset( $post_type );
		$settings  = sfar_get_settings();

		if ( ! $settings || empty( $settings[ $post_type ]['posts_per_archive_page'] ) ) {
			return;
		}

		$query->set( 'posts_per_archive_page', $settings[ $post_type ]['posts_per_archive_page'] );

	}
}
