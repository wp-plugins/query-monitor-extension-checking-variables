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

class QMCV_Output_Variable_Checking extends QM_Output_Html {

	public function __construct( QM_Collector $collector ) {
		parent::__construct( $collector );
		add_filter( 'qm/output/menus', array( $this, 'admin_menu' ), 60 );
	}

	public function output() {
		$data = $this->collector->get_data();

		?>
		<div class="qm" id="<?php echo esc_attr( $this->collector->id() ) ?>">
			<table cellspacing="0" class="QMCV_Table">
				<thead>
					<tr>
						<th colspan="2"><?php echo esc_html( $this->collector->name() ) ?></th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<td colspan="2">
							<?php QMCV_IO::echo_debug( true ); ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php
	}
}

