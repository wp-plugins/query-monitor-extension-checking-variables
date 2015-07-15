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

class Check_Variables_Options extends WP_Admin_Page {
	private static $default_options = array(
		'query_monitor' => false,
		'footer' => false,
		'console' => true,
		'hide' => true,
		'capability' => array( 'administrator' ),
		'users' => ''
	);
	private static $options = array();

	function save_setting() {
		if ( !parent::save_setting() ) return false;

		$capability = ( !is_array( $_POST['capability'] ) ) ? array() : $_POST['capability'];

		$options = array(
			'query_monitor' => isset( $_POST['query_monitor'] ),
			'footer' => isset( $_POST['footer'] ),
			'console' => isset( $_POST['console'] ),
			'hide' => isset( $_POST['hide'] ),
			'capability' => array_keys( $capability ),
			'users' => $_POST['users']
		);

		self::set_options( $options );
	}

	public function __call( $name, $arguments ) {
		if( !parent::__call( $name, $arguments ) ) {
			if ( strstr( $name, 'get_' ) !== false ) {
				$options = self::get_options();
				return $options[ substr( $name, 4 ) ];
			}
		}
	}

	static public function __callStatic( $name, $arguments ) {
		if ( strstr( $name, 'get_' ) !== false ) {
			$options = self::get_options();
			return $options[ substr( $name, 4 ) ];
		}
	}

	static public function set_options( $options = false ) {
		self::$options = ( !$options ) ? self::$default_options : $options;
		update_option( 'QMCV_options', self::$options );
		return self::$options;
	}

	static public function get_options() {
		if ( self::$options ) return self::$options;

		$options = get_option( 'QMCV_options' );

		if ( !$options ) {
			return self::set_options();
		} else {
			self::$options = $options;
			return self::$options;
		}
	}

	static public function is_user_allowed() {
		$user_allowed = false;
		$current_user = wp_get_current_user();
		$get_capability = Check_Variables_Options::get_capability();

		if ( !$get_capability ) {
			$user_allowed = true;

		} else {
			foreach( $current_user->caps as $key => $val ) {
				if ( in_array( $key, $get_capability ) ) {
					$user_allowed = true;
					break;
				}
			}
		}

		if ( $user_allowed === false ) {
			$users = self::get_users();
			$users = str_replace( ' ', '', $users );
			if ( !empty( $users ) && in_array( $current_user->ID, explode( ',', $users ) ) ) $user_allowed = true;
		}

		return $user_allowed;
	}
}