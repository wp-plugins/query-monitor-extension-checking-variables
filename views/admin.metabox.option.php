<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><label for="query_monitor">Query Monitor </label></th>
			<td>
				<label for="query_monitor">
					<input type="checkbox" id="query_monitor" name="query_monitor" <?php if ( Check_Variables_Options::get_query_monitor() === true ) echo 'checked="checked"'; ?> /> Enable Query Monitor
				</label>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="footer">Footer </label></th>
			<td>
				<label for="footer">
					<input type="checkbox" id="footer" name="footer" <?php if ( Check_Variables_Options::get_footer() === true ) echo 'checked="checked"'; ?> /> Enable Footer
				</label>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="hide">Hide in Footer </label></th>
			<td>
				<label for="hide">
					<input type="checkbox" id="hide" name="hide" <?php if ( Check_Variables_Options::get_hide() === true ) echo 'checked="checked"'; ?> /> Hide in Footer
				</label>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="console">Javascript Console </label></th>
			<td>
				<label for="console">
					<input type="checkbox" id="console" name="console" <?php if ( Check_Variables_Options::get_console() === true ) echo 'checked="checked"'; ?> /> Enable Javascript Console
				</label>
			</td>
		</tr>

		<tr>
			<th scope="row">Capability</th>
			<td>
				<?php foreach( get_editable_roles() as $key => $capability ) : ?>

				<label for="capability-<?php echo $key ?>">
					<input type="checkbox" id="capability-<?php echo $key ?>" name="capability[<?php echo $key ?>]" <?php if ( is_array( Check_Variables_Options::get_capability() ) && in_array( $key, Check_Variables_Options::get_capability() ) ) echo 'checked="checked"'; ?> />
					 <?php echo $capability['name'] ?>
				</label>

				<div class="clear">&nbsp;</div>

				<?php endforeach; ?>

				<label for="capability-<?php echo $key ?>">
					<input type="checkbox" id="capability-guest" name="capability[guest]" <?php if ( is_array( Check_Variables_Options::get_capability() ) && in_array( 'guest', Check_Variables_Options::get_capability() ) ) echo 'checked="checked"'; ?> />
					 Guest
				</label>

				<div class="clear">&nbsp;</div>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="users">Users </label></th>
			<td>
				<input type="text" name="users" id="users" class="regular-text" value="<?php echo Check_Variables_Options::get_users() ?>" />
				<p class="description">Input User ID (number) separated by comma(,).</p>
			</td>
		</tr>
	</tbody>
</table>