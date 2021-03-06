<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://shramee.me/
 * @since      1.0.0
 *
 * @package    Sm_Chat
 * @subpackage Sm_Chat/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sm_Chat
 * @subpackage Sm_Chat/public
 * @author     Shramee <shramee.srivastav@gmail.com>
 */
class Sm_Chat_Public {

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
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $loader    Loader HTML localized
	 */
	private $loader;

	/**
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $settings    Plugin settings.
	 */
	private $settings;

	/**
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $user   Current user
	 */
	private $user = array();

	/**
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $users  All site users
	 */
	private $users = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function __get( $name ) {
		if ( strpos( $name, 'user' ) && isset( $this->$name ) ) {
			return $this->$name;
		}
	}

	/**
	 * Checks if it is chat page, initiates chat UI if it is.
	 *
	 * @since    1.0.0
	 */
	public function init() {

		$this->settings = wp_parse_args( get_option( $this->plugin_name, array() ), array(
			'page' => '',
			'login_message' => 'Please login to start chatting.',
		) );

		if ( ! empty( $this->settings['page'] ) && is_page( $this->settings['page'] ) ) {

			add_filter( 'the_content', array( $this, 'render_chat' ) );

			add_filter( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

		}

	}

	/**
	 * Renders chat UI HTML
	 *
	 * @since    1.0.0
	 */
	public function render_chat( $content ) {

		ob_start();

		if ( is_user_logged_in() ) {
			include 'partials/chat.php';
		} else {
			echo $this->settings['login_message'];
		}

		$chat_ui = ob_get_clean();

		return $chat_ui;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue() {

		// Styles
		wp_enqueue_style( 'font-awesome', plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css', array(), $this->version );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sm-chat-public.css', array(), $this->version );

		// Scripts
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sm-chat-public.js', array( 'jquery' ), $this->version, false );

		// Current user
		$usr = wp_get_current_user();
		$this->user = array(
			'id' => $usr->ID,
			'name' => $usr->display_name,
			'avatar' => get_avatar_url( $usr->ID, 96 ),
		);

		// All blog users
		$users = get_users( 'blog_id=1' );
		foreach ( $users as $usr ) {
			$this->users[ $usr->ID ] = array(
				'id' => $usr->ID,
				'name' => $usr->display_name,
				'avatar' => get_avatar_url( $usr->ID, 96 ),
			);
		}

		// Loader ( Localized )
		$this->loader = '<div class="loader"></div><h2>' . __( 'Loading...', $this->plugin_name ) . '</h2>';

		// Localize
		wp_localize_script( $this->plugin_name, 'smChatData', array(
			'users' => $this->users,
			'user' => $this->user,
			'loader' => $this->loader,
			'url' => admin_url( 'admin-ajax.php' ),
		) );

	}

}
