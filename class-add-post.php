<?php
/**
 * Creates a post.
 *
 * @package Vidfuse
 */
 
class Vidfuse_Add_Post {
    public function create_post( $data ){
        $post_id = wp_insert_post(
            array(
                'ping_status'		=>	'closed',
                'post_content'		=>	'',
                'post_title'		=>	sanitize_text_field($data['title']),
                'post_status'		=>	'publish',
                'post_type'		    =>	'post'
            ), true
        );
        if($post_id instanceof WP_Error){
            return $post_id;
        }
        
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
         
        $media_id = media_handle_upload( 'file', $post_id );
        $media_url = wp_get_attachment_url($media_id);

        $changed_post = array(
            'ID'           => $post_id,
            'post_content' => sanitize_text_field( $data['description'] ) . '[embed]' . $media_url . '[/embed]',
        );
      
        wp_update_post( $changed_post );

        return $post_id;
    }
}
?>