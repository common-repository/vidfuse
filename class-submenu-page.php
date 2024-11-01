<?php
/**
 * Creates the submenu page for the plugin.
 *
 * @package Vidfuse
 */
 
class Vidfuse_Submenu_Page {
 
  private $token;
  private $code;
  private $redirect;
  private $post;
  private $deserializer;
  private $output;
  private $is_connected;

  public function __construct($deserializer) {
    $this->deserializer = $deserializer;
    $this->token = $this->get_token();
    $this->is_connected = esc_attr($this->deserializer->get_value('vidfuse_connected'));  
    $this->code = esc_attr($this->deserializer->get_value('vidfuse_code'));  
    $this->init();
  }

  public function render() {
    include_once('views/settings.php');
  }

  public function get_token(){
    $token = esc_attr($this->deserializer->get_value('vidfuse_token'));    
    if(empty($token)){
      $token = md5('808dc4a05266412085b306096e4e4837'.uniqid()); 
      update_option('vidfuse_token', $token);     
    }
    return $token;
  }

  public function get_connect_url(){
    $this->redirect = sprintf('%s&return', $this->currentUrl());
    return sprintf(VIDFUSE_REST_API_URL . '/connect?client_id=%s&redirect=%s&token=%s&post=%s', VIDFUSE_CLIENT_ID, urlencode($this->redirect), $this->get_token(), urlencode($this->post));
  }

  private function currentUrl(){
    return get_admin_url() . "options-general.php?page=" . VIDFUSE_SLUG;
  }

  public function init(){    
    $this->post = sprintf('%s/wp-json/vidfuse/v%s/post', get_site_url(), VIDFUSE_REST_API_VERSION);

    if (isset($_GET['return']) || ($this->is_connected == '1')) {
      $success = sanitize_text_field($_GET['success']) ?: '';
      $message = sanitize_text_field($_GET['message']) ?: 'Connected.';
      $code = sanitize_text_field($_GET['code']) ?: '';

      if($success == '1'){
        update_option('vidfuse_connected', '1');
        update_option('vidfuse_code', $code);
      } 
      $disconnectUrl = sprintf(VIDFUSE_REST_API_URL . '/disconnect?code=%s&token=%s', $this->code, $this->token);
      
      $this->output = $message . "<hr /><a class=\"button button-primary\" id=\"disconnect\" target='_blank' href='".$disconnectUrl."'>DISCONNECT</a>";
      $ajax = new Vidfuse_Ajax("Disconnected.<hr /><a class=\"button button-primary\" href='". $this->get_connect_url() ."'>CONNECT</a>");

    } else {
      $this->output = "<hr /><a class=\"button button-primary\" href='". $this->get_connect_url() ."'>CONNECT</a>";
    }

    $add_post = new Vidfuse_Add_Post();
    $rest_api = new Vidfuse_Rest_API($add_post, $this->post, $this->token);
  }
}