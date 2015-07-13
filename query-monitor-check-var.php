<?php
/**
 * Plugin Name: Query Monitor Extension - Checking Variables
 * Plugin URI: http://www.sujinc.com/
 * Description: Variables Checker for Developers
 * Version: 3.0
 * Author: Sujin 수진 Choi
 * Author URI: http://www.sujinc.com/
 * License: GPLv3 or later
 * Text Domain: query-monitor-check-var
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

# Definitions
if ( !defined( 'QMCV_PLUGIN_NAME' ) ) {
	$basename = trim( dirname( plugin_basename( __FILE__ ) ), '/' );
	if ( !is_dir( WP_PLUGIN_DIR . '/' . $basename ) ) {
		$basename = explode( '/', $basename );
		$basename = array_pop( $basename );
	}

	define( 'QMCV_PLUGIN_NAME', $basename );

	if ( !defined( 'QMCV_PLUGIN_BASE' ) )
		define( 'QMCV_PLUGIN_BASE', WP_PLUGIN_DIR . '/' . QMCV_PLUGIN_NAME . '/' . basename(__FILE__) );

	if ( !defined( 'QMCV_PLUGIN_DIR' ) )
		define( 'QMCV_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . QMCV_PLUGIN_NAME );

	if ( !defined( 'QMCV_CLASS_DIR' ) )
		define( 'QMCV_CLASS_DIR', WP_PLUGIN_DIR . '/' . QMCV_PLUGIN_NAME . '/classes/' );

	if ( !defined( 'QMCV_VIEW_DIR' ) )
		define( 'QMCV_VIEW_DIR', WP_PLUGIN_DIR . '/' . QMCV_PLUGIN_NAME . '/views/' );

	if ( !defined( 'QMCV_ASSETS_URL' ) )
		define( 'QMCV_ASSETS_URL', plugin_dir_url( __FILE__ ) . 'assets/' );
}

# Load Classes
include_once( QMCV_CLASS_DIR . 'admin_plugins.php');
include_once( QMCV_CLASS_DIR . 'abstract.admin_page.php');
include_once( QMCV_CLASS_DIR . 'check_variables_options.php');
include_once( QMCV_CLASS_DIR . 'io.php');
include_once( QMCV_CLASS_DIR . 'init.php');

QMCV::initialize();

