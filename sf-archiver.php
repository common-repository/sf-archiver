<?php
/*
 * Plugin Name: SF Archiver
 * Plugin URI: https://www.screenfeed.fr/archi/
 * Description: Add some small and useful utilities for managing your Custom Post Types archives.
 * Version: 3.0.2
 * Author: Grégory Viguier
 * Author URI: https://www.screenfeed.fr/greg/
 * License: GPLv3
 * License URI: https://www.screenfeed.fr/gpl-v3.txt
 * Text Domain: sf-archiver
 * Domain Path: /languages/
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin\' uh?' );
}

// !Check WordPress Version.
global $wp_version;

if ( version_compare( $wp_version, '4.4-alpha' ) < 0 ) {
	return;
}

/*------------------------------------------------------------------------------------------------*/
/* !CONSTANTS =================================================================================== */
/*------------------------------------------------------------------------------------------------*/

define( 'SFAR_VERSION', '3.0.2' );
define( 'SFAR_FILE',    __FILE__ );


/*------------------------------------------------------------------------------------------------*/
/* !INCLUDES ==================================================================================== */
/*------------------------------------------------------------------------------------------------*/

add_action( 'plugins_loaded', 'sfar_includes' );

function sfar_includes() {

	$plugin_dir = plugin_dir_path( SFAR_FILE );

	if ( is_admin() && ! doing_ajax() ) {

		include( $plugin_dir . 'inc/migrate.php' );
		include( $plugin_dir . 'inc/admin.php' );

	} elseif ( is_frontend() ) {

		include( $plugin_dir . 'inc/frontend.php' );

	}
}


/*------------------------------------------------------------------------------------------------*/
/* !SETTINGS ==================================================================================== */
/*------------------------------------------------------------------------------------------------*/

// !Settings sanitization function.

function sfar_sanitize_settings( $value, $option_name = null, $context = null ) {
	$out     = array();
	$context = 'get' === $context ? 'get' : 'db';	// $context === 'db' means we're setting new values.

	if ( $value && is_array( $value ) ) {
		foreach ( $value as $post_type => $atts ) {
			if ( is_array( $atts ) ) {
				$out[ $post_type ] = array(
					'posts_per_archive_page' => ! empty( $atts['posts_per_archive_page'] ) && (int) $atts['posts_per_archive_page'] > 0 ? (int) $atts['posts_per_archive_page'] : 0,
				);
			}
		}
	}

	return apply_filters( 'sf_archiver_settings', $out, $value, $context );
}


// !Get sabitized settings.

function sfar_get_settings() {
	$settings = get_option( 'sf_archiver', array() );
	return sfar_sanitize_settings( $settings, 'sf_archiver', 'get' );
}


/*------------------------------------------------------------------------------------------------*/
/* !TOOLS ======================================================================================= */
/*------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'doing_ajax' ) ) :
	function doing_ajax() {
		return defined( 'DOING_AJAX' ) && DOING_AJAX && is_admin();
	}
endif;


if ( ! function_exists( 'is_frontend' ) ) :
	function is_frontend() {
		return ! defined( 'XMLRPC_REQUEST' ) && ! defined( 'DOING_CRON' ) && ! is_admin();
	}
endif;
