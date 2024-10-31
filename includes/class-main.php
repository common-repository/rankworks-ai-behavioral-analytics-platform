<?php

if( ! defined('ABSPATH') )
	exit; // Exit if accessed directly

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://rankworks.com
 *
 * @package    rankworks_connection
 * @subpackage rankworks_connection/includes
 * @author     RankWorks <media@rankworks.com>
 */

class rankworks_connection
{
	
	protected $plugin_name;
	protected $version;
	
	private static $instance = null;
	
	public static function get_instance()
	{
		if( self::$instance == null )
		{
			self::$instance = new rankworks_connection();
		}
		
		return self::$instance;
	}
	
	private function __construct()
	{
		$this->version = rankworks_connection_version;
		$this->plugin_name = rankworks_connection_name;
		
		$this->load_dependencies();
		
	}
	
	/**
	 * Load the required dependencies for this plugin.
	 *
	 */
	private function load_dependencies()
	{
		
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin.php';
		
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-public.php';
		
		//include ajax file
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ajax.php';
		
	}
	
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}
	
	public function get_version()
	{
		return $this->version;
	}
	
	public static function get_settings_defaults()
	{
		$defaults = array(
			'script_url' => '',
		);
		return $defaults;
	}
	
	public static function get_settings()
	{
		$settings = get_option( rankworks_connection_settings, array() );
		$defaults = self::get_settings_defaults();
		$settings = wp_parse_args( $settings, $defaults );
		return $settings;
	}
	
}
