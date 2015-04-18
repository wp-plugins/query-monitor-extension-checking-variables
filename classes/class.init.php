<?php
/**
 * Initialize
 *
 * project	Query Monitor Extension - Checking Variables
 * version	1.0
 * Author: Sujin
 * Author URI: http://www.sujinc.com/
 *
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class QMCV {
	# 초기화
	function __construct() {
		add_action( 'plugins_loaded', array( $this, 'initialize' ) );
		add_filter( 'qm/outputter/html', array( $this, 'register_output' ), 10, 2 );

		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
	}

	function initialize() {
		if ( !class_exists( 'QueryMonitor' ) ) return false;

		$this->set_collector();
	}

	function wp_enqueue_scripts() {
		$style_path = QMCV_ASSETS_URL . 'debugger.css';
		$script_path = QMCV_ASSETS_URL . 'debugger.js';

		wp_enqueue_style( 'QMCV_Debugger', $style_path, false, '1.0' );
		wp_enqueue_script( 'QMCV_Debugger', $script_path, array( 'jquery' ), '1.0' );
	}

	function register_output( array $output, QM_Collectors $collectors ) {
		$collector = QM_Collectors::get( 'variable_checking' );
		if ( $collector && $collector->has_message() ) {
			require_once( QMCV_CLASS_DIR . 'class.output.variables.php' );
			$output['variable_checking'] = new QMCV_Output_Variable_Checking( $collector );
		}
		return $output;
	}

	private function set_collector() {
		require_once( QMCV_CLASS_DIR . 'class.collector.variables.php' );
		QM_Collectors::add( new QMCV_Collector_Variable_Checking );
	}
}

if( !function_exists( 'console' ) ) {
	/**
		* print debug message
		*
		* @return void
		* @since 1.0
	*/

	function console() {
		if ( !class_exists( 'QM_Collectors' ) ) return false;

		$collector = QM_Collectors::get( 'variable_checking' );
		$numargs = func_num_args();
		$arg_list = func_get_args();

		for ($i = 0; $i < $numargs; $i++) {
			$collector->debug_messages[] = $arg_list[$i];
		}
	}
}
new QMCV;


