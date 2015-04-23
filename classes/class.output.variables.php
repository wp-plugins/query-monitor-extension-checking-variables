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
		global $QMCV;
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
					if ( count( $QMCV->debug_messages ) ) {
						foreach( $QMCV->debug_messages as $debug_message ) {
							$type_size = $QMCV->get_type_and_size( $debug_message );

							?>
							<tr>
								<td colspan="2">
									<dl class="SJD-depth1">
									<?php $QMCV->print_dt( $debug_message ); ?>
									<?php $QMCV->echo_depth( $debug_message ); ?>
									</dl>
								</td>
							</tr>
							<?php
						}
					} else {
						?>
						<tr>
							<td colspan="2"><em>none</em></td>
						</tr>
						<?php
					}
 					?>
				</tbody>

				<tfoot>
					<tr>
						<td colspan="2"><a href="#" id="btn-IndependVarCheck">Set Variable Checking Independently.</a></td>
					</tr>
				</tfoot>
			</table>
		</div>
		<?php
	}
}

