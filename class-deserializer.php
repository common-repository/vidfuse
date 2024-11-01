<?php
/**
 * Retrieves information from the database.
 *
 * @package Vidfuse
 */
 
class Vidfuse_Deserializer {
    public function get_value($option_key) {
        return get_option($option_key, '');
    }
}
?>