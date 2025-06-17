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

// Check for plugin updates.
require 'vendor/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/vegasgeek/diditrun-companion/',
	__FILE__,
	'diditrun-companion'
);

// Set the branch that contains the stable release.
$myUpdateChecker->setBranch( 'main' );
