<?php

if( ! defined('ABSPATH') )
	exit; // Exit if accessed directly

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://jibranshabir.com
 *
 * @package    rankworks_connection
 * @subpackage rankworks_connection/admin
 * @author     Jibran Shabir <jibran2792@gmail.com>
 */

class rankworks_connection_admin
{
	
	private $plugin_name;
	private $version;
	
	private static $instance = null;
	
	private $menu_page;
	private $sub_menu_page;
	
	private $menu_page_slug;
	private $sub_menu_page_slug;
	
	public static function get_instance()
	{
		if( self::$instance == null )
		{
			self::$instance = new rankworks_connection_admin();
		}
		
		return self::$instance;
	}
	
	private function __construct()
	{
		$this->plugin_name = rankworks_connection_name;
		$this->version = rankworks_connection_version;
		$this->menu_page_slug = 'rankworks_connection';
		$this->sub_menu_page_slug = 'rankworks_connection_submenu';
		
	}
	
	public function define_hooks()
	{
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		
		add_action( 'admin_menu', array( $this, 'plugin_admin_menu' ) );
		
		//Plugin Settings Page
		add_filter( 'plugin_action_links', array( $this, 'link_menu_page_on_plugins_page' ), 10, 4 );
		
	}
	
	public function enqueue_styles( $hook )
	{
		if( $hook == $this->menu_page || $hook == $this->sub_menu_page )
		{
			wp_enqueue_style( 'select2', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/select2.min.css', array(), '4.1.0', 'all' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'rankworks-connection-admin.css', array(), $this->version, 'all' );
			
		}
		
	}
	
	public function enqueue_scripts( $hook )
	{
		if( $hook == $this->menu_page || $hook == $this->sub_menu_page )
		{
			wp_enqueue_script( 'select2', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/select2.min.js', array( 'jquery' ), '4.1.0', false );
			wp_enqueue_script( 'jquery-blockui', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/jquery.blockUI.2.70.min.js', array( 'jquery' ), '2.70', false );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'rankworks-connection-admin.js', array( 'jquery' ), $this->version, false );
			wp_localize_script(
				$this->plugin_name,
				'rankworks_connection',
				array(
					'plugin_url' => rankworks_connection_dir_url,
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'admin_nonce' => wp_create_nonce( 'rankworks_connection_admin_ajax_nonce' ),
				)
			);
			
		}
		
	}
	
	public function plugin_admin_menu()
	{
		$this->menu_page = add_menu_page(
			rankworks_connection_label, // Title of the page
			rankworks_connection_label, // Text to show on the menu link
			'manage_options', // Capability requirement to see the link
			$this->menu_page_slug, // The 'slug' - to display in browser link
			array( $this, 'admin_menu_page_display' ), // function to call for the file to display
			'',
			20
		);
		
	}
	public function admin_menu_page_display()
	{
		include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views/admin-menu-display.php';
	}
	
	public function link_menu_page_on_plugins_page( $actions, $plugin_file, $plugin_data, $context )
	{
		if( $plugin_file == rankworks_connection_base_name )
		{
			// Build and escape the URL.
			$url = esc_url_raw( add_query_arg(
				array(
					'page' => $this->menu_page_slug,
				),
				admin_url( 'admin.php' ),
			) );
			// Create the link.
			$menu_page_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';
			// Adds the link to the start of the array.
			$actions = array_merge( array(
				$menu_page_link
			), $actions );
			
		}
		
		return $actions;
	}
	
	public function get_business_categories()
	{
		$cats = get_transient( 'rankworks_business_categories' );
		if( ! is_array( $cats ) || empty( $cats ) )
		{
			$json_file = rankworks_connection_dir_url . 'admin/business_categories.json';
			$response = wp_remote_get(
				$json_file,
				[
					'sslverify' => false,
				]
			);
			$json = wp_remote_retrieve_body( $response );
			
			// Decode the JSON file 
			$cats = json_decode( $json, true );
			if( ! is_array( $cats ) || empty( $cats ) )
				$cats = [];
			set_transient( 'rankworks_business_categories', $cats, 30 * DAY_IN_SECONDS );
			
		}
		
		return $cats;
	}
	
}

function rankworks_init_rankworks_connection_admin()
{
	$plugin_admin = rankworks_connection_admin::get_instance();
	$plugin_admin->define_hooks();
	
}
rankworks_init_rankworks_connection_admin();
