<?php
/*
 * Plugin Name:        RankWorks In-Site
 * Description:        Connect with RankWorks platform.
 * Author:             RankWorks
 * Plugin URI:         https://rankworks.com/in-site-wordpress-plugin/
 * Author URI:         https://rankworks.com
 * Version:            1.0.0
 * License:			   GPLv3
 * License URI:        https://www.gnu.org/licenses/gpl-3.0.html
*/

if( ! defined('ABSPATH') )
	exit; // Exit if accessed directly

define( 'rankworks_connection_dir_url', plugin_dir_url( __FILE__ ) );
define( 'rankworks_connection_dir_path', plugin_dir_path( __FILE__ ) );
define( 'rankworks_connection_version', '1.0.0' );
define( 'rankworks_connection_label', 'RankWorks' );
define( 'rankworks_connection_name', 'rankworks-connection' );
define( 'rankworks_connection_base_name', plugin_basename( __FILE__ ) );
define( 'rankworks_connection_settings', 'rankworks_connection_settings' );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-activator.php
 */
function rankworks_activate_rankworks_connection()
{
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-activator.php';
	rankworks_connection_activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-deactivator.php
 */
function rankworks_deactivate_rankworks_connection()
{
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-deactivator.php';
	rankworks_connection_deactivator::deactivate();
}

register_activation_hook( __FILE__, 'rankworks_activate_rankworks_connection' );
register_deactivation_hook( __FILE__, 'rankworks_deactivate_rankworks_connection' );

/**
 * The core plugin class that is used to include,
 * admin-specific file, and public-facing site file.
 */
require_once plugin_dir_path(__FILE__) . 'includes/class-main.php';

function rankworks_run_rankworks_connection()
{
	$plugin = rankworks_connection::get_instance();
	
}
rankworks_run_rankworks_connection();
