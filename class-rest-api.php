<?php
/**
 * Rest API.
 *
 * @package Vidfuse
 */
class Vidfuse_Rest_API {
 
  private $add_post;
  private $uri;
  private $token;

  public function __construct( $add_post, $uri, $token ) {
    $this->add_post = $add_post;
    $this->uri = $uri;
    $this->token = $token;
    $this->init();
  }

  public function init() {
    add_action( 'rest_api_init', function () {
      register_rest_route( 'vidfuse/v' . VIDFUSE_REST_API_VERSION, '/post', array(
        'methods' => 'POST',
        'callback' => array( $this, 'create_post' ),
      ) );
    } );
  }

  public function create_post( $data ) {
    $res = [
        'status' => 500,
        'message' => "Internal error"
    ];
    if( $data['token'] == $this->token ){
      $create_post = new Vidfuse_Add_Post();
      $post_id = $create_post->create_post( $data );
      if($post_id instanceof WP_Error){
        $res['message'] = $post_id->get_error_message();
      }elseif( $post_id > 0 ){
        $res['id'] = $post_id;
        $res['url'] = get_the_permalink($post_id);
        $res['status'] = 200;
        $res['message'] = "Post has been created successfully.";
      }
    }else{
      $res['message'] = "Token did not match.";
    }
    
    return $res;
  }
}