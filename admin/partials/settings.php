<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://shramee.me/
 * @since      1.0.0
 *
 * @package    Sm_Chat
 * @subpackage Sm_Chat/admin/partials
 */

$settings = wp_parse_args( get_option( $this->plugin_name, array() ), array(
	'page' => '',
	'login_message' => 'Please login to start chatting.',
) );

?>

<h2><?php _e( "Shramee's chat settings", $this->plugin_name ) ?></h2>
<form method="POST" action="options.php">
	<table class="form-table">
		<tr>
			<th scope="row"><?php _e( "Show on page", $this->plugin_name ) ?></th>
			<td>
				<select name='<?php echo $this->plugin_name ?>[page]' id='<?php echo $this->plugin_name ?>'>
					<option value=""><?php _e( "Please choose...", $this->plugin_name ) ?></option>
					<?php

					$pages = get_pages();

					foreach( $pages as $p ) {
						echo "<option value='$p->ID' " . selected( $p->ID, $settings['page'] ) . ">$p->post_title</option>";
					}

					?>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e( "User not logged in message", $this->plugin_name ) ?></th>
			<td>
				<textarea cols="50" rows="3" name="<?php echo $this->plugin_name ?>[login_message]"><?php echo $settings['login_message'] ?></textarea>
			</td>
		</tr>
	</table>

	<?php

	settings_fields( $this->plugin_name );

	submit_button();
	?>
</form>