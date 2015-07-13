<?php
/**
 * Input and Output
 *
 * project	Query Monitor Extension - Checking Variables
 * version	3.0
 * Author: Sujin 수진 Choi
 * Author URI: http://www.sujinc.com/
 *
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class QMCV_IO {
	static $message = array();
	static $message_sandbox = array();
	static $message_stack = array();
	static $json_sandbox = array();
	static $message_key = 2;

	public static function trigger_hooks() {
		if ( Check_Variables_Options::is_user_allowed() && ( Check_Variables_Options::get_footer() || Check_Variables_Options::get_console() ) ) {
			add_action( 'wp_footer', array( QMCV_IO, 'echo_debug' ) );
			add_action( 'admin_footer', array( QMCV_IO, 'echo_debug' ) );
		}
	}

	static function echo_debug( $query_monitor = false ) {
		foreach( self::$message as $key => $message ) {
			switch( $message['type'] ) {
				case 'object' :
				case 'array' :
					self::$message_sandbox = array();
					self::$message_stack = explode( PHP_EOL, $message['message'] );
					$title = self::$message_stack[0];
					unset(self::$message_stack[0]);
					unset(self::$message_stack[1]);

					self::txt2structure( self::$message_stack, self::$message_sandbox );

					if ( Check_Variables_Options::get_footer() || $query_monitor ) {
						$hide = ( Check_Variables_Options::get_hide() && !$query_monitor ) ? ' hidden' : '';
						printf( '<div class="QMCV_IO %s">', $hide );

						printf( '<h3 data-id="%s">%s <span>%s</span></h3>', $key, $message['key'], $title );
						printf( '<div class="file">%s <span>( Line %s )</span></div>', $message['file'], $message['line'] );

						printf( '<dl data-id="%s">', $key );
						self::structure2html( self::$message_sandbox );
						echo '</dl>';

						echo '</div>';
					}

					if ( Check_Variables_Options::get_console() && !$query_monitor ) {
						self::structure2json( self::$message_sandbox );
						$json = json_encode( self::$json_sandbox );
						$json = str_replace( "'", "\'", $json );
						?>
						<script type="text/javascript">
							QMCV_Data = '<?php echo $json; ?>';
							QMCV_Data = JSON.parse( QMCV_Data );
							console.log( QMCV_Data );
						</script>
						<?php
					}
				break;

				case 'integer' :
				case 'string' :
				case 'double' :
				case 'float' :
					if ( Check_Variables_Options::get_footer() || $query_monitor ) {
						echo '<div class="QMCV_IO">';

						printf( '<h3>%s <span>%s</span></h3>', $message['key'], $message['type'] );
						printf( '<div class="file">%s <span>( Line %s )</span></div>', $message['file'], $message['line'] );

						printf( '<div class="QMCV_value">%s</div>', $message['message'] );

						echo '</div>';
					}

					if ( Check_Variables_Options::get_console() && !$query_monitor ) {
						?>
						<script type="text/javascript">
							QMCV_Data = '<?php printf( '{"%s":"%s"}', $message['key'], $message['message'] ); ?>';
							QMCV_Data = JSON.parse( QMCV_Data );
							console.log( QMCV_Data );
						</script>
						<?php
					}
				break;

				case 'boolean' :
					$message['message'] = ( $message['message'] ) ? 'true' : 'false';

					if ( Check_Variables_Options::get_footer() || $query_monitor ) {
						echo '<div class="QMCV_IO">';

						printf( '<h3>%s <span>%s</span></h3>', $message['key'], $message['type'] );
						printf( '<div class="file">%s <span>( Line %s )</span></div>', $message['file'], $message['line'] );

						printf( '<div class="QMCV_value">%s</div>', $message['message'] );

						echo '</div>';
					}

					if ( Check_Variables_Options::get_console() && !$query_monitor ) {
						?>
						<script type="text/javascript">
							QMCV_Data = '<?php printf( '{"%s":"%s"}', $message['key'], $message['message'] ); ?>';
							QMCV_Data = JSON.parse( QMCV_Data );
							console.log( QMCV_Data );
						</script>
						<?php
					}
				break;

				case 'NULL' :
					if ( Check_Variables_Options::get_footer() || $query_monitor ) {
						echo '<div class="QMCV_IO">';

						printf( '<h3>%s <span>%s</span></h3>', $message['key'], $message['type'] );
						printf( '<div class="file">%s <span>( Line %s )</span></div>', $message['file'], $message['line'] );

						printf( '<div class="QMCV_value">NULL</div>' );

						echo '</div>';
					}

					if ( Check_Variables_Options::get_console() && !$query_monitor ) {
						?>
						<script type="text/javascript">
							QMCV_Data = '<?php printf( '{"%s":"NULL"}', $message['key'] ); ?>';
							QMCV_Data = JSON.parse( QMCV_Data );
							console.log( QMCV_Data );
						</script>
						<?php
					}
				break;
			}
		}
	}

	static function txt2structure( &$message_stack, &$message_sandbox ) {
		foreach ( $message_stack as $msg_key => $message ) {
			$trimed = trim( $message );
			if ( !$trimed ) continue;

			if ( $trimed == ')' ) {
				unset($message_stack[$msg_key]);
				return true;
			}

			$trimed_next = trim( $message_stack[$msg_key+1] );
			$explode = explode(  '=>', $trimed, 2 );

			$key = substr( trim( $explode[0] ), 1, -1 ) ;
			$val = trim( $explode[1] );

			unset($message_stack[$msg_key]);

			if ( $trimed_next == '(' ) {
				unset($message_stack[$msg_key + 1]);

				$message_sandbox[$key] = array(
					'type' => $val,
					'value' => array()
				);
				self::txt2structure( $message_stack, $message_sandbox[$key]['value'] );
			} else {
				$message_sandbox[$key] = $val;
			}
		}
	}

	static function structure2json( $message_sandbox ) {
		foreach( $message_sandbox as $key => $message ) {
			$visibility = 'public';

			if ( $protected_position = strstr( $key, ':protected' ) !== false ) {
				$key = substr( $key, 0, $protected_position );
				$visibility = 'protected';
			}

			if ( $private_position = strstr( $key, ':private' ) !== false ) {
				$key = substr( $key, 0, $private_position );
				$visibility = 'private';
			}

			if ( is_array( $message ) ) {
				self::$json_sandbox[$key] = array(
					'visibility' => $visibility,
					'type' => strtolower( $message['type'] )
				);
				if ( !$message['value'] ) {
					self::$json_sandbox[$key]['value'] = array();
				} else {
					self::$json_sandbox[$key]['value'] = self::structure2json( $message['value'] );
				}
			} else {
				self::$json_sandbox[$key] = str_replace( array( "\"" ), array( "\\\"" ), $message );

			}
		}
	}

	static function structure2html( $message_sandbox, $parentKey = array() ) {
		if ( $parentKey ) echo '<dl>';

		foreach( $message_sandbox as $key => $message ) {
			$protected = $private = '';

			if ( $protected_position = strstr( $key, ':protected' ) !== false ) {
				$key = substr( $key, 0, $protected_position );
				$protected = '<span class="class_keyword">:protected</span>';
			}

			if ( $private_position = strstr( $key, ':private' ) !== false ) {
				$key = substr( $key, 0, $private_position );
				$private = '<span class="class_keyword">:private</span>';
			}

			if ( is_array( $message ) ) {
				$parentKey[] = str_replace( ' ', '', $key );
				$itemKey = implode( '-', $parentKey );

				printf( '<dt class="foldable" data-id="QMCV_IO-%s"><span class="dashicons dashicons-arrow-down"></span> [%s%s%s] <span class="type">( %s )</span></dt>', $itemKey, $key, $protected, $private, strtolower( $message['type'] ) );
				printf( '<dd data-id="QMCV_IO-%s">', $itemKey );
				self::structure2html( $message['value'], $parentKey );
				echo '</dd>';
			} else {
				printf( '<dt class="single"><span class="dashicons"></span> [%s%s%s] <span class="value">%s</span></dt>', $key, $protected, $private, $message );
			}
		}

		if ( $parentKey ) echo '</dl>';
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
		$src = debug_backtrace();

		foreach( func_get_args() as $message ) {
			$idx = strpos($src[0]['file'], 'id.php') ? 1 : 0;
			$src = (object)$src[$idx];
			$file = file($src->file);

			$i = 1;
			do {
				$line = $file[$src->line - $i++];
			} while (strpos($line, 'console') === false);

			preg_match('/console\((.+?)\)?(?:$|;|\?>)/', $line, $m);
			$key = $m[1];
			$key = trim(explode(',', $key)[0]);

			QMCV_IO::$message[] = array(
				'message' => print_r( $message, true ),
				'file' => $src->file,
				'line' => $src->line,
				'key' => $key,
				'type' => gettype( $message )
			);
		}
	}
}


