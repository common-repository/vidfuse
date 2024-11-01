<?php
/**
 * Creates the submenu item for the plugin.
 *
 * @package Vidfuse
 */
class Vidfuse_Submenu {
 
  private $submenu_page;

  public function __construct($submenu_page) {
    $this->submenu_page = $submenu_page;
  }

  public function init() {
    add_action('admin_menu', array($this, 'add_options_page'));
  }

  public function add_options_page() {
    add_options_page(
      'Vidfuse Plugin',
      'Vidfuse Plugin',
      'manage_options',
      VIDFUSE_SLUG,
      array($this->submenu_page, 'render')
    );
  }
}