<?php

if( ! defined('ABSPATH') )
	exit; // Exit if accessed directly

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://rankworks.com
 *
 * @package    rankworks_connection
 * @subpackage rankworks_connection/public
 * @author     RankWorks <media@rankworks.com>
 */

class rankworks_connection_public
{
	
	private $plugin_name;
	private $version;
	
	private static $instance = null;
	
	public static function get_instance()
	{
		if( self::$instance == null )
		{
			self::$instance = new rankworks_connection_public();
		}
		
		return self::$instance;
	}
	
	private function __construct()
	{
		$this->plugin_name = rankworks_connection_name;
		$this->version = rankworks_connection_version;
		
	}
	
	public function define_hooks()
	{
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		
		
	}
	
	public function register_scripts()
	{
		$settings = rankworks_connection::get_settings();
		
		$script_url = $settings['script_url'];
		
		if( empty( $script_url ) )
			return;
		
		wp_enqueue_script( 'rankworks_api_script', esc_url( $script_url ), array(  ), $this->version, false );
		
	}
	
}

function rankworks_init_rankworks_connection_public()
{
	$plugin_public = rankworks_connection_public::get_instance();
	$plugin_public->define_hooks();
	
}
rankworks_init_rankworks_connection_public();
