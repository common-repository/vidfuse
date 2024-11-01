<?php
/**
 * @package Vidfuse
 * @version 1.0.0
 */
/*
Plugin Name: Vidfuse
Description: Plugin for video auto-posting from Vidfuse application
Version: 1.0.0
*/
if (!defined('WPINC')) {
  die;
}

define( 'VIDFUSE_CLIENT_ID', 'kjIUH$bmNBiugIKBp' );
define( 'VIDFUSE_REST_API_VERSION', '1' );
define( 'VIDFUSE_REST_API_URL', 'https://app.vidfuse.com' );
define( 'VIDFUSE_SLUG', 'vidfuse-admin-page' );

foreach (glob(plugin_dir_path( __FILE__ ) . '/*.php') as $file) {
  include_once $file;
}

add_action('plugins_loaded', 'vidfuse_init');
register_uninstall_hook(__FILE__, 'vidfuse_uninstall');

function vidfuse_init(){ 

  $deserializer = new Vidfuse_Deserializer();

  $plugin = new Vidfuse_Submenu(new Vidfuse_Submenu_Page($deserializer));
  $plugin->init();

}

function vidfuse_uninstall(){
  $option_name = 'vidfuse_token'; 
  delete_option($option_name);
  delete_site_option($option_name);

  $option_name = 'vidfuse_connected'; 
  delete_option($option_name);
  delete_site_option($option_name);

  $option_name = 'vidfuse_code'; 
  delete_option($option_name);
  delete_site_option($option_name);
}
 
?>