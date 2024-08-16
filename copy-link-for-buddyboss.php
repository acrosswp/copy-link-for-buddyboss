<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/WPBoilerplate/copy-link-for-buddyboss
 * @since             1.0.0
 * @package           Copy_Link_For_BuddyBoss
 *
 * @wordpress-plugin
 * Plugin Name:       Copy Link For BuddyBoss
 * Plugin URI:        https://acrosswp.com/downloads/copy-link-for-buddyboss/
 * Description:       Copy Link For BuddyBoss by WPBoilerplate
 * Version:           1.0.1
 * Author:            AcrossWP
 * Author URI:        https://acrosswp.com/downloads/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       copy-link-for-buddyboss
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'COPY_LINK_FOR_BUDDYBOSS_FILES', __FILE__ );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-copy-link-for-buddyboss.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function copy_link_for_buddyboss_run() {

	$plugin = Copy_Link_For_BuddyBoss::instance();

	/**
	 * Run this plugin on the plugins_loaded functions
	 */
	add_action( 'plugins_loaded', array( $plugin, 'run' ), 0 );

}
copy_link_for_buddyboss_run();
