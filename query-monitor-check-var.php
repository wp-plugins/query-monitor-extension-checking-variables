<?php
/**
 * Plugin Name: Query Monitor Extension - Checking Variables
 * Plugin URI:
 * Description:
 * Version: 1.0
 * Author: Sujin
 * Author URI: http://www.sujinc.com/
 * License: GPLv3 or later
 * Text Domain: query-monitor-check-var
 */

error_reporting(-1);
ini_set( 'error_reporting', E_ALL );


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

# Definitions
if ( !defined( 'QMCV_PLUGIN_NAME' ) ) {
	$basename = trim( dirname( plugin_basename( __FILE__ ) ), '/' );
	if ( !is_dir( WP_PLUGIN_DIR . '/' . $basename ) ) {
		$basename = explode( '/', $basename );
		$basename = array_pop( $basename );
	}

	define( 'QMCV_PLUGIN_NAME', $basename );

	if ( !defined( 'QMCV_PLUGIN_DIR' ) )
		define( 'QMCV_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . QMCV_PLUGIN_NAME );

	if ( !defined( 'QMCV_CLASS_DIR' ) )
		define( 'QMCV_CLASS_DIR', WP_PLUGIN_DIR . '/' . QMCV_PLUGIN_NAME . '/classes/' );

	if ( !defined( 'QMCV_TEMPLATE_DIR' ) )
		define( 'QMCV_TEMPLATE_DIR', WP_PLUGIN_DIR . '/' . QMCV_PLUGIN_NAME . '/templates/' );

	if ( !defined( 'QMCV_ASSETS_URL' ) )
		define( 'QMCV_ASSETS_URL', plugin_dir_url( __FILE__ ) . 'assets/' );
}

# Load Classes
include_once( QMCV_CLASS_DIR . 'class.init.php');
