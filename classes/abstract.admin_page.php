<?php
/**
 * WP_Admin_Page Class
 *
 * @author Sujin 수진 Choi
 * @package wp-hacks
 * @version 1.0.0
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
				'callback' => 'view_option'
			), $options ) );

			$this->position = $position;
			$this->page_name = $name;
			$this->capability = $cap;
			$this->callback = $callback;
			$this->key = get_class( $this );

			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		}

		function add_admin_menu() {
			switch ( $this->position ) {
				case 'option' :
					add_options_page( $this->page_name, $this->page_name, $this->capability, $this->key, array( $this, 'view_' . $this->callback ));
				break;
			}
		}

		function page__header() {
			?>
			<div class="wrap" id="<?php echo $this->key ?>">
				<h2><?php echo $this->page_name ?></h2>
				<div class="clear"></div>
			<?php
		}

		function page__footer() {
			?>
			</div>
			<?php
		}
	}
}


