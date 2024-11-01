<?php
/**
 * Test ajax
 *
 * @package Vidfuse
 */

class Vidfuse_Ajax {
  private $output;
 
  public function __construct( $output ) {
    $this->output = $output;

    add_action( 'admin_enqueue_scripts', array($this, 'scripts'));    
    add_action( 'wp_ajax_disconnect', array($this, 'disconnect_callback' ));
    add_action( 'wp_ajax_nopriv_disconnect', array($this, 'disconnect_callback' ));
  }

  public function scripts(){
    $url = trailingslashit( plugin_dir_url( __FILE__ ) );
    wp_register_script( 'disconnect-script', $url . "assets/script.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'disconnect-script', 'disconnect_url', admin_url( 'admin-ajax.php' ) );
    wp_enqueue_script( 'disconnect-script' );
  }

  public function disconnect_callback() {
    $option_name = 'vidfuse_token'; 
    delete_option($option_name);
    delete_site_option($option_name);

    $option_name = 'vidfuse_connected'; 
    delete_option($option_name);
    delete_site_option($option_name);

    $option_name = 'vidfuse_code'; 
    delete_option($option_name);
    delete_site_option($option_name);
    ?>
    <p><?php echo $this->output; ?></p>
    <?php
    wp_die();
  }

}
?>