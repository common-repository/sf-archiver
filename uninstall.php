<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die();
}

// Options.
delete_option( 'sf_archiver' );
delete_option( 'sfar_version' );
