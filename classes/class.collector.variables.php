<?php
/**
 * Collector for Query Monitor
 *
 * project	Query Monitor Extension - Checking Variables
 * version	1.0
 * Author: Sujin
 * Author URI: http://www.sujinc.com/
 *
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class QMCV_Collector_Variable_Checking extends QM_Collector {
	public $id = 'variable_checking';
	public $debug_messages = array();

	function name() {
		return __( 'Variable Checking', 'query-monitor' );
	}

	function has_message() {
		return count( $this->debug_messages );
	}

	function process() {
		$this->data['debug'] = $this->debug_messages;
	}
}


