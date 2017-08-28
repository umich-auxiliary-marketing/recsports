<?php
/**
 * Plugin name: Nested Menu Builder
 * Plugin URI: https://github.com/kucrut/wp-menu-item-custom-fields
 * Description: <em><em>Custom</em></em> Saves a one-level, parent-child object when menus are saved for more efficient parsing on the front-end.
 * Version: 1.0.0
 * Author: John Matula
 * Author URI: http://johnmatula.com
 * License: GPL v2
 * Text Domain: menu-item-custom-fields-example
 */


add_filter( 'wp_update_nav_menu', 'create_nested_object', PHP_INT_MAX );
function create_nested_object($menu_id) {

	check_admin_referer( 'update-nav_menu', 'update-nav-menu-nonce' );

	$sorted_menu_items = wp_get_nav_menu_items($menu_id);
	$output = array();

	$root_id = 0;
  // find the current menu item
  foreach ( $sorted_menu_items as $menu_item ) {
		$menu_item->children = array();

    if ( $menu_item->current ) {
      // set the root id based on whether the current menu item has a parent or not
      $root_id = ( $menu_item->menu_item_parent ) ? $menu_item->menu_item_parent : $menu_item->ID;
      break;
    }
  }

  // find the top level parent
  if ( ! isset( $args->direct_parent ) ) {
    $prev_root_id = $root_id;
    while ( $prev_root_id != 0 ) {
      foreach ( $sorted_menu_items as $menu_item ) {
        if ( $menu_item->ID == $prev_root_id ) {
          $prev_root_id = $menu_item->menu_item_parent;
          // don't set the root_id to 0 if we've reached the top of the menu
          if ( $prev_root_id != 0 ) $root_id = $menu_item->menu_item_parent;
          break;
        }
      }
    }
  }
  $menu_item_parents = array();
  foreach ( $sorted_menu_items as $key => $item ) {

    // init menu_item_parents
		//$item->blah = "yesyes";
		//$item = array("new_property" => "BLAHBLAH");

    if ( $item->menu_item_parent == $root_id ) {
			$output[$item->ID] = $item;
		}
    if ( isset($output[$item->menu_item_parent]) && !empty($output[$item->menu_item_parent])) {
      // part of sub-tree: keep!
      $output[$item->menu_item_parent]->children[] = $item;
			unset($sorted_menu_items[$key]);
    }
  }


	$value = $sorted_menu_items;

	// Update
	if ( ! is_null( $value ) ) {
		//delete_post_meta( $menu_id, $key );
		update_post_meta( (int)$menu_id, 'nested_menu', $value );
	}
	else {
		delete_post_meta( $menu_id, 'nested_menu');
	}
}
