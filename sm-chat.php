<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://shramee.me/
 * @since             1.0.0
 * @package           Sm_Chat
 *
 * @wordpress-plugin
 * Plugin Name:       Shramee's chat
 * Plugin URI:        https://github.com/shramee/shramee-chat
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Shramee
 * Author URI:        http://shramee.me/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sm-chat
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sm-chat-activator.php
 */
function activate_sm_chat() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sm-chat-activator.php';
	Sm_Chat_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sm-chat-deactivator.php
 */
function deactivate_sm_chat() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sm-chat-deactivator.php';
	Sm_Chat_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sm_chat' );
register_deactivation_hook( __FILE__, 'deactivate_sm_chat' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sm-chat.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sm_chat() {

	$plugin = new Sm_Chat();
	$plugin->run();

}
run_sm_chat();