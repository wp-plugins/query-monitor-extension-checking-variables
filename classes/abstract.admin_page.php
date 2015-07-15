<?php
/**
 * WP_Admin_Page Class
 *
 * @author Sujin 수진 Choi
 * @package wp-hacks
 * @version 1.0.1
 *
 */

if ( !defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( !class_exists('WP_Admin_Page' ) ) {
	abstract class WP_Admin_Page {
		private $position;
		private $page_name;
		private $capability;
		private $callback;
		private $key;

		private $file;
		public $url;

		public function __call( $name, $arguments ) {
			if ( $name === 'view_' . $this->callback ) {
				$this->page__header();
				$this->{ $this->callback }();
				$this->page__footer();

				return true;
			}

			return false;
		}

		function __construct( $options = array() ) {
			extract( shortcode_atts( array(
				'position' => 'option',
				'name' => 'Page Name',
				'cap' =>'activate_plugins',
				'callback' => 'view_option',
				'setting_button' => false
			), $options ) );

			$this->position = $position;
			$this->page_name = $name;
			$this->capability = $cap;
			$this->callback = $callback;
			$this->key = get_class( $this );

			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

			if ( $setting_button ) {
				add_filter( 'plugin_row_meta' , array( $this, 'add_setting_into_plugins_table' ), 15, 3 );
				$this->file = $setting_button;
			}
		}

		function add_admin_menu() {
			switch ( $this->position ) {
				case 'option' :
					add_options_page( $this->page_name, $this->page_name, $this->capability, $this->key, array( $this, 'view_' . $this->callback ));
					$this->url = admin_url( 'options-general.php?page=' . $this->key );
				break;
			}
		}

		function page__header() {
			printf( '<div class="wrap" id="%s"><h2>%s</h2><div class="clear"></div>', $this->key, $this->page_name );
		}

		function page__footer() {
			echo '</div>';
		}

		function add_setting_into_plugins_table( $plugin_meta, $file, $plugin_data ) {
			if ( strpos( $file, $this->file ) !== false ) {
				$plugin_meta[] = sprintf( '<a href="%s">Setting</a>', $this->url );
			}

			return $plugin_meta;
		}
	}
}


