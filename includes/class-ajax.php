<?php

if( ! defined('ABSPATH') )
	exit; // Exit if accessed directly

/**
 * The file that defines the ajax plugin class
 *
 * A class definition that includes ajax functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://jibranshabir.com
 *
 * @package    rankworks_connection
 * @subpackage rankworks_connection/includes
 * @author     Jibran Shabir <jibran2792@gmail.com>
 */

class rankworks_connection_ajax
{
	private static $instance = null;
	
	private function __construct()
	{
		add_action( 'wp_ajax_rankworks_create_wc_rest_api_keys', array( $this, 'rankworks_create_wc_rest_api_keys' ) );
		add_action( 'wp_ajax_nopriv_rankworks_create_wc_rest_api_keys', array( $this, 'rankworks_create_wc_rest_api_keys' ) );
		
		add_action( 'wp_ajax_save_rankworks_script', array( $this, 'save_rankworks_script' ) );
		add_action( 'wp_ajax_nopriv_save_rankworks_script', array( $this, 'save_rankworks_script' ) );
		
	}
	
	public static function get_instance()
	{
		if( self::$instance == null )
		{
			self::$instance = new rankworks_connection_ajax();
		}
		
		return self::$instance;
	}
	
	public function rankworks_create_wc_rest_api_keys()
	{
		if( ! isset( $_POST['nonce'] )
			|| ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['nonce'] ) ), 'rankworks_connection_admin_ajax_nonce' )
		)
		{
			wp_send_json( [
				'success' => false,
				'message' => esc_html__( 'Security check error, please refresh and try again.', 'rankworks-connection' ),
			] );
		}
		
		if( ! is_user_logged_in() )
		{
			wp_send_json( [
				'success' => false,
				'message' => esc_html__( 'User is not logged in.', 'rankworks-connection' ),
			] );
		}
		
		if( ! class_exists( 'woocommerce' ) )
		{
			wp_send_json( [
				'success' => false,
				'message' => esc_html__( 'Woocommerce is not active.', 'rankworks-connection' ),
			] );
		}
		
		$id = sanitize_text_field( $_POST['id'] );
		
		$user_id = get_current_user_id();
		$app_name = 'Rankworks';
		$permissions = 'read';
		$api_key = $this->wc_create_rest_api_keys( $app_name, $user_id, $permissions );
		$api_key['key_type'] = 'Woocommerce rest api key';
		$api_key['base_url'] = site_url();
		$api_key['id'] = $id;
		
		$endpoint = "https://api.plugins.rankworks.com/plugin_tokens?id=$id";
		
		$body = [
			'data'  => $api_key,
		];
		
		wp_send_json( [
			'success' => true,
			'endpoint' => $endpoint,
			'body' => $body,
			'message' => esc_html__( 'Done.', 'rankworks-connection' ),
		] );
		
	}
	
	public function save_rankworks_script()
	{
		if( ! isset( $_POST['nonce'] )
			|| ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['nonce'] ) ), 'rankworks_connection_admin_ajax_nonce' )
		)
		{
			wp_send_json( [
				'success' => false,
				'message' => esc_html__( 'Security check error, please refresh and try again.', 'rankworks-connection' ),
			] );
		}
		
		if( ! isset( $_POST['script_url'] ) )
		{
			wp_send_json( [
				'success' => false,
				'message' => esc_html__( 'Values not sent.', 'rankworks-form-api' ),
			] );
			
		}
		
		$settings['script_url'] = sanitize_url( $_POST['script_url'] );
		$settings['id'] = sanitize_text_field( $_POST['id'] );
		update_option( rankworks_connection_settings, $settings );
		
		wp_send_json( [
			'success' => true,
			'message' => esc_html__( 'Done.', 'rankworks-connection' ),
		] );
		
	}
	
	private function wc_create_rest_api_keys( $app_name, $app_user_id, $scope )
	{
		global $wpdb;
		
		$description = sprintf(
			'%s - API (%s)',
			wc_trim_string( wc_clean( $app_name ), 170 ),
			gmdate( 'Y-m-d H:i:s' )
		);
		
		$permissions     = in_array( $scope, array( 'read', 'write', 'read_write' ), true ) ? sanitize_text_field( $scope ) : 'read';
		$consumer_key    = 'ck_' . wc_rand_hash();
		$consumer_secret = 'cs_' . wc_rand_hash();
		
		// @codingStandardsIgnoreStart
		$wpdb->insert( //db call ok; no-cache ok
			$wpdb->prefix . 'woocommerce_api_keys',
			array(
				'user_id'         => $app_user_id,
				'description'     => $description,
				'permissions'     => $permissions,
				'consumer_key'    => wc_api_hash( $consumer_key ),
				'consumer_secret' => $consumer_secret,
				'truncated_key'   => substr( $consumer_key, -7 ),
			),
			array(
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
			)
		);
		// @codingStandardsIgnoreEnd
		
		return array(
			'consumer_key'    => $consumer_key,
			'consumer_secret' => $consumer_secret,
		);
	}
	
}

function rankworks_init_rankworks_connection_ajax()
{
	$plugin_ajax = rankworks_connection_ajax::get_instance();
	
}
rankworks_init_rankworks_connection_ajax();
