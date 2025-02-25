<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.wilhemarnoldy.fr
 * @since             1.2
 * @package           Wa_Golfs
 *
 * @wordpress-plugin
 * Plugin Name:       WA GOLFS Global functions
 * Plugin URI:        https://www.wilhemarnoldy.fr
 * Description:       A plugin to add "parcours", "compétition", testimony of "le Golf de Salouël"
 * Version:           1.2
 * Author:            Wilhem Arnoldy
 * Author URI:        https://www.wilhemarnoldy.fr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wa-golfs
 * Domain Path:       /languages
 * https://wppb.me
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if( !defined("IS_ADMIN"))
	define("IS_ADMIN",  is_admin());

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WA_GOLFS_VERSION', '1.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wa-golfs-activator.php
 */
function activate_wa_golfs() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wa-golfs-activator.php';
	Wa_Golfs_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wa-golfs-deactivator.php
 */
function deactivate_wa_golfs() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wa-golfs-deactivator.php';
	Wa_Golfs_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wa_golfs' );
register_deactivation_hook( __FILE__, 'deactivate_wa_golfs' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wa-golfs.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wa_golfs() {

	$plugin = new Wa_Golfs();
	$plugin->run();

}
run_wa_golfs();

// function list_all_admin_notices() {
// 	add_action('admin_notices', function() {
// 		global $wp_filter;
// 		if ( isset( $wp_filter['admin_notices'] ) ) {
// 			// Remove the specific notice
// 			if ( isset( $wp_filter['admin_notices']->callbacks[10] ) ) {
// 				foreach ( $wp_filter['admin_notices']->callbacks[10] as $key => $value ) {
// 					if ( is_array( $value['function'] ) && is_object( $value['function'][0] ) && get_class( $value['function'][0] ) === 'MetaBox\Updater\Notification' && $value['function'][1] === 'notify' ) {
// 						unset( $wp_filter['admin_notices']->callbacks[10][$key] );
// 					}
// 				}
// 			}
// 			// echo '<pre>';
// 			// print_r( $wp_filter['admin_notices']->callbacks );
// 			// echo '</pre>';
// 		}
// 	});
// }
// add_action( 'plugin_loaded', 'list_all_admin_notices' , 1000);
