<?php
/**
 * Created by PhpStorm.
 * User: shramee
 * Date: 23/06/17
 * Time: 6:44 AM
 */

class Sm_Chat_Messages {

	// Message structure: msg-uid1-uid2::uidsender::timestamp

	private function msg_group_prefix( $uid1, $uid2 ) {

		$users = array( $uid1, $uid2 );

		asort( $users, SORT_NUMERIC );

		return implode( '-', $users );

	}


	/**
	 * Gets message shared between 2 users
	 *
	 * @param int $uid1 First participant's user id
	 * @param int $uid2 Second participant's user id
	 *
	 * @return array Messages
	 */
	function get_messages( $uid1, $uid2 ) {

		global $wpdb;

		$grp = $this->msg_group_prefix( $uid1, $uid2 );

		$options = "msg-$grp::%";

		$msgs = $wpdb->get_results( "SELECT option_name AS info, option_value AS text FROM $wpdb->options WHERE option_name LIKE '$options' ORDER BY option_name DESC LIMIT 10" );

		return $msgs;

	}

	/**
	 * Adds message from one user to another
	 *
	 * @param int $sender Sender's user id
	 * @param int $recipient Recipient's user id
	 * @param string $msg Message
	 *
	 * @return bool Success
	 */
	function add_message( $sender, $recipient, $msg ) {

		$grp = $this->msg_group_prefix( $sender, $recipient );

		$chats = get_user_meta( $recipient, 'chats' );
		if ( ! in_array( $sender, $chats ) ) {
			add_user_meta( $sender, 'chats', $recipient );
			add_user_meta( $recipient, 'chats', $sender );
		}

		$time = time();

		$option = "msg-$grp::$time::$sender";

		// Add message, Don't autoload
		return "$option > $msg " . update_option( $option, $msg, 'no' );
	}

}