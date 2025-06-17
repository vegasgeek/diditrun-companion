<?php
/**
 * Plugin Name: Did It Run Companion
 * Plugin URI: https://diditrun.dev
 * Description: Companion plugin for Did It Run? monitoring service.
 * Version: 1.0.4
 * Author: VegasGeek
 * Author URI: https://vegasgeek.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: diditrun-companion
 *
 * @package itto-companion
 */

define( 'DIRMS_COMPANION_VERSION', '1.0.4' );
define( 'DIRMS_COMPANION_PATH', plugin_dir_path( __FILE__ ) );
define( 'DIRMS_COMPANION_URL', plugin_dir_url( __FILE__ ) );

// Include admin functionality.
require_once DIRMS_COMPANION_PATH . 'includes/diditrun-admin.php';
require_once DIRMS_COMPANION_PATH . 'includes/functions.php';
