<?php
/**
 * Plugin Name: Iterel
 * Description: A product filtering plugin.
 * Version: 0.1.0
 * Plugin URL: https://github.com/bigyanse/iterel
 * Author: bigyanse
 * Author URI: https://bigyandahal.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Update URL: https://github.com/bigyanse/iterel
 * Requires at least: 6.7
 * Tested up to: 6.7
 * Requires PHP: 8.2.18
 * Text Domain: iterel
 * Domain Path: /languages
 * Requires Plugins: woocommerce
 */

if (!defined('ABSPATH')) {
    exit;
}

// Scenario                                             Deactivation Hook    Uninstall Hook
// Flush Cache/Temp                                       Yes                   No
// Flush Permalinks                                       Yes                   No
// Remove Options from {$wpdb->prefix}_options            No                    Yes
// Remove Tables from wpdb                                No                    Yes
register_activation_hook(__FILE__, 'iterel_activate');
register_deactivation_hook(__FILE__, 'iterel_deactivate');
register_uninstall_hook(__FILE__, 'iterel_uninstall');

/**
 * Hook to activate the plugin and regsiter important functionality that the plugin uses
 */
function iterel_activate()
{
    // flush_rewrite_rules(); // used mainly after adding custom post types
}

/**
 * Hook to turn off the plugin and its functionality
 */
function iterel_deactivate()
{
    // flush_rewrite_rules(); // used mainly after removing/unregistering custom post types
}

/**
 * Hook to remove options and database fields
 */
function iterel_uninstall()
{
    $option_name = 'iterel_options';
    delete_option($option_name);

    // for site options in Multisite
    // delete_site_option($option_name);

    // drop a custom database table
    // global $wpdb;
    // $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}mytable");
}

/**
 * Displays admin notices
 */
function iterel_admin_notices()
{
    // echo '<div class="notice notice-info">Iterel: Hello from the plugin</div>';
}
add_action('admin_notices', 'iterel_admin_notices');

/**
 * custom options and settings
 */
function iterel_settings_init()
{
    // Registers setting
    register_setting('iterel', 'iterel_options');
}
add_action('admin_init', 'iterel_settings_init');

// Setup general page and its sections.
require __DIR__ . '/menus/general/general.php';

// Extended functionalities
require __DIR__ . '/functions.php';
