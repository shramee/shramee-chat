<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://shramee.me/
 * @since      1.0.0
 *
 * @package    Sm_Chat
 * @subpackage Sm_Chat/public/partials
 */

$chat_header = __( 'Loading', $this->plugin_name );
$chat_body = $this->loader;

?>
<div id="sm-chat" class="flex stretch">
	<aside>
		<header class="flex stretch">
			<a href="#my-chats" title="My chats" class="active"><i class="fa fa-comments-o" aria-hidden="true"></i></a>
			<a href="#new-chat" title="New chat"><i class="fa fa-address-book-o" aria-hidden="true"></i></a>
		</header>
		<section id="my-chats" class="toggled">
			<h3>Recent chats</h3>

			<?php
			$chats = get_user_meta( $this->user['id'], 'chats' );

			if ( ! empty( $chats ) ) {
				echo '<ul class="chats">';
				foreach ( $chats as $chat ) {
					if ( isset( $this->users[ $chat ] ) ) {
						$user = $this->users[ $chat ];
						echo "<li class='chat-init' data-user='$user[id]'><img class='user-img' src='$user[avatar]'>$user[name]</li>";
					}
				}
				echo '</ul>';
			} else {
				$chat_header = __( 'Select a chat', $this->plugin_name );
				$chat_body = __( 'Select the chat from the left to get started!', $this->plugin_name );
				_e( "You don't seem to started any chats. Click address book icon and select a user to start chat with.", $this->plugin_name );
			}
			?>
		</section>
		<section id="new-chat" class="toggled" style="display:none;">
			<h3>New chat with</h3>
			<ul class="users">
				<?php
				foreach ( $this->users as $usr ) {
					if ( $this->user['id'] != $usr['id'] ) {
						echo "<li class='chat-init new' data-user='$usr[id]'><img class='user-img' src='$usr[avatar]'>$usr[name]</li>";
					}
				}
				?>
			</ul>
		</section>
	</aside>
	<main class="flex">
		<header id="chat-header">
			<?php echo $chat_header; ?>
		</header>
		<section id="chat-body">
			<?php echo $chat_body; ?>
		</section>
		<footer id="chat-footer" class="flex stretch">
			<input type="text" id="chat-msg">
			<input type="button" id="chat-send" value="Send">
		</footer>
	</main>
</div>
