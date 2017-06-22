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
	'page' => ''
) );
?>

<h2>Shramee's chat settings</h2>
<form method="POST" action="options.php">
	<table class="form-table">
		<tr>
			<th scope="row">Show on page</th>
			<td>
				<select name='<?php echo $this->plugin_name ?>[page]' id='<?php echo $this->plugin_name ?>'>
					<option value="">Please choose...</option>
					<?php

					$pages = get_pages();

					foreach( $pages as $p ) {
						echo "<option value='$p->ID' " . selected( $p->ID, $settings['page'] ) . ">$p->post_title</option>";
					}

					?>
				</select>
			</td>
		</tr>
	</table>

	<?php

	settings_fields( $this->plugin_name );

	submit_button();
	?>
</form>