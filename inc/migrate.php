<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin\' uh?' );
}

/*------------------------------------------------------------------------------------------------*/
/* !MIGRATE ===================================================================================== */
/*------------------------------------------------------------------------------------------------*/

function sfar_migrate_to_220() {
	global $wpdb;

	// It's time to get rid of the old stuff.
	$metas = $wpdb->get_col( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_menu_item_object' AND meta_value = 'cpt-archive'" );

	if ( $metas ) {
		foreach ( $metas as $menu_item_id ) {
			$post_type = get_post_meta( $menu_item_id, '_menu_item_type', true );
			update_post_meta( $menu_item_id, '_menu_item_object', $post_type );
			update_post_meta( $menu_item_id, '_menu_item_type', 'cpt-archive' );
		}
	}
}


function sfar_migrate_to_wp440() {
	global $wpdb;

	// Convert old menu items.
	$metas = $wpdb->get_col( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_menu_item_type' AND meta_value = 'cpt-archive'" );

	if ( $metas ) {
		foreach ( $metas as $menu_item_id ) {
			update_post_meta( $menu_item_id, '_menu_item_type', 'post_type_archive' );
		}
	}

	// User metas for the metabox order in the menus page.
	// Side note: I'm not sure why this user meta still exists, it does not seem to be of any use anymore.
	$metas = $wpdb->get_results( "SELECT * FROM $wpdb->usermeta WHERE meta_key = 'meta-box-order_nav-menus' AND meta_value LIKE '%add-cpt-archive%'" );

	if ( $metas ) {
		foreach ( $metas as $meta ) {
			$meta->meta_value = unserialize( $meta->meta_value );
			$meta->meta_value['side'] = trim( str_replace( ',add-cpt-archive,', ',', ',' . $meta->meta_value['side'] . ',' ), ',' );
			update_user_meta( $meta->user_id, 'meta-box-order_nav-menus', $meta->meta_value );
		}
	}
}


$db_version = get_option( 'sfar_version' );

if ( ! $db_version ) {
	sfar_migrate_to_220();
}

if ( ! $db_version || version_compare( SFAR_VERSION, $db_version ) > 0 ) {
	sfar_migrate_to_wp440();
	update_option( 'sfar_version', SFAR_VERSION );
}
