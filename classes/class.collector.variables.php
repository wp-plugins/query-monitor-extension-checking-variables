<?php
/**
 * Collector for Query Monitor
 *
 * project	Query Monitor Extension - Checking Variables
 * version	2.0
 * Author: Sujin
 * Author URI: http://www.sujinc.com/
 *
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class QMCV_Collector_Variable_Checking extends QM_Collector {
	public $id = 'variable_checking';
	function name() {
		return __( 'Variable Checking', 'query-monitor' );
	}
}


