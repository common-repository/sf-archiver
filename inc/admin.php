<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin\' uh?' );
}

/*------------------------------------------------------------------------------------------------*/
/* !I18N ======================================================================================== */
/*------------------------------------------------------------------------------------------------*/

add_action( 'init', 'sfar_i18n' );

function sfar_i18n() {
	load_plugin_textdomain( 'sf-archiver', false, basename( dirname( SFAR_FILE ) ) . '/languages/' );
}


/*------------------------------------------------------------------------------------------------*/
/* !POSTS/CPTS LISTS ============================================================================ */
/*------------------------------------------------------------------------------------------------*/

// !On posts/CPTs list, display a link to frontend archive.
// https://www.screenfeed.fr/blog/afficher-dans-administration-lien-vers-archive-cpts-02542/

add_action( 'admin_footer-edit.php', 'sfar_print_post_type_archive_link_script' );

function sfar_print_post_type_archive_link_script() {
	global $typenow;

	if ( ! $typenow || ! ( 'post' === $typenow || array_key_exists( $typenow, sfar_get_post_types() ) ) ) {
		return;
	}

	$href = 'post' === $typenow ? get_page_for_posts( true ) : get_post_type_archive_link( $typenow );

	echo '<script>jQuery(document).ready( function( $ ) {
	$( ".page-title-action" ).first().before( "<a class=\"post-type-archive-link dashicons dashicons-external\" href=\"' . esc_url( $href ) . '\" style=\"vertical-align: middle; margin-right: 8px;\"><span class=\"screen-reader-text\">' . __( 'Visit Site' ) . '</span></a>" );
} );</script>' . "\n";
}


/*------------------------------------------------------------------------------------------------*/
/* !SETTINGS ==================================================================================== */
/*------------------------------------------------------------------------------------------------*/

// !Register settings.

add_action( 'plugins_loaded', 'sfar_register_settings', 9 );

function sfar_register_settings() {
	sf_register_setting( 'reading', 'sf_archiver', 'sfar_sanitize_settings' );
}


// !In the plugins list, add a link to the settings page.

add_filter( 'plugin_action_links_' . plugin_basename( SFAR_FILE ), 'sfar_add_settings_action_link', 10, 2 );

function sfar_add_settings_action_link( $links, $file ) {
	$links['settings'] = '<a href="' . admin_url( 'options-reading.php' ) . '">' . __( 'Reading Settings' ) . '</a>';
	return $links;
}


// !Add settings section and fields.

add_action( 'load-options-reading.php', 'sfar_settings_fields' );

function sfar_settings_fields() {
	$post_types = sfar_get_post_types();

	if ( empty( $post_types ) ) {
		return;
	}

	add_settings_section( 'custom-post-types', __( 'Post types', 'sf-archiver' ), null, 'reading' );

	foreach ( $post_types as $post_type => $atts ) {
		$args = array(
			'post_type'  => $post_type,
			'label_for'  => $post_type . '|posts_per_archive_page',
			'setting'    => 'posts_per_archive_page',
			'after'      => $atts->label,
			'attributes' => array(
				'type'   => 'number',
				'class'  => 'small-text',
				'min'    => 1,
				'step'   => 1,
			),
		);
		add_settings_field( $post_type . '-posts_per_archive_page', sprintf( _x( '%s per page', 's: post type name (plural form)', 'sf-archiver' ), $atts->label ), 'sfar_field', 'reading', 'custom-post-types', $args );
	}
}


// !Input field html.

function sfar_field( $args ) {
	if ( empty( $args['setting'] ) || empty( $args['post_type'] ) ) {
		return;
	}

	$name  = ! empty( $args['label_for'] ) ? str_replace( '|', '][', $args['label_for'] ) : $args['setting'];
	$value = sfar_get_settings();
	$value = ! empty( $value[ $args['post_type'] ] ) ? $value[ $args['post_type'] ] : array();
	$value = ! empty( $value[ $args['setting'] ] )   ? $value[ $args['setting'] ]   : '';

	$attributes = ! empty( $args['attributes'] ) ? $args['attributes'] : array();
	$attributes = array_map( 'esc_attr', $attributes );
	$attributes = array_merge( array(
		'type'  => 'text',
		'name'  => 'sf_archiver[' . $name . ']',
		'value' => $value,
	), $attributes );

	if ( ! empty( $args['label_for'] ) ) {
		$attributes['id'] = esc_attr( $args['label_for'] );
	}

	echo '<input' . build_html_atts( $attributes ) . '/> ';
	echo ! empty( $args['after'] ) ? $args['after'] : '';
}


/*------------------------------------------------------------------------------------------------*/
/* !TOOLS ======================================================================================= */
/*------------------------------------------------------------------------------------------------*/

// !Build a string for html attributes (means: separated by a space) : array( 'width' => '200', 'height' => '150', 'yolo' => 'foo' ) ==> ' width="200" height="150" yolo="foo"'

if ( ! function_exists( 'build_html_atts' ) ) :
	function build_html_atts( $attributes, $quote = '"' ) {
		$out = '';

		if ( ! is_array( $attributes ) || empty( $attributes ) ) {
			return '';
		}

		foreach ( $attributes as $att_name => $att_value ) {
			$out .= ' ' . esc_attr( $att_name ) . '=' . $quote . $att_value . $quote;
		}

		return $out;
	}
endif;


if ( ! function_exists( 'get_page_for_posts' ) ) :
	function get_page_for_posts( $permalink = false ) {
		static $out;

		if ( ! isset( $out ) ) {
			$out            = array( 'ID' => false, 'permalink' => '' );
			$show_on_front  = get_option( 'show_on_front' );

			if ( 'page' === $show_on_front ) {
				$page_for_posts = absint( get_option( 'page_for_posts' ) );
				$page_for_posts = $page_for_posts ? get_post( $page_for_posts ) : false;

				if ( $page_for_posts ) {
					$out        = array(
						'ID'        => $page_for_posts->ID,
						'permalink' => get_permalink( $page_for_posts ),
					);
				}
			} else {
				$out['permalink'] = user_trailingslashit( home_url() );
			}
		}

		return $permalink ? $out['permalink'] : $out['ID'];
	}
endif;


// !register_setting() is not always defined...

if ( ! function_exists( 'sf_register_setting' ) ) :
	function sf_register_setting( $option_group, $option_name, $sanitize_callback = '' ) {
		global $new_whitelist_options;

		if ( function_exists( 'register_setting' ) ) {
			register_setting( $option_group, $option_name, $sanitize_callback );
			return;
		}

		$new_whitelist_options = isset( $new_whitelist_options ) && is_array( $new_whitelist_options ) ? $new_whitelist_options : array();
		$new_whitelist_options[ $option_group ] = isset( $new_whitelist_options[ $option_group ] ) && is_array( $new_whitelist_options[ $option_group ] ) ? $new_whitelist_options[ $option_group ] : array();
		$new_whitelist_options[ $option_group ][] = $option_name;

		if ( ! $sanitize_callback ) {
			add_filter( "sanitize_option_{$option_name}", $sanitize_callback );
		}
	}
endif;


// !Get public post types that have an archive.

function sfar_get_post_types() {
	$post_types = get_post_types( array(
		'public'      => true,
		'show_ui'     => true,
		'has_archive' => true,
	), 'objects' );

	return apply_filters( 'sf_archiver_post_types', $post_types );
}
