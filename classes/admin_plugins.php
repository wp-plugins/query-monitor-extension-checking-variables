<?php
/**
 * Admin : Plugins Hooking
 *
 * project	Query Monitor Extension - Checking Variables
 * version	3.0
 * Author: Sujin 수진 Choi
 * Author URI: http://www.sujinc.com/
 *
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class QMCV_AdminPlugins {
	# 초기화
	static function hook() {
		add_filter( 'plugin_row_meta' , array( QMCV_AdminPlugins, 'add_setting_into_table' ), 15, 3 );
	}

	static function add_setting_into_table( $plugin_meta, $a, $plugin_data ) {
		if ( $plugin_data['TextDomain'] === 'query-monitor-check-var' ) {
			$plugin_meta[] = '<a href="#">Setting</a>';
		}

		return $plugin_meta;
	}
}