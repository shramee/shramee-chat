<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://shramee.me/
 * @since      1.0.0
 *
 * @package    Sm_Chat
 * @subpackage Sm_Chat/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sm_Chat
 * @subpackage Sm_Chat/admin
 * @author     Shramee <shramee.srivastav@gmail.com>
 */
class Sm_Chat_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->msg = Sm_Chat::$msg;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sm-chat-admin.css', array(), $this->version );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sm-chat-admin.js', array( 'jquery' ), $this->version );

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function admin_init() {
		// Register our setting so that $_POST handling is done for us and
		// our callback function just has to echo the <input>
		register_setting( $this->plugin_name, $this->plugin_name );

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function admin_menu() {

		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Shramee Chat', $this->plugin_name ),
			__( 'Shramee Chat', $this->plugin_name ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {

		include_once 'partials/settings.php';

	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		$links['settings'] = '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>';
		return $links;

	}

	/**
	 * Handles get messages AJAX calls
	 *
	 * @since 1.0.0
	 */
	public function handle_get_messages() {
		$uid1 = filter_input( INPUT_POST, 'uid1' );
		$uid2 = filter_input( INPUT_POST, 'uid2' );

		if ( is_numeric( $uid1 ) && is_numeric( $uid2 ) ) {
			$response = array(
				'success'  => true,
				'messages' => $this->msg->get_messages( $uid1, $uid2 ),
			);
		} else {
			$response = array(
				'success'  => false,
				'msg' => 'User IDs of both users should be numeric',
			);
		}

		die( json_encode( $response ) );
	}

	/**
	 * Handles add message AJAX calls
	 *
	 * @since 1.0.0
	 */
	public function handle_add_message() {
		$sender = filter_input( INPUT_POST, 'sender' );
		$recipient = filter_input( INPUT_POST, 'recipient' );
		$msg = filter_input( INPUT_POST, 'msg' );

		if ( ! is_numeric( $sender ) || ! is_numeric( $recipient ) ) {
			$response = array(
				'success'  => false,
				'msg' => 'User IDs of both sender and recipient should be numeric',
			);
		} else if ( empty( $msg ) ) {
			$response = array(
				'success'  => false,
				'msg' => 'Message not found.',
			);
		} else {
			$response = array(
				'success'  => $this->msg->add_message( $sender, $recipient, $msg ),
			);
		}

		die( json_encode( $response ) );
	}
}
