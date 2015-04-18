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

class QMCV_Output_Variable_Checking extends QM_Output_Html {

	public function __construct( QM_Collector $collector ) {
		parent::__construct( $collector );
		add_filter( 'qm/output/menus', array( $this, 'admin_menu' ), 60 );
	}

	public function output() {

		$data = $this->collector->get_data();

		?>
		<div class="qm" id="<?php echo esc_attr( $this->collector->id() ) ?>">
			<table cellspacing="0" class="SJ_Debugger">
				<thead>
					<tr>
						<th colspan="2"><?php echo esc_html( $this->collector->name() ) ?></th>
					</tr>
				</thead>

				<tbody>
					<?php
					foreach( $data['debug'] as $key => $debug_message ) {
						$type_size = $this->get_type_and_size( $debug_message );
					?>
					<tr>
						<td colspan="2">
							<dl class="SJD-depth1">
							<?php $this->print_dt( $debug_message ); ?>
							<?php $this->echo_depth( $debug_message ); ?>
							</dl>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	private function echo_depth( $debugs ) {
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

	private function print_dt( $debugs, $key = false ) {
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

	private function get_type_and_size( $var ) {
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

