<?php
/**
 * Plugin Name: Did It Run Companion
 * Plugin URI: https://diditrun.dev
 * Description: Companion plugin for Did It Run? monitoring service.
 * Version: 1.0.8
 * Author: VegasGeek
 * Author URI: https://vegasgeek.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: diditrun-companion
 *
 * @package diditrun-companion
 */

/**
 * Exit if called directly.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'DIDITRUN_COMPANION_PATH', plugin_dir_path( __FILE__ ) );
define( 'DIDITRUN_COMPANION_URL', plugin_dir_url( __FILE__ ) );

// Include admin functionality.
require_once DIDITRUN_COMPANION_PATH . 'includes/diditrun-admin.php';
require_once DIDITRUN_COMPANION_PATH . 'includes/functions.php';
require_once DIDITRUN_COMPANION_PATH . 'includes/diditrun-plugin-updater.php';

// Plugin update details.
// URL to call for plugin updates.
define( 'DIDITRUN_PLUGIN_UPDATE_URL', 'https://diditrun.dev' );

// Product ID.
define( 'DIDITRUN_PRODUCT_ID', 1501 );

// Product Name.
define( 'DIDITRUN_PRODUCT_NAME', 'Did It Run? Companion' );

// License page.
define( 'DIDITRUN_LICENSE_PAGE', 'diditrun-license' );

if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater.
	include __DIR__ . '/EDD_SL_Plugin_Updater.php';
}

/**
 * Initialize the updater. Hooked into `init` to work with the
 * wp_version_check cron job, which allows auto-updates.
 */
function diditrun_plugin_updater() {

	// To support auto-updates, this needs to run during the wp_version_check cron job for privileged users.
	$doing_cron = defined( 'DOING_CRON' ) && DOING_CRON;
	if ( ! current_user_can( 'manage_options' ) && ! $doing_cron ) {
		return;
	}

	// retrieve our license key from the DB
	// $license_key = trim( get_option( 'edd_sample_license_key' ) );

	// setup the updater
	$edd_updater = new EDD_SL_Plugin_Updater(
		DIDITRUN_PLUGIN_UPDATE_URL,
		__FILE__,
		array(
			'version' => '1.0.7',
			'license' => null,
			'item_id' => DIDITRUN_PRODUCT_ID,
			'author'  => 'VegasGeek',
			'beta'    => false,
		)
	);
}
add_action( 'init', 'diditrun_plugin_updater' );
