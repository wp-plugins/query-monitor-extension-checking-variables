<?php
/**
 * Initialize
 *
 * project	Query Monitor Extension - Checking Variables
 * version	2.0
 * Author: Sujin
 * Author URI: http://www.sujinc.com/
 *
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class QMCV {
	private $is_qm = false;
	public $mode = 'extended';
	public $debug_messages = array();

	# 초기화
	function __construct() {
		add_action( 'plugins_loaded', array( $this, 'initialize' ) );
		add_filter( 'qm/outputter/html', array( $this, 'register_output' ), 10, 2 );

		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );

		# AJAX
		add_action( 'wp_ajax_QMCV_CHANGE_MODE', array( $this, 'change_mode' ) );
	}

	function initialize() {
		if ( !class_exists( 'QueryMonitor' ) ) {
			$this->mode = 'stand alone';
		} else {
			$this->is_qm = true;
			$this->mode = get_option( 'QMCV_mode', 'extended' );
		}

		if ( $this->mode == 'extended' ) {
			$this->set_collector();
		} else {
			add_action( 'wp_footer', array( $this, 'echo_debug' ) );
			add_action( 'admin_footer', array( $this, 'echo_debug' ) );
		}
	}

	function change_mode() {
		if ( $_POST['mode'] ) {
			update_option( 'QMCV_mode', $_POST['mode'] );
		}
		die;
	}

	function wp_enqueue_scripts() {
		$style_path = QMCV_ASSETS_URL . 'debugger.css';
		$script_path = QMCV_ASSETS_URL . 'debugger.min.js';

		wp_enqueue_style( 'QMCV_Debugger', $style_path, false, '1.0' );
		wp_enqueue_script( 'QMCV_Debugger', $script_path, array( 'jquery' ), '1.0' );
	}

	function register_output( array $output, QM_Collectors $collectors ) {
		if ( $collector = QM_Collectors::get( 'variable_checking' ) ) {
			require_once( QMCV_CLASS_DIR . 'class.output.variables.php' );
			$output['variable_checking'] = new QMCV_Output_Variable_Checking( $collector );
		}
		return $output;
	}

	private function set_collector() {
		require_once( QMCV_CLASS_DIR . 'class.collector.variables.php' );
		QM_Collectors::add( new QMCV_Collector_Variable_Checking );
	}

	public function push( $message ) {
		$this->debug_messages[] = $message;
	}

	public function echo_debug() {
		if ( $this->debug_messages ) {
			foreach ( $this->debug_messages as $debug_message ) {
				$type_size = $this->get_type_and_size( $debug_message );
				printf( '<div class="SJ_Debugger" data-type="%s" data-name="%s" data-size="%s">', $type_size[0], $type_size[1], $type_size[2] );
				echo '<dl class="SJD-depth1">';
				$type_size = $this->print_dt( $debug_message );
				$this->echo_depth( $debug_message );
				echo '</dl></div>';
			}
		}

		if ( $this->is_qm ) {
			?>
			<div class="SJ_Debugger">
				<a href="#" id="btn-DependVarCheck">Set Variable Checking Dependently into Query Monitor.</a>
			</div>
			<?php
		}

		if ( $this->debug_messages || $this->is_qm ) {
			?>
			<div class="SJ_Debugger">&nbsp;</div>
			<?php
		}
	}

	public function echo_depth( $debugs ) {
		$type_size = $this->get_type_and_size( $debugs );

		switch ( $type_size[0] ) {
			case 'boolean' :
				if ( $debugs ) echo '<dd class="boolean-true">TRUE</dd>';
				else echo '<dd class="boolean-false">FALSE</dd>';

				break;

			case 'array' :
			case 'object' :
				echo '<dd>';

				foreach( $debugs as $key => $debug ) {
					$type = gettype( $debug );
					echo '<dl>';

					if ( $type == 'array' || $type == 'object' ) {
						$this->print_dt( $debug, $key );

						if ( $debug ) {
							$this->echo_depth( $debug );
						}
					} else if ( $type == 'boolean' ) {
						echo '<dt class="fleft"><span class="key">[' . $key . ']</span></dt>';

						if ( $debug ) echo '<dd class="boolean-true">TRUE</dd>';
						else echo '<dd class="boolean-false">FALSE</dd>';

					} else {
						echo '<dt class="fleft"><span class="key">[' . $key . ']</span></dt>';

						if ( !$debug ) echo '<dd class="empty">(empty)</dd>';
						else echo '<dd class="' . $type . '">' . $debug . '</dd>';
					}

					echo '</dl>';
				}
				echo '</dd>';

				break;

			case 'string' :
			default :
				if ( !$debugs ) echo '<dd class="empty">(empty)</dd>';
				else printf ( '<dd class="%s">%s</dd>', $type_size[0], $debugs );

				break;
		}
	}

	public function print_dt( $debugs, $key = false ) {
		$type_size = $this->get_type_and_size( $debugs );
		$key = ( $key !== false ) ? "<span class='key'>[{$key}]</span> " : '';

		$empty = ( !$debugs ) ? 'empty' : '';

		switch ( $type_size[0] ) {
			case 'string' :
			case 'array' :
				printf ( '<dt class="type-%s %s">%s<strong>%s</strong> <span class="size">( size=%s )</span></dt>', $type_size[0], $empty, $key, $type_size[1], $type_size[2] );
				break;

			case 'object' :
				printf ( '<dt class="type-%s %s">%s<span class="object">(object)</span> <strong>%s</strong> <span class="size">( size=%s )</span></dt>', $type_size[0], $empty, $key, $type_size[1], $type_size[2] );
				break;

			default :
				printf ( '<dt class="type-%s %s">%s<strong>%s</strong></dt>', $type_size[0], $empty, $key, $type_size[0] );
				break;
		}

		return $type_size;
	}

	public function get_type_and_size( $var ) {
		$type = $name = gettype( $var );

		switch ( $type ) {
			case 'string' :
				$size = strlen( $var );
				break;

			case 'array' :
				$size = count( $var );
				break;

			case 'object' :
				$name = get_class( $var );
				$size = count( (array) $var );
				break;

			default :
				$size = false;
				break;
		}

		return array( $type, $name, $size );
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
		global $QMCV;

		$numargs = func_num_args();
		$arg_list = func_get_args();

		for ($i = 0; $i < $numargs; $i++) {
			$QMCV->push( $arg_list[$i] );
		}
	}
}
global $QMCV;
$QMCV = new QMCV();


