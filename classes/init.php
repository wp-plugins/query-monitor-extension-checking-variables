<?php
/**
 * Initialize
 *
 * project	Query Monitor Extension - Checking Variables
 * version	3.0
 * Author: Sujin 수진 Choi
 * Author URI: http://www.sujinc.com/
 *
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class QMCV {
	private static $__instance;
	private static $query_monitor_mode = false;
	public static $mode = 'extended';

	private static $style_path;
	private static $script_path;

	private $QMCV_AdminPage;

	function __construct() {
		self::$style_path = QMCV_ASSETS_URL . 'debugger.css';
		self::$script_path = QMCV_ASSETS_URL . 'debugger.min.js';

		register_activation_hook( QMCV_PLUGIN_BASE , array( $this, 'activate' ) );

		add_action( 'plugins_loaded', array( $this, 'trigger_all' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );

		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
			QMCV_AdminPlugins::hook();
			$QMCV_AdminPage = new Check_Variables_Options( array(
				'name' => 'Check Variables',
				'callback' => 'check_var'
			) );
		}
	}

	function activate() {
		Check_Variables_Options::get_options();
	}

	function trigger_all() {
		QMCV_IO::trigger_hooks();

		if ( class_exists( 'QueryMonitor' ) && Check_Variables_Options::get_query_monitor() === true ) {
			// Set Query Monitor Collector
			include_once( QMCV_CLASS_DIR . 'query_monitor/collector.php');
 			QMCV_Collector_Variable_Checking::initialize();
		}
	}

	function wp_enqueue_scripts() {
		wp_enqueue_style( 'QMCV_Debugger', self::$style_path, false, '1.0' );
		wp_enqueue_script( 'QMCV_Debugger', self::$script_path, array( 'jquery' ), '1.0' );
	}

	/**
	 * initialize
	 *
	 * @since 3.0
	 * @access public
	 */
	public static function initialize(){
		QMCV::getInstance();
	}

	/**
	 * Return Instance
	 *
	 * @since 3.0
	 * @access public
	 */
	public static function getInstance() {
		// check if instance is avaible
		if ( self::$__instance==null ) {
			// create new instance if not
			self::$__instance = new self();
		}
		return self::$__instance;
	}
}
