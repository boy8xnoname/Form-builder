<?php
/*
 * Plugin Name: EP Multi Step Form
 * Version: 1.0.0
 * Plugin URI: https://advesa.com/
 * Description: EP Multi Step Form Create and embed Multi Step Form.
 * Author: Advesa
 * Author URI: https://advesa.com/
 * Requires at least: 5.0
 * Tested up to: 5.5
 *
 * Text Domain: multi-step-form
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Advessa
 * @since 1.0.0
 */

if (!defined('ABSPATH')) exit;

// Load plugin class files
require_once('includes/ep.class.php');
require_once('includes/ep-settings.class.php');
require_once('includes/ep-settings-api.class.php');

// Load plugin libraries
require_once('includes/lib/ep-wizard-repository.class.php');
require_once('includes/lib/ep-wizard-service.class.php');

require_once('includes/admin/ep-admin.class.php');
require_once('includes/admin/ep-list-table.class.php');
require_once('includes/lib/ep-shortcode.class.php');
require_once('includes/admin/blocks/gutenberg/ep-gutenberg.php');
require_once('includes/admin/blocks/elementor/ep-elementor.php');
require_once('includes/lib/ep-wizard.class.php');
require_once('includes/lib/ep-wizard-step.class.php');
require_once('includes/lib/ep-wizard-step-part.class.php');

// Blocks
require_once('includes/lib/ep-block.class.php');
require_once('includes/lib/ep-blocks/agree/ep-block-agree.class.php');
require_once('includes/lib/ep-blocks/radio/ep-block-radio.class.php');
require_once('includes/lib/ep-blocks/email/ep-block-email.class.php');
require_once('includes/lib/ep-blocks/getvariable/ep-block-get-variable.class.php');
require_once('includes/lib/ep-blocks/numeric/ep-block-numeric.class.php');
require_once('includes/lib/ep-blocks/file/ep-block-file.class.php');
require_once('includes/lib/ep-blocks/date/ep-block-date.class.php');
require_once('includes/lib/ep-blocks/paragraph/ep-block-paragraph.class.php');
require_once('includes/lib/ep-blocks/media/ep-block-media.class.php');
require_once('includes/lib/ep-blocks/select/ep-block-select.class.php');
require_once('includes/lib/ep-blocks/text/ep-block-text.class.php');
require_once('includes/lib/ep-blocks/textarea/ep-block-textarea.class.php');

// Custom scripts.
require_once __DIR__ . '/custom/custom.php';

function ep_activate_form_wizard($network_wide = false) {
	require_once plugin_dir_path(__FILE__) . 'includes/lib/ep-activator.class.php';
	EP_Mondula_Form_Wizard_Activator::activate($network_wide);
}

register_activation_hook(__FILE__, 'ep_activate_form_wizard');

function ep_new_blog($blog_id, $user_id, $domain, $path, $site_id, $meta) {
	if (is_plugin_active_for_network('multi-step-form/mondula-form-wizard.php')) {
		require_once plugin_dir_path(__FILE__) . 'includes/lib/ep-activator.class.php';
		EP_Mondula_Form_Wizard_Activator::activate_for_blog($blog_id);
	}
}

add_action('wpmu_new_blog', 'ep_new_blog', 10, 6);


function ep_drop_tables($tables = array(), $blog_id = null) {
  	require_once plugin_dir_path(__FILE__) . 'includes/lib/ep-activator.class.php';
  	return EP_Mondula_Form_Wizard_Activator::drop_table($tables, $blog_id);
}

add_filter('wpmu_drop_tables', 'ep_drop_tables', 10, 2);

/**
 * Returns the main instance of EP_Mondula_Form_Wizard to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object EP_Mondula_Form_Wizard
 */
function EP_Mondula_Form_Wizard() {
	$instance = EP_Mondula_Form_Wizard::instance(__FILE__, '1.7.2');

	if (is_null($instance->settings)) {
		$instance->settings = EP_Mondula_Form_Wizard_Settings::instance($instance);
	}

	return $instance;
}

EP_Mondula_Form_Wizard();
