<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that
 * also follow WordPress Coding Standards and PHP best practices.
 *
 * @package   Author_Ownership
 * @author    Ryan Gonzales <ryngonz@gmail.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Ryan Gonzales
 *
 * @wordpress-plugin
 * Plugin Name:       Author Ownership
 * Plugin URI:        TODO
 * Description:       Adds custom field Ownership to Page/Post
 * Version:           1.0.0
 * Author:            Ryan Gonzales
 * Author URI:        TODO
 * Text Domain:       plugin-name-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/<owner>/<repo>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*
 * TODO:
 *
 * - replace `class-plugin-name.php` with the name of the plugin's class file
 * - replace `class-plugin-admin.php` with the name of the plugin's admin file
 *
 */
require_once( plugin_dir_path( __FILE__ ) . 'class-author-ownership.php' );
require_once( plugin_dir_path( __FILE__ ) . 'class-author-ownership-admin.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 * TODO:
 *
 * - replace Plugin_Name with the name of the class defined in
 *   `class-plugin-name.php`
 */
register_activation_hook( __FILE__, array( 'Author_Ownership', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Author_Ownership', 'deactivate' ) );

/*
 * TODO:
 *
 * - replace Plugin_Name with the name of the class defined in
 *   `class-plugin-name.php`
 * - replace Plugin_Name_Admin with the name of the class defined in
 *   `class-plugin-name-admin.php`
 */
add_action( 'plugins_loaded', array( 'Author_Ownership', 'get_instance' ) );
add_action( 'plugins_loaded', array( 'Author_Ownership_Admin', 'get_instance' ) );