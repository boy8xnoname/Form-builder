<?php

require_once __DIR__ . '/inc/CustomServices.php';
require_once __DIR__ . '/inc/EP_Form_Wizard_Block_Services.php';

new AdvesaGroup\EpDental\CustomServices();

// Auto-import functions.
add_action( 'admin_init', 'ep_dental_auto_import_data' );

function ep_dental_auto_import_data() {
	global $wpdb;

	if ( get_option( 'ep_dental_auto_import' ) === 'true' ) {
		return;
	}

	$prefix = $wpdb->prefix;
	$count = (int) $wpdb->get_var( "SELECT COUNT(*) FROM `${prefix}EP_Mondula_Form_Wizards`" );

	if ( $count === 0 ) {
		// $json_data = file_get_contents( __DIR__ . '/sample-data.json' );
		$data = file_get_contents( __DIR__ . '/sample-data.json' );
		$json_data = json_encode(json_decode($data), JSON_UNESCAPED_UNICODE);
		$wpdb->insert( $prefix . 'EP_Mondula_Form_Wizards', [
			'json' => $json_data,
			'version' => '1.7.2',
			'date' => current_time( 'mysql' ),
		] );

		update_option( 'ep_dental_auto_import', 'true', true );
	}
}
